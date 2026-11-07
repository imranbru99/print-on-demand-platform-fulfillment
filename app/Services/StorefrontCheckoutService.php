<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\PaymentGatewayInterface;
use App\Enums\OrderStatus;
use App\Events\OrderPaid;
use App\Models\Cart;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Store;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

final class StorefrontCheckoutService
{
    public function __construct(
        private readonly CheckoutService $checkout,
        private readonly PricingEngineService $pricing,
        private readonly PaymentGatewayInterface $payments,
        private readonly StorefrontCartService $carts,
        private readonly OrderStateMachine $orderStateMachine,
    ) {}

    /**
     * @param  array{client_subtotal: string, email: string, first_name: string, last_name: string}  $payload
     */
    public function placeOrder(Store $store, Cart $cart, array $payload, ?User $user = null): Order
    {
        return DB::transaction(function () use ($store, $cart, $payload, $user): Order {
            $validated = $this->checkout->validateCartTotals($cart, $payload['client_subtotal']);

            $customer = $this->resolveCustomer($store, $payload, $user);

            $orderNumber = 'ORD-'.strtoupper(Str::random(8));

            $order = Order::query()->create([
                'tenant_id' => $store->tenant_id,
                'store_id' => $store->getKey(),
                'customer_id' => $customer?->getKey(),
                'number' => $orderNumber,
                'status' => 'pending_payment',
                'currency' => $cart->currency ?? 'USD',
                'subtotal' => $validated['subtotal'],
                'tax_total' => '0.0000',
                'shipping_total' => '0.0000',
                'discount_total' => '0.0000',
                'grand_total' => $validated['subtotal'],
            ]);

            foreach ($validated['items'] as $line) {
                $cartItem = $cart->items->firstWhere('id', $line['id']);
                if ($cartItem === null) {
                    continue;
                }

                OrderItem::query()->create([
                    'tenant_id' => $store->tenant_id,
                    'order_id' => $order->getKey(),
                    'product_id' => $cartItem->product_id,
                    'name' => $cartItem->product?->name ?? 'Product',
                    'quantity' => $line['quantity'],
                    'unit_price' => $line['unit_price'],
                    'line_total' => $line['line_total'],
                ]);
            }

            $idempotency = 'checkout_'.$order->getKey();
            $auth = $this->payments->authorize($idempotency, [
                'amount' => $validated['subtotal'],
                'currency' => $order->currency,
            ]);
            $capture = $this->payments->capture($idempotency.'_cap', (string) $auth['authorization_reference'], [
                'amount' => $validated['subtotal'],
            ]);

            Payment::query()->create([
                'tenant_id' => $store->tenant_id,
                'order_id' => $order->getKey(),
                'provider' => 'sandbox',
                'status' => $capture['status'] ?? 'captured',
                'amount' => $validated['subtotal'],
            ]);

            $order = $this->orderStateMachine->transition($order, OrderStatus::Paid);
            event(new OrderPaid($order));
            $cart->items()->delete();

            return $order->fresh(['items', 'payments']);
        });
    }

    /**
     * @param  array{email: string, first_name: string, last_name: string}  $payload
     */
    private function resolveCustomer(Store $store, array $payload, ?User $user): ?Customer
    {
        $email = strtolower(trim($payload['email']));

        $customer = Customer::query()
            ->where('email', $email)
            ->first();

        if ($customer === null) {
            $customer = Customer::query()->create([
                'tenant_id' => $store->tenant_id,
                'email' => $email,
                'first_name' => $payload['first_name'],
                'last_name' => $payload['last_name'],
                'user_id' => $user?->getKey(),
            ]);
        }

        return $customer;
    }
}

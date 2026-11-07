<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductPricing;
use App\Models\ProductVariant;
use App\Models\Store;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

final class StorefrontCartService
{
    public function __construct(
        private readonly PricingEngineService $pricing,
    ) {}

    public function cartForStore(Store $store, ?string $explicitToken = null): Cart
    {
        $token = $explicitToken ?? Session::get($this->sessionKey($store));

        $query = Cart::query()
            ->where('store_id', $store->getKey())
            ->with('items.product', 'items.variant');

        if (is_string($token) && $token !== '') {
            $cart = $query->where('session_token', $token)->first();
            if ($cart !== null) {
                return $cart;
            }
        }

        $token = Str::uuid()->toString();
        if ($explicitToken === null) {
            Session::put($this->sessionKey($store), $token);
        }

        return Cart::query()->create([
            'tenant_id' => $store->tenant_id,
            'store_id' => $store->getKey(),
            'session_token' => $token,
            'currency' => $store->currency ?? 'USD',
        ]);
    }

    public function resolveUnitPrice(Product $product, ?ProductVariant $variant): string
    {
        $pricing = ProductPricing::query()
            ->where('product_id', $product->getKey())
            ->when(
                $variant !== null,
                fn ($q) => $q->where('variant_id', $variant->getKey()),
                fn ($q) => $q->whereNull('variant_id'),
            )
            ->orderByDesc('updated_at')
            ->first();

        if ($pricing !== null) {
            return (string) $pricing->base_price;
        }

        return '0.0000';
    }

    public function addItem(Store $store, string $productId, ?string $variantId, int $quantity, ?string $cartToken = null): Cart
    {
        $cart = $this->cartForStore($store, $cartToken);
        $product = Product::query()->whereKey($productId)->firstOrFail();
        $variant = $variantId !== null
            ? ProductVariant::query()->whereKey($variantId)->where('product_id', $product->getKey())->first()
            : $product->variants()->first();

        $unitPrice = $this->resolveUnitPrice($product, $variant);

        $existing = $cart->items()
            ->where('product_id', $product->getKey())
            ->when($variant !== null, fn ($q) => $q->where('variant_id', $variant->getKey()))
            ->first();

        if ($existing !== null) {
            $existing->update([
                'quantity' => (int) $existing->quantity + $quantity,
                'unit_price' => $unitPrice,
            ]);
        } else {
            CartItem::query()->create([
                'tenant_id' => $store->tenant_id,
                'cart_id' => $cart->getKey(),
                'product_id' => $product->getKey(),
                'variant_id' => $variant?->getKey(),
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
            ]);
        }

        return $cart->fresh('items');
    }

    public function subtotal(Cart $cart): string
    {
        $cart->loadMissing('items');

        return $this->pricing->sumLines(
            $cart->items->map(static fn (CartItem $item): array => [
                'unit_price' => (string) $item->unit_price,
                'quantity' => (int) $item->quantity,
            ]),
        );
    }

    public function itemCount(Cart $cart): int
    {
        return (int) $cart->items()->sum('quantity');
    }

    private function sessionKey(Store $store): string
    {
        return 'storefront_cart_'.$store->getKey();
    }
}

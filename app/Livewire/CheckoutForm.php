<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Store;
use App\Services\StorefrontCartService;
use App\Services\StorefrontCheckoutService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

final class CheckoutForm extends Component
{
    public string $storeId;

    public string $email = '';

    public string $first_name = '';

    public string $last_name = '';

    public string $client_subtotal = '';

    public ?string $error = null;

    public function mount(string $storeId, string $clientSubtotal): void
    {
        $this->storeId = $storeId;
        $this->client_subtotal = $clientSubtotal;

        $user = Auth::user();
        if ($user !== null) {
            $this->email = $user->email;
            $parts = explode(' ', $user->name, 2);
            $this->first_name = $parts[0] ?? '';
            $this->last_name = $parts[1] ?? '';
        }
    }

    public function submit(
        StorefrontCartService $carts,
        StorefrontCheckoutService $checkout,
    ) {
        $this->validate([
            'email' => ['required', 'email'],
            'first_name' => ['required', 'string', 'max:80'],
            'last_name' => ['required', 'string', 'max:80'],
            'client_subtotal' => ['required', 'string'],
        ]);

        $store = Store::query()->findOrFail($this->storeId);
        $cart = $carts->cartForStore($store);

        try {
            $serverSubtotal = $carts->subtotal($cart);
            if ($this->client_subtotal !== $serverSubtotal) {
                $this->client_subtotal = $serverSubtotal;
            }

            $order = $checkout->placeOrder($store, $cart, [
                'email' => $this->email,
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'client_subtotal' => $serverSubtotal,
            ], Auth::user());

            return $this->redirectRoute('storefront.checkout.thank-you', [
                'storeSlug' => $store->slug,
                'orderNumber' => $order->number,
            ], navigate: true);
        } catch (\Throwable $e) {
            $this->error = $e->getMessage();
        }
    }

    public function render(StorefrontCartService $carts)
    {
        $store = Store::query()->findOrFail($this->storeId);
        $cart = $carts->cartForStore($store)->load('items.product');
        $this->client_subtotal = $carts->subtotal($cart);

        return view('livewire.checkout-form', [
            'store' => $store,
            'cart' => $cart,
            'subtotal' => $carts->subtotal($cart),
        ]);
    }
}

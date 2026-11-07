<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Product;
use App\Models\Store;
use App\Services\StorefrontCartService;
use Livewire\Component;

final class AddToCart extends Component
{
    public string $storeId;

    public string $productId;

    public ?string $variantId = null;

    public int $quantity = 1;

    public function mount(string $storeId, string $productId, ?string $variantId = null): void
    {
        $this->storeId = $storeId;
        $this->productId = $productId;
        $this->variantId = $variantId;
    }

    public function add(StorefrontCartService $carts): void
    {
        $this->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:99'],
        ]);

        $store = Store::query()->findOrFail($this->storeId);
        Product::query()->whereKey($this->productId)->firstOrFail();

        $carts->addItem($store, $this->productId, $this->variantId, $this->quantity);

        $this->dispatch('cart-updated');
        session()->flash('cart_message', 'Added to cart.');
    }

    public function render()
    {
        return view('livewire.add-to-cart');
    }
}

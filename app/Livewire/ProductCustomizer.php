<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Product;
use App\Models\Store;
use App\Services\StorefrontCartService;
use Livewire\Component;

final class ProductCustomizer extends Component
{
    public string $storeId;

    public string $productId;

    public ?string $variantId = null;

    public int $quantity = 1;

    /** @var array<int, array{id: string, src: string, x: int, y: int, width: int, height: int, rotate: int}> */
    public array $layers = [];

    public function mount(string $storeId, string $productId, ?string $variantId = null): void
    {
        $this->storeId = $storeId;
        $this->productId = $productId;
        $this->variantId = $variantId;
    }

    public function addLayer(string $dataUrl): void
    {
        $this->layers[] = [
            'id' => (string) count($this->layers),
            'src' => $dataUrl,
            'x' => 40,
            'y' => 40,
            'width' => 160,
            'height' => 160,
            'rotate' => 0,
        ];
    }

    public function removeLayer(int $index): void
    {
        if (isset($this->layers[$index])) {
            unset($this->layers[$index]);
            $this->layers = array_values($this->layers);
        }
    }

    public function addToCart(StorefrontCartService $carts): void
    {
        $store = Store::query()->findOrFail($this->storeId);
        Product::query()->whereKey($this->productId)->firstOrFail();

        $carts->addItem($store, $this->productId, $this->variantId, $this->quantity);

        $this->dispatch('cart-updated');
        session()->flash('cart_message', 'Custom design added to cart.');
    }

    public function render()
    {
        $product = Product::query()->with('printAreas')->findOrFail($this->productId);

        return view('livewire.product-customizer', compact('product'));
    }
}

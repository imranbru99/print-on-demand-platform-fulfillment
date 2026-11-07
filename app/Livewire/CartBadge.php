<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Store;
use App\Services\StorefrontCartService;
use Livewire\Component;

final class CartBadge extends Component
{
    public string $storeId;

    public function mount(string $storeId): void
    {
        $this->storeId = $storeId;
    }

    protected $listeners = ['cart-updated' => '$refresh'];

    public function render(StorefrontCartService $carts)
    {
        $store = Store::query()->withoutGlobalScopes()->findOrFail($this->storeId);
        $count = $carts->itemCount($carts->cartForStore($store));

        return view('livewire.cart-badge', ['count' => $count]);
    }
}

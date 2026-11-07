<?php

declare(strict_types=1);

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Services\StorefrontCartService;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class CartController extends Controller
{
    public function index(Request $request, StorefrontCartService $carts): View
    {
        /** @var Store $store */
        $store = $request->attributes->get('store');
        $cart = $carts->cartForStore($store)->load('items.product', 'items.variant');

        return view('storefront.cart.index', [
            'store' => $store,
            'cart' => $cart,
            'subtotal' => $carts->subtotal($cart),
        ]);
    }
}

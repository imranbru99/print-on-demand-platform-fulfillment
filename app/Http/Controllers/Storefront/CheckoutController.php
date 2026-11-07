<?php

declare(strict_types=1);

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Services\StorefrontCartService;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class CheckoutController extends Controller
{
    public function show(Request $request, StorefrontCartService $carts): View
    {
        /** @var Store $store */
        $store = $request->attributes->get('store');
        $cart = $carts->cartForStore($store)->load('items.product', 'items.variant');

        return view('storefront.checkout.show', [
            'store' => $store,
            'cart' => $cart,
            'subtotal' => $carts->subtotal($cart),
        ]);
    }

    public function thankYou(Request $request, string $storeSlug, string $orderNumber): View
    {
        /** @var Store $store */
        $store = $request->attributes->get('store');

        return view('storefront.checkout.thank-you', compact('store', 'orderNumber'));
    }
}

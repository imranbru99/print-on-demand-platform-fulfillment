<?php

declare(strict_types=1);

namespace App\Http\Controllers\Storefront;

use App\Enums\ProductStatus;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class StoreHomeController extends Controller
{
    public function __invoke(Request $request): View
    {
        /** @var Store $store */
        $store = $request->attributes->get('store');

        $featured = Product::query()
            ->where('store_id', $store->getKey())
            ->where('status', ProductStatus::Active)
            ->with(['images', 'pricing'])
            ->limit(8)
            ->get();

        return view('storefront.home', compact('store', 'featured'));
    }
}

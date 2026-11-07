<?php

declare(strict_types=1);

namespace App\Http\Controllers\Storefront;

use App\Enums\ProductStatus;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class ShopController extends Controller
{
    public function index(Request $request): View
    {
        /** @var Store $store */
        $store = $request->attributes->get('store');

        $products = Product::query()
            ->where('store_id', $store->getKey())
            ->where('status', ProductStatus::Active)
            ->with(['images', 'pricing', 'category'])
            ->orderBy('name')
            ->paginate(24);

        $categories = ProductCategory::query()->orderBy('name')->get();

        return view('storefront.shop.index', compact('store', 'products', 'categories'));
    }

    public function category(Request $request, string $storeSlug, string $categorySlug): View
    {
        /** @var Store $store */
        $store = $request->attributes->get('store');

        $category = ProductCategory::query()->where('slug', $categorySlug)->first();
        if ($category === null) {
            throw new NotFoundHttpException();
        }

        $products = Product::query()
            ->where('store_id', $store->getKey())
            ->where('category_id', $category->getKey())
            ->where('status', ProductStatus::Active)
            ->with(['images', 'pricing'])
            ->paginate(24);

        return view('storefront.shop.category', compact('store', 'category', 'products'));
    }
}

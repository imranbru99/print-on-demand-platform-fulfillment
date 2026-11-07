<?php

declare(strict_types=1);

namespace App\Http\Controllers\Storefront;

use App\Enums\ProductStatus;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class ProductController extends Controller
{
    public function show(Request $request, string $storeSlug, string $productSlug): View
    {
        /** @var Store $store */
        $store = $request->attributes->get('store');

        $product = Product::query()
            ->where('store_id', $store->getKey())
            ->where('slug', $productSlug)
            ->where('status', ProductStatus::Active)
            ->with(['variants', 'images', 'pricing', 'printAreas'])
            ->first();

        if ($product === null) {
            throw new NotFoundHttpException();
        }

        return view('storefront.product.show', compact('store', 'product'));
    }

    public function customize(Request $request, string $storeSlug, string $productSlug): View
    {
        /** @var Store $store */
        $store = $request->attributes->get('store');

        $product = Product::query()
            ->where('store_id', $store->getKey())
            ->where('slug', $productSlug)
            ->where('status', ProductStatus::Active)
            ->with(['variants', 'printAreas'])
            ->firstOrFail();

        return view('storefront.product.customize', compact('store', 'product'));
    }
}

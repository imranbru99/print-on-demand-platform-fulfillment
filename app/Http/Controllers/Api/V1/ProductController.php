<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\ProductResource;
use App\Models\Product;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

final class ProductController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $products = Product::query()
            ->with(['variants', 'pricing'])
            ->orderBy('name')
            ->paginate(20);

        return ProductResource::collection($products);
    }

    public function show(string $productId): ProductResource
    {
        $product = Product::query()
            ->whereKey($productId)
            ->with(['variants', 'pricing'])
            ->firstOrFail();

        return new ProductResource($product);
    }
}

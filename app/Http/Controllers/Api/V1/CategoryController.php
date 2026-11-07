<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\CategoryResource;
use App\Models\ProductCategory;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

final class CategoryController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $categories = ProductCategory::query()
            ->orderBy('sort_order')
            ->paginate(50);

        return CategoryResource::collection($categories);
    }

    public function show(string $categoryId): CategoryResource
    {
        $category = ProductCategory::query()->whereKey($categoryId)->firstOrFail();

        return new CategoryResource($category);
    }
}

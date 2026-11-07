<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\StoreResource;
use App\Models\Store;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

final class StoreController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $stores = Store::query()->orderBy('name')->paginate(20);

        return StoreResource::collection($stores);
    }

    public function show(Store $store): StoreResource
    {
        return new StoreResource($store);
    }
}

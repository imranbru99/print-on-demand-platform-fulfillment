<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\OrderResource;
use App\Models\Order;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

final class OrderController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $orders = Order::query()
            ->with('items')
            ->latest()
            ->paginate(20);

        return OrderResource::collection($orders);
    }

    public function show(string $orderId): OrderResource
    {
        $order = Order::query()->whereKey($orderId)->with('items')->firstOrFail();

        return new OrderResource($order);
    }
}

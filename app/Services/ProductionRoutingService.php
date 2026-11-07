<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\ProductionStatus;
use App\Models\Order;
use App\Models\ProductionJob;
use App\Models\ProductionOrder;
use Illuminate\Support\Facades\DB;

final class ProductionRoutingService
{
    public function routeOrder(Order $order): ProductionOrder
    {
        return DB::transaction(function () use ($order): ProductionOrder {
            $productionOrder = ProductionOrder::query()->create([
                'tenant_id' => $order->tenant_id,
                'order_id' => $order->getKey(),
                'status' => ProductionStatus::Queued->value,
            ]);

            foreach ($order->items()->get() as $item) {
                ProductionJob::query()->create([
                    'tenant_id' => $order->tenant_id,
                    'production_order_id' => $productionOrder->getKey(),
                    'order_item_id' => $item->getKey(),
                    'status' => ProductionStatus::Queued->value,
                ]);
            }

            return $productionOrder->load('jobs');
        });
    }
}

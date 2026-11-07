<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\ShippingProviderInterface;
use App\Enums\FulfillmentStatus;
use App\Models\FulfillmentOrder;
use App\Models\Order;
use App\Models\Shipment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

final class FulfillmentService
{
    public function __construct(
        private readonly ShippingProviderInterface $shippingProvider,
    ) {}

    public function createFulfillment(Order $order, ?string $idempotencyKey = null): FulfillmentOrder
    {
        $key = $idempotencyKey ?? Str::ulid()->toBase32();

        $existing = FulfillmentOrder::query()
            ->withoutGlobalScopes()
            ->where('tenant_id', $order->tenant_id)
            ->where('idempotency_key', $key)
            ->first();

        if ($existing !== null) {
            return $existing;
        }

        return FulfillmentOrder::query()->create([
            'tenant_id' => $order->tenant_id,
            'order_id' => $order->getKey(),
            'status' => FulfillmentStatus::Pending->value,
            'idempotency_key' => $key,
        ]);
    }

    public function ship(FulfillmentOrder $fulfillment, array $shipmentPayload): Shipment
    {
        return DB::transaction(function () use ($fulfillment, $shipmentPayload): Shipment {
            $idempotencyKey = $shipmentPayload['idempotency_key'] ?? $fulfillment->idempotency_key;

            $response = $this->shippingProvider->createShipment($idempotencyKey, $shipmentPayload);

            $shipment = Shipment::query()->create([
                'tenant_id' => $fulfillment->tenant_id,
                'fulfillment_order_id' => $fulfillment->getKey(),
                'carrier' => $response['carrier'] ?? null,
                'tracking_number' => $response['tracking_number'] ?? null,
                'status' => FulfillmentStatus::Shipped->value,
                'shipping_cost' => $response['shipping_cost'] ?? 0,
            ]);

            $fulfillment->update(['status' => FulfillmentStatus::Shipped->value]);

            return $shipment;
        });
    }
}

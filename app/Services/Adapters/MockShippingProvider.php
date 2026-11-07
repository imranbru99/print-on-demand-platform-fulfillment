<?php

declare(strict_types=1);

namespace App\Services\Adapters;

use App\Contracts\ShippingProviderInterface;
use Illuminate\Support\Str;

final class MockShippingProvider implements ShippingProviderInterface
{
    public function createShipment(string $idempotencyKey, array $shipment): array
    {
        return [
            'idempotency_key' => $idempotencyKey,
            'carrier' => $shipment['carrier'] ?? 'mock-carrier',
            'tracking_number' => 'MOCK'.Str::upper(Str::random(10)),
            'shipping_cost' => $shipment['shipping_cost'] ?? '0.0000',
            'status' => 'created',
        ];
    }

    public function track(string $trackingNumber): array
    {
        return [
            'tracking_number' => $trackingNumber,
            'status' => 'in_transit',
            'events' => [],
        ];
    }
}

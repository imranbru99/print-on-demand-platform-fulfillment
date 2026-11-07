<?php

declare(strict_types=1);

namespace App\Contracts;

interface ShippingProviderInterface
{
    /**
     * @param  array<string, mixed>  $shipment
     * @return array<string, mixed>
     */
    public function createShipment(string $idempotencyKey, array $shipment): array;

    /**
     * @return array<string, mixed>
     */
    public function track(string $trackingNumber): array;
}

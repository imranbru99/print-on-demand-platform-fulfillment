<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Contracts\ShippingProviderInterface;
use App\Models\Shipment;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

final class SyncShipmentTrackingJob implements ShouldQueue
{
    use Queueable;

    public function handle(ShippingProviderInterface $shippingProvider): void
    {
        Shipment::query()
            ->withoutGlobalScopes()
            ->whereNotNull('tracking_number')
            ->whereNotIn('status', ['delivered', 'cancelled'])
            ->orderBy('id')
            ->chunkById(50, function ($shipments) use ($shippingProvider): void {
                foreach ($shipments as $shipment) {
                    $tracking = $shippingProvider->track((string) $shipment->tracking_number);
                    $status = (string) ($tracking['status'] ?? $shipment->status);

                    if ($status !== $shipment->status) {
                        $shipment->update(['status' => $status]);
                    }
                }
            });
    }
}

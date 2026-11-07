<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\WebhookDelivery;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

final class RetryFailedWebhookDeliveriesJob implements ShouldQueue
{
    use Queueable;

    public function handle(): void
    {
        WebhookDelivery::query()
            ->withoutGlobalScopes()
            ->whereNull('delivered_at')
            ->where('attempt', '<', 5)
            ->where('updated_at', '<', now()->subMinutes(5))
            ->orderBy('id')
            ->limit(100)
            ->get()
            ->each(function (WebhookDelivery $delivery): void {
                DeliverWebhookJob::dispatch($delivery->getKey(), [
                    'event' => $delivery->event,
                    'delivery_id' => $delivery->getKey(),
                ]);
            });
    }
}

<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Webhook;
use App\Models\WebhookDelivery;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

final class DeliverWebhookJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 5;

    /** @var list<int> */
    public array $backoff = [60, 300, 900, 3600];

    /**
     * @param  array<string, mixed>  $payload
     */
    public function __construct(
        public readonly string $webhookDeliveryId,
        public readonly array $payload = [],
    ) {}

    public function handle(): void
    {
        if (config('pod.demo_mode')) {
            return;
        }

        $delivery = WebhookDelivery::query()->withoutGlobalScopes()->find($this->webhookDeliveryId);

        if ($delivery === null) {
            return;
        }

        $webhook = Webhook::query()->withoutGlobalScopes()->find($delivery->webhook_id);

        if ($webhook === null || ! ($webhook->is_active ?? true)) {
            return;
        }

        $body = json_encode($this->payload, JSON_THROW_ON_ERROR);
        $signature = hash_hmac('sha256', $body, (string) $webhook->secret);

        try {
            $response = Http::timeout(15)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'X-Webhook-Signature' => $signature,
                    'X-Webhook-Event' => $delivery->event,
                ])
                ->withBody($body, 'application/json')
                ->post($webhook->url);

            $delivery->update([
                'response_status' => $response->status(),
                'response_body' => $response->body(),
                'attempt' => $delivery->attempt + 1,
                'delivered_at' => $response->successful() ? now() : null,
            ]);

            if (! $response->successful()) {
                $this->fail('Webhook endpoint returned HTTP '.$response->status());
            }
        } catch (Throwable $exception) {
            $delivery->update([
                'attempt' => $delivery->attempt + 1,
                'response_body' => $exception->getMessage(),
            ]);

            Log::warning('Webhook delivery failed', [
                'webhook_delivery_id' => $delivery->getKey(),
                'error' => $exception->getMessage(),
            ]);

            throw $exception;
        }
    }
}

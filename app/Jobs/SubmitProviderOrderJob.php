<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Contracts\PrintProviderInterface;
use App\Models\Order;
use App\Models\PrintProvider;
use App\Models\ProviderOrder;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

final class SubmitProviderOrderJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly string $orderId,
    ) {}

    public function handle(PrintProviderInterface $printProvider): void
    {
        $order = Order::query()->withoutGlobalScopes()->with('items')->find($this->orderId);

        if ($order === null) {
            return;
        }

        $existing = ProviderOrder::query()
            ->withoutGlobalScopes()
            ->where('order_id', $order->getKey())
            ->first();

        if ($existing !== null && $existing->status === 'submitted') {
            return;
        }

        $provider = PrintProvider::query()->firstOrCreate(
            ['key' => 'mock'],
            [
                'name' => 'Mock Print Provider',
                'capabilities' => ['mock'],
            ],
        );

        $providerOrder = $existing ?? ProviderOrder::query()->create([
            'tenant_id' => $order->tenant_id,
            'order_id' => $order->getKey(),
            'print_provider_id' => $provider->getKey(),
            'status' => 'pending',
        ]);

        $reference = 'provider_'.$providerOrder->getKey();

        $response = $printProvider->submitOrder($reference, [
            'order_id' => $order->getKey(),
            'order_number' => $order->number,
            'items' => $order->items->map(fn ($item) => [
                'name' => $item->name,
                'quantity' => $item->quantity,
            ])->all(),
        ]);

        $providerOrder->update([
            'status' => 'submitted',
            'external_reference' => $response['external_id'] ?? null,
            'payload' => $response,
        ]);
    }
}

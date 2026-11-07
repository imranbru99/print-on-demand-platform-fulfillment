<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Invoice;
use App\Models\Order;
use App\Services\DocumentService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Str;

final class GenerateOrderDocumentsJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly string $orderId,
    ) {}

    public function handle(DocumentService $documents): void
    {
        $order = Order::query()->withoutGlobalScopes()->with(['items', 'store', 'customer'])->find($this->orderId);

        if ($order === null) {
            return;
        }

        Invoice::query()->firstOrCreate(
            [
                'tenant_id' => $order->tenant_id,
                'order_id' => $order->getKey(),
            ],
            [
                'number' => 'INV-'.Str::upper(Str::random(8)),
                'amount' => $order->grand_total,
                'status' => 'issued',
            ],
        );

        $documents->storeOrderInvoice($order);
        $documents->storePackingSlip($order);
    }
}

<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Enums\OrderStatus;
use App\Models\AutomationWorkflow;
use App\Models\Order;
use App\Models\ProductionOrder;
use App\Services\OrderStateMachine;
use App\Services\ProductionRoutingService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

final class ProcessPaidOrderJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly string $orderId,
    ) {}

    public function handle(
        OrderStateMachine $stateMachine,
        ProductionRoutingService $productionRouting,
    ): void {
        $order = Order::query()->withoutGlobalScopes()->with('items')->find($this->orderId);

        if ($order === null) {
            return;
        }

        if ($order->status === OrderStatus::PendingPayment->value) {
            $stateMachine->transition($order, OrderStatus::Paid);
            $order = $order->fresh(['items']);
        }

        $hasProduction = ProductionOrder::query()
            ->withoutGlobalScopes()
            ->where('order_id', $order->getKey())
            ->exists();

        if (! $hasProduction) {
            $productionRouting->routeOrder($order);

            if ($order->status === OrderStatus::Paid->value) {
                $stateMachine->transition($order->fresh(), OrderStatus::InProduction);
            }
        }

        AutomationWorkflow::query()
            ->withoutGlobalScopes()
            ->where('tenant_id', $order->tenant_id)
            ->where('is_active', true)
            ->where('trigger', 'order.paid')
            ->each(function (AutomationWorkflow $workflow) use ($order): void {
                ProcessAutomationWorkflowJob::dispatch($workflow->getKey(), [
                    'order_id' => $order->getKey(),
                ]);
            });

        SubmitProviderOrderJob::dispatch($order->getKey());
        GenerateOrderDocumentsJob::dispatch($order->getKey());
    }
}

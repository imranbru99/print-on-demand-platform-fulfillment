<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\OrderStatusChanged;
use App\Jobs\ProcessAutomationWorkflowJob;
use App\Models\AutomationWorkflow;
use Illuminate\Support\Facades\Log;

final class HandleOrderStatusChanged
{
    public function handle(OrderStatusChanged $event): void
    {
        if (! config('pod.demo_mode')) {
            Log::info('Order status notification stub (email skipped in demo)', [
                'order_id' => $event->order->getKey(),
                'number' => $event->order->number,
                'from' => $event->from->value,
                'to' => $event->to->value,
            ]);
        }

        $trigger = 'order.status.'.$event->to->value;

        $workflows = AutomationWorkflow::query()
            ->withoutGlobalScopes()
            ->where('tenant_id', $event->order->tenant_id)
            ->where('is_active', true)
            ->where('trigger', $trigger)
            ->get();

        foreach ($workflows as $workflow) {
            ProcessAutomationWorkflowJob::dispatch($workflow->getKey(), [
                'order_id' => $event->order->getKey(),
                'from_status' => $event->from->value,
                'to_status' => $event->to->value,
            ]);
        }
    }
}

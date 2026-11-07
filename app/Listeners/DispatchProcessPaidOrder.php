<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\OrderPaid;
use App\Jobs\ProcessPaidOrderJob;

final class DispatchProcessPaidOrder
{
    public function handle(OrderPaid $event): void
    {
        ProcessPaidOrderJob::dispatch($event->order->getKey())->afterResponse();
    }
}

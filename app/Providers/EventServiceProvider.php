<?php

declare(strict_types=1);

namespace App\Providers;

use App\Events\OrderPaid;
use App\Events\OrderStatusChanged;
use App\Listeners\DispatchProcessPaidOrder;
use App\Listeners\HandleOrderStatusChanged;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

final class EventServiceProvider extends ServiceProvider
{
    /** @var array<class-string, list<class-string>> */
    protected $listen = [
        OrderPaid::class => [
            DispatchProcessPaidOrder::class,
        ],
        OrderStatusChanged::class => [
            HandleOrderStatusChanged::class,
        ],
    ];
}

<?php

declare(strict_types=1);

namespace App\Filament\Business\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class BusinessRevenueOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $paidStatuses = ['paid', 'in_production', 'quality_review', 'ready_to_ship', 'shipped', 'delivered'];

        $revenue = (float) Order::query()
            ->whereIn('status', $paidStatuses)
            ->sum('grand_total');

        $orders = Order::query()->count();

        return [
            Stat::make('Revenue', '$'.number_format($revenue, 2))
                ->description('From fulfilled-payment orders')
                ->color('success'),
            Stat::make('Orders', (string) $orders)
                ->description('All statuses in current tenant')
                ->color('primary'),
        ];
    }
}

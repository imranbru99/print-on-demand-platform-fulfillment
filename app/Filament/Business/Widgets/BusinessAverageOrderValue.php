<?php

declare(strict_types=1);

namespace App\Filament\Business\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class BusinessAverageOrderValue extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $paidStatuses = ['paid', 'in_production', 'quality_review', 'ready_to_ship', 'shipped', 'delivered'];

        $query = Order::query()->whereIn('status', $paidStatuses);

        $count = (int) $query->count();
        $sum = (float) (clone $query)->sum('grand_total');
        $aov = $count > 0 ? $sum / $count : 0.0;

        return [
            Stat::make('Average order value', '$'.number_format($aov, 2))
                ->description("Across {$count} paid orders")
                ->descriptionIcon('heroicon-m-calculator')
                ->color('warning'),
        ];
    }
}

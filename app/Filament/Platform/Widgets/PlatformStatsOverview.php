<?php

declare(strict_types=1);

namespace App\Filament\Platform\Widgets;

use App\Models\Order;
use App\Models\Subscription;
use App\Models\Tenant;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
class PlatformStatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $tenantCount = Tenant::query()->count();
        $subscriptionCount = Subscription::query()->withoutGlobalScopes()->count();
        $activeSubscriptions = Subscription::query()
            ->withoutGlobalScopes()
            ->whereIn('status', ['active', 'trialing'])
            ->count();
        $orderCount = Order::query()->withoutGlobalScopes()->count();
        $revenue = (float) Order::query()
            ->withoutGlobalScopes()
            ->whereNotIn('status', ['draft', 'cancelled'])
            ->sum('grand_total');

        return [
            Stat::make('Tenants', (string) $tenantCount)
                ->description('Registered businesses')
                ->descriptionIcon('heroicon-m-building-office-2')
                ->color('primary'),
            Stat::make('Subscriptions', (string) $subscriptionCount)
                ->description("{$activeSubscriptions} active or trialing")
                ->descriptionIcon('heroicon-m-arrow-path')
                ->color('success'),
            Stat::make('Orders (all tenants)', (string) $orderCount)
                ->description('USD '.number_format($revenue, 2).' captured revenue')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('warning'),
        ];
    }
}

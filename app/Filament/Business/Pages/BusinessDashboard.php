<?php

declare(strict_types=1);

namespace App\Filament\Business\Pages;

use App\Filament\Business\Widgets\BusinessAverageOrderValue;
use App\Filament\Business\Widgets\BusinessLowStock;
use App\Filament\Business\Widgets\BusinessOrdersByStatusChart;
use App\Filament\Business\Widgets\BusinessRevenueOverview;
use App\Filament\Business\Widgets\BusinessTopProducts;
use Filament\Pages\Dashboard as BaseDashboard;

class BusinessDashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-presentation-chart-line';

    protected static ?string $title = 'Business Overview';

    /**
     * @return array<class-string>
     */
    public function getWidgets(): array
    {
        return [
            BusinessRevenueOverview::class,
            BusinessAverageOrderValue::class,
            BusinessOrdersByStatusChart::class,
            BusinessTopProducts::class,
            BusinessLowStock::class,
        ];
    }
}

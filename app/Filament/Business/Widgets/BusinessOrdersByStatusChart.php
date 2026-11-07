<?php

declare(strict_types=1);

namespace App\Filament\Business\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class BusinessOrdersByStatusChart extends ChartWidget
{
    protected static ?string $heading = 'Orders by status';

    protected function getData(): array
    {
        $rows = Order::query()
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->orderBy('status')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Orders',
                    'data' => $rows->pluck('total')->map(fn ($v) => (int) $v)->all(),
                    'backgroundColor' => '#0d9488',
                ],
            ],
            'labels' => $rows->pluck('status')->all(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}

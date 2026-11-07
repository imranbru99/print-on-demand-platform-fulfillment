<?php

declare(strict_types=1);

namespace App\Filament\Business\Widgets;

use App\Models\OrderItem;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\DB;

class BusinessTopProducts extends BaseWidget
{
    protected static ?string $heading = 'Top products by quantity sold';

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                OrderItem::query()
                    ->select([
                        DB::raw('MIN(order_items.id) as id'),
                        'order_items.name',
                        DB::raw('SUM(order_items.quantity) as units_sold'),
                        DB::raw('SUM(order_items.line_total) as revenue'),
                    ])
                    ->groupBy('order_items.name')
                    ->orderByDesc('units_sold')
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Product'),
                Tables\Columns\TextColumn::make('units_sold')->label('Units'),
                Tables\Columns\TextColumn::make('revenue')
                    ->label('Revenue')
                    ->money('USD'),
            ])
            ->paginated(false);
    }
}

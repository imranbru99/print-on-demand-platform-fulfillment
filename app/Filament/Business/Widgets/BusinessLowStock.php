<?php

declare(strict_types=1);

namespace App\Filament\Business\Widgets;

use App\Models\InventoryItem;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class BusinessLowStock extends BaseWidget
{
    protected static ?string $heading = 'Low stock (≤ 10 units)';

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                InventoryItem::query()
                    ->where('quantity_on_hand', '<=', 10)
                    ->orderBy('quantity_on_hand')
            )
            ->columns([
                Tables\Columns\TextColumn::make('sku'),
                Tables\Columns\TextColumn::make('quantity_on_hand')->label('On hand'),
            ])
            ->paginated(false);
    }
}

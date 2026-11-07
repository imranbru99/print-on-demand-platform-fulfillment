<?php

declare(strict_types=1);

namespace App\Filament\Business\Resources;

use App\Filament\Business\Resources\ProductionOrderResource\Pages;
use App\Models\ProductionOrder;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ProductionOrderResource extends Resource
{
    protected static ?string $model = ProductionOrder::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationGroup = 'Fulfillment';

    public static function form(Form $form): Form
    {
        return $form->schema([
                Forms\Components\TextInput::make('status'),
                Forms\Components\TextInput::make('priority'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('status'),
                Tables\Columns\TextColumn::make('priority'),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProductionOrders::route('/'),
            'create' => Pages\CreateProductionOrder::route('/create'),
            'edit' => Pages\EditProductionOrder::route('/{record}/edit'),
        ];
    }}
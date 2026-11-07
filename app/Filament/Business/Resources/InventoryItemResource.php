<?php

declare(strict_types=1);

namespace App\Filament\Business\Resources;

use App\Filament\Business\Resources\InventoryItemResource\Pages;
use App\Models\InventoryItem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class InventoryItemResource extends Resource
{
    protected static ?string $model = InventoryItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';

    protected static ?string $navigationGroup = 'Inventory';

    public static function form(Form $form): Form
    {
        return $form->schema([
                Forms\Components\TextInput::make('sku'),
                Forms\Components\TextInput::make('quantity_on_hand')->numeric(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('sku'),
                Tables\Columns\TextColumn::make('quantity_on_hand'),
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
            'index' => Pages\ListInventoryItems::route('/'),
            'create' => Pages\CreateInventoryItem::route('/create'),
            'edit' => Pages\EditInventoryItem::route('/{record}/edit'),
        ];
    }}
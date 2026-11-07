<?php

declare(strict_types=1);

namespace App\Filament\Business\Resources;

use App\Filament\Business\Resources\FulfillmentOrderResource\Pages;
use App\Models\FulfillmentOrder;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class FulfillmentOrderResource extends Resource
{
    protected static ?string $model = FulfillmentOrder::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';

    protected static ?string $navigationGroup = 'Fulfillment';

    public static function form(Form $form): Form
    {
        return $form->schema([
                Forms\Components\TextInput::make('status'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('status'),
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
            'index' => Pages\ListFulfillmentOrders::route('/'),
            'create' => Pages\CreateFulfillmentOrder::route('/create'),
            'edit' => Pages\EditFulfillmentOrder::route('/{record}/edit'),
        ];
    }}
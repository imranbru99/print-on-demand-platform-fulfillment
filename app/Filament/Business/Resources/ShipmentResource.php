<?php

declare(strict_types=1);

namespace App\Filament\Business\Resources;

use App\Filament\Business\Resources\ShipmentResource\Pages;
use App\Models\Shipment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ShipmentResource extends Resource
{
    protected static ?string $model = Shipment::class;

    protected static ?string $navigationIcon = 'heroicon-o-paper-airplane';

    protected static ?string $navigationGroup = 'Fulfillment';

    public static function form(Form $form): Form
    {
        return $form->schema([
                Forms\Components\TextInput::make('tracking_number'),
                Forms\Components\TextInput::make('status'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tracking_number'),
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
            'index' => Pages\ListShipments::route('/'),
            'create' => Pages\CreateShipment::route('/create'),
            'edit' => Pages\EditShipment::route('/{record}/edit'),
        ];
    }}
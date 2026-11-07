<?php

declare(strict_types=1);

namespace App\Filament\Business\Resources;

use App\Filament\Business\Resources\ReturnResource\Pages;
use App\Models\OrderReturn;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ReturnResource extends Resource
{
    protected static ?string $model = OrderReturn::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-uturn-left';

    protected static ?string $navigationGroup = 'Fulfillment';

    public static function form(Form $form): Form
    {
        return $form->schema([
                Forms\Components\TextInput::make('status'),
                Forms\Components\Textarea::make('reason'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('status'),
                Tables\Columns\TextColumn::make('reason'),
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
            'index' => Pages\ListReturns::route('/'),
            'create' => Pages\CreateReturn::route('/create'),
            'edit' => Pages\EditReturn::route('/{record}/edit'),
        ];
    }}
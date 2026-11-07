<?php

declare(strict_types=1);

namespace App\Filament\Platform\Resources;

use App\Filament\Platform\Resources\PlanResource\Pages;
use App\Models\Plan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PlanResource extends Resource
{
    protected static ?string $model = Plan::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    protected static ?string $navigationGroup = 'Billing';

    public static function form(Form $form): Form
    {
        return $form->schema([
                Forms\Components\TextInput::make('key'),
                Forms\Components\TextInput::make('name'),
                Forms\Components\TextInput::make('price')->numeric(),
                Forms\Components\TextInput::make('currency'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('key'),
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('price'),
                Tables\Columns\TextColumn::make('currency'),
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
            'index' => Pages\ListPlans::route('/'),
            'create' => Pages\CreatePlan::route('/create'),
            'edit' => Pages\EditPlan::route('/{record}/edit'),
        ];
    }}
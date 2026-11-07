<?php

declare(strict_types=1);

namespace App\Filament\Business\Resources\ProductResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class PricingRelationManager extends RelationManager
{
    protected static string $relationship = 'pricing';

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('variant_id')->label('Variant ID'),
            Forms\Components\TextInput::make('base_price')->numeric()->required(),
            Forms\Components\TextInput::make('currency')->default('USD'),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('variant_id'),
                Tables\Columns\TextColumn::make('base_price')->money('USD'),
                Tables\Columns\TextColumn::make('currency'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }
}

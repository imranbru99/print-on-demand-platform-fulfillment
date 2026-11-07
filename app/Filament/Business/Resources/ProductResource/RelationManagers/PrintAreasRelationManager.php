<?php

declare(strict_types=1);

namespace App\Filament\Business\Resources\ProductResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class PrintAreasRelationManager extends RelationManager
{
    protected static string $relationship = 'printAreas';

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')->required(),
            Forms\Components\TextInput::make('width_mm')->numeric()->label('Width (mm)'),
            Forms\Components\TextInput::make('height_mm')->numeric()->label('Height (mm)'),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('width_mm'),
                Tables\Columns\TextColumn::make('height_mm'),
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

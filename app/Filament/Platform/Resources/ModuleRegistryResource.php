<?php

declare(strict_types=1);

namespace App\Filament\Platform\Resources;

use App\Filament\Platform\Resources\ModuleRegistryResource\Pages;
use App\Models\ModuleRegistry;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ModuleRegistryResource extends Resource
{
    protected static ?string $model = ModuleRegistry::class;

    protected static ?string $navigationIcon = 'heroicon-o-puzzle-piece';

    protected static ?string $navigationGroup = 'Modules';

    public static function form(Form $form): Form
    {
        return $form->schema([
                Forms\Components\TextInput::make('key'),
                Forms\Components\TextInput::make('name'),
                Forms\Components\TextInput::make('version'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('key'),
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('version'),
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
            'index' => Pages\ListModuleRegistries::route('/'),
            'create' => Pages\CreateModuleRegistry::route('/create'),
            'edit' => Pages\EditModuleRegistry::route('/{record}/edit'),
        ];
    }}
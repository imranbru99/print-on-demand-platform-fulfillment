<?php

declare(strict_types=1);

namespace App\Filament\Business\Resources;

use App\Filament\Business\Resources\MockupResource\Pages;
use App\Models\Mockup;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class MockupResource extends Resource
{
    protected static ?string $model = Mockup::class;

    protected static ?string $navigationIcon = 'heroicon-o-device-phone-mobile';

    protected static ?string $navigationGroup = 'Design';

    public static function form(Form $form): Form
    {
        return $form->schema([
                Forms\Components\TextInput::make('name'),
                Forms\Components\TextInput::make('status'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
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
            'index' => Pages\ListMockups::route('/'),
            'create' => Pages\CreateMockup::route('/create'),
            'edit' => Pages\EditMockup::route('/{record}/edit'),
        ];
    }}
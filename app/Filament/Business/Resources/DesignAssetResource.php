<?php

declare(strict_types=1);

namespace App\Filament\Business\Resources;

use App\Filament\Business\Resources\DesignAssetResource\Pages;
use App\Models\DesignAsset;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class DesignAssetResource extends Resource
{
    protected static ?string $model = DesignAsset::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static ?string $navigationGroup = 'Design';

    public static function form(Form $form): Form
    {
        return $form->schema([
                Forms\Components\TextInput::make('name'),
                Forms\Components\TextInput::make('mime_type'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('mime_type'),
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
            'index' => Pages\ListDesignAssets::route('/'),
            'create' => Pages\CreateDesignAsset::route('/create'),
            'edit' => Pages\EditDesignAsset::route('/{record}/edit'),
        ];
    }}
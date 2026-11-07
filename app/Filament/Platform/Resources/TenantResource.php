<?php

declare(strict_types=1);

namespace App\Filament\Platform\Resources;

use App\Filament\Platform\Resources\TenantResource\Pages;
use App\Models\Tenant;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TenantResource extends Resource
{
    protected static ?string $model = Tenant::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    protected static ?string $navigationGroup = 'Tenancy';

    public static function form(Form $form): Form
    {
        return $form->schema([
                Forms\Components\TextInput::make('name'),
                Forms\Components\TextInput::make('slug'),
                Forms\Components\TextInput::make('status'),
                Forms\Components\TextInput::make('billing_email'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('slug'),
                Tables\Columns\TextColumn::make('status'),
                Tables\Columns\TextColumn::make('billing_email'),
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
            'index' => Pages\ListTenants::route('/'),
            'create' => Pages\CreateTenant::route('/create'),
            'edit' => Pages\EditTenant::route('/{record}/edit'),
        ];
    }}
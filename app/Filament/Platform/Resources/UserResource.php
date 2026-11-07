<?php

declare(strict_types=1);

namespace App\Filament\Platform\Resources;

use App\Filament\Platform\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'Access';

    public static function form(Form $form): Form
    {
        return $form->schema([
                Forms\Components\TextInput::make('name'),
                Forms\Components\TextInput::make('email'),
                Forms\Components\TextInput::make('type'),
                Forms\Components\Toggle::make('is_platform_user'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('email'),
                Tables\Columns\TextColumn::make('type'),
                Tables\Columns\TextColumn::make('is_platform_user'),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where(function (Builder $query): void {
            $query->where('is_platform_user', true)->orWhere('type', 'platform');
        });
    }
}
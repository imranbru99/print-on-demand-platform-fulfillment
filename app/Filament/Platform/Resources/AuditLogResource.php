<?php

declare(strict_types=1);

namespace App\Filament\Platform\Resources;

use App\Filament\Platform\Resources\AuditLogResource\Pages;
use App\Models\AuditLog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class AuditLogResource extends Resource
{
    protected static ?string $model = AuditLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationGroup = 'Compliance';

    public static function form(Form $form): Form
    {
        return $form->schema([
                Forms\Components\TextInput::make('event'),
                Forms\Components\TextInput::make('auditable_type'),
                Forms\Components\DateTimePicker::make('created_at'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('event'),
                Tables\Columns\TextColumn::make('auditable_type'),
                Tables\Columns\TextColumn::make('created_at'),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAuditLogs::route('/'),
            'view' => Pages\ViewAuditLog::route('/{record}'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withoutGlobalScopes();
    }
}
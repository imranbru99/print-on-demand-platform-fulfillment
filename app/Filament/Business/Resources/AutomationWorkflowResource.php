<?php

declare(strict_types=1);

namespace App\Filament\Business\Resources;

use App\Filament\Business\Resources\AutomationWorkflowResource\Pages;
use App\Models\AutomationWorkflow;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AutomationWorkflowResource extends Resource
{
    protected static ?string $model = AutomationWorkflow::class;

    protected static ?string $navigationIcon = 'heroicon-o-bolt';

    protected static ?string $navigationGroup = 'Automation';

    protected static ?string $navigationLabel = 'Workflows';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')->required()->maxLength(120),
            Forms\Components\TextInput::make('trigger')
                ->required()
                ->helperText('Examples: order.paid, order.status.in_production'),
            Forms\Components\Textarea::make('definition')
                ->rows(6)
                ->helperText('JSON definition, e.g. {"steps":[{"action":"notify"}]}')
                ->formatStateUsing(fn ($state) => is_array($state) ? json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) : (string) $state)
                ->dehydrateStateUsing(function (?string $state): array {
                    if ($state === null || trim($state) === '') {
                        return [];
                    }

                    $decoded = json_decode($state, true);

                    return is_array($decoded) ? $decoded : [];
                }),
            Forms\Components\Toggle::make('is_active')->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('trigger'),
                Tables\Columns\IconColumn::make('is_active')->boolean(),
                Tables\Columns\TextColumn::make('updated_at')->dateTime()->sortable(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAutomationWorkflows::route('/'),
            'create' => Pages\CreateAutomationWorkflow::route('/create'),
            'edit' => Pages\EditAutomationWorkflow::route('/{record}/edit'),
        ];
    }
}

<?php

declare(strict_types=1);

namespace App\Filament\Production\Resources;

use App\Filament\Production\Resources\QualityCheckResource\Pages;
use App\Models\QualityCheck;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class QualityCheckResource extends Resource
{
    protected static ?string $model = QualityCheck::class;

    protected static ?string $navigationIcon = 'heroicon-o-check-badge';

    protected static ?string $navigationGroup = 'QC';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('production_job_id')
                ->label('Production job ID')
                ->required(),
            Forms\Components\Select::make('status')
                ->options([
                    'pending' => 'Pending',
                    'passed' => 'Passed',
                    'failed' => 'Failed',
                ])
                ->required(),
            Forms\Components\Textarea::make('notes')->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('production_job_id')->label('Job')->limit(10),
                Tables\Columns\TextColumn::make('status')->badge(),
                Tables\Columns\TextColumn::make('notes')->limit(40),
                Tables\Columns\TextColumn::make('updated_at')->since(),
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
            'index' => Pages\ListQualityChecks::route('/'),
            'create' => Pages\CreateQualityCheck::route('/create'),
            'edit' => Pages\EditQualityCheck::route('/{record}/edit'),
        ];
    }
}

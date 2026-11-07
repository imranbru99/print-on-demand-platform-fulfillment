<?php

declare(strict_types=1);

namespace App\Filament\Production\Resources;

use App\Enums\ProductionStatus;
use App\Filament\Production\Resources\ProductionJobResource\Pages;
use App\Models\ProductionJob;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ProductionJobResource extends Resource
{
    protected static ?string $model = ProductionJob::class;

    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';

    protected static ?string $navigationGroup = 'Floor';

    protected static ?string $navigationLabel = 'All production jobs';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('status')
                ->options(collect(ProductionStatus::cases())->mapWithKeys(fn (ProductionStatus $s) => [$s->value => str($s->value)->headline()])),
            Forms\Components\TextInput::make('station'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('Job')->limit(10),
                Tables\Columns\TextColumn::make('status')->badge(),
                Tables\Columns\TextColumn::make('station'),
                Tables\Columns\TextColumn::make('updated_at')->since(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('advance')
                    ->label('Mark in progress')
                    ->visible(fn (ProductionJob $record): bool => in_array($record->status, [ProductionStatus::Queued->value, ProductionStatus::Assigned->value], true))
                    ->action(fn (ProductionJob $record) => static::setStatus($record, ProductionStatus::InProgress)),
                Tables\Actions\Action::make('complete')
                    ->color('success')
                    ->visible(fn (ProductionJob $record): bool => $record->status === ProductionStatus::InProgress->value)
                    ->action(fn (ProductionJob $record) => static::setStatus($record, ProductionStatus::Completed)),
            ])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereHas('assignments', fn (Builder $query) => $query->where('user_id', auth()->id()));
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProductionJobs::route('/'),
            'create' => Pages\CreateProductionJob::route('/create'),
            'edit' => Pages\EditProductionJob::route('/{record}/edit'),
        ];
    }

    private static function setStatus(ProductionJob $record, ProductionStatus $status): void
    {
        $record->update(['status' => $status->value]);

        Notification::make()
            ->title('Job marked as '.$status->value)
            ->success()
            ->send();
    }
}

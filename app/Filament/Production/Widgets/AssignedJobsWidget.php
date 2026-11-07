<?php

declare(strict_types=1);

namespace App\Filament\Production\Widgets;

use App\Enums\ProductionStatus;
use App\Models\ProductionJob;
use Filament\Notifications\Notification;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class AssignedJobsWidget extends BaseWidget
{
    protected static ?string $heading = 'Jobs assigned to you';

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                ProductionJob::query()
                    ->whereHas('assignments', fn (Builder $query) => $query->where('user_id', auth()->id()))
            )
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('Job ID')->limit(12),
                Tables\Columns\TextColumn::make('status')->badge(),
                Tables\Columns\TextColumn::make('station'),
                Tables\Columns\TextColumn::make('updated_at')->dateTime(),
            ])
            ->actions([
                Tables\Actions\Action::make('start')
                    ->visible(fn (ProductionJob $record): bool => $record->status === ProductionStatus::Queued->value || $record->status === ProductionStatus::Assigned->value)
                    ->action(fn (ProductionJob $record) => $this->updateStatus($record, ProductionStatus::InProgress)),
                Tables\Actions\Action::make('complete')
                    ->color('success')
                    ->visible(fn (ProductionJob $record): bool => $record->status === ProductionStatus::InProgress->value)
                    ->action(fn (ProductionJob $record) => $this->updateStatus($record, ProductionStatus::Completed)),
            ]);
    }

    private function updateStatus(ProductionJob $record, ProductionStatus $status): void
    {
        $record->update(['status' => $status->value]);

        Notification::make()
            ->title('Production job updated to '.$status->value)
            ->success()
            ->send();
    }
}

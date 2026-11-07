<?php

declare(strict_types=1);

namespace App\Filament\Business\Resources;

use App\Enums\OrderStatus;
use App\Filament\Business\Resources\OrderResource\Pages;
use App\Models\Order;
use App\Services\OrderStateMachine;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Throwable;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $navigationGroup = 'Sales';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('number')->required(),
            Forms\Components\Select::make('status')
                ->options(collect(OrderStatus::cases())->mapWithKeys(fn (OrderStatus $s) => [$s->value => str($s->value)->headline()]))
                ->disabled(),
            Forms\Components\TextInput::make('grand_total')->numeric()->prefix('$'),
            Forms\Components\TextInput::make('customer_id'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('number')->searchable(),
                Tables\Columns\TextColumn::make('status')->badge(),
                Tables\Columns\TextColumn::make('grand_total')->money('USD'),
                Tables\Columns\TextColumn::make('customer.email')->label('Customer'),
                Tables\Columns\TextColumn::make('created_at')->dateTime(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ActionGroup::make(static::transitionActions())
                    ->label('Transition')
                    ->icon('heroicon-m-arrow-path'),
            ])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    /**
     * @return list<Tables\Actions\Action>
     */
    protected static function transitionActions(): array
    {
        $machine = app(OrderStateMachine::class);
        $actions = [];

        foreach (OrderStatus::cases() as $target) {
            $actions[] = Tables\Actions\Action::make('to_'.$target->value)
                ->label('Mark as '.str($target->value)->headline())
                ->visible(function (Order $record) use ($target): bool {
                    $current = OrderStatus::tryFrom($record->status) ?? OrderStatus::Draft;

                    return $current->canTransitionTo($target);
                })
                ->action(function (Order $record) use ($machine, $target): void {
                    try {
                        $machine->transition($record, $target, auth()->id());
                        Notification::make()->title('Order status updated')->success()->send();
                    } catch (Throwable $exception) {
                        Notification::make()->title('Transition failed')->body($exception->getMessage())->danger()->send();
                    }
                });
        }

        return $actions;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}

<?php

declare(strict_types=1);

namespace App\Filament\Business\Resources;

use App\Enums\ProductStatus;
use App\Filament\Business\Resources\ProductResource\Pages;
use App\Filament\Business\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use ImranDevBd\AiCatalog\Facades\AiCatalog;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';

    protected static ?string $navigationGroup = 'Catalog';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')->required(),
            Forms\Components\TextInput::make('slug')->required(),
            Forms\Components\Select::make('status')
                ->options(collect(ProductStatus::cases())->mapWithKeys(fn (ProductStatus $s) => [$s->value => str($s->value)->headline()])),
            Forms\Components\TextInput::make('store_id')->label('Store ID'),
            Forms\Components\TextInput::make('category_id')->label('Category ID'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('slug'),
                Tables\Columns\TextColumn::make('status')->badge(),
                Tables\Columns\TextColumn::make('variants_count')->counts('variants'),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\Action::make('aiDraft')
                    ->label('AI draft')
                    ->icon('heroicon-o-sparkles')
                    ->form([
                        Forms\Components\TextInput::make('keyword')
                            ->label('Seed keyword')
                            ->required()
                            ->maxLength(120),
                    ])
                    ->action(function (Product $record, array $data): void {
                        try {
                            AiCatalog::forProduct($record)
                                ->fromKeyword($data['keyword'])
                                ->generate();

                            $record->update(['status' => ProductStatus::Draft->value]);

                            Notification::make()
                                ->title('AI catalog draft generated')
                                ->body('Product kept in draft — review before publishing.')
                                ->success()
                                ->send();
                        } catch (\Throwable $exception) {
                            Notification::make()
                                ->title('AI generation failed')
                                ->body($exception->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\VariantsRelationManager::class,
            RelationManagers\PrintAreasRelationManager::class,
            RelationManagers\PricingRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}

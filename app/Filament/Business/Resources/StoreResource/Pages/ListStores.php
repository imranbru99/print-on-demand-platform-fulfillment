<?php

declare(strict_types=1);

namespace App\Filament\Business\Resources\StoreResource\Pages;

use App\Filament\Business\Resources\StoreResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStores extends ListRecords
{
    protected static string $resource = StoreResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->visible(true),
        ];
    }
}

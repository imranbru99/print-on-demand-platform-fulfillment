<?php

declare(strict_types=1);

namespace App\Filament\Business\Resources\MockupResource\Pages;

use App\Filament\Business\Resources\MockupResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMockups extends ListRecords
{
    protected static string $resource = MockupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->visible(true),
        ];
    }
}

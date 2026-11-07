<?php

declare(strict_types=1);

namespace App\Filament\Business\Resources\ReturnResource\Pages;

use App\Filament\Business\Resources\ReturnResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListReturns extends ListRecords
{
    protected static string $resource = ReturnResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->visible(true),
        ];
    }
}

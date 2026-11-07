<?php

declare(strict_types=1);

namespace App\Filament\Business\Resources\ShipmentResource\Pages;

use App\Filament\Business\Resources\ShipmentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListShipments extends ListRecords
{
    protected static string $resource = ShipmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->visible(true),
        ];
    }
}

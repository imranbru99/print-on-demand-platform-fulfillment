<?php

declare(strict_types=1);

namespace App\Filament\Business\Resources\ProductionOrderResource\Pages;

use App\Filament\Business\Resources\ProductionOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProductionOrders extends ListRecords
{
    protected static string $resource = ProductionOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->visible(true),
        ];
    }
}

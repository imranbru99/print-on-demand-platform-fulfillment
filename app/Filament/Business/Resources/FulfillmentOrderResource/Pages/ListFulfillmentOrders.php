<?php

declare(strict_types=1);

namespace App\Filament\Business\Resources\FulfillmentOrderResource\Pages;

use App\Filament\Business\Resources\FulfillmentOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFulfillmentOrders extends ListRecords
{
    protected static string $resource = FulfillmentOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->visible(true),
        ];
    }
}

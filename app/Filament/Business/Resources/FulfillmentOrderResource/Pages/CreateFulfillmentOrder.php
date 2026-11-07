<?php

declare(strict_types=1);

namespace App\Filament\Business\Resources\FulfillmentOrderResource\Pages;

use App\Filament\Business\Resources\FulfillmentOrderResource;
use Filament\Resources\Pages\CreateRecord;

class CreateFulfillmentOrder extends CreateRecord
{
    protected static string $resource = FulfillmentOrderResource::class;
}

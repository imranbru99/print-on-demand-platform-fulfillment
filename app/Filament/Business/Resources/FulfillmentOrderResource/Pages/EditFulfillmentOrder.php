<?php

declare(strict_types=1);

namespace App\Filament\Business\Resources\FulfillmentOrderResource\Pages;

use App\Filament\Business\Resources\FulfillmentOrderResource;
use Filament\Resources\Pages\EditRecord;

class EditFulfillmentOrder extends EditRecord
{
    protected static string $resource = FulfillmentOrderResource::class;
}

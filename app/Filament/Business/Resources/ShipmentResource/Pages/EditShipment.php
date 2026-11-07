<?php

declare(strict_types=1);

namespace App\Filament\Business\Resources\ShipmentResource\Pages;

use App\Filament\Business\Resources\ShipmentResource;
use Filament\Resources\Pages\EditRecord;

class EditShipment extends EditRecord
{
    protected static string $resource = ShipmentResource::class;
}

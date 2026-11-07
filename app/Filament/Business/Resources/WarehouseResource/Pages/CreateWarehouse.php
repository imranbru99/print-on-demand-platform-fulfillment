<?php

declare(strict_types=1);

namespace App\Filament\Business\Resources\WarehouseResource\Pages;

use App\Filament\Business\Resources\WarehouseResource;
use Filament\Resources\Pages\CreateRecord;

class CreateWarehouse extends CreateRecord
{
    protected static string $resource = WarehouseResource::class;
}

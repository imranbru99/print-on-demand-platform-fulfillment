<?php

declare(strict_types=1);

namespace App\Filament\Business\Resources\InventoryItemResource\Pages;

use App\Filament\Business\Resources\InventoryItemResource;
use Filament\Resources\Pages\CreateRecord;

class CreateInventoryItem extends CreateRecord
{
    protected static string $resource = InventoryItemResource::class;
}

<?php

declare(strict_types=1);

namespace App\Filament\Business\Resources\InventoryItemResource\Pages;

use App\Filament\Business\Resources\InventoryItemResource;
use Filament\Resources\Pages\EditRecord;

class EditInventoryItem extends EditRecord
{
    protected static string $resource = InventoryItemResource::class;
}

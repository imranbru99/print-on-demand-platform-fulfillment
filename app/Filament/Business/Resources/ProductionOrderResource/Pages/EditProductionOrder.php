<?php

declare(strict_types=1);

namespace App\Filament\Business\Resources\ProductionOrderResource\Pages;

use App\Filament\Business\Resources\ProductionOrderResource;
use Filament\Resources\Pages\EditRecord;

class EditProductionOrder extends EditRecord
{
    protected static string $resource = ProductionOrderResource::class;
}

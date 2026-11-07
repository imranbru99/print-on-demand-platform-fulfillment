<?php

declare(strict_types=1);

namespace App\Filament\Business\Resources\ProductionOrderResource\Pages;

use App\Filament\Business\Resources\ProductionOrderResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProductionOrder extends CreateRecord
{
    protected static string $resource = ProductionOrderResource::class;
}

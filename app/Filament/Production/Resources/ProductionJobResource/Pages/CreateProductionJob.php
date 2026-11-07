<?php

declare(strict_types=1);

namespace App\Filament\Production\Resources\ProductionJobResource\Pages;

use App\Filament\Production\Resources\ProductionJobResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProductionJob extends CreateRecord
{
    protected static string $resource = ProductionJobResource::class;
}

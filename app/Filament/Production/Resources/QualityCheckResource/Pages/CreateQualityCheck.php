<?php

declare(strict_types=1);

namespace App\Filament\Production\Resources\QualityCheckResource\Pages;

use App\Filament\Production\Resources\QualityCheckResource;
use Filament\Resources\Pages\CreateRecord;

class CreateQualityCheck extends CreateRecord
{
    protected static string $resource = QualityCheckResource::class;
}

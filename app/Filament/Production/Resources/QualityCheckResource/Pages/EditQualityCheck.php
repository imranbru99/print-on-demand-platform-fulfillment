<?php

declare(strict_types=1);

namespace App\Filament\Production\Resources\QualityCheckResource\Pages;

use App\Filament\Production\Resources\QualityCheckResource;
use Filament\Resources\Pages\EditRecord;

class EditQualityCheck extends EditRecord
{
    protected static string $resource = QualityCheckResource::class;
}

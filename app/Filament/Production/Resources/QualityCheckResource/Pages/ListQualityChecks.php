<?php

declare(strict_types=1);

namespace App\Filament\Production\Resources\QualityCheckResource\Pages;

use App\Filament\Production\Resources\QualityCheckResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListQualityChecks extends ListRecords
{
    protected static string $resource = QualityCheckResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->visible(true),
        ];
    }
}

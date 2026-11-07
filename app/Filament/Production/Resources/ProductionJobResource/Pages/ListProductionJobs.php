<?php

declare(strict_types=1);

namespace App\Filament\Production\Resources\ProductionJobResource\Pages;

use App\Filament\Production\Resources\ProductionJobResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProductionJobs extends ListRecords
{
    protected static string $resource = ProductionJobResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->visible(true),
        ];
    }
}

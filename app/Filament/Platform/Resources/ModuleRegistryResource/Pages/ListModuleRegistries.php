<?php

declare(strict_types=1);

namespace App\Filament\Platform\Resources\ModuleRegistryResource\Pages;

use App\Filament\Platform\Resources\ModuleRegistryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListModuleRegistries extends ListRecords
{
    protected static string $resource = ModuleRegistryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->visible(true),
        ];
    }
}

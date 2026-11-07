<?php

declare(strict_types=1);

namespace App\Filament\Platform\Resources\ModuleRegistryResource\Pages;

use App\Filament\Platform\Resources\ModuleRegistryResource;
use Filament\Resources\Pages\EditRecord;

class EditModuleRegistry extends EditRecord
{
    protected static string $resource = ModuleRegistryResource::class;
}

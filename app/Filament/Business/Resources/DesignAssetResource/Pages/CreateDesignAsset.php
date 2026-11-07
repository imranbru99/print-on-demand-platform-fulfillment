<?php

declare(strict_types=1);

namespace App\Filament\Business\Resources\DesignAssetResource\Pages;

use App\Filament\Business\Resources\DesignAssetResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDesignAsset extends CreateRecord
{
    protected static string $resource = DesignAssetResource::class;
}

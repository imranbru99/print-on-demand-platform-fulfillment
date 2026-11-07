<?php

declare(strict_types=1);

namespace App\Filament\Business\Resources\DesignAssetResource\Pages;

use App\Filament\Business\Resources\DesignAssetResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDesignAssets extends ListRecords
{
    protected static string $resource = DesignAssetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->visible(true),
        ];
    }
}

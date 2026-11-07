<?php

declare(strict_types=1);

namespace App\Filament\Platform\Resources\AuditLogResource\Pages;

use App\Filament\Platform\Resources\AuditLogResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAuditLogs extends ListRecords
{
    protected static string $resource = AuditLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->visible(false),
        ];
    }
}

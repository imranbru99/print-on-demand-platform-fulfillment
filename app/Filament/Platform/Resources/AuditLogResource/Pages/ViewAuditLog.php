<?php

declare(strict_types=1);

namespace App\Filament\Platform\Resources\AuditLogResource\Pages;

use App\Filament\Platform\Resources\AuditLogResource;
use Filament\Resources\Pages\ViewRecord;

class ViewAuditLog extends ViewRecord
{
    protected static string $resource = AuditLogResource::class;
}

<?php

declare(strict_types=1);

namespace App\Filament\Business\Resources\SupportTicketResource\Pages;

use App\Filament\Business\Resources\SupportTicketResource;
use Filament\Resources\Pages\EditRecord;

class EditSupportTicket extends EditRecord
{
    protected static string $resource = SupportTicketResource::class;
}

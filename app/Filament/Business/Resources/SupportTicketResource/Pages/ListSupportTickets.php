<?php

declare(strict_types=1);

namespace App\Filament\Business\Resources\SupportTicketResource\Pages;

use App\Filament\Business\Resources\SupportTicketResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSupportTickets extends ListRecords
{
    protected static string $resource = SupportTicketResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->visible(true),
        ];
    }
}

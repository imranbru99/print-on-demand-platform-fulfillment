<?php

declare(strict_types=1);

namespace App\Filament\Business\Resources\AutomationWorkflowResource\Pages;

use App\Filament\Business\Resources\AutomationWorkflowResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAutomationWorkflows extends ListRecords
{
    protected static string $resource = AutomationWorkflowResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

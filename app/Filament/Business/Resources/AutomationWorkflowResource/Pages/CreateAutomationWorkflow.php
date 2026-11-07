<?php

declare(strict_types=1);

namespace App\Filament\Business\Resources\AutomationWorkflowResource\Pages;

use App\Filament\Business\Resources\AutomationWorkflowResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAutomationWorkflow extends CreateRecord
{
    protected static string $resource = AutomationWorkflowResource::class;
}

<?php

declare(strict_types=1);

namespace App\Filament\Business\Resources\EmployeeResource\Pages;

use App\Filament\Business\Resources\EmployeeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEmployees extends ListRecords
{
    protected static string $resource = EmployeeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->visible(true),
        ];
    }
}

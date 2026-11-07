<?php

declare(strict_types=1);

namespace App\Filament\Business\Resources\EmployeeResource\Pages;

use App\Filament\Business\Resources\EmployeeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEmployee extends CreateRecord
{
    protected static string $resource = EmployeeResource::class;
}

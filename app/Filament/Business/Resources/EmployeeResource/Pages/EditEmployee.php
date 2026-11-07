<?php

declare(strict_types=1);

namespace App\Filament\Business\Resources\EmployeeResource\Pages;

use App\Filament\Business\Resources\EmployeeResource;
use Filament\Resources\Pages\EditRecord;

class EditEmployee extends EditRecord
{
    protected static string $resource = EmployeeResource::class;
}

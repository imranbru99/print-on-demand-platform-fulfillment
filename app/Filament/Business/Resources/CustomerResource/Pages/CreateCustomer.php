<?php

declare(strict_types=1);

namespace App\Filament\Business\Resources\CustomerResource\Pages;

use App\Filament\Business\Resources\CustomerResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCustomer extends CreateRecord
{
    protected static string $resource = CustomerResource::class;
}

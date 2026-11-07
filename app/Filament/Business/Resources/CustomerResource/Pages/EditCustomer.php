<?php

declare(strict_types=1);

namespace App\Filament\Business\Resources\CustomerResource\Pages;

use App\Filament\Business\Resources\CustomerResource;
use Filament\Resources\Pages\EditRecord;

class EditCustomer extends EditRecord
{
    protected static string $resource = CustomerResource::class;
}

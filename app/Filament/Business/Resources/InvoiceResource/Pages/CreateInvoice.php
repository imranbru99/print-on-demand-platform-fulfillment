<?php

declare(strict_types=1);

namespace App\Filament\Business\Resources\InvoiceResource\Pages;

use App\Filament\Business\Resources\InvoiceResource;
use Filament\Resources\Pages\CreateRecord;

class CreateInvoice extends CreateRecord
{
    protected static string $resource = InvoiceResource::class;
}

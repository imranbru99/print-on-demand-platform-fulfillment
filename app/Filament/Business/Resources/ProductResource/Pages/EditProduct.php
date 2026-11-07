<?php

declare(strict_types=1);

namespace App\Filament\Business\Resources\ProductResource\Pages;

use App\Filament\Business\Resources\ProductResource;
use Filament\Resources\Pages\EditRecord;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;
}

<?php

declare(strict_types=1);

namespace App\Filament\Business\Resources\StoreResource\Pages;

use App\Filament\Business\Resources\StoreResource;
use Filament\Resources\Pages\EditRecord;

class EditStore extends EditRecord
{
    protected static string $resource = StoreResource::class;
}

<?php

declare(strict_types=1);

namespace App\Filament\Business\Resources\MockupResource\Pages;

use App\Filament\Business\Resources\MockupResource;
use Filament\Resources\Pages\CreateRecord;

class CreateMockup extends CreateRecord
{
    protected static string $resource = MockupResource::class;
}

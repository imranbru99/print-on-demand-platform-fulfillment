<?php

declare(strict_types=1);

namespace App\Filament\Business\Resources\MockupResource\Pages;

use App\Filament\Business\Resources\MockupResource;
use Filament\Resources\Pages\EditRecord;

class EditMockup extends EditRecord
{
    protected static string $resource = MockupResource::class;
}

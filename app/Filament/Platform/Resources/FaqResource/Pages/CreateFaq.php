<?php

declare(strict_types=1);

namespace App\Filament\Platform\Resources\FaqResource\Pages;

use App\Filament\Platform\Resources\FaqResource;
use Filament\Resources\Pages\CreateRecord;

class CreateFaq extends CreateRecord
{
    protected static string $resource = FaqResource::class;
}

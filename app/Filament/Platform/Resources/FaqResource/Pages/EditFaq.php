<?php

declare(strict_types=1);

namespace App\Filament\Platform\Resources\FaqResource\Pages;

use App\Filament\Platform\Resources\FaqResource;
use Filament\Resources\Pages\EditRecord;

class EditFaq extends EditRecord
{
    protected static string $resource = FaqResource::class;
}

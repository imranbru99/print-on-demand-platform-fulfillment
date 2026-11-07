<?php

declare(strict_types=1);

namespace App\Filament\Platform\Resources\FaqResource\Pages;

use App\Filament\Platform\Resources\FaqResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFaqs extends ListRecords
{
    protected static string $resource = FaqResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->visible(true),
        ];
    }
}

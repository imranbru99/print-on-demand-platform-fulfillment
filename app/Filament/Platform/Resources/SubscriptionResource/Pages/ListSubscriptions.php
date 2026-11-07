<?php

declare(strict_types=1);

namespace App\Filament\Platform\Resources\SubscriptionResource\Pages;

use App\Filament\Platform\Resources\SubscriptionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSubscriptions extends ListRecords
{
    protected static string $resource = SubscriptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->visible(true),
        ];
    }
}

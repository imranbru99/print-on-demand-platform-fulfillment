<?php

declare(strict_types=1);

namespace App\Filament\Platform\Resources\SubscriptionResource\Pages;

use App\Filament\Platform\Resources\SubscriptionResource;
use Filament\Resources\Pages\EditRecord;

class EditSubscription extends EditRecord
{
    protected static string $resource = SubscriptionResource::class;
}

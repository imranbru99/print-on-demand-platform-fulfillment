<?php

declare(strict_types=1);

namespace App\Filament\Business\Resources\CampaignResource\Pages;

use App\Filament\Business\Resources\CampaignResource;
use Filament\Resources\Pages\EditRecord;

class EditCampaign extends EditRecord
{
    protected static string $resource = CampaignResource::class;
}

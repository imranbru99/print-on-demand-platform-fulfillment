<?php

declare(strict_types=1);

namespace App\Filament\Business\Resources\CampaignResource\Pages;

use App\Filament\Business\Resources\CampaignResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCampaign extends CreateRecord
{
    protected static string $resource = CampaignResource::class;
}

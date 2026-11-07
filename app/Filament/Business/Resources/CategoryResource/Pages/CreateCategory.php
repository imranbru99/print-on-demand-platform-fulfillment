<?php

declare(strict_types=1);

namespace App\Filament\Business\Resources\CategoryResource\Pages;

use App\Filament\Business\Resources\CategoryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCategory extends CreateRecord
{
    protected static string $resource = CategoryResource::class;
}

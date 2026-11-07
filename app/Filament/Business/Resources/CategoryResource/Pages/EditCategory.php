<?php

declare(strict_types=1);

namespace App\Filament\Business\Resources\CategoryResource\Pages;

use App\Filament\Business\Resources\CategoryResource;
use Filament\Resources\Pages\EditRecord;

class EditCategory extends EditRecord
{
    protected static string $resource = CategoryResource::class;
}

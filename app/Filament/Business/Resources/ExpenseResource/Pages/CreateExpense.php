<?php

declare(strict_types=1);

namespace App\Filament\Business\Resources\ExpenseResource\Pages;

use App\Filament\Business\Resources\ExpenseResource;
use Filament\Resources\Pages\CreateRecord;

class CreateExpense extends CreateRecord
{
    protected static string $resource = ExpenseResource::class;
}

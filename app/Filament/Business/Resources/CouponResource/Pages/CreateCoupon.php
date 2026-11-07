<?php

declare(strict_types=1);

namespace App\Filament\Business\Resources\CouponResource\Pages;

use App\Filament\Business\Resources\CouponResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCoupon extends CreateRecord
{
    protected static string $resource = CouponResource::class;
}

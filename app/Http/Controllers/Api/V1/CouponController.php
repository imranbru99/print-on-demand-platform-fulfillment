<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\CouponResource;
use App\Models\Coupon;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

final class CouponController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $coupons = Coupon::query()->orderBy('code')->paginate(20);

        return CouponResource::collection($coupons);
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\View\View;

final class HomeController extends Controller
{
    public function __invoke(): View
    {
        $plans = Plan::query()->where('is_active', true)->orderBy('price')->limit(3)->get();

        return view('marketing.home', [
            'plans' => $plans,
        ]);
    }
}

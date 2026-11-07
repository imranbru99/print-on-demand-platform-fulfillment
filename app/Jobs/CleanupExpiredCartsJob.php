<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Cart;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;

final class CleanupExpiredCartsJob implements ShouldQueue
{
    use Queueable;

    public function handle(): void
    {
        $staleBefore = now()->subDays(30);

        Cart::query()
            ->withoutGlobalScopes()
            ->where(function ($query) use ($staleBefore): void {
                $query->where('expires_at', '<', now())
                    ->orWhere(function ($inner) use ($staleBefore): void {
                        $inner->whereNull('expires_at')
                            ->where('updated_at', '<', $staleBefore);
                    });
            })
            ->orderBy('id')
            ->chunkById(100, function ($carts): void {
                foreach ($carts as $cart) {
                    DB::transaction(function () use ($cart): void {
                        $cart->items()->delete();
                        $cart->delete();
                    });
                }
            });
    }
}

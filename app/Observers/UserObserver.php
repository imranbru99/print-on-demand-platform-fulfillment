<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\User;
use Illuminate\Support\Str;

final class UserObserver
{
    public function creating(User $user): void
    {
        if ($user->ulid === null) {
            $user->ulid = (string) Str::ulid();
        }
    }
}

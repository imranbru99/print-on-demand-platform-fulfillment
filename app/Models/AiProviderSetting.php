<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class AiProviderSetting extends Model
{
    use HasUlids;

    protected $fillable = [
        'provider',
        'config',
        'is_active',
    ];
}

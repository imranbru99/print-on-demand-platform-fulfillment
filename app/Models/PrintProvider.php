<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class PrintProvider extends Model
{
    use HasUlids;

    protected $fillable = [
        'key',
        'name',
        'capabilities',
    ];

    protected function casts(): array
    {
        return [
            'capabilities' => 'array',
        ];
    }
}

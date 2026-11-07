<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasUlids;

    protected $fillable = [
        'key',
        'name',
        'price',
        'currency',
        'features',
    ];
    public function subscriptions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    protected function casts(): array
    {
        return [
            'features' => 'array',
            'price' => 'decimal:4',
            'is_active' => 'boolean',
        ];
    }
}

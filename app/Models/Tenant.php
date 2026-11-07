<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    use HasUlids;

    protected $fillable = [
        'name',
        'slug',
        'status',
        'billing_email',
        'metadata',
    ];
    public function domains(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TenantDomain::class);
    }

    public function memberships(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TenantMembership::class);
    }

    public function subscriptions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function modules(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TenantModule::class);
    }

    public function stores(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Store::class);
    }

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
            'trial_ends_at' => 'datetime',
        ];
    }
}

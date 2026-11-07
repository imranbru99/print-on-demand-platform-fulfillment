<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, HasRoles, HasUlids, Notifiable;

    public function uniqueIds(): array
    {
        return ['ulid'];
    }

    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'ulid',
        'type',
        'is_platform_user',
        'phone',
        'avatar',
        'locale',
        'timezone',
        'mfa_enabled',
        'last_login_at',
        'suspended_at',
        'current_tenant_id',
    ];

    /**
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_platform_user' => 'boolean',
            'mfa_enabled' => 'boolean',
            'last_login_at' => 'datetime',
            'suspended_at' => 'datetime',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        if ($this->suspended_at !== null) {
            return false;
        }

        return match ($panel->getId()) {
            'platform' => $this->is_platform_user || $this->type === 'platform',
            'business' => in_array($this->type, ['business', 'platform'], true) && $this->current_tenant_id !== null,
            'production' => in_array($this->type, ['production', 'business', 'platform'], true) && $this->current_tenant_id !== null,
            default => false,
        };
    }

    public function currentTenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class, 'current_tenant_id');
    }

    public function memberships(): HasMany
    {
        return $this->hasMany(TenantMembership::class);
    }

    public function tenants(): BelongsToMany
    {
        return $this->belongsToMany(Tenant::class, 'tenant_memberships')
            ->withPivot(['role', 'status', 'invited_at', 'accepted_at'])
            ->withTimestamps();
    }
}

<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class TenantDomain extends Model
{
    use HasUlids, BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'domain',
        'is_primary',
        'verified',
    ];

    protected static function booted(): void
    {
        static::bootBelongsToTenant();
    }
    public function tenant(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}

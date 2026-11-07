<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class WebhookDelivery extends Model
{
    use HasUlids, BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'webhook_id',
        'event',
        'response_status',
        'response_body',
        'attempt',
        'delivered_at',
    ];

    protected function casts(): array
    {
        return [
            'delivered_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::bootBelongsToTenant();
    }
    public function tenant(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}

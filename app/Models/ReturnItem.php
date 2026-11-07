<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class ReturnItem extends Model
{
    use HasUlids, BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'return_id',
        'order_item_id',
        'quantity',
    ];

    protected static function booted(): void
    {
        static::bootBelongsToTenant();
    }
    public function tenant(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function orderReturn(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(OrderReturn::class, 'return_id');
    }

    public function orderItem(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(OrderItem::class, 'order_item_id');
    }
}

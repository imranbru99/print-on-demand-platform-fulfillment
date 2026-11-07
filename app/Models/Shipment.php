<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    use HasUlids, BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'fulfillment_order_id',
        'carrier',
        'tracking_number',
        'status',
    ];

    protected static function booted(): void
    {
        static::bootBelongsToTenant();
    }
    public function tenant(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function fulfillmentOrder(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(FulfillmentOrder::class, 'fulfillment_order_id');
    }
}

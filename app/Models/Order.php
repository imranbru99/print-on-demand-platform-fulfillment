<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasUlids, BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'store_id',
        'customer_id',
        'number',
        'status',
        'currency',
        'subtotal',
        'tax_total',
        'shipping_total',
        'discount_total',
        'grand_total',
        'metadata',
    ];

    protected static function booted(): void
    {
        static::bootBelongsToTenant();
    }
    public function tenant(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function store(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function customer(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function items(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function events(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(OrderEvent::class);
    }

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
            'subtotal' => 'decimal:4',
            'tax_total' => 'decimal:4',
            'shipping_total' => 'decimal:4',
            'discount_total' => 'decimal:4',
            'grand_total' => 'decimal:4',
        ];
    }
}

<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasUlids, BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'store_id',
        'category_id',
        'name',
        'slug',
        'status',
    ];

    protected static function booted(): void
    {
        static::bootBelongsToTenant();
    }
    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    public function store(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function variants(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function images(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    public function pricing(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ProductPricing::class);
    }

    public function printAreas(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PrintArea::class);
    }

    protected function casts(): array
    {
        return [
            'status' => \App\Enums\ProductStatus::class,
        ];
    }
    public function tenant(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}

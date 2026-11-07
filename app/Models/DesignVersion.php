<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class DesignVersion extends Model
{
    use HasUlids, BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'design_asset_id',
        'version',
        'layers',
        'preview_path',
    ];

    protected function casts(): array
    {
        return [
            'layers' => 'array',
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

    public function designAsset(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(DesignAsset::class, 'design_asset_id');
    }
}

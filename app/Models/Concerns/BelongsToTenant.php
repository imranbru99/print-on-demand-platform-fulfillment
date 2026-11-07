<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use App\Models\Tenant;
use App\Support\TenantContext;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToTenant
{
    public static function bootBelongsToTenant(): void
    {
        static::addGlobalScope('tenant', function (Builder $builder): void {
            $tenantId = app(TenantContext::class)->id();

            if ($tenantId !== null) {
                $builder->where($builder->getModel()->getTable().'.tenant_id', $tenantId);
            }
        });

        static::creating(function (Model $model): void {
            if ($model->getAttribute('tenant_id') === null) {
                $tenantId = app(TenantContext::class)->id();

                if ($tenantId !== null) {
                    $model->setAttribute('tenant_id', $tenantId);
                }
            }
        });
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}

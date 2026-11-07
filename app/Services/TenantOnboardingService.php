<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Plan;
use App\Models\Subscription;
use App\Models\Tenant;
use App\Models\TenantMembership;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

final class TenantOnboardingService
{
    public function __construct(
        private readonly ModuleRegistryService $moduleRegistry,
    ) {}

    public function onboard(User $owner, string $tenantName, ?string $planKey = null): Tenant
    {
        return DB::transaction(function () use ($owner, $tenantName, $planKey): Tenant {
            $tenant = Tenant::query()->create([
                'name' => $tenantName,
                'slug' => Str::slug($tenantName).'-'.Str::lower(Str::random(4)),
                'status' => 'active',
                'billing_email' => $owner->email,
                'trial_ends_at' => now()->addDays(14),
            ]);

            TenantMembership::query()->withoutGlobalScopes()->create([
                'tenant_id' => $tenant->getKey(),
                'user_id' => $owner->getKey(),
                'role' => 'owner',
                'status' => 'active',
                'accepted_at' => now(),
            ]);

            $owner->forceFill(['current_tenant_id' => $tenant->getKey()])->save();

            $plan = $planKey !== null
                ? Plan::query()->where('key', $planKey)->first()
                : Plan::query()->where('is_active', true)->orderBy('price')->first();

            if ($plan !== null) {
                Subscription::query()->withoutGlobalScopes()->create([
                    'tenant_id' => $tenant->getKey(),
                    'plan_id' => $plan->getKey(),
                    'status' => 'trialing',
                    'starts_at' => now(),
                ]);
            }

            foreach (config('pod.core_modules', []) as $moduleKey) {
                $this->moduleRegistry->activate($tenant, $moduleKey);
            }

            return $tenant;
        });
    }
}

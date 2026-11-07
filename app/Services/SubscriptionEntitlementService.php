<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\SubscriptionStatus;
use App\Models\Subscription;
use App\Models\Tenant;

final class SubscriptionEntitlementService
{
    public function activeSubscription(Tenant $tenant): ?Subscription
    {
        return Subscription::query()
            ->withoutGlobalScopes()
            ->where('tenant_id', $tenant->getKey())
            ->whereIn('status', [
                SubscriptionStatus::Active->value,
                SubscriptionStatus::Trialing->value,
            ])
            ->latest('starts_at')
            ->first();
    }

    public function hasFeature(Tenant $tenant, string $featureKey): bool
    {
        $subscription = $this->activeSubscription($tenant);

        if ($subscription === null) {
            return false;
        }

        $subscription->loadMissing('plan');
        $features = $subscription->plan?->features ?? [];

        return (bool) data_get($features, $featureKey, false);
    }
}

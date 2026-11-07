<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\ModuleStatus;
use App\Models\ModuleDependency;
use App\Models\Tenant;
use App\Models\TenantModule;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

final class ModuleRegistryService
{
    public function isEnabled(Tenant $tenant, string $moduleKey): bool
    {
        return TenantModule::query()
            ->withoutGlobalScopes()
            ->where('tenant_id', $tenant->getKey())
            ->where('module_key', $moduleKey)
            ->where('status', ModuleStatus::Active->value)
            ->exists();
    }

    public function activate(Tenant $tenant, string $moduleKey, array $config = []): TenantModule
    {
        $this->assertDependenciesActive($tenant, $moduleKey);

        return DB::transaction(function () use ($tenant, $moduleKey, $config): TenantModule {
            $module = TenantModule::query()
                ->withoutGlobalScopes()
                ->firstOrNew([
                    'tenant_id' => $tenant->getKey(),
                    'module_key' => $moduleKey,
                ]);

            $module->fill([
                'status' => ModuleStatus::Active->value,
                'config' => array_merge($module->config ?? [], $config),
                'activated_at' => now(),
                'deactivated_at' => null,
            ]);
            $module->save();

            return $module;
        });
    }

    public function deactivate(Tenant $tenant, string $moduleKey): TenantModule
    {
        $dependents = ModuleDependency::query()
            ->where('depends_on_key', $moduleKey)
            ->pluck('module_key');

        if ($dependents->isNotEmpty()) {
            $activeDependents = TenantModule::query()
                ->withoutGlobalScopes()
                ->where('tenant_id', $tenant->getKey())
                ->whereIn('module_key', $dependents)
                ->where('status', ModuleStatus::Active->value)
                ->pluck('module_key');

            if ($activeDependents->isNotEmpty()) {
                throw new InvalidArgumentException(
                    'Cannot deactivate module while dependents are active: '.$activeDependents->implode(', ')
                );
            }
        }

        $module = TenantModule::query()
            ->withoutGlobalScopes()
            ->where('tenant_id', $tenant->getKey())
            ->where('module_key', $moduleKey)
            ->firstOrFail();

        $module->update([
            'status' => ModuleStatus::Inactive->value,
            'deactivated_at' => now(),
        ]);

        return $module->fresh();
    }

    private function assertDependenciesActive(Tenant $tenant, string $moduleKey): void
    {
        $dependencies = ModuleDependency::query()
            ->where('module_key', $moduleKey)
            ->pluck('depends_on_key');

        foreach ($dependencies as $dependsOn) {
            if (! $this->isEnabled($tenant, $dependsOn)) {
                throw new InvalidArgumentException("Module [{$moduleKey}] requires [{$dependsOn}] to be active.");
            }
        }
    }
}

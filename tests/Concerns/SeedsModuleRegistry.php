<?php

declare(strict_types=1);

namespace Tests\Concerns;

use App\Models\ModuleDependency;
use App\Models\ModuleRegistry;

trait SeedsModuleRegistry
{
    protected function seedModuleRegistryFromConfig(): void
    {
        $modules = config('pod.modules', []);

        foreach ($modules as $key => $meta) {
            ModuleRegistry::query()->updateOrCreate(
                ['key' => $key],
                [
                    'name' => $meta['name'],
                    'description' => $meta['description'] ?? null,
                    'is_core' => in_array($key, config('pod.core_modules', []), true),
                ],
            );

            foreach ($meta['dependencies'] ?? [] as $dependsOn) {
                ModuleDependency::query()->firstOrCreate([
                    'module_key' => $key,
                    'depends_on_key' => $dependsOn,
                ]);
            }
        }
    }
}

<?php

declare(strict_types=1);

namespace App\Filament\Business\Pages;

use App\Enums\ModuleStatus;
use App\Models\ModuleRegistry;
use App\Models\TenantModule;
use App\Services\ModuleRegistryService;
use App\Support\TenantContext;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Collection;
use Throwable;

class ModuleMarketplace extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-puzzle-piece';

    protected static string $view = 'filament.business.pages.module-marketplace';

    protected static ?string $navigationGroup = 'Platform';

    protected static ?string $title = 'Module Marketplace';

    /** @var Collection<int, array{key: string, name: string, description: string, enabled: bool}> */
    public Collection $modules;

    public function mount(TenantContext $tenantContext): void
    {
        $tenant = $tenantContext->require();

        $catalog = collect(config('pod.modules', []))
            ->map(function (array $meta, string $key) use ($tenant): array {
                $enabled = TenantModule::query()
                    ->where('tenant_id', $tenant->getKey())
                    ->where('module_key', $key)
                    ->where('status', ModuleStatus::Active->value)
                    ->exists();

                $registry = ModuleRegistry::query()->where('key', $key)->first();

                return [
                    'key' => $key,
                    'name' => $registry?->name ?? ($meta['name'] ?? $key),
                    'description' => $registry?->description ?? ($meta['description'] ?? ''),
                    'enabled' => $enabled,
                ];
            })
            ->values();

        $this->modules = $catalog;
    }

    public function toggle(string $moduleKey, ModuleRegistryService $modules, TenantContext $tenantContext): void
    {
        $tenant = $tenantContext->require();

        $isEnabled = TenantModule::query()
            ->where('tenant_id', $tenant->getKey())
            ->where('module_key', $moduleKey)
            ->where('status', ModuleStatus::Active->value)
            ->exists();

        try {
            if ($isEnabled) {
                $modules->deactivate($tenant, $moduleKey);
                Notification::make()->title("Module [{$moduleKey}] disabled")->success()->send();
            } else {
                $modules->activate($tenant, $moduleKey);
                Notification::make()->title("Module [{$moduleKey}] enabled")->success()->send();
            }
        } catch (Throwable $exception) {
            Notification::make()
                ->title('Module change failed')
                ->body($exception->getMessage())
                ->danger()
                ->send();
        }

        $this->mount($tenantContext);
    }
}

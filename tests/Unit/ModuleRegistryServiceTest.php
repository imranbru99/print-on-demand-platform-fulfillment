<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Models\Tenant;
use App\Services\ModuleRegistryService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\Test;
use Tests\Concerns\SeedsModuleRegistry;
use Tests\TestCase;

final class ModuleRegistryServiceTest extends TestCase
{
    use RefreshDatabase;
    use SeedsModuleRegistry;

    #[Test]
    public function it_rejects_activation_when_dependencies_are_missing(): void
    {
        $this->seedModuleRegistryFromConfig();

        $tenant = Tenant::query()->create([
            'name' => 'Module Test Co',
            'slug' => 'module-test',
            'status' => 'active',
        ]);

        $service = app(ModuleRegistryService::class);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('requires [cart]');

        $service->activate($tenant, 'checkout');
    }

    #[Test]
    public function it_activates_module_when_dependencies_are_enabled(): void
    {
        $this->seedModuleRegistryFromConfig();

        $tenant = Tenant::query()->create([
            'name' => 'Module Chain Co',
            'slug' => 'module-chain',
            'status' => 'active',
        ]);

        $service = app(ModuleRegistryService::class);

        foreach (['core', 'tenancy', 'stores', 'catalog', 'pricing', 'cart'] as $module) {
            $service->activate($tenant, $module);
        }

        $checkout = $service->activate($tenant, 'checkout');

        $this->assertTrue($service->isEnabled($tenant, 'checkout'));
        $this->assertSame('checkout', $checkout->module_key);
    }
}

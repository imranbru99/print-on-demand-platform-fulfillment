<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Coupon;
use App\Models\Tenant;
use App\Models\TenantMembership;
use App\Models\User;
use App\Services\ModuleRegistryService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Test;
use Tests\Concerns\SeedsModuleRegistry;
use Tests\TestCase;

final class ModuleAccessTest extends TestCase
{
    use RefreshDatabase;
    use SeedsModuleRegistry;

    #[Test]
    public function disabled_module_blocks_route_without_deleting_data(): void
    {
        $this->seedModuleRegistryFromConfig();

        $tenant = Tenant::query()->create(['name' => 'Coupon Co', 'slug' => 'coupon-co', 'status' => 'active']);
        $user = User::factory()->create(['current_tenant_id' => $tenant->getKey()]);
        TenantMembership::query()->withoutGlobalScopes()->create([
            'tenant_id' => $tenant->getKey(),
            'user_id' => $user->getKey(),
            'role' => 'owner',
            'status' => 'active',
        ]);

        $registry = app(ModuleRegistryService::class);
        foreach (['core', 'tenancy', 'stores', 'catalog', 'pricing', 'cart', 'checkout', 'coupons'] as $module) {
            $registry->activate($tenant, $module);
        }

        Coupon::query()->withoutGlobalScopes()->create([
            'tenant_id' => $tenant->getKey(),
            'code' => 'SAVE5',
            'type' => 'fixed',
            'value' => '5.0000',
            'is_active' => true,
        ]);

        Sanctum::actingAs($user);

        $this->getJson('/api/v1/coupons')->assertOk();

        $registry->deactivate($tenant, 'coupons');

        $this->getJson('/api/v1/coupons')->assertForbidden();

        $this->assertDatabaseHas('coupons', ['code' => 'SAVE5']);
    }
}

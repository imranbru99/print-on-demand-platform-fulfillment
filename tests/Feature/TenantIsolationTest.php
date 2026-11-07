<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\ProductStatus;
use App\Models\Product;
use App\Models\Tenant;
use App\Models\TenantMembership;
use App\Models\User;
use App\Support\TenantContext;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Test;
use Tests\Concerns\SeedsModuleRegistry;
use Tests\TestCase;

final class TenantIsolationTest extends TestCase
{
    use RefreshDatabase;
    use SeedsModuleRegistry;

    #[Test]
    public function tenant_user_cannot_view_another_tenants_product_via_api(): void
    {
        $this->seedModuleRegistryFromConfig();

        $tenantA = Tenant::query()->create(['name' => 'A', 'slug' => 'tenant-a', 'status' => 'active']);
        $tenantB = Tenant::query()->create(['name' => 'B', 'slug' => 'tenant-b', 'status' => 'active']);

        $userA = User::factory()->create(['current_tenant_id' => $tenantA->getKey()]);
        TenantMembership::query()->withoutGlobalScopes()->create([
            'tenant_id' => $tenantA->getKey(),
            'user_id' => $userA->getKey(),
            'role' => 'owner',
            'status' => 'active',
        ]);

        $productB = Product::query()->withoutGlobalScopes()->create([
            'tenant_id' => $tenantB->getKey(),
            'name' => 'Secret Tee',
            'slug' => 'secret-tee',
            'status' => ProductStatus::Active->value,
        ]);

        foreach (['core', 'tenancy', 'stores', 'catalog'] as $module) {
            app(\App\Services\ModuleRegistryService::class)->activate($tenantA, $module);
        }

        Sanctum::actingAs($userA);

        $response = $this->getJson('/api/v1/products/'.$productB->getKey());

        $response->assertNotFound();
    }

    #[Test]
    public function tenant_context_scopes_product_queries(): void
    {
        $tenantA = Tenant::query()->create(['name' => 'A2', 'slug' => 'tenant-a2', 'status' => 'active']);
        $tenantB = Tenant::query()->create(['name' => 'B2', 'slug' => 'tenant-b2', 'status' => 'active']);

        Product::query()->withoutGlobalScopes()->create([
            'tenant_id' => $tenantA->getKey(),
            'name' => 'A Product',
            'slug' => 'a-product',
            'status' => ProductStatus::Active->value,
        ]);
        Product::query()->withoutGlobalScopes()->create([
            'tenant_id' => $tenantB->getKey(),
            'name' => 'B Product',
            'slug' => 'b-product',
            'status' => ProductStatus::Active->value,
        ]);

        app(TenantContext::class)->set($tenantA);

        $this->assertSame(1, Product::query()->count());
    }
}

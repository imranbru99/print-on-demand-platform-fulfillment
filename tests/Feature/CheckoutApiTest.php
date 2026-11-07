<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\ProductStatus;
use App\Models\Product;
use App\Models\ProductPricing;
use App\Models\ProductVariant;
use App\Models\Store;
use App\Models\Tenant;
use App\Models\TenantMembership;
use App\Models\User;
use App\Services\ModuleRegistryService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Test;
use Tests\Concerns\SeedsModuleRegistry;
use Tests\TestCase;

final class CheckoutApiTest extends TestCase
{
    use RefreshDatabase;
    use SeedsModuleRegistry;

    #[Test]
    public function checkout_creates_paid_order_with_sandbox_payment(): void
    {
        $this->seedModuleRegistryFromConfig();

        $tenant = Tenant::query()->create(['name' => 'Checkout Co', 'slug' => 'checkout-co', 'status' => 'active']);
        $store = Store::query()->withoutGlobalScopes()->create([
            'tenant_id' => $tenant->getKey(),
            'name' => 'Checkout Store',
            'slug' => 'checkout-store',
            'status' => 'active',
            'currency' => 'USD',
        ]);

        $user = User::factory()->create(['current_tenant_id' => $tenant->getKey()]);
        TenantMembership::query()->withoutGlobalScopes()->create([
            'tenant_id' => $tenant->getKey(),
            'user_id' => $user->getKey(),
            'role' => 'owner',
            'status' => 'active',
        ]);

        $registry = app(ModuleRegistryService::class);
        foreach (['core', 'tenancy', 'stores', 'catalog', 'pricing', 'cart', 'checkout', 'orders', 'payments', 'customers'] as $module) {
            $registry->activate($tenant, $module);
        }

        $product = Product::query()->withoutGlobalScopes()->create([
            'tenant_id' => $tenant->getKey(),
            'store_id' => $store->getKey(),
            'name' => 'API Tee',
            'slug' => 'api-tee',
            'status' => ProductStatus::Active->value,
        ]);

        $variant = ProductVariant::query()->withoutGlobalScopes()->create([
            'tenant_id' => $tenant->getKey(),
            'product_id' => $product->getKey(),
            'name' => 'Black / M',
            'sku' => 'API-TEE-BLK-M',
            'is_default' => true,
        ]);

        ProductPricing::query()->withoutGlobalScopes()->create([
            'tenant_id' => $tenant->getKey(),
            'product_id' => $product->getKey(),
            'variant_id' => $variant->getKey(),
            'base_price' => '25.0000',
            'currency' => 'USD',
        ]);

        Sanctum::actingAs($user);

        $add = $this->postJson("/api/v1/stores/{$store->getKey()}/cart/items", [
            'product_id' => $product->getKey(),
            'variant_id' => $variant->getKey(),
            'quantity' => 2,
        ]);

        $add->assertOk();
        $cartToken = $add->json('meta.cart_token');

        $checkout = $this->withHeader('X-Cart-Token', $cartToken)->postJson("/api/v1/stores/{$store->getKey()}/checkout", [
            'client_subtotal' => '50.0000',
            'email' => 'buyer@checkout.test',
            'first_name' => 'Casey',
            'last_name' => 'Buyer',
        ]);

        $checkout->assertCreated()
            ->assertJsonPath('data.status', 'paid')
            ->assertJsonPath('data.grand_total', '50.0000');

        $this->assertDatabaseHas('payments', [
            'provider' => 'sandbox',
            'status' => 'captured',
            'amount' => '50.0000',
        ]);
    }
}

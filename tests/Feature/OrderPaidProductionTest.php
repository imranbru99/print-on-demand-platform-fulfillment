<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\OrderStatus;
use App\Jobs\ProcessPaidOrderJob;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Store;
use App\Models\Tenant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

final class OrderPaidProductionTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function order_paid_event_creates_production_order(): void
    {
        $tenant = Tenant::query()->create(['name' => 'Prod Co', 'slug' => 'prod-co', 'status' => 'active']);
        $store = Store::query()->withoutGlobalScopes()->create([
            'tenant_id' => $tenant->getKey(),
            'name' => 'Prod Store',
            'slug' => 'prod-store',
            'status' => 'active',
            'currency' => 'USD',
        ]);
        $customer = Customer::query()->withoutGlobalScopes()->create([
            'tenant_id' => $tenant->getKey(),
            'email' => 'maker@prod.test',
            'first_name' => 'Maker',
            'last_name' => 'Test',
        ]);

        $order = Order::query()->withoutGlobalScopes()->create([
            'tenant_id' => $tenant->getKey(),
            'store_id' => $store->getKey(),
            'customer_id' => $customer->getKey(),
            'number' => 'ORD-PAID-01',
            'status' => OrderStatus::Paid->value,
            'currency' => 'USD',
            'subtotal' => '30.0000',
            'grand_total' => '30.0000',
        ]);

        OrderItem::query()->withoutGlobalScopes()->create([
            'tenant_id' => $tenant->getKey(),
            'order_id' => $order->getKey(),
            'name' => 'Test Tee',
            'quantity' => 1,
            'unit_price' => '30.0000',
            'line_total' => '30.0000',
        ]);

        ProcessPaidOrderJob::dispatchSync($order->getKey());

        $this->assertDatabaseHas('production_orders', [
            'tenant_id' => $tenant->getKey(),
            'order_id' => $order->getKey(),
        ]);

        $this->assertDatabaseHas('production_jobs', [
            'tenant_id' => $tenant->getKey(),
        ]);

        $this->assertSame(
            OrderStatus::InProduction->value,
            $order->fresh()->status,
        );
    }
}

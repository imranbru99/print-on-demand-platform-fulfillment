<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Enums\OrderStatus;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Store;
use App\Models\Tenant;
use App\Services\OrderStateMachine;
use Illuminate\Foundation\Testing\RefreshDatabase;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

final class OrderStateMachineTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_transitions_allowed_status_changes(): void
    {
        $tenant = Tenant::query()->create(['name' => 'SM Co', 'slug' => 'sm-co', 'status' => 'active']);
        $store = Store::query()->withoutGlobalScopes()->create([
            'tenant_id' => $tenant->getKey(),
            'name' => 'Store',
            'slug' => 'store',
            'status' => 'active',
            'currency' => 'USD',
        ]);
        $customer = Customer::query()->withoutGlobalScopes()->create([
            'tenant_id' => $tenant->getKey(),
            'email' => 'buyer@test.com',
            'first_name' => 'Buyer',
        ]);

        $order = Order::query()->withoutGlobalScopes()->create([
            'tenant_id' => $tenant->getKey(),
            'store_id' => $store->getKey(),
            'customer_id' => $customer->getKey(),
            'number' => 'T-10001',
            'status' => OrderStatus::PendingPayment->value,
            'currency' => 'USD',
            'subtotal' => '10.0000',
            'grand_total' => '10.0000',
        ]);

        $machine = app(OrderStateMachine::class);
        $machine->transition($order, OrderStatus::Paid);

        $this->assertSame(OrderStatus::Paid->value, $order->fresh()->status);
        $this->assertDatabaseHas('order_events', [
            'order_id' => $order->getKey(),
            'event' => 'status_changed',
        ]);
    }

    #[Test]
    public function it_blocks_invalid_transitions(): void
    {
        $tenant = Tenant::query()->create(['name' => 'SM2', 'slug' => 'sm2', 'status' => 'active']);
        $order = Order::query()->withoutGlobalScopes()->create([
            'tenant_id' => $tenant->getKey(),
            'number' => 'T-10002',
            'status' => OrderStatus::Delivered->value,
            'currency' => 'USD',
            'subtotal' => '1.0000',
            'grand_total' => '1.0000',
        ]);

        $this->expectException(InvalidArgumentException::class);

        app(OrderStateMachine::class)->transition($order, OrderStatus::Paid);
    }
}

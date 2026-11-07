<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fulfillment_orders', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignUlid('order_id')->constrained('orders')->cascadeOnDelete();
            $table->string('status', 32)->default('pending');
            $table->string('idempotency_key')->unique();
            $table->timestamps();
        });

        Schema::create('shipments', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignUlid('fulfillment_order_id')->constrained('fulfillment_orders')->cascadeOnDelete();
            $table->string('carrier', 64)->nullable();
            $table->string('tracking_number')->nullable()->index();
            $table->string('status', 32)->default('pending');
            $table->decimal('shipping_cost', 14, 4)->default(0);
            $table->timestamps();
        });

        Schema::create('shipment_items', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignUlid('shipment_id')->constrained('shipments')->cascadeOnDelete();
            $table->foreignUlid('order_item_id')->constrained('order_items')->cascadeOnDelete();
            $table->unsignedInteger('quantity');
            $table->timestamps();
        });

        Schema::create('tracking_events', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignUlid('shipment_id')->constrained('shipments')->cascadeOnDelete();
            $table->string('status', 64);
            $table->text('description')->nullable();
            $table->timestamp('occurred_at');
            $table->json('raw')->nullable();
            $table->timestamps();
        });

        Schema::create('shipping_zones', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->string('name');
            $table->json('countries')->nullable();
            $table->timestamps();
        });

        Schema::create('shipping_rates', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignUlid('shipping_zone_id')->constrained('shipping_zones')->cascadeOnDelete();
            $table->string('name');
            $table->decimal('rate', 14, 4);
            $table->decimal('min_weight', 10, 3)->nullable();
            $table->decimal('max_weight', 10, 3)->nullable();
            $table->timestamps();
        });

        Schema::create('returns', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignUlid('order_id')->constrained('orders')->cascadeOnDelete();
            $table->string('status', 32)->default('requested');
            $table->text('reason')->nullable();
            $table->timestamps();
        });

        Schema::create('return_items', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignUlid('return_id')->constrained('returns')->cascadeOnDelete();
            $table->foreignUlid('order_item_id')->constrained('order_items')->cascadeOnDelete();
            $table->unsignedInteger('quantity');
            $table->string('condition', 32)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('return_items');
        Schema::dropIfExists('returns');
        Schema::dropIfExists('shipping_rates');
        Schema::dropIfExists('shipping_zones');
        Schema::dropIfExists('tracking_events');
        Schema::dropIfExists('shipment_items');
        Schema::dropIfExists('shipments');
        Schema::dropIfExists('fulfillment_orders');
    }
};

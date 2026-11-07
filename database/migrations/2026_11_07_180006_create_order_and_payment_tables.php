<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignUlid('store_id')->nullable()->constrained('stores')->nullOnDelete();
            $table->foreignUlid('customer_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->string('number')->unique();
            $table->string('status', 32)->default('draft');
            $table->string('currency', 3)->default('USD');
            $table->decimal('subtotal', 14, 4)->default(0);
            $table->decimal('tax_total', 14, 4)->default(0);
            $table->decimal('shipping_total', 14, 4)->default(0);
            $table->decimal('discount_total', 14, 4)->default(0);
            $table->decimal('grand_total', 14, 4)->default(0);
            $table->json('metadata')->nullable();
            $table->timestamps();
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignUlid('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignUlid('product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->foreignUlid('variant_id')->nullable()->constrained('product_variants')->nullOnDelete();
            $table->string('name');
            $table->string('sku')->nullable();
            $table->unsignedInteger('quantity');
            $table->decimal('unit_price', 14, 4);
            $table->decimal('line_total', 14, 4);
            $table->json('customization')->nullable();
            $table->timestamps();
        });

        Schema::create('order_addresses', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignUlid('order_id')->constrained('orders')->cascadeOnDelete();
            $table->string('type', 16);
            $table->string('line1');
            $table->string('line2')->nullable();
            $table->string('city');
            $table->string('region')->nullable();
            $table->string('postal_code', 32)->nullable();
            $table->string('country', 2);
            $table->timestamps();
        });

        Schema::create('order_events', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignUlid('order_id')->constrained('orders')->cascadeOnDelete();
            $table->string('event');
            $table->json('payload')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('order_notes', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignUlid('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->text('body');
            $table->boolean('is_customer_visible')->default(false);
            $table->timestamps();
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignUlid('order_id')->constrained('orders')->cascadeOnDelete();
            $table->string('provider', 64);
            $table->string('status', 32)->default('pending');
            $table->decimal('amount', 14, 4);
            $table->string('currency', 3)->default('USD');
            $table->string('reference')->nullable()->unique();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });

        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignUlid('payment_id')->constrained('payments')->cascadeOnDelete();
            $table->string('type', 32);
            $table->string('status', 32);
            $table->decimal('amount', 14, 4);
            $table->string('idempotency_key')->unique();
            $table->json('response')->nullable();
            $table->timestamps();
        });

        Schema::create('refunds', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignUlid('payment_id')->constrained('payments')->cascadeOnDelete();
            $table->foreignUlid('order_id')->constrained('orders')->cascadeOnDelete();
            $table->decimal('amount', 14, 4);
            $table->string('status', 32)->default('pending');
            $table->string('reason')->nullable();
            $table->timestamps();
        });

        Schema::create('coupons', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->string('code')->unique();
            $table->string('type', 32);
            $table->decimal('value', 14, 4);
            $table->unsignedInteger('max_uses')->nullable();
            $table->unsignedInteger('used_count')->default(0);
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('promotions', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->string('name');
            $table->string('type', 32);
            $table->json('rules')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promotions');
        Schema::dropIfExists('coupons');
        Schema::dropIfExists('refunds');
        Schema::dropIfExists('payment_transactions');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('order_notes');
        Schema::dropIfExists('order_events');
        Schema::dropIfExists('order_addresses');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
};

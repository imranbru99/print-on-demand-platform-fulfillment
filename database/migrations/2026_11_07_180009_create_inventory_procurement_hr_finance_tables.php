<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('warehouses', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->string('name');
            $table->string('code', 32)->nullable();
            $table->json('address')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('inventory_items', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignUlid('warehouse_id')->constrained('warehouses')->cascadeOnDelete();
            $table->foreignUlid('product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->foreignUlid('variant_id')->nullable()->constrained('product_variants')->nullOnDelete();
            $table->string('sku')->nullable();
            $table->integer('quantity_on_hand')->default(0);
            $table->integer('reorder_level')->default(0);
            $table->timestamps();
        });

        Schema::create('stock_movements', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignUlid('inventory_item_id')->constrained('inventory_items')->cascadeOnDelete();
            $table->string('type', 32);
            $table->integer('quantity');
            $table->string('reference_type')->nullable();
            $table->ulid('reference_id')->nullable();
            $table->timestamps();
        });

        Schema::create('stock_reservations', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignUlid('inventory_item_id')->constrained('inventory_items')->cascadeOnDelete();
            $table->integer('quantity');
            $table->timestamp('expires_at')->nullable();
            $table->string('reference_type')->nullable();
            $table->ulid('reference_id')->nullable();
            $table->timestamps();
        });

        Schema::create('suppliers', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone', 32)->nullable();
            $table->json('address')->nullable();
            $table->timestamps();
        });

        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignUlid('supplier_id')->constrained('suppliers')->cascadeOnDelete();
            $table->string('number')->unique();
            $table->string('status', 32)->default('draft');
            $table->decimal('total', 14, 4)->default(0);
            $table->timestamps();
        });

        Schema::create('purchase_order_items', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignUlid('purchase_order_id')->constrained('purchase_orders')->cascadeOnDelete();
            $table->string('description');
            $table->unsignedInteger('quantity');
            $table->decimal('unit_cost', 14, 4);
            $table->decimal('line_total', 14, 4);
            $table->timestamps();
        });

        Schema::create('departments', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('employees', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignUlid('department_id')->nullable()->constrained('departments')->nullOnDelete();
            $table->string('employee_number')->nullable();
            $table->string('job_title')->nullable();
            $table->date('hired_at')->nullable();
            $table->timestamps();
        });

        Schema::table('production_assignments', function (Blueprint $table) {
            $table->foreign('employee_id')->references('id')->on('employees')->nullOnDelete();
        });

        Schema::create('expenses', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->string('category');
            $table->decimal('amount', 14, 4);
            $table->string('currency', 3)->default('USD');
            $table->date('incurred_on');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('financial_transactions', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->string('type', 32);
            $table->decimal('amount', 14, 4);
            $table->string('currency', 3)->default('USD');
            $table->string('reference')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });

        Schema::create('invoices', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignUlid('order_id')->nullable()->constrained('orders')->nullOnDelete();
            $table->string('number')->unique();
            $table->decimal('amount', 14, 4);
            $table->string('status', 32)->default('draft');
            $table->timestamp('issued_at')->nullable();
            $table->timestamp('due_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('financial_transactions');
        Schema::dropIfExists('expenses');
        Schema::table('production_assignments', function (Blueprint $table) {
            $table->dropForeign(['employee_id']);
        });
        Schema::dropIfExists('employees');
        Schema::dropIfExists('departments');
        Schema::dropIfExists('purchase_order_items');
        Schema::dropIfExists('purchase_orders');
        Schema::dropIfExists('suppliers');
        Schema::dropIfExists('stock_reservations');
        Schema::dropIfExists('stock_movements');
        Schema::dropIfExists('inventory_items');
        Schema::dropIfExists('warehouses');
    }
};

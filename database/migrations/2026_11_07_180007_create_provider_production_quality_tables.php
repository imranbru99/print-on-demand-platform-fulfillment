<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('print_providers', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('key')->unique();
            $table->string('name');
            $table->json('capabilities')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('provider_accounts', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignUlid('print_provider_id')->constrained('print_providers')->cascadeOnDelete();
            $table->string('label');
            $table->json('credentials')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('provider_products', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignUlid('print_provider_id')->constrained('print_providers')->cascadeOnDelete();
            $table->foreignUlid('product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->string('external_id');
            $table->json('metadata')->nullable();
            $table->timestamps();
        });

        Schema::create('provider_orders', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignUlid('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignUlid('print_provider_id')->constrained('print_providers');
            $table->string('external_reference')->nullable();
            $table->string('status', 32)->default('pending');
            $table->json('payload')->nullable();
            $table->timestamps();
        });

        Schema::create('production_orders', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignUlid('order_id')->constrained('orders')->cascadeOnDelete();
            $table->string('status', 32)->default('queued');
            $table->unsignedInteger('priority')->default(100);
            $table->timestamp('due_at')->nullable();
            $table->timestamps();
        });

        Schema::create('production_jobs', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignUlid('production_order_id')->constrained('production_orders')->cascadeOnDelete();
            $table->foreignUlid('order_item_id')->nullable()->constrained('order_items')->nullOnDelete();
            $table->string('status', 32)->default('queued');
            $table->string('station', 64)->nullable();
            $table->json('instructions')->nullable();
            $table->timestamps();
        });

        Schema::create('production_assignments', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignUlid('production_job_id')->constrained('production_jobs')->cascadeOnDelete();
            $table->foreignUlid('employee_id')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamp('assigned_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('quality_checks', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignUlid('production_job_id')->constrained('production_jobs')->cascadeOnDelete();
            $table->string('status', 32)->default('pending');
            $table->foreignId('inspector_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('quality_defects', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignUlid('quality_check_id')->constrained('quality_checks')->cascadeOnDelete();
            $table->string('code', 64);
            $table->text('description')->nullable();
            $table->string('severity', 16)->default('minor');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quality_defects');
        Schema::dropIfExists('quality_checks');
        Schema::dropIfExists('production_assignments');
        Schema::dropIfExists('production_jobs');
        Schema::dropIfExists('production_orders');
        Schema::dropIfExists('provider_orders');
        Schema::dropIfExists('provider_products');
        Schema::dropIfExists('provider_accounts');
        Schema::dropIfExists('print_providers');
    }
};

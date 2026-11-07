<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stores', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->string('name');
            $table->string('slug');
            $table->string('status', 32)->default('active');
            $table->string('currency', 3)->default('USD');
            $table->timestamps();
            $table->unique(['tenant_id', 'slug']);
        });

        Schema::create('store_domains', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('store_id')->constrained('stores')->cascadeOnDelete();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->string('domain')->unique();
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
        });

        Schema::create('store_settings', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('store_id')->constrained('stores')->cascadeOnDelete();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->string('key');
            $table->json('value')->nullable();
            $table->timestamps();
            $table->unique(['store_id', 'key']);
        });

        Schema::create('product_categories', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignUlid('parent_id')->nullable()->constrained('product_categories')->nullOnDelete();
            $table->string('name');
            $table->string('slug');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
            $table->unique(['tenant_id', 'slug']);
        });

        Schema::create('products', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignUlid('store_id')->nullable()->constrained('stores')->nullOnDelete();
            $table->foreignUlid('category_id')->nullable()->constrained('product_categories')->nullOnDelete();
            $table->string('name');
            $table->string('slug');
            $table->string('sku')->nullable();
            $table->string('status', 32)->default('draft');
            $table->text('description')->nullable();
            $table->timestamps();
            $table->unique(['tenant_id', 'slug']);
        });

        Schema::create('product_variants', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignUlid('product_id')->constrained('products')->cascadeOnDelete();
            $table->string('name');
            $table->string('sku')->nullable();
            $table->json('options')->nullable();
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });

        Schema::create('product_images', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignUlid('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignUlid('variant_id')->nullable()->constrained('product_variants')->nullOnDelete();
            $table->string('path');
            $table->string('alt')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('product_attributes', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignUlid('product_id')->constrained('products')->cascadeOnDelete();
            $table->string('key');
            $table->text('value')->nullable();
            $table->timestamps();
        });

        Schema::create('print_areas', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignUlid('product_id')->constrained('products')->cascadeOnDelete();
            $table->string('name');
            $table->decimal('width_mm', 10, 2);
            $table->decimal('height_mm', 10, 2);
            $table->json('position')->nullable();
            $table->timestamps();
        });

        Schema::create('product_pricing', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignUlid('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignUlid('variant_id')->nullable()->constrained('product_variants')->nullOnDelete();
            $table->decimal('base_price', 14, 4);
            $table->decimal('compare_at_price', 14, 4)->nullable();
            $table->string('currency', 3)->default('USD');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_pricing');
        Schema::dropIfExists('print_areas');
        Schema::dropIfExists('product_attributes');
        Schema::dropIfExists('product_images');
        Schema::dropIfExists('product_variants');
        Schema::dropIfExists('products');
        Schema::dropIfExists('product_categories');
        Schema::dropIfExists('store_settings');
        Schema::dropIfExists('store_domains');
        Schema::dropIfExists('stores');
    }
};

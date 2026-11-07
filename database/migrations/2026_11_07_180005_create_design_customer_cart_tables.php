<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('design_assets', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('path');
            $table->string('mime_type', 128)->nullable();
            $table->unsignedBigInteger('bytes')->default(0);
            $table->json('metadata')->nullable();
            $table->timestamps();
        });

        Schema::create('design_versions', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignUlid('design_asset_id')->constrained('design_assets')->cascadeOnDelete();
            $table->unsignedInteger('version')->default(1);
            $table->json('layers')->nullable();
            $table->string('preview_path')->nullable();
            $table->timestamps();
        });

        Schema::create('mockups', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignUlid('product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->foreignUlid('design_version_id')->nullable()->constrained('design_versions')->nullOnDelete();
            $table->string('path');
            $table->string('status', 32)->default('pending');
            $table->timestamps();
        });

        Schema::create('customers', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('email');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('phone', 32)->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->unique(['tenant_id', 'email']);
        });

        Schema::create('customer_addresses', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignUlid('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->string('label')->nullable();
            $table->string('line1');
            $table->string('line2')->nullable();
            $table->string('city');
            $table->string('region')->nullable();
            $table->string('postal_code', 32)->nullable();
            $table->string('country', 2);
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });

        Schema::create('carts', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignUlid('store_id')->nullable()->constrained('stores')->nullOnDelete();
            $table->foreignUlid('customer_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->string('session_token')->nullable()->index();
            $table->string('currency', 3)->default('USD');
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });

        Schema::create('cart_items', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignUlid('cart_id')->constrained('carts')->cascadeOnDelete();
            $table->foreignUlid('product_id')->constrained('products');
            $table->foreignUlid('variant_id')->nullable()->constrained('product_variants')->nullOnDelete();
            $table->unsignedInteger('quantity')->default(1);
            $table->decimal('unit_price', 14, 4);
            $table->json('customization')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cart_items');
        Schema::dropIfExists('carts');
        Schema::dropIfExists('customer_addresses');
        Schema::dropIfExists('customers');
        Schema::dropIfExists('mockups');
        Schema::dropIfExists('design_versions');
        Schema::dropIfExists('design_assets');
    }
};

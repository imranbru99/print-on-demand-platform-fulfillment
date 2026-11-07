<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cms_pages', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->nullable()->constrained('tenants')->cascadeOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('body')->nullable();
            $table->boolean('is_published')->default(false);
            $table->timestamps();
        });

        Schema::create('blog_posts', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->nullable()->constrained('tenants')->cascadeOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable();
            $table->longText('body')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });

        Schema::create('faqs', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->nullable()->constrained('tenants')->cascadeOnDelete();
            $table->string('question');
            $table->text('answer');
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });

        Schema::create('testimonials', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->nullable()->constrained('tenants')->cascadeOnDelete();
            $table->string('author_name');
            $table->text('body');
            $table->unsignedTinyInteger('rating')->nullable();
            $table->boolean('is_published')->default(false);
            $table->timestamps();
        });

        Schema::create('reviews', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignUlid('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignUlid('customer_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->unsignedTinyInteger('rating');
            $table->text('body')->nullable();
            $table->boolean('is_approved')->default(false);
            $table->timestamps();
        });

        Schema::create('wishlists', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignUlid('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->foreignUlid('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignUlid('variant_id')->nullable()->constrained('product_variants')->nullOnDelete();
            $table->timestamps();
            $table->unique(['customer_id', 'product_id', 'variant_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wishlists');
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('testimonials');
        Schema::dropIfExists('faqs');
        Schema::dropIfExists('blog_posts');
        Schema::dropIfExists('cms_pages');
    }
};

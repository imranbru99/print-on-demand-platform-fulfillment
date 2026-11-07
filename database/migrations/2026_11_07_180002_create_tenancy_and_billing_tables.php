<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('status', 32)->default('active');
            $table->string('billing_email')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamp('trial_ends_at')->nullable();
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreignUlid('current_tenant_id')->nullable()->constrained('tenants')->nullOnDelete();
        });

        Schema::create('tenant_domains', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->string('domain')->unique();
            $table->boolean('is_primary')->default(false);
            $table->boolean('verified')->default(false);
            $table->timestamps();
        });

        Schema::create('tenant_memberships', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('role', 64)->default('member');
            $table->string('status', 32)->default('active');
            $table->timestamp('invited_at')->nullable();
            $table->timestamp('accepted_at')->nullable();
            $table->timestamps();
            $table->unique(['tenant_id', 'user_id']);
        });

        Schema::create('plans', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('key')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('billing_interval', 16)->default('monthly');
            $table->decimal('price', 14, 4)->default(0);
            $table->string('currency', 3)->default('USD');
            $table->json('features')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('subscriptions', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignUlid('plan_id')->constrained('plans');
            $table->string('status', 32)->default('trialing');
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });

        Schema::create('subscription_invoices', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('subscription_id')->constrained('subscriptions')->cascadeOnDelete();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->string('number')->unique();
            $table->decimal('amount', 14, 4);
            $table->string('currency', 3)->default('USD');
            $table->string('status', 32)->default('pending');
            $table->timestamp('issued_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->json('line_items')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscription_invoices');
        Schema::dropIfExists('subscriptions');
        Schema::dropIfExists('plans');
        Schema::dropIfExists('tenant_memberships');
        Schema::dropIfExists('tenant_domains');
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('current_tenant_id');
        });
        Schema::dropIfExists('tenants');
    }
};

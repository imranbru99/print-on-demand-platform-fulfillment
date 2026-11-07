<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->string('name');
            $table->string('channel', 32)->nullable();
            $table->string('status', 32)->default('draft');
            $table->json('content')->nullable();
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->timestamps();
        });

        Schema::create('support_tickets', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignUlid('customer_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->foreignUlid('order_id')->nullable()->constrained('orders')->nullOnDelete();
            $table->string('subject');
            $table->string('status', 32)->default('open');
            $table->string('priority', 16)->default('normal');
            $table->timestamps();
        });

        Schema::create('support_messages', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignUlid('support_ticket_id')->constrained('support_tickets')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->text('body');
            $table->boolean('is_staff')->default(false);
            $table->timestamps();
        });

        Schema::create('knowledge_articles', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->nullable()->constrained('tenants')->cascadeOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('body')->nullable();
            $table->boolean('is_published')->default(false);
            $table->timestamps();
        });

        Schema::create('ai_provider_settings', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('provider', 64);
            $table->json('config')->nullable();
            $table->boolean('is_active')->default(false);
            $table->timestamps();
            $table->unique('provider');
        });

        Schema::create('integrations', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->string('type', 64);
            $table->string('name');
            $table->json('config')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('integration_logs', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignUlid('integration_id')->constrained('integrations')->cascadeOnDelete();
            $table->string('direction', 16);
            $table->string('event', 128);
            $table->json('payload')->nullable();
            $table->string('status', 32)->default('ok');
            $table->timestamps();
        });

        Schema::create('api_keys', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->string('name');
            $table->string('key_hash');
            $table->string('prefix', 16);
            $table->json('abilities')->nullable();
            $table->timestamp('last_used_at')->nullable();
            $table->timestamp('revoked_at')->nullable();
            $table->timestamps();
        });

        Schema::create('webhooks', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->string('url');
            $table->string('secret');
            $table->json('events')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('webhook_deliveries', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignUlid('webhook_id')->constrained('webhooks')->cascadeOnDelete();
            $table->string('event', 128);
            $table->unsignedSmallInteger('response_status')->nullable();
            $table->text('response_body')->nullable();
            $table->unsignedTinyInteger('attempt')->default(1);
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();
        });

        Schema::create('automation_workflows', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->string('name');
            $table->string('trigger', 128);
            $table->json('definition')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('automation_executions', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignUlid('automation_workflow_id')->constrained('automation_workflows')->cascadeOnDelete();
            $table->string('status', 32)->default('pending');
            $table->json('context')->nullable();
            $table->json('result')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('automation_executions');
        Schema::dropIfExists('automation_workflows');
        Schema::dropIfExists('webhook_deliveries');
        Schema::dropIfExists('webhooks');
        Schema::dropIfExists('api_keys');
        Schema::dropIfExists('integration_logs');
        Schema::dropIfExists('integrations');
        Schema::dropIfExists('ai_provider_settings');
        Schema::dropIfExists('knowledge_articles');
        Schema::dropIfExists('support_messages');
        Schema::dropIfExists('support_tickets');
        Schema::dropIfExists('campaigns');
    }
};

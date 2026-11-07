<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('module_registry', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('key')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('version', 32)->default('1.0.0');
            $table->boolean('is_core')->default(false);
            $table->json('default_config')->nullable();
            $table->timestamps();
        });

        Schema::create('tenant_modules', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->string('module_key');
            $table->string('status', 32)->default('inactive');
            $table->json('config')->nullable();
            $table->timestamp('activated_at')->nullable();
            $table->timestamp('deactivated_at')->nullable();
            $table->timestamps();
            $table->unique(['tenant_id', 'module_key']);
        });

        Schema::create('module_dependencies', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('module_key');
            $table->string('depends_on_key');
            $table->timestamps();
            $table->unique(['module_key', 'depends_on_key']);
        });

        Schema::create('feature_flags', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->nullable()->constrained('tenants')->cascadeOnDelete();
            $table->string('key');
            $table->boolean('enabled')->default(false);
            $table->json('payload')->nullable();
            $table->timestamps();
            $table->unique(['tenant_id', 'key']);
        });

        Schema::create('platform_settings', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('key')->unique();
            $table->json('value')->nullable();
            $table->timestamps();
        });

        Schema::create('audit_logs', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->nullable()->constrained('tenants')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('event');
            $table->string('auditable_type')->nullable();
            $table->ulid('auditable_id')->nullable();
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
            $table->index(['auditable_type', 'auditable_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('platform_settings');
        Schema::dropIfExists('feature_flags');
        Schema::dropIfExists('module_dependencies');
        Schema::dropIfExists('tenant_modules');
        Schema::dropIfExists('module_registry');
    }
};

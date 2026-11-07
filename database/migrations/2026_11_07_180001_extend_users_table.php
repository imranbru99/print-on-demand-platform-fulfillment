<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->ulid('ulid')->nullable()->unique()->after('id');
            $table->string('type', 32)->default('business')->after('email');
            $table->boolean('is_platform_user')->default(false)->after('type');
            $table->string('phone', 32)->nullable()->after('password');
            $table->string('avatar')->nullable()->after('phone');
            $table->string('locale', 16)->default('en')->after('avatar');
            $table->string('timezone', 64)->default('UTC')->after('locale');
            $table->boolean('mfa_enabled')->default(false)->after('timezone');
            $table->timestamp('last_login_at')->nullable()->after('mfa_enabled');
            $table->timestamp('suspended_at')->nullable()->after('last_login_at');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'ulid', 'type', 'is_platform_user', 'phone', 'avatar', 'locale',
                'timezone', 'mfa_enabled', 'last_login_at', 'suspended_at',
            ]);
        });
    }
};

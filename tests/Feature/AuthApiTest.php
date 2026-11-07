<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Plan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes\Test;
use Tests\Concerns\SeedsModuleRegistry;
use Tests\TestCase;

final class AuthApiTest extends TestCase
{
    use RefreshDatabase;
    use SeedsModuleRegistry;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedModuleRegistryFromConfig();

        Plan::query()->create([
            'key' => 'starter',
            'name' => 'Starter',
            'price' => '29.0000',
            'currency' => 'USD',
            'features' => ['modules' => ['core', 'tenancy']],
            'is_active' => true,
        ]);
    }

    #[Test]
    public function user_can_login_via_api(): void
    {
        $user = User::factory()->create([
            'email' => 'api-user@test.com',
            'password' => Hash::make('secret-pass'),
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'api-user@test.com',
            'password' => 'secret-pass',
        ]);

        $response->assertOk()
            ->assertJsonStructure(['data' => ['token', 'token_type', 'user' => ['email']]]);
    }

    #[Test]
    public function user_can_register_via_api(): void
    {
        $response = $this->postJson('/api/v1/auth/register', [
            'name' => 'API Founder',
            'email' => 'founder@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'company' => 'Founder POD LLC',
            'plan' => 'starter',
        ]);

        $response->assertCreated()
            ->assertJsonPath('data.user.email', 'founder@test.com');

        $this->assertDatabaseHas('tenants', ['name' => 'Founder POD LLC']);
    }
}

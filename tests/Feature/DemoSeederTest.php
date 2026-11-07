<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\DemoSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

final class DemoSeederTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function demo_seeder_creates_expected_users_in_testing_environment(): void
    {
        $this->seed(DemoSeeder::class);

        $emails = [
            'platform-admin@pod-demo.test',
            'owner@pod-demo.test',
            'designer@pod-demo.test',
            'production@pod-demo.test',
            'customer@pod-demo.test',
        ];

        foreach ($emails as $email) {
            $this->assertDatabaseHas('users', ['email' => $email]);
        }

        $owner = User::query()->where('email', 'owner@pod-demo.test')->first();
        $this->assertNotNull($owner);
        $this->assertTrue(Hash::check('DemoPass123!', $owner->password));

        $this->assertDatabaseHas('tenants', ['slug' => 'printflow-demo', 'name' => 'PrintFlow Demo Co']);
        $this->assertGreaterThanOrEqual(8, \App\Models\Product::query()->withoutGlobalScopes()->count());
        $this->assertGreaterThanOrEqual(10, \App\Models\Order::query()->withoutGlobalScopes()->count());
    }
}

<?php

namespace Tests\Feature;

use App\Models\Plan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Plan::query()->create([
            'key' => 'starter',
            'name' => 'Starter',
            'price' => '29.0000',
            'currency' => 'USD',
            'features' => [],
            'is_active' => true,
        ]);
    }

    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}

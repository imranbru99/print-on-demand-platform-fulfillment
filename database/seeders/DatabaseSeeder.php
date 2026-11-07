<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (app()->environment(['local', 'testing', 'demo']) || config('pod.demo_mode')) {
            $this->call(DemoSeeder::class);

            return;
        }
    }
}

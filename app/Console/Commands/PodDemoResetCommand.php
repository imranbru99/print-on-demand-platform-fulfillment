<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;

final class PodDemoResetCommand extends Command
{
    protected $signature = 'pod:demo-reset';

    protected $description = 'Drop all tables, re-run migrations, and seed demo data (non-production only)';

    public function handle(): int
    {
        if (app()->environment('production')) {
            $this->error('Refusing to reset the database in production.');

            return self::FAILURE;
        }

        $this->call('migrate:fresh', [
            '--seed' => true,
            '--force' => true,
        ]);

        $this->info('Demo database has been reset and seeded.');

        return self::SUCCESS;
    }
}

<?php

use App\Jobs\CleanupExpiredCartsJob;
use App\Jobs\RetryFailedWebhookDeliveriesJob;
use App\Jobs\SyncShipmentTrackingJob;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::job(new SyncShipmentTrackingJob)->everyFifteenMinutes();
Schedule::job(new RetryFailedWebhookDeliveriesJob)->hourly();
Schedule::job(new CleanupExpiredCartsJob)->daily();

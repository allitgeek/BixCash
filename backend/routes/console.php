<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Auto-confirm expired partner transactions every minute
Schedule::command('transactions:auto-confirm')
    ->everyMinute()
    ->withoutOverlapping()
    ->runInBackground();

// Recalculate profit sharing levels daily at 2:00 AM
Schedule::command('profit-sharing:assign-levels')
    ->dailyAt('02:00')
    ->withoutOverlapping()
    ->runInBackground();

// Calculate monthly partner commissions on 1st of each month at 2:00 AM
Schedule::command('commission:calculate-monthly')
    ->monthlyOn(1, '02:00')
    ->withoutOverlapping()
    ->runInBackground();

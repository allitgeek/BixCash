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

// Recalculate profit sharing levels monthly on the 1st at 3:00 AM
Schedule::command('profit-sharing:assign-levels')
    ->monthlyOn(1, '03:00')
    ->withoutOverlapping()
    ->runInBackground();

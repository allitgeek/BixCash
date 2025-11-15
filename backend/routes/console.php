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

// Send commission payment reminders every Monday at 9:00 AM
Schedule::command('commission:send-reminders --min-days=7 --min-amount=1000')
    ->weeklyOn(1, '09:00')
    ->withoutOverlapping()
    ->runInBackground();

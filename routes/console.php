<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
|--------------------------------------------------------------------------
| Scheduled Tasks
|--------------------------------------------------------------------------
|
| Here you may define all of your scheduled tasks. These tasks will be
| run based on the schedule defined. This is useful for automated
| maintenance and content monitoring.
|
*/

// Clear sitemap cache every hour
Schedule::command('sitemap:clear')->hourly();

// Check for outdated content daily at 8 AM
Schedule::command('content:check-outdated --days=180 --json')->dailyAt('08:00');

// Archive old agendas weekly on Sunday at 9 AM
Schedule::command('content:check-outdated --archive')->weeklyOn(0, '09:00');

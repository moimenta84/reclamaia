<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Check for claims that need escalation every day at 09:00 CET
Schedule::command('reclamaia:check-escalations')
    ->dailyAt('09:00')
    ->timezone('Europe/Madrid')
    ->withoutOverlapping()
    ->runInBackground();

<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Reconcile pawaPay payments stuck in PENDING/PROCESSING every 15 minutes.
Schedule::command('pawapay:reconcile')
    ->everyFifteenMinutes()
    ->withoutOverlapping()
    ->runInBackground();

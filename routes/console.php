<?php

use App\Console\Commands\CheckContractExpiry;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
// بدل dailyAt('08:00') 
Schedule::command(CheckContractExpiry::class)->everyMinute();
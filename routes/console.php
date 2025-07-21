<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Jobs\CheckCartReminders;
//ca ne marche pas car il faut forcément une classe panier
Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Planification des tâches
Schedule::job(new CheckCartReminders)->hourly();

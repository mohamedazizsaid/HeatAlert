<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Planification automatique de la prédiction des coupures
// S'exécute automatiquement toutes les 6 heures avec notifications et planification préventive
Schedule::command('predictions:auto-check --threshold=0.70 --notify --auto-draft')
    ->everySixHours()
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/predictions-auto-check.log'));


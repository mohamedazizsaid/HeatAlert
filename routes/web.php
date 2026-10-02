<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AlerteMeteoController;
use App\Http\Controllers\Admin\ConseilController;
use App\Http\Controllers\Admin\ZoneController;
use App\Http\Controllers\FrontController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes — HeatAlert
|--------------------------------------------------------------------------
*/

// Page publique d'accueil
Route::get('/', function () {
    return redirect()->route('login');
});

// Route 'dashboard' pour compatibilité Breeze (redirige selon le rôle)
Route::get('/dashboard', function () {
    if (auth()->user()?->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('front.home');
})->middleware('auth')->name('dashboard');

// Routes authentifiées — Front (utilisateurs ROLE_USER)
Route::middleware(['auth'])->group(function () {
    Route::get('/home', [FrontController::class, 'index'])->name('front.home');
});

// Routes Admin — accessible uniquement aux ROLE_ADMIN
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

        // Gestion des Zones
        Route::resource('zones', ZoneController::class)->except(['show']);

        // Gestion des Alertes Météo
        Route::resource('alertes', AlerteMeteoController::class)->except(['show']);

        // Gestion des Conseils
        Route::resource('conseils', ConseilController::class)->except(['show']);
    });

// Routes d'authentification (Breeze)
require __DIR__.'/auth.php';

<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AlerteMeteoController;
use App\Http\Controllers\Admin\ConseilController;
use App\Http\Controllers\Admin\ZoneController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes — HeatAlert
|--------------------------------------------------------------------------
*/

// ─── Page d'accueil publique (splash screen + présentation)
Route::get('/', [HomeController::class, 'index'])->name('home');

// ─── Route 'dashboard' pour compatibilité Breeze (redirige selon le rôle)
Route::get('/dashboard', function () {
    if (auth()->user()?->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('front.home');
})->middleware('auth')->name('dashboard');

// ─── Routes authentifiées — Front (utilisateurs ROLE_USER)
Route::middleware(['auth'])->group(function () {
    Route::get('/accueil', [FrontController::class, 'index'])->name('front.home');
    Route::get('/meteo-mondiale', [FrontController::class, 'globalWeather'])->name('front.weather.global');
    Route::get('/zones', [FrontController::class, 'zones'])->name('front.zones.index');
    Route::get('/zones/{zone}', [FrontController::class, 'zoneShow'])->name('front.zones.show');
    Route::get('/alertes', [FrontController::class, 'alertes'])->name('front.alertes.index');
    Route::get('/alertes/{alerte}', [FrontController::class, 'alerteShow'])->name('front.alertes.show');
    Route::get('/conseils', [FrontController::class, 'conseils'])->name('front.conseils.index');
    Route::get('/conseils/{conseil}', [FrontController::class, 'conseilShow'])->name('front.conseils.show');

    // ─── Profil Utilisateur (Détails & Modification)
    Route::get('/profil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profil', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profil', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ─── Routes Admin — accessible uniquement aux ROLE_ADMIN
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
        Route::get('/statistiques', [AdminController::class, 'statistiques'])->name('statistiques');

        // Profil & Sécurité Administrateur
        Route::get('/profil', [AdminController::class, 'profile'])->name('profile.edit');
        Route::patch('/profil', [AdminController::class, 'updateProfile'])->name('profile.update');

        // Gestion des Zones (avec recherche/filtre et page détails)
        Route::resource('zones', ZoneController::class);

        // Gestion des Alertes Météo
        Route::resource('alertes', AlerteMeteoController::class);

        // Gestion des Conseils
        Route::resource('conseils', ConseilController::class);
    });

// ─── Routes d'authentification (Breeze)
require __DIR__.'/auth.php';

<?php

use App\Http\Controllers\CabinetMinistre\DashboardController;
use App\Http\Controllers\CabinetMinistre\RapportController;
use App\Http\Controllers\CabinetMinistre\SuiviController;
use App\Http\Controllers\CabinetMinistre\AlertController;
use Illuminate\Support\Facades\Route;

Route::prefix('cabinet-ministre')->middleware(['auth'])->name('cabinet_ministre.')->group(function () {

    // Dashboard cabinet
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Alertes & Suivi
    Route::get('/alertes', [AlertController::class, 'index'])->name('alertes');
    Route::post('/alertes/{recommandation}/escalader', [AlertController::class, 'escalader'])->name('alertes.escalader');


    // Rapports et statistiques
    Route::get('/rapports', [RapportController::class, 'index'])->name('rapports.index');
    Route::get('/rapports/nouveau', [RapportController::class, 'create'])->name('rapports.create');
    Route::post('/rapports', [RapportController::class, 'store'])->name('rapports.store');
    Route::get('/rapports/{rapport}', [RapportController::class, 'show'])->name('rapports.show');
    // Legacy mapping (to be safe if I miss a view update, though I will update view next)
    Route::post('/rapports/generer', [RapportController::class, 'store'])->name('rapports.generer');

    // Suivi global
    Route::get('/suivi', [SuiviController::class, 'index'])->name('suivi.index');
    Route::get('/suivi/{recommandation}', [SuiviController::class, 'show'])->name('suivi.show');

    // Téléchargement de fichiers liés aux recommandations
    Route::get('/suivi/document/{documentId}/download', [SuiviController::class, 'download'])->name('suivi.document.download');

    // Alertes et notifications importantes
    Route::post('/alertes/{recommandation}/escalader', [AlertController::class, 'escalader'])->name('alertes.escalader');
});

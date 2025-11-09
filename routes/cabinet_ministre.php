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
    Route::get('/rapports/generer', [RapportController::class, 'generer'])->name('rapports.generer');
    Route::get('/rapports/export-pdf', [RapportController::class, 'exportPDF'])->name('rapports.pdf');
    Route::get('/rapports/export-excel', [RapportController::class, 'exportExcel'])->name('rapports.excel');

    // Suivi global
    Route::get('/suivi', [SuiviController::class, 'index'])->name('suivi.index');
    Route::get('/suivi/{recommandation}', [SuiviController::class, 'show'])->name('suivi.show');

    // Alertes et notifications importantes
    Route::post('/alertes/{recommandation}/escalader', [AlertController::class, 'escalader'])->name('alertes.escalader');
});

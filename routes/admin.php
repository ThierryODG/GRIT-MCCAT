<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ITS\RapportController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\RolePermissionController;
use App\Http\Controllers\Admin\SystemSettingsController;
use App\Http\Controllers\Admin\UserController;

Route::prefix('admin')->middleware(['auth',/*'admin'*/])->name('admin.')->group(function () {

    // ==================== DASHBOARD ====================
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ==================== GESTION UTILISATEURS ====================
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::resource('users', UserController::class)->except(['create', 'edit']);
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');

    // ==================== GESTION STRUCTURES ====================
    Route::resource('structures', \App\Http\Controllers\Admin\StructureController::class);

    // ==================== GESTION RÔLES & PERMISSIONS ====================
    Route::prefix('roles')->name('roles.')->group(function () {
        // CRUD complet
        Route::get('/', [RolePermissionController::class, 'index'])->name('index');
        Route::get('/create', [RolePermissionController::class, 'create'])->name('create');
        Route::post('/', [RolePermissionController::class, 'store'])->name('store');
        Route::get('/{role}', [RolePermissionController::class, 'show'])->name('show');
        Route::get('/{role}/edit', [RolePermissionController::class, 'edit'])->name('edit');
        Route::put('/{role}', [RolePermissionController::class, 'update'])->name('update');
        Route::delete('/{role}', [RolePermissionController::class, 'destroy'])->name('destroy');

        // Routes spécialisées
        Route::get('/{role}/matrice', [RolePermissionController::class, 'matrice'])->name('matrice');
        Route::put('/{role}/permissions', [RolePermissionController::class, 'updatePermissions'])->name('permissions.update');
    });

    // ==================== RAPPORTS ====================
    // Route::prefix('rapports')->name('rapports.')->group(function () {
    //     Route::get('/', [RapportController::class, 'index'])->name('index');
    //     Route::get('/generer', [RapportController::class, 'generer'])->name('generer');
    //     Route::get('/export-pdf', [RapportController::class, 'exportPDF'])->name('pdf');
    //     Route::get('/export-excel', [RapportController::class, 'exportExcel'])->name('excel');
    // });

    // ==================== CONFIGURATION SYSTÈME ====================
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [SystemSettingsController::class, 'index'])->name('index');
        Route::put('/', [SystemSettingsController::class, 'update'])->name('update');
        Route::post('/reset', [SystemSettingsController::class, 'reset'])->name('reset');
        Route::post('/clear-cache', [SystemSettingsController::class, 'clearCache'])->name('clearCache');
    });
});

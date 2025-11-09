<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Ici sont définies toutes les routes accessibles depuis le navigateur.
|--------------------------------------------------------------------------
*/

// ==================== PAGE D'ACCUEIL ====================
Route::redirect('/', '/login');

// ==================== DASHBOARD PRINCIPAL ====================
Route::get('/dashboard', function () {
    // Redirection vers le dashboard selon le rôle
    if (Auth::check()) {
        $user = Auth::user();

        if ($user->hasRole('its')) {
            return redirect()->route('its.dashboard');
        } elseif ($user->hasRole('inspecteur_general')) {
            return redirect()->route('inspecteur_general.dashboard');
        } elseif ($user->hasRole('point_focal')) {
            return redirect()->route('point_focal.dashboard');
        } elseif ($user->hasRole('responsable')) {
            return redirect()->route('responsable.dashboard');
        } elseif ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        }
        elseif ($user->hasRole('cabinet_ministre')) {
            return redirect()->route('cabinet_ministre.dashboard');
        }
    }

})->middleware(['auth', 'verified'])->name('dashboard');

// ==================== PROFIL (Breeze) ====================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ==================== NOTIFICATIONS ====================
Route::middleware('auth')->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/marquer-lu', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::post('/notifications/tout-marquer-lu', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
});


// ==================== ROUTES PAR RÔLE ====================
require __DIR__ . '/auth.php';
require __DIR__ . '/admin.php';
require __DIR__ . '/its.php';
require __DIR__ . '/inspecteur_general.php';
require __DIR__ . '/point_focal.php';
require __DIR__ . '/responsable.php';
require __DIR__ . '/cabinet_ministre.php';

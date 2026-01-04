<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class SystemSettingsController extends Controller
{
    /**
     * Afficher la page des paramètres système
     */
    public function index()
    {
        $systemInfo = $this->getSystemInfo();
        $appSettings = $this->getAppSettings();
        
        // Fetch Business Settings
        $businessSettings = DB::table('settings')->pluck('value', 'key')->all();

        return view('admin.settings.index', compact('systemInfo', 'appSettings', 'businessSettings'));
    }

    /**
     * Mettre à jour les paramètres
     */
    public function update(Request $request)
    {
        // Check if we are updating business settings
        if ($request->has('setting_type') && $request->setting_type === 'business') {
            return $this->updateBusinessSettings($request);
        }

        $validated = $request->validate([
            'app_name' => 'required|string|max:255',
            'app_env' => 'required|in:local,production,staging',
            'app_debug' => 'boolean',
            'maintenance_mode' => 'boolean',
            'email_from_address' => 'required|email',
            'email_from_name' => 'required|string|max:255',
        ]);

        try {
            $this->updateEnvFile($validated);

            return redirect()->route('admin.settings.index')
                ->with('success', 'Paramètres système mis à jour avec succès. Un redémarrage peut être nécessaire.');

        } catch (\Exception $e) {
            return redirect()->route('admin.settings.index')
                ->with('error', 'Erreur lors de la mise à jour: ' . $e->getMessage());
        }
    }

    private function updateBusinessSettings(Request $request)
    {
        $validated = $request->validate([
            'alert_deadline_1_days' => 'required|integer|min:1',
            'alert_deadline_2_days' => 'required|integer|min:1|lt:alert_deadline_1_days',
            'default_deadline_months' => 'required|integer|min:1',
        ]);

        foreach ($validated as $key => $value) {
            DB::table('settings')->updateOrInsert(
                ['key' => $key],
                ['value' => $value, 'updated_at' => now()]
            );
        }

        // Clear config cache to ensure new settings are picked up if we cache them
        Cache::forget('business_settings');

        return redirect()->route('admin.settings.index')
            ->with('success', 'Paramètres métier mis à jour avec succès.');
    }

    /**
     * Réinitialiser certaines données
     */
    public function reset(Request $request)
    {
        $request->validate([
            'reset_type' => 'required|in:cache,logs,sessions,statistics'
        ]);

        try {
            switch ($request->reset_type) {
                case 'cache':
                    Cache::flush();
                    Artisan::call('cache:clear');
                    $message = 'Cache système vidé avec succès.';
                    break;

                case 'logs':
                    if (file_exists(storage_path('logs/laravel.log'))) {
                        file_put_contents(storage_path('logs/laravel.log'), '');
                    }
                    $message = 'Fichiers de log vidés avec succès.';
                    break;

                case 'sessions':
                    DB::table('sessions')->truncate();
                    $message = 'Sessions utilisateurs vidées avec succès.';
                    break;

                case 'statistics':
                    // Exemple: réinitialiser des compteurs statistiques
                    // À adapter selon vos besoins
                    $message = 'Statistiques réinitialisées avec succès.';
                    break;
            }

            return redirect()->route('admin.settings.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            return redirect()->route('admin.settings.index')
                ->with('error', 'Erreur lors de la réinitialisation: ' . $e->getMessage());
        }
    }

    /**
     * Vider les caches
     */
    public function clearCache()
    {
        try {
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('route:clear');
            Artisan::call('view:clear');

            return redirect()->route('admin.settings.index')
                ->with('success', 'Tous les caches ont été vidés avec succès.');

        } catch (\Exception $e) {
            return redirect()->route('admin.settings.index')
                ->with('error', 'Erreur lors du vidage des caches: ' . $e->getMessage());
        }
    }

    /**
     * Obtenir les informations système
     */
    private function getSystemInfo(): array
    {
        return [
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'N/A',
            'database_driver' => config('database.default'),
            'timezone' => config('app.timezone'),
            'upload_max_size' => ini_get('upload_max_filesize'),
            'memory_limit' => ini_get('memory_limit'),
            'max_execution_time' => ini_get('max_execution_time'),
            'app_env' => config('app.env'),
        ];
    }

    /**
     * Obtenir les paramètres de l'application
     */
    private function getAppSettings(): array
    {
        return [
            'app_name' => config('app.name'),
            'app_env' => config('app.env'),
            'app_debug' => config('app.debug'),
            'app_url' => config('app.url'),
            'email_from_address' => config('mail.from.address'),
            'email_from_name' => config('mail.from.name'),
        ];
    }

    /**
     * Mettre à jour le fichier .env
     */
    private function updateEnvFile(array $data): void
    {
        $envPath = base_path('.env');

        if (!file_exists($envPath)) {
            throw new \Exception('Fichier .env non trouvé');
        }

        $envContent = file_get_contents($envPath);

        $updates = [
            'APP_NAME' => $data['app_name'],
            'APP_ENV' => $data['app_env'],
            'APP_DEBUG' => $data['app_debug'] ? 'true' : 'false',
            'MAIL_FROM_ADDRESS' => $data['email_from_address'],
            'MAIL_FROM_NAME' => $data['email_from_name'],
        ];

        foreach ($updates as $key => $value) {
            $envContent = preg_replace(
                "/^{$key}=.*/m",
                "{$key}=\"{$value}\"",
                $envContent
            );
        }

        file_put_contents($envPath, $envContent);
    }
}

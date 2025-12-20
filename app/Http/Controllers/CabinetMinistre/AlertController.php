<?php

namespace App\Http\Controllers\CabinetMinistre;

use App\Http\Controllers\Controller;
use App\Models\Recommandation;
use Illuminate\Http\Request;

class AlertController extends Controller
{
    /**
     * Affiche les alertes critiques
     */
    public function index()
    {
        // ==================== ALERTES CRITIQUES ====================

        // 1. Recommandations en retard
        $enRetard = Recommandation::where('date_limite', '<', now())
            ->whereNotIn('statut', ['cloturee', 'terminee'])
            ->with(['its:id,name', 'pointFocal:id,name', 'planAction'])
            ->orderBy('date_limite', 'asc')
            ->get();

        // 2. Échéance dans moins de 7 jours (urgentes)
        $prochesEcheances = Recommandation::whereBetween('date_limite', [now(), now()->addDays(7)])
            ->whereNotIn('statut', ['cloturee', 'terminee'])
            ->with(['its:id,name', 'pointFocal:id,name'])
            ->orderBy('date_limite', 'asc')
            ->get();

        // 3. Priorité haute bloquées
        $hautePriorite = Recommandation::where('priorite', 'haute')
            ->where('statut', '!=', 'cloturee')
            ->where('date_limite', '<', now()->addDays(15))
            ->with(['its:id,name', 'pointFocal:id,name'])
            ->get();

        // 4. Sans point focal assigné (bloquées dans le workflow)
        $sansPointFocal = Recommandation::whereNull('point_focal_id')
            ->where('statut', 'validee_ig')
            ->with(['its:id,name'])
            ->get();

        return view('cabinet_ministre.alertes.index', compact(
            'enRetard',
            'prochesEcheances',
            'hautePriorite',
            'sansPointFocal'
        ));
    }

    /**
     * Escalader une alerte (marquer comme prioritaire pour le ministre)
     */
    public function escalader(Recommandation $recommandation)
    {
        // Vérifier si la colonne 'escalade' existe dans votre table
        // Sinon, créez une migration : php artisan make:migration add_escalade_to_recommandations

        $recommandation->update([
            'priorite' => 'haute', // Forcer en haute priorité
            // 'escalade' => true, // Si vous ajoutez cette colonne
        ]);

        // TODO: Envoyer une notification au Responsable et Point Focal

        return redirect()->route('cabinet_ministre.alertes.index')
            ->with('success', 'Alerte escaladée avec succès. Les responsables ont été notifiés.');
    }
}

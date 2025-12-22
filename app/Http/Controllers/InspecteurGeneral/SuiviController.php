<?php

namespace App\Http\Controllers\InspecteurGeneral;

use App\Http\Controllers\Controller;
use App\Models\Recommandation;
use Illuminate\Support\Facades\Auth;

class SuiviController extends Controller
{
    /**
     * Suivi global des recommandations validées par l'IG
     */
    public function index()
    {
        $recommandations = Recommandation::where('inspecteur_general_id', Auth::id())
            ->with(['its:id,name,email', 'pointFocal:id,name', 'plansAction', 'structure:id,nom,sigle'])
            ->whereIn('statut', [
                'plan_valide_ig',
                'en_execution',
                'execution_terminee',
                'demande_cloture',
                'cloturee'
            ])
            ->orderBy('created_at', 'desc')
            ->get();

        // Grouper par structure
        $structures = $recommandations->groupBy('structure_id')->map(function ($items) {
            return [
                'info' => $items->first()->structure,
                'recommandations' => $items
            ];
        });

        return view('inspecteur_general.suivi.index', compact('structures'));
    }

    /**
     * Vue détaillée du suivi (Stepper lecture seule)
     */
    public function show(Recommandation $recommandation)
    {
        // Vérifier l'accès
        if ($recommandation->inspecteur_general_id !== Auth::id()) {
            abort(403);
        }

        $recommandation->load(['plansAction', 'pointFocal', 'structure', 'its']);

        // Calcul progression
        $totalActions = $recommandation->plansAction->count();
        $completedActions = $recommandation->plansAction->where('statut_execution', 'termine')->count();
        $globalProgress = $totalActions > 0 ? round(($completedActions / $totalActions) * 100) : 0;

        return view('inspecteur_general.suivi.show', compact('recommandation', 'globalProgress'));
    }
}

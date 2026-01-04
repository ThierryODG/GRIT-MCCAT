<?php

namespace App\Http\Controllers\Responsable;

use Illuminate\Http\Request;
use App\Models\Recommandation;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class SuiviController extends Controller
{
    /**
     * Suivi de toutes mes recommandations
     */
    public function index(Request $request)
    {
        $query = Recommandation::where('responsable_id', Auth::id())
            ->whereIn('statut', [
                'plan_valide_ig',
                'en_execution',
                'execution_terminee',
                'demande_cloture',
                'cloturee'
            ]);

        // Filtres
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('point_focal_id')) {
            $query->where('point_focal_id', $request->point_focal_id);
        }

        if ($request->filled('priorite')) {
            $query->where('priorite', $request->priorite);
        }

        $recommandations = $query->with([
                'its:id,name',
                'inspecteurGeneral:id,name',
                'pointFocal:id,name',
                'plansAction'
            ])
            ->orderBy('date_limite', 'asc')
            ->paginate(20);

        // Liste des Points Focaux pour le filtre
        $pointsFocaux = \App\Models\User::whereHas('role', function($q) {
                $q->where('nom', 'point_focal');
            })
            ->where('structure_id', Auth::user()->structure_id)
            ->get();

        return view('responsable.suivi.index', compact('recommandations', 'pointsFocaux'));
    }

    /**
     * Détails d'une recommandation
     */
    public function show(Recommandation $recommandation)
    {
        if ($recommandation->responsable_id !== Auth::id()) {
            abort(403);
        }

        $recommandation->load([
            'its:id,name',
            'inspecteurGeneral:id,name',
            'pointFocal:id,name,telephone',
            'plansAction.preuvesExecution'
        ]);

        // Calcul progression
        $totalActions = $recommandation->plansAction->count();
        $completedActions = $recommandation->plansAction->where('statut_execution', 'termine')->count();
        $globalProgress = $totalActions > 0 ? round(($completedActions / $totalActions) * 100) : 0;

        return view('responsable.suivi.show', compact('recommandation', 'globalProgress'));
    }

    /**
     * Export Excel des recommandations
     */
    public function export()
    {
        // TODO: Implémenter l'export Excel
        return back()->with('info', 'Export Excel à implémenter.');
    }
}

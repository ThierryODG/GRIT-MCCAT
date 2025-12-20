<?php

namespace App\Http\Controllers\CabinetMinistre;

use App\Http\Controllers\Controller;
use App\Models\Recommandation;
use Illuminate\Http\Request;

class SuiviController extends Controller
{
    /**
     * Liste des recommandations avec filtres
     */
    public function index(Request $request)
    {
        $query = Recommandation::with(['its:id,name', 'inspecteurGeneral:id,name', 'pointFocal:id,name']);

        // ==================== FILTRES ====================

        // Filtre par statut
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        // Filtre par priorité
        if ($request->filled('priorite')) {
            $query->where('priorite', $request->priorite);
        }

        // Filtre par date
        if ($request->filled('date_debut') && $request->filled('date_fin')) {
            $query->whereBetween('created_at', [$request->date_debut, $request->date_fin]);
        }

        // Filtre par structure ITS
        if ($request->filled('its_id')) {
            $query->where('its_id', $request->its_id);
        }

        // Afficher uniquement les recommandations en retard
        if ($request->boolean('en_retard')) {
            $query->where('date_limite', '<', now())
                  ->whereNotIn('statut', ['cloturee', 'terminee']);
        }

        $recommandations = $query->orderBy('created_at', 'desc')
            ->paginate(20);

        // Liste des ITS pour le filtre
        $listITS = \App\Models\User::whereHas('role', function($q) {
            $q->where('nom', 'its');
        })->pluck('name', 'id');

        return view('cabinet_ministre.suivi.index', compact('recommandations', 'listITS'));
    }

    /**
     * Détails d'une recommandation
     */
    public function show(Recommandation $recommandation)
    {
        $recommandation->load([
            'its:id,name,telephone',
            'inspecteurGeneral:id,name',
            'pointFocal:id,name,telephone',
            'planAction.avancement' // Si vous avez un modèle Avancement
        ]);

        return view('cabinet_ministre.suivi.show', compact('recommandation'));
    }
}

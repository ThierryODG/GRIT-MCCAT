<?php

namespace App\Http\Controllers\ITS;

use Illuminate\Http\Request;
use App\Models\Recommandation;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class RapportController extends Controller
{
    /**
     * Page d'accueil des rapports
     */
    public function index()
    {
        $stats = [
            'total' => Recommandation::where('its_id', Auth::id())->count(),
            'brouillon' => Recommandation::where('its_id', Auth::id())
                ->where('statut', 'brouillon')->count(),
            'soumises_ig' => Recommandation::where('its_id', Auth::id())
                ->where('statut', 'soumise_ig')->count(),
            'validees_ig' => Recommandation::where('its_id', Auth::id())
                ->where('statut', 'validee_ig')->count(),
            'en_execution' => Recommandation::where('its_id', Auth::id())
                ->where('statut', 'en_execution')->count(),
            'cloturees' => Recommandation::where('its_id', Auth::id())
                ->where('statut', 'cloturee')->count(),
        ];

        return view('its.rapports.index', compact('stats'));
    }

    /**
     * Formulaire de génération de rapport
     */
    public function generer()
    {
        return view('its.rapports.generer');
    }

    /**
     * Générer le rapport selon les filtres
     */
    public function generateReport(Request $request)
    {
        $validated = $request->validate([
            'type_rapport' => 'required|in:statistiques,details,cloture',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut',
            'statut' => 'nullable|string',
            'priorite' => 'nullable|in:basse,moyenne,haute',
            'format' => 'nullable|in:html,pdf,excel'
        ]);

        // ==================== RÉCUPÉRATION DES DONNÉES ====================
        $query = Recommandation::where('its_id', Auth::id())
            ->whereBetween('created_at', [$validated['date_debut'], $validated['date_fin']]);

        if ($request->filled('statut')) {
            $query->where('statut', $validated['statut']);
        }

        if ($request->filled('priorite')) {
            $query->where('priorite', $validated['priorite']);
        }

        $recommandations = $query->with(['inspecteurGeneral', 'pointFocal', 'planAction'])
            ->get();

        // ==================== STATISTIQUES ====================
        $stats = [
            'total' => $recommandations->count(),
            'validees_ig' => $recommandations->where('statut', 'validee_ig')->count(),
            'en_execution' => $recommandations->where('statut', 'en_execution')->count(),
            'cloturees' => $recommandations->where('statut', 'cloturee')->count(),
            'en_retard' => $recommandations->filter(function($rec) {
                return $rec->date_limite < now() && !in_array($rec->statut, ['cloturee']);
            })->count()
        ];

        // Répartition par statut
        $statsParStatut = $recommandations->groupBy('statut')->map->count();

        // Répartition par priorité
        $statsParPriorite = $recommandations->groupBy('priorite')->map->count();

        $filters = $validated;

        // ==================== GÉNÉRATION DE LA VUE ====================
        switch ($validated['type_rapport']) {
            case 'statistiques':
                return view('its.rapports.stats', compact(
                    'recommandations',
                    'stats',
                    'statsParStatut',
                    'statsParPriorite',
                    'filters'
                ));

            case 'details':
                return view('its.rapports.details', compact('recommandations', 'filters'));

            case 'cloture':
                $cloturees = $recommandations->where('statut', 'cloturee');
                return view('its.rapports.cloture', compact('cloturees', 'filters'));

            default:
                return back()->with('error', 'Type de rapport non supporté.');
        }
    }
}

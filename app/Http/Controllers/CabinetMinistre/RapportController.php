<?php

namespace App\Http\Controllers\CabinetMinistre;

use App\Http\Controllers\Controller;
use App\Models\Recommandation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RapportController extends Controller
{
    /**
     * Affiche la page de génération de rapports
     */
    public function index()
    {
        return view('cabinet_ministre.rapports.index');
    }

    /**
     * Génère un rapport selon les filtres
     */
    public function generer(Request $request)
    {
        $request->validate([
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut',
            'statut' => 'nullable|string',
        ]);

        $query = Recommandation::with(['its', 'inspecteurGeneral', 'pointFocal', 'planAction']);

        // Filtres
        $query->whereBetween('created_at', [$request->date_debut, $request->date_fin]);

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        $recommandations = $query->get();

        // Statistiques du rapport
        $statistiques = [
            'total' => $recommandations->count(),
            'validees' => $recommandations->where('statut', 'validee_ig')->count(),
            'en_cours' => $recommandations->where('statut', 'en_cours')->count(),
            'cloturees' => $recommandations->where('statut', 'cloturee')->count(),
            'en_retard' => $recommandations->filter->estEnRetard()->count(),
        ];

        // Répartition par structure
        $parStructure = $recommandations->groupBy(function($r) {
            $its = $r->its;
            if ($its && $its->relationLoaded('structure')) {
                return $its->structure?->nom ?? 'Inconnu';
            }
            // Fallback: try to access via relation or return 'Inconnu'
            return $its && $its->structure ? $its->structure->nom : 'Inconnu';
        })->map->count()->sortDesc();

        return view('cabinet_ministre.rapports.resultat', compact(
            'recommandations',
            'statistiques',
            'parStructure'
        ));
    }

    /**
     * Exporte le rapport en PDF (TODO: implémenter avec DomPDF)
     */
    public function exportPDF(Request $request)
    {
        // TODO: Implémenter l'export PDF
        return back()->with('info', 'Export PDF : fonctionnalité à implémenter prochainement.');
    }

    /**
     * Exporte le rapport en Excel (TODO: implémenter avec Maatwebsite)
     */
    public function exportExcel(Request $request)
    {
        // TODO: Implémenter l'export Excel
        return back()->with('info', 'Export Excel : fonctionnalité à implémenter prochainement.');
    }
}

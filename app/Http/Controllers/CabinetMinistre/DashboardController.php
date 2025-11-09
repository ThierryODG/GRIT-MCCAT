<?php

namespace App\Http\Controllers\CabinetMinistre;

use App\Http\Controllers\Controller;
use App\Models\Recommandation;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // ==================== STATISTIQUES PRINCIPALES ====================
        $stats = [
            'total_recommandations' => Recommandation::count(),
            'en_attente_validation' => Recommandation::where('statut', 'en_attente_validation')->count(),
            'validees_ig' => Recommandation::where('statut', 'validee_ig')->count(),
            'en_cours' => Recommandation::where('statut', 'en_cours')->count(),
            'cloturees' => Recommandation::where('statut', 'cloturee')->count(),
        ];

        // ==================== INDICATEURS CRITIQUES ====================

        // Recommandations en retard (TRÈS IMPORTANT pour le ministre)
        $enRetard = Recommandation::where('date_limite', '<', now())
            ->whereNotIn('statut', ['cloturee', 'terminee'])
            ->count();

        // Taux de mise en œuvre (KPI principal)
        $tauxMiseEnOeuvre = $stats['total_recommandations'] > 0
            ? round(($stats['cloturees'] / $stats['total_recommandations']) * 100, 1)
            : 0;

        // Délai moyen de traitement (en jours) - CORRIGÉ POUR POSTGRESQL
        $delaiMoyen = Recommandation::where('statut', 'cloturee')
            ->whereNotNull('date_cloture')
            ->selectRaw('AVG(EXTRACT(EPOCH FROM (date_cloture - created_at))/86400) as delai')
            ->value('delai');
        $delaiMoyen = $delaiMoyen ? round($delaiMoyen, 1) : 0;

        // ==================== RÉPARTITION PAR STATUT ====================
        $repartitionStatut = Recommandation::select('statut', DB::raw('count(*) as total'))
            ->groupBy('statut')
            ->pluck('total', 'statut')
            ->toArray();

        // ==================== GRAPHIQUE : 6 DERNIERS MOIS - CORRIGÉ POUR POSTGRESQL ====================
        $recommandationsParMois = Recommandation::select(
                DB::raw("TO_CHAR(created_at, 'YYYY-MM') as mois"),
                DB::raw('count(*) as total')
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('mois')
            ->orderBy('mois')
            ->pluck('total', 'mois');

        // ==================== TOP 5 STRUCTURES PERFORMANTES ====================
        $topStructures = Recommandation::select('its_id', DB::raw('count(*) as total'))
            ->with('its:id,name,direction')
            ->where('statut', 'cloturee')
            ->groupBy('its_id')
            ->orderByDesc('total')
            ->take(5)
            ->get();

        // ==================== DERNIÈRES ACTIVITÉS ====================
        $dernieresActivites = Recommandation::with(['its:id,name', 'inspecteurGeneral:id,name'])
            ->latest()
            ->take(10)
            ->get();

        return view('cabinet_ministre.dashboard', compact(
            'stats',
            'enRetard',
            'tauxMiseEnOeuvre',
            'delaiMoyen',
            'repartitionStatut',
            'recommandationsParMois',
            'topStructures',
            'dernieresActivites'
        ));
    }
}

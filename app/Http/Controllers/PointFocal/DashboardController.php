<?php

namespace App\Http\Controllers\PointFocal;

use App\Http\Controllers\Controller;
use App\Models\Recommandation;
use App\Models\PlanAction;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        // ==================== STATISTIQUES ====================
        $stats = [
            // Recommandations qui me sont assignées
            'total_assignees' => Recommandation::where('point_focal_id', $userId)->count(),

            // Plans d'action à remplir
            'plans_a_remplir' => PlanAction::where('point_focal_id', $userId)
                ->where('statut_validation', 'en_attente_responsable')
                ->whereNull('action') // Plan pas encore rempli
                ->count(),

            // Plans en attente de validation
            'plans_attente_validation' => PlanAction::where('point_focal_id', $userId)
                ->whereIn('statut_validation', ['en_attente_responsable', 'en_attente_ig'])
                ->whereNotNull('action') // Plan rempli
                ->count(),

            // Plans validés (en cours d'exécution)
            'en_execution' => PlanAction::where('point_focal_id', $userId)
                ->where('statut_validation', 'valide_ig')
                ->where('statut_execution', 'en_cours')
                ->count(),

            // Plans terminés
            'termines' => PlanAction::where('point_focal_id', $userId)
                ->where('statut_execution', 'termine')
                ->count(),

            // Recommandations en retard
            'en_retard' => Recommandation::where('point_focal_id', $userId)
                ->where('date_limite', '<', now())
                ->whereNotIn('statut', ['cloturee', 'execution_terminee'])
                ->count(),
        ];

        // ==================== MES RECOMMANDATIONS RÉCENTES ====================
        $mesRecommandations = Recommandation::where('point_focal_id', $userId)
            ->with(['its:id,name', 'inspecteurGeneral:id,name', 'responsable:id,name', 'planAction'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // ==================== GRAPHIQUE D'AVANCEMENT ====================
        $avancementGlobal = PlanAction::where('point_focal_id', $userId)
            ->where('statut_validation', 'valide_ig')
            ->avg('pourcentage_avancement');

        // ==================== ALERTES ====================
        $alertes = [
            'plans_a_remplir' => $stats['plans_a_remplir'],
            'en_retard' => $stats['en_retard'],
            'validations_rejetees' => PlanAction::where('point_focal_id', $userId)
                ->whereIn('statut_validation', ['rejete_responsable', 'rejete_ig'])
                ->count()
        ];

        return view('point_focal.dashboard', compact(
            'stats',
            'mesRecommandations',
            'avancementGlobal',
            'alertes'
        ));
    }
}

<?php

namespace App\Http\Controllers\InspecteurGeneral;

use App\Http\Controllers\Controller;
use App\Models\Recommandation;
use App\Models\PlanAction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // ==================== PHASE 1 : Validation Recommandations ====================
        $statsRecommandations = [
            'en_attente_validation' => Recommandation::where('statut', 'soumise_ig')->count(),
            'validees_ig' => Recommandation::where('statut', 'validee_ig')
                ->where('inspecteur_general_id', Auth::id())
                ->count(),
            'rejetees_ig' => Recommandation::where('statut', 'rejetee_ig')
                ->where('inspecteur_general_id', Auth::id())
                ->count(),
        ];

        // ==================== PHASE 3 : Validation Plans d'Action ====================
        $statsPlansAction = [
            // Plans dont la recommandation est soumise à l'IG
            'en_attente_validation' => PlanAction::whereHas('recommandation', function($q) {
                $q->where('statut', 'plan_soumis_ig');
            })->count(),
            'valides' => PlanAction::where('validateur_ig_id', Auth::id())
                ->whereHas('recommandation', function($q) {
                    $q->where('statut', 'plan_valide_ig');
                })->count(),
            'rejetes' => PlanAction::where('validateur_ig_id', Auth::id())
                ->whereHas('recommandation', function($q) {
                    $q->where('statut', 'plan_rejete_ig');
                })->count(),
        ];

        // ==================== ACTIVITÉS RÉCENTES ====================

        // Recommandations récemment validées par cet IG
        $recommandationsRecentes = Recommandation::where('inspecteur_general_id', Auth::id())
            ->with(['its:id,name,email,telephone', 'structure:id,nom']) // CORRIGÉ : structure au lieu de direction
            ->whereIn('statut', ['validee_ig', 'rejetee_ig'])
            ->latest('date_validation_ig')
            ->take(5)
            ->get();

        // Plans d'action récemment traités
        $plansActionsRecents = PlanAction::where('validateur_ig_id', Auth::id())
            ->with(['recommandation.its:id,name,email', 'recommandation.structure:id,nom']) // CORRIGÉ : structure au lieu de direction
            ->whereHas('recommandation', function($q) {
                $q->whereIn('statut', ['plan_valide_ig', 'plan_rejete_ig']);
            })
            ->latest('date_validation_ig')
            ->take(5)
            ->get();

        // ==================== GRAPHIQUES ====================

        // Évolution des validations (6 derniers mois) - CORRIGÉ POUR POSTGRESQL
        $validationsParMois = Recommandation::where('inspecteur_general_id', Auth::id())
            ->whereIn('statut', ['validee_ig', 'rejetee_ig'])
            ->select(
                DB::raw("TO_CHAR(date_validation_ig, 'YYYY-MM') as mois"),
                DB::raw('count(*) as total')
            )
            ->where('date_validation_ig', '>=', now()->subMonths(6))
            ->groupBy(DB::raw("TO_CHAR(date_validation_ig, 'YYYY-MM')"))
            ->orderBy('mois')
            ->pluck('total', 'mois');

        return view('inspecteur_general.dashboard', compact(
            'statsRecommandations',
            'statsPlansAction',
            'recommandationsRecentes',
            'plansActionsRecents',
            'validationsParMois'
        ));
    }

    /**
     * Suivi global des recommandations validées par l'IG
     */
    /**
     * Suivi global des recommandations validées par l'IG
     */
    public function suivi()
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
    public function showSuivi(Recommandation $recommandation)
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

    /**
     * Liste des rapports (à implémenter plus tard)
     */
    public function rapports()
    {
        // TODO: Implémenter la logique de rapports
        return view('inspecteur_general.rapports.index');
    }
}

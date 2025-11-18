<?php

namespace App\Http\Controllers\PointFocal;

use App\Models\Recommandation;
use App\Models\PlanAction;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // ==================== STATISTIQUES PRINCIPALES ====================
        $totalRecommandations = Recommandation::where('point_focal_id', $userId)->count();
        $attentePlanCount = Recommandation::where('point_focal_id', $userId)
            ->whereIn('statut', ['point_focal_assigne', 'plan_en_redaction'])
            ->count();
        $enExecutionCount = Recommandation::where('point_focal_id', $userId)
            ->where('statut', 'en_execution')
            ->count();
        $enRetardCount = Recommandation::where('point_focal_id', $userId)->enRetard()->count();

        // ==================== RÉPARTITION PAR STATUT ====================
        $statuts = Recommandation::where('point_focal_id', $userId)
            ->selectRaw('statut, COUNT(*) as count')
            ->groupBy('statut')
            ->get()
            ->map(function ($item) {
                $colors = [
                    'point_focal_assigne' => 'bg-purple-100 text-purple-800',
                    'plan_en_redaction' => 'bg-pink-100 text-pink-800',
                    'plan_soumis_responsable' => 'bg-orange-100 text-orange-800',
                    'plan_valide_responsable' => 'bg-teal-100 text-teal-800',
                    'plan_soumis_ig' => 'bg-cyan-100 text-cyan-800',
                    'plan_valide_ig' => 'bg-emerald-100 text-emerald-800',
                    'plan_rejete_ig' => 'bg-rose-100 text-rose-800',
                    'en_execution' => 'bg-sky-100 text-sky-800',
                    'execution_terminee' => 'bg-lime-100 text-lime-800',
                    'demande_cloture' => 'bg-amber-100 text-amber-800',
                    'cloturee' => 'bg-gray-100 text-gray-800'
                ];

                $labels = [
                    'point_focal_assigne' => 'À traiter',
                    'plan_en_redaction' => 'Plan en rédaction',
                    'plan_soumis_responsable' => 'En attente responsable',
                    'plan_valide_responsable' => 'Validé responsable',
                    'plan_soumis_ig' => 'En attente IG',
                    'plan_valide_ig' => 'Validé par l\'IG',
                    'plan_rejete_ig' => 'Plan rejeté',
                    'en_execution' => 'En exécution',
                    'execution_terminee' => 'Exécution terminée',
                    'demande_cloture' => 'Demande clôture',
                    'cloturee' => 'Clôturée'
                ];

                return (object)[
                    'statut' => $item->statut,
                    'count' => $item->count,
                    'color_class' => $colors[$item->statut] ?? 'bg-gray-100 text-gray-800',
                    'label' => $labels[$item->statut] ?? $item->statut
                ];
            });

        // ==================== RÉPARTITION PAR PRIORITÉ ====================
        $priorites = Recommandation::where('point_focal_id', $userId)
            ->selectRaw('priorite, COUNT(*) as count')
            ->groupBy('priorite')
            ->get();

        // ==================== PLANS D'ACTION EN RETARD ====================
        // Les dates sont maintenant sur Recommandation, pas sur PlanAction
        $plansEnRetard = Recommandation::where('point_focal_id', $userId)
            ->where('date_fin_prevue', '<', now())
            ->whereNotIn('statut', ['cloturee', 'execution_terminee'])
            ->with('plansAction')
            ->get();

        // ==================== PROCHAÎNES ÉCHÉANCES (7 jours) ====================
        $prochainesEcheances = Recommandation::where('point_focal_id', $userId)
            ->whereBetween('date_fin_prevue', [now(), now()->addDays(7)])
            ->whereNotIn('statut', ['cloturee', 'execution_terminee'])
            ->with('plansAction')
            ->orderBy('date_fin_prevue', 'asc')
            ->get();

        // ==================== RECOMMANDATIONS RÉCENTES ====================
        $recentRecommandations = Recommandation::where('point_focal_id', $userId)
            ->with(['structure', 'plansAction'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('point_focal.dashboard', compact(
            'totalRecommandations',
            'attentePlanCount',
            'enExecutionCount',
            'enRetardCount',
            'statuts',
            'priorites',
            'plansEnRetard',
            'prochainesEcheances',
            'recentRecommandations'
        ));
    }
}

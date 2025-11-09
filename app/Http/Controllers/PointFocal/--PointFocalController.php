<?php

namespace App\Http\Controllers;

use App\Models\Recommandation;
use App\Models\PlanAction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PointFocalController extends Controller
{
    public function dashboard()
    {
        $userId = Auth::id();

        // Statistiques
        $stats = [
            'recommandations_assignees' => Recommandation::where('point_focal_id', $userId)->count(),
            'plans_a_creer' => Recommandation::where('point_focal_id', $userId)
                ->where('statut', 'validee')
                ->whereDoesntHave('planAction')
                ->count(),
            'plans_en_cours' => PlanAction::where('point_focal_id', $userId)
                ->where('statut', 'en_cours')
                ->count(),
            'plans_termines' => PlanAction::where('point_focal_id', $userId)
                ->where('statut', 'termine')
                ->count(),
        ];

        // Données pour les tableaux
        $recommandationsRecent = Recommandation::where('point_focal_id', $userId)
            ->with('planAction')
            ->latest()
            ->take(5)
            ->get();

        $mesPlans = PlanAction::where('point_focal_id', $userId)
            ->with('recommandation')
            ->latest()
            ->take(5)
            ->get();

        $plansEnRetard = PlanAction::where('point_focal_id', $userId)
            ->where('date_fin_prevue', '<', now())
            ->where('statut', '!=', 'termine')
            ->with('recommandation')
            ->get();

        $recommandationsSansPlan = Recommandation::where('point_focal_id', $userId)
            ->where('statut', 'validee')
            ->whereDoesntHave('planAction')
            ->get();

        return view('pointfocal.dashboard', compact(
            'stats',
            'recommandationsRecent',
            'mesPlans',
            'plansEnRetard',
            'recommandationsSansPlan'
        ));
    }
}

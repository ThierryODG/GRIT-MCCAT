<?php

namespace App\Http\Controllers\Responsable;

use App\Http\Controllers\Controller;
use App\Models\Recommandation;
use App\Models\PlanAction;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $structureId = Auth::user()->structure_id;

        $stats = $this->getStats($structureId);
        $recommandationsAttente = $this->getRecommandationsAttente($structureId);
        $recommandationsRecentes = $this->getRecommandationsRecentes($structureId);

        return view('responsable.dashboard', compact(
            'stats',
            'recommandationsAttente',
            'recommandationsRecentes'
        ));
    }

    private function getStats(int $structureId): array
    {
        // Recommandations assignées à la structure
        $recommandationsAssignees = Recommandation::where('structure_id', $structureId)->count();

        // Recommandations en attente de validation (statut plan_soumis_responsable)
        $recommandationsAttenteCount = Recommandation::where('structure_id', $structureId)
            ->where('statut', 'plan_soumis_responsable')
            ->count();

        // Recommandations en retard
        $recommandationsRetard = Recommandation::where('structure_id', $structureId)
            ->where('date_limite', '<', now())
            ->whereNotIn('statut', ['cloturee', 'execution_terminee'])
            ->count();

        // Taux de progression globale
        $plansAction = PlanAction::whereHas('recommandation', function($q) use ($structureId) {
            $q->where('structure_id', $structureId);
        })->get();

        $totalProgression = $plansAction->sum('pourcentage_avancement');
        $progressionGlobale = $plansAction->count() > 0 ? round($totalProgression / $plansAction->count()) : 0;

        // Répartition par priorité
        $priorites = Recommandation::where('structure_id', $structureId)
            ->selectRaw('priorite, count(*) as total')
            ->groupBy('priorite')
            ->pluck('total', 'priorite')
            ->toArray();

        // S'assurer que toutes les priorités sont présentes
        $priorites = array_merge([
            'haute' => 0,
            'moyenne' => 0,
            'basse' => 0
        ], $priorites);

        return [
            'recommandations_assignees' => $recommandationsAssignees,
            'recommandations_attente' => $recommandationsAttenteCount,
            'recommandations_retard' => $recommandationsRetard,
            'progression_globale' => $progressionGlobale,
            'par_priorite' => $priorites
        ];
    }

    private function getRecommandationsAttente(int $structureId)
    {
        return Recommandation::where('structure_id', $structureId)
            ->where('statut', 'plan_soumis_responsable')
            ->with(['pointFocal:id,name', 'its:id,name'])
            ->latest()
            ->take(5)
            ->get();
    }

    private function getRecommandationsRecentes(int $structureId)
    {
        return Recommandation::where('structure_id', $structureId)
            ->with([
                'its:id,name',
                'pointFocal:id,name',
                'plansAction' // Pour afficher la mini barre de progression si besoin
            ])
            ->latest()
            ->take(5)
            ->get();
    }
}

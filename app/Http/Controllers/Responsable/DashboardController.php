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
        $plansEnAttente = $this->getPlansEnAttente($structureId);
        $recommandationsRecentes = $this->getRecommandationsRecentes($structureId);

        return view('responsable.dashboard', compact(
            'stats',
            'plansEnAttente',
            'recommandationsRecentes'
        ));
    }

    private function getStats($structureId): array
    {
        // Recommandations assignées à la structure
        $recommandationsAssignees = Recommandation::where('structure_id', $structureId)->count();

        // Plans en attente de validation responsable
        $plansEnAttenteCount = PlanAction::whereHas('recommandation', function($query) use ($structureId) {
            $query->where('structure_id', $structureId);
        })
        ->where('statut_validation', 'en_attente_responsable')
        ->count();

        // Recommandations en retard
        $recommandationsRetard = Recommandation::where('structure_id', $structureId)
            ->where('date_limite', '<', now())
            ->whereNotIn('statut', ['cloturee', 'execution_terminee'])
            ->count();

        // Taux de validation des plans
        $totalPlansSoumis = PlanAction::whereHas('recommandation', function($query) use ($structureId) {
            $query->where('structure_id', $structureId);
        })->count();

        $plansValides = PlanAction::whereHas('recommandation', function($query) use ($structureId) {
            $query->where('structure_id', $structureId);
        })
        ->whereIn('statut_validation', ['valide_responsable', 'valide_ig'])
        ->count();

        $tauxValidation = $totalPlansSoumis > 0 ? round(($plansValides / $totalPlansSoumis) * 100, 1) : 0;

        return [
            'recommandations_assignees' => $recommandationsAssignees,
            'plans_en_attente' => $plansEnAttenteCount,
            'recommandations_retard' => $recommandationsRetard,
            'taux_validation' => $tauxValidation,
        ];
    }

    private function getPlansEnAttente($structureId)
    {
        return PlanAction::whereHas('recommandation', function($query) use ($structureId) {
            $query->where('structure_id', $structureId);
        })
        ->where('statut_validation', 'en_attente_responsable')
        ->with([
            'recommandation:id,titre',
            'pointFocal:id,name'
        ])
        ->latest()
        ->take(5)
        ->get();
    }

    private function getRecommandationsRecentes($structureId)
    {
        return Recommandation::where('structure_id', $structureId)
            ->with([
                'its:id,name',
                'pointFocal:id,name'
            ])
            ->latest()
            ->take(5)
            ->get();
    }
}

<?php

namespace App\Http\Controllers\PointFocal;

use App\Http\Controllers\Controller;
use App\Models\PlanAction;
use App\Models\Recommandation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AvancementController extends Controller
{
    /**
     * Liste des plans d'action en cours d'exécution
     */
    public function index()
    {
        $baseQuery = PlanAction::where('point_focal_id', Auth::id())
            ->whereHas('recommandation', function ($q) {
                $q->where('statut', Recommandation::STATUT_PLAN_VALIDE_IG);
            });

        // Counters for dashboard
        $totalCount = (clone $baseQuery)->count();
        $notStarted = (clone $baseQuery)->where(function($q){
            $q->whereNull('statut_execution')->orWhere('statut_execution', 'non_demarre');
        })->count();
        $inProgress = (clone $baseQuery)->where('statut_execution', 'en_cours')->count();
        $done = (clone $baseQuery)->where('statut_execution', 'termine')->count();

        $plansActions = $baseQuery
            ->with(['recommandation' => function($q){ $q->select('id','reference','titre'); }])
            ->orderBy('created_at', 'asc')
            ->paginate(15);

        return view('point_focal.avancement.index', compact('plansActions', 'totalCount', 'notStarted', 'inProgress', 'done'));
    }

    /**
     * Formulaire de mise à jour de l'avancement
     */
    public function edit(PlanAction $planAction)
    {
        // Vérifications
        if ($planAction->point_focal_id !== Auth::id()) {
            abort(403, 'Ce plan ne vous est pas assigné.');
        }

        if (! $planAction->recommandation || $planAction->recommandation->statut !== Recommandation::STATUT_PLAN_VALIDE_IG) {
            return redirect()->route('point_focal.avancement.index')
                ->with('error', 'Ce plan n\'est pas encore validé.');
        }

        $planAction->load('recommandation');

        return view('point_focal.avancement.edit', compact('planAction'));
    }

    /**
     * Mettre à jour l'avancement
     */
    public function update(Request $request, PlanAction $planAction)
    {
        // Vérifications
        if ($planAction->point_focal_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'pourcentage_avancement' => 'required|integer|min:0|max:100',
            'commentaire_avancement' => 'nullable|string|max:1000',
        ]);

        // Mettre à jour l'avancement
        $planAction->update([
            'pourcentage_avancement' => $validated['pourcentage_avancement'],
            'commentaire_avancement' => $validated['commentaire_avancement'],
        ]);

        // Changer le statut d'exécution selon le pourcentage
        if ($validated['pourcentage_avancement'] == 0) {
            $planAction->update(['statut_execution' => 'non_demarre']);
        } elseif ($validated['pourcentage_avancement'] < 100) {
            $planAction->update(['statut_execution' => 'en_cours']);
            $planAction->recommandation->update(['statut' => Recommandation::STATUT_EN_EXECUTION]);
        } else {
            $planAction->update(['statut_execution' => 'termine']);
            $planAction->recommandation->update(['statut' => Recommandation::STATUT_EXECUTION_TERMINEE]);
        }

        // If AJAX/JSON request, return JSON response for immediate UI update
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Avancement mis à jour avec succès.',
                'pourcentage' => $planAction->pourcentage_avancement,
                'statut_execution' => $planAction->statut_execution,
            ]);
        }

        return redirect()->route('point_focal.avancement.index')
            ->with('success', 'Avancement mis à jour avec succès.');
    }
}

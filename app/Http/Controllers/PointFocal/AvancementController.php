<?php

namespace App\Http\Controllers\PointFocal;

use App\Http\Controllers\Controller;
use App\Models\PlanAction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AvancementController extends Controller
{
    /**
     * Liste des plans d'action en cours d'exécution
     */
    public function index()
    {
        $plansActions = PlanAction::where('point_focal_id', Auth::id())
            ->where('statut_validation', 'valide_ig') // Validés par l'IG
            ->with(['recommandation' => function ($q) {
                $q->select('id', 'its_id');
                $q->with('its:id,name');
                $q->orderBy('date_debut_prevue', 'asc');
            }])
            ->paginate(15);

        return view('point_focal.avancement.index', compact('plansActions'));
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

        if ($planAction->statut_validation !== 'valide_ig') {
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
            $planAction->recommandation->update(['statut' => 'en_execution']);
        } else {
            $planAction->update(['statut_execution' => 'termine']);
            $planAction->recommandation->update(['statut' => 'execution_terminee']);
        }

        return redirect()->route('point_focal.avancement.index')
            ->with('success', 'Avancement mis à jour avec succès.');
    }
}

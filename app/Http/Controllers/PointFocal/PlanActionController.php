<?php

namespace App\Http\Controllers\PointFocal;

use App\Http\Controllers\Controller;
use App\Models\PlanAction;
use Illuminate\Http\Request;

class PlanActionController extends Controller
{
    /**
     * Liste des plans d'action à remplir
     */
    public function index()
    {
        $plansActions = PlanAction::where('point_focal_id', auth()->id())
            ->with(['recommandation.its:id,name,direction', 'responsable:id,name'])
            ->whereNull('action') // Plans pas encore remplis
            ->orWhere(function($q) {
                $q->where('point_focal_id', auth()->id())
                  ->whereIn('statut_validation', ['rejete_responsable', 'rejete_ig']);
            })
            ->orderBy('created_at', 'asc')
            ->paginate(15);

        return view('point_focal.plans_action.index', compact('plansActions'));
    }

    /**
     * Formulaire pour remplir un plan d'action
     */
    public function create(PlanAction $planAction)
    {
        // Vérifications
        if ($planAction->point_focal_id !== auth()->id()) {
            abort(403, 'Ce plan ne vous est pas assigné.');
        }

        if ($planAction->statut_validation === 'valide_ig') {
            return redirect()->route('point_focal.plans_action.index')
                ->with('error', 'Ce plan est déjà validé, vous ne pouvez plus le modifier.');
        }

        $planAction->load('recommandation');

        return view('point_focal.plans_action.create', compact('planAction'));
    }

    /**
     * Enregistrer le plan d'action rempli
     */
    public function store(Request $request, PlanAction $planAction)
    {
        // Vérifications
        if ($planAction->point_focal_id !== auth()->id()) {
            abort(403, 'Ce plan ne vous est pas assigné.');
        }

        $validated = $request->validate([
            'action' => 'required|string',
            'indicateurs' => 'required|string',
            'incidence_financiere' => 'required|in:faible,moyen,eleve',
            'delai_mois' => 'required|integer|min:1|max:24',
            'date_debut_prevue' => 'required|date',
            'date_fin_prevue' => 'required|date|after:date_debut_prevue',
        ]);

        // Remplir le plan
        $planAction->update([
            'action' => $validated['action'],
            'indicateurs' => $validated['indicateurs'],
            'incidence_financiere' => $validated['incidence_financiere'],
            'delai_mois' => $validated['delai_mois'],
            'date_debut_prevue' => $validated['date_debut_prevue'],
            'date_fin_prevue' => $validated['date_fin_prevue'],
            'statut_validation' => 'en_attente_responsable', // Soumis au Responsable
        ]);

        // Mettre à jour le statut de la recommandation
        $planAction->recommandation->update([
            'statut' => 'plan_soumis_responsable'
        ]);

        // TODO: Notifier le Responsable

        return redirect()->route('point_focal.plans_action.index')
            ->with('success', 'Plan d\'action soumis à votre Responsable pour validation.');
    }
}

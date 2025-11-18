<?php

namespace App\Http\Controllers\InspecteurGeneral;

use App\Http\Controllers\Controller;
use App\Models\PlanAction;
use App\Models\Recommandation;
use Auth;
use Illuminate\Http\Request;

class PlanActionController extends Controller
{
    /**
     * Liste des plans d'action en attente de validation IG
     */
    public function index(Request $request)
    {
        // Inclure les plans déjà validés par le Responsable (pour visibilité IG)
        $query = PlanAction::whereIn('statut_validation', ['en_attente_ig', 'valide_responsable'])
            ->with([
                'recommandation.its:id,name',
                'pointFocal:id,name,telephone',
                'responsable:id,name'
            ]);

        // Filtre par priorité
        if ($request->filled('priorite')) {
            $query->whereHas('recommandation', function($q) use ($request) {
                $q->where('priorite', $request->priorite);
            });
        }

        $planActions = $query->orderBy('created_at', 'asc')
            ->paginate(15);

        return view('inspecteur_general.plan_actions.index', compact('planActions'));
    }

    /**
     * Détails d'un plan d'action
     */
    public function show(PlanAction $planAction)
    {
        if (!in_array($planAction->statut_validation, ['en_attente_ig', 'valide_ig', 'rejete'])) {
            return redirect()->route('inspecteur_general.plan_actions.index')
                ->with('error', 'Ce plan d\'action n\'est pas accessible.');
        }

        $planAction->load([
            'recommandation.its:id,name,telephone',
            'pointFocal:id,name,telephone',
            'responsable:id,name,telephone'
        ]);

        return view('inspecteur_general.plan_actions.show', compact('planAction'));
    }

    /**
     * Valider ou rejeter un plan d'action
     */
    public function validatePlan(Request $request, PlanAction $planAction)
    {
        $request->validate([
            'action' => 'required|in:valider,rejeter',
            'commentaire' => 'nullable|string|max:1000',
            'motif' => 'required_if:action,rejeter|string|max:1000'
        ]);

        // Vérifier que le plan peut être traité
        if ($planAction->statut_validation !== 'en_attente_ig') {
            return back()->with('error', 'Ce plan d\'action a déjà été traité.');
        }

        if ($request->action === 'valider') {
            // VALIDATION DU PLAN
            $planAction->update([
                'statut_validation' => 'valide_ig',
                'validateur_ig_id' => Auth::id(),
                'date_validation_ig' => now(),
                'commentaire_validation_ig' => $request->commentaire
            ]);

            // Mettre à jour le statut de la recommandation
            $planAction->recommandation->update([
                'statut' => 'plan_valide_ig'
            ]);

            $message = 'Plan d\'action validé avec succès. Le Point Focal peut démarrer l\'exécution.';

        } else {
            // REJET DU PLAN
            $planAction->update([
                'statut_validation' => 'rejete',
                'validateur_ig_id' => Auth::id(),
                'date_validation_ig' => now(),
                'motif_rejet_ig' => $request->motif
            ]);

            // Mettre à jour le statut de la recommandation
            $planAction->recommandation->update([
                'statut' => 'plan_rejete_ig'
            ]);

            $message = 'Plan d\'action rejeté. Le Point Focal a été notifié pour correction.';
        }

        // TODO: Envoyer notifications

        return redirect()->route('inspecteur_general.plan_actions.index')
            ->with('success', $message);
    }
}

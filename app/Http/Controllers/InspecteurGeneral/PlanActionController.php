<?php

namespace App\Http\Controllers\InspecteurGeneral;

use App\Http\Controllers\Controller;
use App\Models\PlanAction;
use App\Models\Recommandation;
use App\Models\Structure;
use Auth;
use Illuminate\Http\Request;

class PlanActionController extends Controller
{
    /**
     * Liste des plans d'action en attente de validation IG
     */
    public function index(Request $request)
    {
        // Inclure les plans dont la recommandation est soumise à l'IG ou validée par le responsable
        $query = PlanAction::whereHas('recommandation', function($q) {
                $q->whereIn('statut', ['plan_soumis_ig', 'plan_valide_responsable']);
            })
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

        // Charger les structures qui ont des recommandations en statut 'plan_soumis_ig'
        $structures = Structure::whereHas('recommandations', function($q) {
                $q->where('statut', 'plan_soumis_ig');
            })
            ->with(['recommandations' => function($q) {
                $q->where('statut', 'plan_soumis_ig')
                    ->with(['pointFocal:id,name,telephone', 'responsable:id,name'])
                    ->withCount('plansAction');
            }])
            ->get();

        return view('inspecteur_general.plan_actions.index', compact('planActions', 'structures'));
    }

    /**
     * Détails d'un plan d'action
     */
    public function show(PlanAction $planAction)
    {
        if (!in_array(optional($planAction->recommandation)->statut, ['plan_soumis_ig', 'plan_valide_ig', 'plan_rejete_ig'])) {
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

        // Vérifier que la recommandation est en attente IG
        if ($planAction->recommandation->statut !== 'plan_soumis_ig') {
            return back()->with('error', 'Ce plan d\'action a déjà été traité ou n\'est pas en attente IG.');
        }

        if ($request->action === 'valider') {
            // VALIDATION DU PLAN
            $planAction->update([
                'validateur_ig_id' => Auth::id(),
                'date_validation_ig' => now(),
                'commentaire_validation_ig' => $request->commentaire
            ]);

            // Mettre à jour le statut de la recommandation
            $planAction->recommandation->update([
                'statut' => Recommandation::STATUT_PLAN_VALIDE_IG
            ]);

            $message = 'Plan d\'action validé avec succès. Le Point Focal peut démarrer l\'exécution.';

            // Notifier le Point Focal de la validation IG
            try {
                if ($planAction->pointFocal) {
                    $planAction->pointFocal->notify(new \App\Notifications\PlanActionReviewedByIG($planAction, true, $request->commentaire ?? null));
                }
            } catch (\Throwable $e) {
                logger()->warning('Notification PlanActionReviewedByIG (approved) failed: ' . $e->getMessage());
            }
        } else {
            // REJET DU PLAN
            $planAction->update([
                'validateur_ig_id' => Auth::id(),
                'date_validation_ig' => now(),
                'motif_rejet_ig' => $request->motif
            ]);

            // Mettre à jour le statut de la recommandation
            $planAction->recommandation->update([
                'statut' => Recommandation::STATUT_PLAN_REJETE_IG
            ]);

            $message = 'Plan d\'action rejeté. Le Point Focal a été notifié pour correction.';

            // Notifier le Point Focal du rejet IG
            try {
                if ($planAction->pointFocal) {
                    $planAction->pointFocal->notify(new \App\Notifications\PlanActionReviewedByIG($planAction, false, $request->motif));
                }
            } catch (\Throwable $e) {
                logger()->warning('Notification PlanActionReviewedByIG (rejected) failed: ' . $e->getMessage());
            }
        }

        // TODO: Envoyer notifications

        return redirect()->route('inspecteur_general.plan_actions.index')
            ->with('success', $message);
    }
}

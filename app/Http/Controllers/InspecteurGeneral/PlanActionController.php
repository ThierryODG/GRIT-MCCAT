<?php

namespace App\Http\Controllers\InspecteurGeneral;

use App\Http\Controllers\Controller;
use App\Models\PlanAction;
use App\Models\Recommandation;
use App\Models\Structure;
use Auth;
use Illuminate\Http\Request;
use App\Notifications\RecommandationValidee;
use App\Notifications\RecommandationRejetee;
use App\Models\User;
use Illuminate\Support\Facades\Notification;

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

        // Charger les structures qui ont des recommandations en statut 'plan_soumis_ig' ou 'plan_valide_responsable'
        $structures = Structure::whereHas('recommandations', function($q) {
                $q->whereIn('statut', ['plan_soumis_ig', 'plan_valide_responsable']);
            })
            ->with(['recommandations' => function($q) {
                $q->whereIn('statut', ['plan_soumis_ig', 'plan_valide_responsable'])
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
        if (!in_array(optional($planAction->recommandation)->statut, ['plan_soumis_ig', 'plan_valide_responsable', 'plan_valide_ig', 'plan_rejete_ig'])) {
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
        if (!in_array($planAction->recommandation->statut, ['plan_soumis_ig', 'plan_valide_responsable'])) {
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

            // Initialiser intelligemment les échéances des actions
            try {
                \App\Services\ExecutionSchedulingService::initializeSchedules($planAction->recommandation);
            } catch (\Throwable $e) {
                logger()->error('Scheduling initialization failed in validatePlan: ' . $e->getMessage());
            }

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

    /**
     * Affiche le dossier complet d'une recommandation pour validation par l'IG
     */
    public function dossier(Recommandation $recommandation)
    {
        // Autoriser seulement si en attente de validation IG
        if (!in_array($recommandation->statut, ['plan_soumis_ig', 'plan_valide_responsable'])) {
            return redirect()->route('inspecteur_general.plan_actions.index')
                ->with('error', 'Cette recommandation n\'est pas en attente de validation de plans.');
        }

        $recommandation->load([
            'structure',
            'its',
            'pointFocal',
            'responsable',
            'plansAction' => function ($q) {
                $q->whereNotNull('action');
            }
        ]);

        return view('inspecteur_general.plan_actions.dossier', compact('recommandation'));
    }

    /**
     * Valider tous les plans d'une recommandation (depuis le dossier)
     */
    public function validerDossier(Request $request, Recommandation $recommandation)
    {
        $request->validate([
            'commentaire' => 'nullable|string|max:1000'
        ]);

        if (!in_array($recommandation->statut, ['plan_soumis_ig', 'plan_valide_responsable'])) {
            abort(403);
        }

        // Valider tous les plans
        $recommandation->plansAction()
            ->whereNotNull('action')
            ->update([
                'validateur_ig_id' => Auth::id(),
                'date_validation_ig' => now(),
            ]);

        // Mettre à jour la recommandation
        $recommandation->update([
            'statut' => Recommandation::STATUT_PLAN_VALIDE_IG,
            'commentaire_ig' => $request->commentaire,
            'inspecteur_general_id' => Auth::id(),
            'date_validation_ig' => now(),
        ]);

        // Initialiser intelligemment les échéances des actions
        try {
            \App\Services\ExecutionSchedulingService::initializeSchedules($recommandation);
        } catch (\Throwable $e) {
            logger()->error('Scheduling initialization failed: ' . $e->getMessage());
        }

        // Notifier le responsable
        $responsables = User::where('structure_id', $recommandation->structure_id)
                            ->whereHas('role', function($q){
                                $q->where('nom', 'responsable');
                            })->get();
        
        if($responsables->count() > 0){
             Notification::send($responsables, new RecommandationValidee($recommandation));
        }

        return redirect()->route('inspecteur_general.plan_actions.index')
            ->with('success', 'Plan d\'action validé avec succès. L\'exécution peut commencer.');
    }

    /**
     * Rejeter les plans d'une recommandation (depuis le dossier)
     */
    public function rejeterDossier(Request $request, Recommandation $recommandation)
    {
        $request->validate([
            'motif' => 'required|string|max:1000'
        ]);

        if (!in_array($recommandation->statut, ['plan_soumis_ig', 'plan_valide_responsable'])) {
            abort(403);
        }

        // Rejeter les plans (marqueur pour l'IG)
        $recommandation->plansAction()
            ->whereNotNull('action')
            ->update([
                'validateur_ig_id' => Auth::id(),
                'date_validation_ig' => now(),
            ]);

        $recommandation->update([
            'statut' => Recommandation::STATUT_PLAN_REJETE_IG,
            'motif_rejet_ig' => $request->motif,
            'inspecteur_general_id' => Auth::id(),
            'date_validation_ig' => now(),
        ]);

        // Notifier le responsable
        $responsables = User::where('structure_id', $recommandation->structure_id)
                            ->whereHas('role', function($q){
                                $q->where('nom', 'responsable');
                            })->get();

        if($responsables->count() > 0){
            Notification::send($responsables, new RecommandationRejetee($recommandation, $request->motif, 'plan_validation_ig'));
        }

        return redirect()->route('inspecteur_general.plan_actions.index')
            ->with('success', 'Plan d\'action rejeté. Le responsable a été notifié pour correction.');
    }
}

<?php

namespace App\Http\Controllers\Responsable;

use App\Models\PlanAction;
use App\Models\Recommandation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Notifications\RecommandationSoumise;
use Illuminate\Support\Facades\Notification;

class ValidationPlanController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $structureId = $user->structure_id;

        if (!$structureId) {
            return view('responsable.validation_plans.index', [
                'pointFocaux' => collect()
            ])->with('warning', 'Vous n\'êtes pas associé à une structure.');
        }

        $statutsRecommandationsVisibles = [
            Recommandation::STATUT_PLAN_SOUMIS_RESPONSABLE,
            Recommandation::STATUT_PLAN_VALIDE_RESPONSABLE,
            Recommandation::STATUT_PLAN_SOUMIS_IG,
            // permettre au responsable de voir les recommandations rejetées par l'IG
            Recommandation::STATUT_PLAN_REJETE_IG,
        ];

        $pointFocaux = \App\Models\User::where('structure_id', $structureId)
            ->whereHas('recommandationsAssignees', function ($q) use ($structureId, $statutsRecommandationsVisibles) {
                $q->where('structure_id', $structureId)
                  ->whereIn('statut', $statutsRecommandationsVisibles)
                  ->whereHas('plansAction', function ($qa) {
                      $qa->whereNotNull('action');
                  });
            })
            ->with([
                'recommandationsAssignees' => function ($q) use ($structureId, $statutsRecommandationsVisibles) {
                    $q->where('structure_id', $structureId)
                      ->whereIn('statut', $statutsRecommandationsVisibles)
                      ->whereHas('plansAction', function ($qa) {
                          $qa->whereNotNull('action');
                      })
                      ->with([
                          'structure',
                          'plansAction' => function ($qa) {
                              $qa->whereNotNull('action')
                                 ->orderBy('created_at', 'asc');
                          }
                      ])
                      ->orderBy('reference', 'asc');
                }
            ])
            ->orderBy('name', 'asc')
            ->get();

        return view('responsable.validation_plans.index', compact('pointFocaux'));
    }

    public function dossier(Recommandation $recommandation)
    {
        $user = Auth::user();
        $structureId = $user->structure_id;

        if ($recommandation->structure_id !== $structureId) {
            abort(403, 'Cette recommandation ne concerne pas votre structure.');
        }

        $statutsAccessibles = [
            Recommandation::STATUT_PLAN_SOUMIS_RESPONSABLE,
            Recommandation::STATUT_PLAN_VALIDE_RESPONSABLE,
            Recommandation::STATUT_PLAN_REJETE_RESPONSABLE,
            // Permettre également la consultation des recommandations rejetées par l'IG
            Recommandation::STATUT_PLAN_REJETE_IG,
            Recommandation::STATUT_PLAN_SOUMIS_IG,
        ];

        if (!in_array($recommandation->statut, $statutsAccessibles)) {
            abort(403, 'Cette recommandation n\'est pas accessible pour validation.');
        }

        $recommandation->load([
            'structure',
            'its',
            'pointFocal',
            'plansAction' => function ($q) {
                $q->whereNotNull('action')
                  ->orderBy('created_at', 'asc');
            }
        ]);

        return view('responsable.validation_plans.dossier', [
            'recommandation' => $recommandation,
            'peutValider' => $recommandation->peutEtreValideeMaintenant(),
            'peutRejeter' => $recommandation->peutEtreRejeteeMaintenant(),
            'estComplete' => $recommandation->estCompletePourValidation(),
            'estEnAttente' => $recommandation->estEnAttenteValidationResponsable(),
            'estValidee' => $recommandation->estValideeParResponsable(),
            'estRejetee' => $recommandation->estRejeteeParResponsable(),
            'estSoumiseIG' => $recommandation->estSoumiseALIG(),
        ]);
    }

    public function validerRecommandation(Request $request, Recommandation $recommandation)
    {
        $user = Auth::user();
        $structureId = $user->structure_id;

        $request->validate([
            'commentaire' => 'nullable|string|max:1000'
        ]);

        if ($recommandation->structure_id !== $structureId) {
            abort(403, 'Accès refusé.');
        }

        if (!$recommandation->peutEtreValideeMaintenant()) {
            return back()->with('error', 'Cette recommandation ne peut pas être validée.');
        }

        // Valider et transmettre AUTOMATIQUEMENT à l'IG
        $recommandation->update([
            'statut' => Recommandation::STATUT_PLAN_SOUMIS_IG,
            'date_validation_responsable' => now(),
            'commentaire_validation_responsable' => $request->commentaire ?? null,
            'motif_rejet_responsable' => null,
            'date_rejet_responsable' => null,
        ]);

        // Mettre à jour les plans
        $recommandation->plansAction()->update([
            'validateur_responsable_id' => $user->id,
            'date_validation_responsable' => now(),
            'commentaire_validation_responsable' => $request->commentaire ?? null,
            'motif_rejet_responsable' => null,
        ]);

        // Notifier le Point Focal
        try {
            if ($recommandation->pointFocal) {
                $recommandation->pointFocal->notify(new \App\Notifications\PlanningReviewed($recommandation, true, $request->commentaire ?? null));
            }
        } catch (\Throwable $e) {
            logger()->warning('Notification PlanningReviewed (approved) failed: ' . $e->getMessage());
        }

        // Notifier les Inspecteurs Généraux
        $igs = User::whereHas('role', function($q) {
            $q->where('nom', 'inspecteur_general');
        })->get();
        if($igs->count() > 0){
            Notification::send($igs, new RecommandationSoumise($recommandation));
        }

        return redirect()->route('responsable.validation_plans.index')
            ->with('success', 'Recommandation validée et transmise à l\'Inspecteur Général.');
    }

    public function rejeterRecommandation(Request $request, Recommandation $recommandation)
    {
        $user = Auth::user();
        $structureId = $user->structure_id;

        $request->validate([
            'motif' => 'required|string|max:1000'
        ]);

        if ($recommandation->structure_id !== $structureId) {
            abort(403, 'Accès refusé.');
        }

        if (!$recommandation->peutEtreRejeteeMaintenant()) {
            return back()->with('error', 'Cette recommandation ne peut pas être rejetée.');
        }

        // CORRECTION ICI : Utiliser le bon statut et bien enregistrer les données
        $recommandation->update([
            'statut' => Recommandation::STATUT_PLAN_REJETE_RESPONSABLE,
            'motif_rejet_responsable' => $request->motif,
            'date_rejet_responsable' => now(),
            'commentaire_validation_responsable' => null,
            'date_validation_responsable' => null,
        ]);

        // Mettre à jour les plans
        $recommandation->plansAction()->update([
            'validateur_responsable_id' => $user->id,
            'date_validation_responsable' => now(),
            'motif_rejet_responsable' => $request->motif,
            'commentaire_validation_responsable' => null,
        ]);

        // Notifier le Point Focal
        try {
            if ($recommandation->pointFocal) {
                $recommandation->pointFocal->notify(new \App\Notifications\PlanningReviewed($recommandation, false, $request->motif));
            }
        } catch (\Throwable $e) {
            logger()->warning('Notification PlanningReviewed (rejected) failed: ' . $e->getMessage());
        }

        return redirect()->route('responsable.validation_plans.index')
            ->with('success', 'Recommandation rejetée. Le Point Focal a été notifié pour correction.');
    }

    /**
     * Transmettre au Point Focal les corrections demandées par l'IG.
     * Change le statut de la recommandation en 'plan_en_redaction' pour que le PF puisse la modifier.
     */
    public function transmettreAuPointFocal(Request $request, Recommandation $recommandation)
    {
        $user = Auth::user();
        $structureId = $user->structure_id;

        if ($recommandation->structure_id !== $structureId) {
            abort(403, 'Accès refusé.');
        }

        // Ne peut transmettre que si la recommandation a été rejetée par l'IG
        if ($recommandation->statut !== Recommandation::STATUT_PLAN_REJETE_IG) {
            return back()->with('error', 'Cette recommandation n\'est pas dans un état permettant de transmettre les corrections au point focal.');
        }

        // Mettre la recommandation en rédaction pour le point focal.
        $recommandation->update([
            'statut' => Recommandation::STATUT_PLAN_EN_REDACTION,
            // garder `motif_rejet_ig` pour que le PF voie ce qui doit être corrigé;
            // le motif sera effacé lors de la nouvelle soumission du PF (logique PF->soumettre)
        ]);

        // Notifier le Point Focal
        try {
            if ($recommandation->pointFocal) {
                $recommandation->pointFocal->notify(new \App\Notifications\PlanningReviewed($recommandation, false, $recommandation->motif_rejet_ig ?? 'Corrections requises'));
            }
        } catch (\Throwable $e) {
            logger()->warning('Notification PlanningReviewed (transmit to PF) failed: ' . $e->getMessage());
        }

        return redirect()->route('responsable.validation_plans.index')
            ->with('success', 'Recommandation transmise au Point Focal pour correction.');
    }

    public function show(PlanAction $planAction)
    {
        if ($planAction->recommandation->structure_id !== Auth::user()->structure_id) {
            abort(403);
        }

        $planAction->load([
            'recommandation.its:id,name',
            'pointFocal:id,name,telephone'
        ]);

        return view('responsable.validation_plans.show', compact('planAction'));
    }

    public function valider(Request $request, PlanAction $planAction)
    {
        $request->validate([
            'commentaire' => 'nullable|string|max:1000'
        ]);

        if ($planAction->recommandation->structure_id !== Auth::user()->structure_id) {
            abort(403);
        }

        $recommandation = $planAction->recommandation;

        if ($recommandation->statut !== Recommandation::STATUT_PLAN_SOUMIS_RESPONSABLE) {
            return back()->with('error', 'Ce plan ne peut pas être validé (statut de la recommandation incorrect).');
        }

        $recommandation->update([
            'statut' => Recommandation::STATUT_PLAN_SOUMIS_IG,
            'date_validation_responsable' => now(),
            'commentaire_validation_responsable' => $request->commentaire ?? null,
        ]);

        $planAction->update([
            'validateur_responsable_id' => Auth::id(),
            'date_validation_responsable' => now(),
            'commentaire_validation_responsable' => $request->commentaire ?? null
        ]);

        // Notifier les Inspecteurs Généraux
        $igs = User::whereHas('role', function($q) {
            $q->where('nom', 'inspecteur_general');
        })->get();
        if($igs->count() > 0){
            Notification::send($igs, new RecommandationSoumise($recommandation));
        }

        return redirect()->route('responsable.validation_plans.index')
            ->with('success', 'Plan d\'action validé (recommandation transmise à l\'IG).');
    }

    public function rejeter(Request $request, PlanAction $planAction)
    {
        $request->validate([
            'motif' => 'required|string|max:1000'
        ]);

        if ($planAction->recommandation->structure_id !== Auth::user()->structure_id) {
            abort(403);
        }

        $recommandation = $planAction->recommandation;

        if ($recommandation->statut !== Recommandation::STATUT_PLAN_SOUMIS_RESPONSABLE) {
            return back()->with('error', 'Ce plan ne peut pas être rejeté (statut de la recommandation incorrect).');
        }

        $recommandation->update([
            'statut' => Recommandation::STATUT_PLAN_REJETE_RESPONSABLE,
            'motif_rejet_responsable' => $request->motif,
            'date_rejet_responsable' => now(),
        ]);

        $planAction->update([
            'validateur_responsable_id' => Auth::id(),
            'date_validation_responsable' => now(),
            'motif_rejet_responsable' => $request->motif
        ]);

        return redirect()->route('responsable.validation_plans.index')
            ->with('success', 'Plan d\'action rejeté (recommandation retournée au point focal).');
    }

    public function transmettre(PlanAction $planAction)
    {
        if ($planAction->recommandation->structure_id !== Auth::user()->structure_id) {
            abort(403);
        }

        $recommandation = $planAction->recommandation;

        if ($recommandation->statut !== Recommandation::STATUT_PLAN_VALIDE_RESPONSABLE) {
            return back()->with('error', 'Cette recommandation doit être validée avant transmission à l\'IG.');
        }

        $recommandation->update([
            'statut' => Recommandation::STATUT_PLAN_SOUMIS_IG
        ]);

        // Notifier les Inspecteurs Généraux
        $igs = User::whereHas('role', function($q) {
            $q->where('nom', 'inspecteur_general');
        })->get();
        if($igs->count() > 0){
            Notification::send($igs, new RecommandationSoumise($recommandation));
        }

        return back()->with('success', 'Recommandation transmise à l\'Inspecteur Général.');
    }
}

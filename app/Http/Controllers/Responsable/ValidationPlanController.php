<?php

namespace App\Http\Controllers\Responsable;

use App\Models\PlanAction;
use App\Models\Recommandation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ValidationPlanController extends Controller
{
    /**
    <?php

    namespace App\Http\Controllers\Responsable;

    use App\Models\PlanAction;
    use App\Models\Recommandation;
    use Illuminate\Http\Request;
    use App\Http\Controllers\Controller;
    use Illuminate\Support\Facades\Auth;

    class ValidationPlanController extends Controller
    {
        /**
         * Liste des recommandations en attente de validation, groupées par Point Focal
         */
        public function index()
        {
            $userId = Auth::id();

            // Critères : on veut les recommandations qui contiennent au moins
            // un plan d'action assigné au responsable connecté et nécessitant
            // son attention (statuts de validation pertinents).
            $pendingPlanStatuses = ['en_attente_responsable', 'rejete_responsable', 'rejete_ig'];

            // Afficher uniquement les recommandations complètes :
            // plans d'action remplis + champs de planification renseignés.
            $pointFocaux = \App\Models\User::whereHas('recommandationsAssignees', function ($q) use ($userId, $pendingPlanStatuses) {
                $q->whereHas('plansAction', function ($qa) use ($userId, $pendingPlanStatuses) {
                    $qa->where(function ($q2) use ($userId) {
                            $q2->whereNull('responsable_id')
                               ->orWhere('responsable_id', $userId);
                        })
                       ->whereIn('statut_validation', $pendingPlanStatuses)
                       ->whereNotNull('action');
                });
            })->with([
                'recommandationsAssignees' => function ($q) use ($userId, $pendingPlanStatuses) {
                    $q->whereNotNull('indicateurs')
                      ->whereNotNull('incidence_financiere')
                      ->whereNotNull('delai_mois')
                      ->whereNotNull('date_debut_prevue')
                      ->whereNotNull('date_fin_prevue')
                      ->whereHas('plansAction', function ($qa) use ($userId, $pendingPlanStatuses) {
                          $qa->where(function ($q2) use ($userId) {
                                  $q2->whereNull('responsable_id')
                                     ->orWhere('responsable_id', $userId);
                              })
                             ->whereIn('statut_validation', $pendingPlanStatuses)
                             ->whereNotNull('action');
                      })
                      ->with([
                          'structure',
                          'plansAction' => function ($qa) use ($userId, $pendingPlanStatuses) {
                              $qa->where(function ($q2) use ($userId) {
                                  $q2->whereNull('responsable_id')
                                     ->orWhere('responsable_id', $userId);
                              })
                             ->whereIn('statut_validation', $pendingPlanStatuses)
                             ->whereNotNull('action');
                          }
                      ])
                      ->orderBy('reference', 'asc');
                }
            ])->orderBy('name', 'asc')
            ->get();

            return view('responsable.validation_plans.index', compact('pointFocaux'));
        }

        /**
         * Affiche le dossier complet (tous les plans d'une recommandation)
         */
        public function dossier(Recommandation $recommandation)
        {
            $userId = Auth::id();

            // Vérifier que le responsable a accès à cette recommandation
            // (au moins UN plan d'action est soit non assigné, soit lui appartient)
            if (!$recommandation->plansAction()->where(function ($q) use ($userId) {
                $q->whereNull('responsable_id')
                  ->orWhere('responsable_id', $userId);
            })->exists()) {
                abort(403, 'Vous n\'avez pas accès à cette recommandation.');
            }

            // La recommandation doit être dans des statuts consultables
            if (!in_array($recommandation->statut, ['plan_soumis_responsable', 'plan_en_redaction', 'plan_valide_responsable'])) {
                abort(403, 'Cette recommandation n\'est pas en statut consultable.');
            }

            $recommandation->load([
                'structure',
                'its',
                'pointFocal',
                'plansAction' => function ($q) use ($userId) {
                    $q->where(function ($q2) use ($userId) {
                        $q2->whereNull('responsable_id')
                           ->orWhere('responsable_id', $userId);
                    });
                }
            ]);

            return view('responsable.validation_plans.dossier', compact('recommandation'));
        }

        /**
         * Valider tous les plans d'une recommandation
         */
        public function validerRecommandation(Request $request, Recommandation $recommandation)
        {
            $request->validate([
                'commentaire' => 'nullable|string|max:1000'
            ]);

            $userId = Auth::id();

            // Vérifier l'accès : accepter les plans non assignés ou assignés à l'utilisateur
            if (!$recommandation->plansAction()->where(function ($q) use ($userId) {
                $q->whereNull('responsable_id')
                  ->orWhere('responsable_id', $userId);
            })->where('statut_validation', 'en_attente_responsable')->exists()) {
                abort(403);
            }

            // Valider tous les plans (non assignés ou assignés à ce responsable)
            $recommandation->plansAction()
                ->where(function ($q) use ($userId) {
                    $q->whereNull('responsable_id')
                      ->orWhere('responsable_id', $userId);
                })
                ->where('statut_validation', 'en_attente_responsable')
                ->update([
                    'statut_validation' => 'valide_responsable',
                    'validateur_responsable_id' => $userId,
                    'date_validation_responsable' => now(),
                    'commentaire_validation_responsable' => $request->commentaire,
                    'responsable_id' => $userId //prendre en charge le plan si non assigné
                ]);

            // Mettre à jour la recommandation
            $recommandation->update([
                'statut' => 'plan_valide_responsable'
            ]);

            return redirect()->route('responsable.validation_plans.index')
                ->with('success', 'Recommandation validée. Elle sera transmise à l\'IG.');
        }

        /**
         * Rejeter les plans d'une recommandation
         */
        public function rejeterRecommandation(Request $request, Recommandation $recommandation)
        {
            $request->validate([
                'motif' => 'required|string|max:1000'
            ]);

            $userId = Auth::id();

            if (!$recommandation->plansAction()->where(function ($q) use ($userId) {
                $q->whereNull('responsable_id')
                  ->orWhere('responsable_id', $userId);
            })->where('statut_validation', 'en_attente_responsable')->exists()) {
                abort(403);
            }

            // Stocker le rejet au niveau RECOMMANDATION (pas au niveau plan)
            // Les plans gardent leur statut en_attente_responsable
            // Point Focal peut modifier la recommandation sans être bloqué par un statut "rejeté" sur chaque plan
            $recommandation->update([
                'statut' => 'plan_en_redaction',
                'motif_rejet_responsable' => $request->motif,
                'date_rejet_responsable' => now()
            ]);

            return redirect()->route('responsable.validation_plans.index')
                ->with('success', 'Recommandation rejetée. Le Point Focal a été notifié pour correction.');
        }

        /**
         * Transmettre une recommandation validée à l'IG
         */
        public function transmettreIG(Request $request, Recommandation $recommandation)
        {
            $userId = Auth::id();

            if (!$recommandation->plansAction()->where(function ($q) use ($userId) {
                $q->whereNull('responsable_id')
                  ->orWhere('responsable_id', $userId);
            })->where('statut_validation', 'valide_responsable')->exists()) {
                abort(403);
            }

            // Mettre à jour la recommandation
            $recommandation->update([
                'statut' => 'plan_soumis_ig'
            ]);

            // Mettre à jour les plans (prendre en charge si nécessaire)
            $recommandation->plansAction()
                ->where(function ($q) use ($userId) {
                    $q->whereNull('responsable_id')
                      ->orWhere('responsable_id', $userId);
                })
                ->where('statut_validation', 'valide_responsable')
                ->update([
                    'statut_validation' => 'en_attente_ig',
                    'responsable_id' => $userId
                ]);

            return redirect()->route('responsable.validation_plans.index')
                ->with('success', 'Recommandation transmise à l\'Inspecteur Général.');
        }

        /**
         * Détails d'un plan d'action (ancien - conservé pour compatibilité)
         */
        public function show(PlanAction $planAction)
        {
            if ($planAction->responsable_id !== Auth::id()) {
                abort(403);
            }

            $planAction->load([
                'recommandation.its:id,name,direction',
                'pointFocal:id,name,telephone'
            ]);

            return view('responsable.validation_plans.show', compact('planAction'));
        }

        /**
         * Valider un plan d'action (ancien - conservé pour compatibilité)
         */
        public function valider(Request $request, PlanAction $planAction)
        {
            $request->validate([
                'commentaire' => 'nullable|string|max:1000'
            ]);

            // Vérifications
            if ($planAction->responsable_id !== Auth::id()) {
                abort(403);
            }

            if ($planAction->statut_validation !== 'en_attente_responsable') {
                return back()->with('error', 'Ce plan a déjà été traité.');
            }

            // Valider
            $planAction->update([
                'statut_validation' => 'valide_responsable',
                'validateur_responsable_id' => Auth::id(),
                'date_validation_responsable' => now(),
                'commentaire_validation_responsable' => $request->commentaire
            ]);

            // Mettre à jour la recommandation
            $planAction->recommandation->update([
                'statut' => 'plan_valide_responsable'
            ]);

            return redirect()->route('responsable.validation_plans.index')
                ->with('success', 'Plan d\'action validé.');
        }

        /**
         * Rejeter un plan d'action (ancien - conservé pour compatibilité)
         */
        public function rejeter(Request $request, PlanAction $planAction)
        {
            $request->validate([
                'motif' => 'required|string|max:1000'
            ]);

            if ($planAction->responsable_id !== Auth::id()) {
                abort(403);
            }

            if ($planAction->statut_validation !== 'en_attente_responsable') {
                return back()->with('error', 'Ce plan a déjà été traité.');
            }

            // Rejeter
            $planAction->update([
                'statut_validation' => 'rejete_responsable',
                'validateur_responsable_id' => Auth::id(),
                'date_validation_responsable' => now(),
                'motif_rejet_responsable' => $request->motif
            ]);

            // Retour au Point Focal
            $planAction->recommandation->update([
                'statut' => 'plan_en_redaction'
            ]);

            return redirect()->route('responsable.validation_plans.index')
                ->with('success', 'Plan d\'action rejeté.');
        }

        /**
         * Transmettre un plan validé à l'IG (ancien - conservé pour compatibilité)
         */
        public function transmettre(PlanAction $planAction)
        {
            if ($planAction->responsable_id !== Auth::id()) {
                abort(403);
            }

            if ($planAction->statut_validation !== 'valide_responsable') {
                return back()->with('error', 'Ce plan doit d\'abord être validé.');
            }

            // Transmettre à l'IG
            $planAction->update([
                'statut_validation' => 'en_attente_ig'
            ]);

            $planAction->recommandation->update([
                'statut' => 'plan_soumis_ig'
            ]);

            // TODO: Notifier l'IG

            return back()->with('success', 'Plan d\'action transmis à l\'Inspecteur Général.');
        }
    }

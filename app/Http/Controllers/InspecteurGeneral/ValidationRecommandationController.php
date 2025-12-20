<?php

namespace App\Http\Controllers\InspecteurGeneral;

use App\Models\Recommandation;
use App\Models\Structure;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ValidationRecommandationController extends Controller
{
    /**
     * Liste les recommandations en attente de validation par l'IG, groupées par structure
     */
    public function index()
    {
        // Récupérer toutes les structures ayant des recommandations soumises à l'IG
        $structures = Structure::whereHas('recommandations', function ($q) {
            $q->where('statut', 'plan_soumis_ig')
              ->whereHas('plansAction', function ($qa) {
                  $qa->whereNotNull('action');
              });
        })
        ->with([
            'recommandations' => function ($q) {
                $q->where('statut', 'plan_soumis_ig')
                  ->with([
                      'its',
                      'pointFocal',
                      'responsable',
                      'plansAction' => function ($qa) {
                          $qa->whereNotNull('action');
                      }
                  ])
                  ->orderBy('reference', 'asc');
            }
        ])
        ->orderBy('nom', 'asc')
        ->get();

        return view('inspecteur_general.validation_recommandations.index', compact('structures'));
    }

    /**
     * Affiche le dossier complet d'une recommandation pour validation par l'IG
     */
    public function dossier(Recommandation $recommandation)
    {
        // Vérifier que la recommandation est en attente de validation IG
        if ($recommandation->statut !== 'plan_soumis_ig') {
            abort(403, 'Cette recommandation n\'est pas en attente de validation.');
        }

            if (!$recommandation->plansAction()->whereNotNull('action')->exists()) {
                abort(403, 'Aucun plan d\'action en attente de validation.');
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

        return view('inspecteur_general.validation_recommandations.dossier', compact('recommandation'));
    }

    /**
     * Valider tous les plans d'une recommandation (par l'IG)
     */
    public function valider(Request $request, Recommandation $recommandation)
    {
        $request->validate([
            'commentaire' => 'nullable|string|max:1000'
        ]);

        // Vérifications
        if ($recommandation->statut !== 'plan_soumis_ig') {
            abort(403);
        }

            if (!$recommandation->plansAction()->whereNotNull('action')->exists()) {
                abort(403);
            }

        // Valider tous les plans (mise à jour d'état uniquement)
            $recommandation->plansAction()
                ->whereNotNull('action')
            ->update([
                'statut_execution' => 'non_demarre',
                'validateur_ig_id' => Auth::id(),
                'date_validation_ig' => now(),
            ]);

        // Mettre à jour la recommandation (stocker le commentaire de l'IG au niveau de la recommandation)
        $recommandation->update([
                'statut' => Recommandation::STATUT_PLAN_VALIDE_IG,
                'commentaire_validation_ig' => $request->commentaire,
                'validateur_ig_id' => Auth::id(),
                'date_validation_ig' => now(),
        ]);

        return redirect()->route('inspecteur_general.validation_recommandations.index')
            ->with('success', 'Recommandation validée. L\'exécution peut commencer.');
    }

    /**
     * Rejeter les plans d'une recommandation (par l'IG)
     */
    public function rejeter(Request $request, Recommandation $recommandation)
    {
        $request->validate([
            'motif' => 'required|string|max:1000'
        ]);

        if ($recommandation->statut !== 'plan_soumis_ig') {
            abort(403);
        }

            if (!$recommandation->plansAction()->whereNotNull('action')->exists()) {
                abort(403);
            }

        // Rejeter tous les plans (on marque le validateur et la date, le motif est stocké sur la recommandation)
            $recommandation->plansAction()
                ->whereNotNull('action')
            ->update([
                'validateur_ig_id' => Auth::id(),
                'date_validation_ig' => now(),
            ]);

        // Retour au responsable pour correction et stocker le motif de rejet sur la recommandation
        $recommandation->update([
                'statut' => Recommandation::STATUT_PLAN_REJETE_IG,
                'motif_rejet_ig' => $request->motif,
                'validateur_ig_id' => Auth::id(),
                'date_validation_ig' => now(),
        ]);

        return redirect()->route('inspecteur_general.validation_recommandations.index')
            ->with('success', 'Recommandation rejetée. Le responsable a été notifié pour correction.');
    }
}

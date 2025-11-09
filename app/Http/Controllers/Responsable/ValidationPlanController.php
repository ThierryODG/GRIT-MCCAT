<?php

namespace App\Http\Controllers\Responsable;

use App\Models\PlanAction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ValidationPlanController extends Controller
{
    /**
     * Liste des plans d'action à valider
     */
    public function index()
    {
        $plansActions = PlanAction::where('responsable_id', Auth::id())
            ->where('statut_validation', 'en_attente_responsable')
            ->whereNotNull('action') // Plans remplis par le Point Focal
            ->with([
                'recommandation.its:id,name',
                'pointFocal:id,name,telephone'
            ])
            ->orderBy('created_at', 'asc')
            ->paginate(15);

        return view('responsable.validation_plans.index', compact('plansActions'));
    }

    /**
     * Détails d'un plan d'action
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
     * Valider un plan d'action
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

        // TODO: Notifier le Point Focal

        return redirect()->route('responsable.validation_plans.index')
            ->with('success', 'Plan d\'action validé. Il sera transmis à l\'IG.');
    }

    /**
     * Rejeter un plan d'action
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

        // Mettre à jour la recommandation
        $planAction->recommandation->update([
            'statut' => 'plan_en_redaction' // Retour au Point Focal
        ]);

        // TODO: Notifier le Point Focal

        return redirect()->route('responsable.validation_plans.index')
            ->with('success', 'Plan d\'action rejeté. Le Point Focal a été notifié pour correction.');
    }

    /**
     * Transmettre un plan validé à l'IG
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

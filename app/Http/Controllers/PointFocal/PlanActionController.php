<?php

namespace App\Http\Controllers\PointFocal;

use App\Models\Recommandation;
use App\Models\PlanAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlanActionController extends Controller
{
    /**
     * Liste des plans d'action du Point Focal
     */
    public function index(Request $request)
    {
        $query = PlanAction::where('point_focal_id', Auth::id());

        // Filtres
        if ($request->filled('statut_validation')) {
            $query->where('statut_validation', $request->statut_validation);
        }

        if ($request->filled('statut_execution')) {
            $query->where('statut_execution', $request->statut_execution);
        }

        $plansAction = $query->with(['recommandation.structure'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $statutsValidation = [
            'en_attente_responsable' => 'En attente responsable',
            'valide_responsable' => 'Validé responsable',
            'rejete_responsable' => 'Rejeté responsable',
            'en_attente_ig' => 'En attente IG',
            'valide_ig' => 'Validé IG',
            'rejete_ig' => 'Rejeté IG'
        ];

        $statutsExecution = [
            'non_demarre' => 'Non démarré',
            'en_cours' => 'En cours',
            'termine' => 'Terminé'
        ];

        return view('point_focal.plansaction.index', compact(
            'plansAction',
            'statutsValidation',
            'statutsExecution'
        ));
    }

    /**
     * Formulaire de création d'un plan d'action
     */
    public function create(Recommandation $recommandation)
    {
        // Vérifications de sécurité
        if ($recommandation->point_focal_id !== Auth::id()) {
            abort(403, 'Action non autorisée.');
        }

        if (!in_array($recommandation->statut, ['point_focal_assigne', 'plan_en_redaction'])) {
            abort(403, 'Cette recommandation ne peut pas recevoir de plan d\'action.');
        }

        return view('point_focal.plans_action.create', compact('recommandation'));
    }

    /**
     * Sauvegarde d'un nouveau plan d'action
     */
    public function store(Request $request, Recommandation $recommandation)
    {
        // Vérifications de sécurité
        if ($recommandation->point_focal_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'action' => 'required|string|max:1000',
        ]);

        // Création du plan d'action (seulement action + workflow/execution)
        $planAction = new PlanAction([
            'action' => $validated['action'],
        ]);
        $planAction->recommandation_id = $recommandation->id;
        $planAction->point_focal_id = Auth::id();
        $planAction->statut_validation = 'en_attente_responsable';
        $planAction->statut_execution = 'non_demarre';
        $planAction->pourcentage_avancement = 0;

        $planAction->save();

        // Mise à jour du statut de la recommandation
        if ($recommandation->statut === 'point_focal_assigne') {
            $recommandation->update(['statut' => 'plan_en_redaction']);
        }

        return redirect()->route('point_focal.recommandations.show', $recommandation)
            ->with('success', 'Plan d\'action créé avec succès.');
    }

    /**
     * Édition d'un plan d'action
     */
    public function edit(PlanAction $planAction)
    {
        // Vérifications de sécurité
        if ($planAction->point_focal_id !== Auth::id()) {
            abort(403, 'Action non autorisée.');
        }

        // On ne peut modifier que les plans en attente ou rejetés
        if (!in_array($planAction->statut_validation, ['en_attente_responsable', 'rejete_responsable', 'rejete_ig'])) {
            return redirect()->route('point_focal.recommandations.show', $planAction->recommandation_id)
                ->with('error', 'Ce plan d\'action ne peut plus être modifié.');
        }

        return view('point_focal.plans_action.edit', compact('planAction'));
    }

    /**
     * Mise à jour d'un plan d'action
     */
    public function update(Request $request, PlanAction $planAction)
    {
        // Vérifications de sécurité
        if ($planAction->point_focal_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'action' => 'required|string|max:1000',
        ]);

        // Réinitialiser le statut de validation si le plan était rejeté
        if (in_array($planAction->statut_validation, ['rejete_responsable', 'rejete_ig'])) {
            $validated['statut_validation'] = 'en_attente_responsable';
            $validated['motif_rejet_responsable'] = null;
            $validated['motif_rejet_ig'] = null;
        }

        $planAction->update(['action' => $validated['action']]);

        return redirect()->route('point_focal.recommandations.show', $planAction->recommandation_id)
            ->with('success', 'Plan d\'action modifié avec succès.');
    }

    /**
     * Suppression d'un plan d'action
     */
    public function destroy(PlanAction $planAction)
    {
        // Vérifications de sécurité
        if ($planAction->point_focal_id !== Auth::id()) {
            abort(403, 'Action non autorisée.');
        }

        // On ne peut supprimer que les plans en attente ou rejetés
        if (!in_array($planAction->statut_validation, ['en_attente_responsable', 'rejete_responsable', 'rejete_ig'])) {
            return redirect()->route('point_focal.recommandations.show', $planAction->recommandation_id)
                ->with('error', 'Ce plan d\'action ne peut pas être supprimé.');
        }

        $recommandation_id = $planAction->recommandation_id;
        $planAction->delete();

        return redirect()->route('point_focal.recommandations.show', $recommandation_id)
            ->with('success', 'Plan d\'action supprimé avec succès.');
    }
}

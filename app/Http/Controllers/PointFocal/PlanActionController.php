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
            // maintenant on filtre par statut de la recommandation
            $query->whereHas('recommandation', function($q) use ($request) {
                $q->where('statut', $request->statut_validation);
            });
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

        return view('point_focal.plans_action.index', compact(
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

        // Autoriser aussi la création si la recommandation a été rejetée par le responsable
        if (!in_array($recommandation->statut, ['point_focal_assigne', 'plan_en_redaction', 'plan_rejete_responsable'])) {
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
            'executant_type' => 'required|in:self,other',
            'executant_nom' => 'nullable|required_if:executant_type,other|string|max:255',
            'executant_role' => 'nullable|string|max:255',
        ]);

        // Création du plan d'action (seulement action + workflow/execution)
        $planAction = new PlanAction([
            'action' => $validated['action'],
            'executant_type' => $validated['executant_type'],
            'executant_nom' => $validated['executant_type'] === 'other' ? $validated['executant_nom'] : Auth::user()->name,
            'executant_role' => $validated['executant_role'],
        ]);
        $planAction->recommandation_id = $recommandation->id;
        $planAction->point_focal_id = Auth::id();
        $planAction->statut_execution = 'non_demarre';
        $planAction->pourcentage_avancement = 0;

        $planAction->save();

        // Mise à jour du statut de la recommandation
        if (in_array($recommandation->statut, ['point_focal_assigne', 'plan_rejete_responsable'])) {
            $recommandation->update([
                'statut' => 'plan_en_redaction',
                // Effacer le motif de rejet responsable si le PF crée une nouvelle action
                'motif_rejet_responsable' => null,
                'date_rejet_responsable' => null,
            ]);

            // Effacer les motifs IG au niveau des plans si présents
            $recommandation->plansAction()->update(['motif_rejet_ig' => null]);
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

        // Ne pas permettre l'édition si la recommandation a déjà été soumise
        if ($planAction->recommandation && $planAction->recommandation->statut === 'plan_soumis_responsable') {
            return redirect()->route('point_focal.recommandations.show', $planAction->recommandation_id)
                ->with('error', 'La planification a été soumise au responsable. Vous ne pouvez plus modifier ce plan tant que la décision n\'a pas été prise.');
        }

        // On ne peut modifier que si la recommandation est en rédaction ou rejetée
        $allowedRecStatuts = ['point_focal_assigne', 'plan_en_redaction', 'plan_rejete_responsable'];
        if (!in_array($planAction->recommandation->statut, $allowedRecStatuts)) {
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

        // Ne pas permettre la mise à jour si la recommandation a été soumise
        if ($planAction->recommandation && $planAction->recommandation->statut === 'plan_soumis_responsable') {
            return redirect()->route('point_focal.recommandations.show', $planAction->recommandation_id)
                ->with('error', 'La planification a été soumise au responsable. Vous ne pouvez plus modifier ce plan tant que la décision n\'a pas été prise.');
        }

        $validated = $request->validate([
            'action' => 'required|string|max:1000',
            'executant_type' => 'required|in:self,other',
            'executant_nom' => 'nullable|required_if:executant_type,other|string|max:255',
            'executant_role' => 'nullable|string|max:255',
        ]);

        // Si le plan avait un motif de rejet précédent, on le nettoie et on efface le motif global de la recommandation
        // Toujours appliquer la modification
        $planAction->update([
            'action' => $validated['action'],
            'executant_type' => $validated['executant_type'],
            'executant_nom' => $validated['executant_type'] === 'other' ? $validated['executant_nom'] : Auth::user()->name,
            'executant_role' => $validated['executant_role'],
        ]);

        // Quand le Point Focal modifie un plan (quel que soit le motif précédent),
        // on considère qu'il corrige la recommandation :
        // - repasser la recommandation en rédaction
        // - effacer les motifs de rejet responsables
        // - effacer les motifs IG sur les plans
        $reco = $planAction->recommandation;
        if ($reco) {
            $reco->update([
                'statut' => 'plan_en_redaction',
                'motif_rejet_responsable' => null,
                'date_rejet_responsable' => null,
            ]);

            // Clear motifs IG au niveau des plans
            $reco->plansAction()->update(['motif_rejet_ig' => null]);
        }

        // Clear motifs sur le plan courant aussi
        $planAction->motif_rejet_responsable = null;
        $planAction->motif_rejet_ig = null;
        $planAction->save();

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

        // Ne pas permettre la suppression si la recommandation a été soumise
        if ($planAction->recommandation && $planAction->recommandation->statut === 'plan_soumis_responsable') {
            return redirect()->route('point_focal.recommandations.show', $planAction->recommandation_id)
                ->with('error', 'La planification a été soumise au responsable. Vous ne pouvez plus supprimer ce plan tant que la décision n\'a pas été prise.');
        }
        // On ne peut supprimer que si la recommandation est en rédaction ou rejetée
        $allowedRecStatuts = ['point_focal_assigne', 'plan_en_redaction', 'plan_rejete_responsable'];
        if (!in_array($planAction->recommandation->statut, $allowedRecStatuts)) {
            return redirect()->route('point_focal.recommandations.show', $planAction->recommandation_id)
                ->with('error', 'Ce plan d\'action ne peut pas être supprimé.');
        }

        $recommandation_id = $planAction->recommandation_id;
        $planAction->delete();

        // Après suppression, considérer que le PF a modifié la planification :
        // repasser la recommandation en rédaction et effacer les motifs de rejet.
        $reco = \App\Models\Recommandation::find($recommandation_id);
        if ($reco) {
            $reco->update([
                'statut' => 'plan_en_redaction',
                'motif_rejet_responsable' => null,
                'date_rejet_responsable' => null,
            ]);

            $reco->plansAction()->update(['motif_rejet_ig' => null]);
        }

        return redirect()->route('point_focal.recommandations.show', $recommandation_id)
            ->with('success', 'Plan d\'action supprimé avec succès.');
    }
}

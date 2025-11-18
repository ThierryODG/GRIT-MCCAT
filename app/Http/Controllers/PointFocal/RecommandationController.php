<?php

namespace App\Http\Controllers\PointFocal;

use App\Models\Recommandation;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class RecommandationController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // Méthode 1: Utiliser la relation existante via les recommandations
        $recommandations = Recommandation::where('point_focal_id', $userId)
            ->with(['its', 'structure', 'plansAction'])
            ->get();

        // Grouper les recommandations par ITS
        $inspecteurs = $recommandations->groupBy('its_id')->map(function($recommandationsParIts) {
            $its = $recommandationsParIts->first()->its;
            $its->recommandations = $recommandationsParIts;
            return $its;
        });

        return view('point_focal.recommandations.index', compact('inspecteurs'));
    }

    public function show(Recommandation $recommandation)
    {
        // Vérifier que l'utilisateur est bien le point focal assigné
        if ($recommandation->point_focal_id !== Auth::id()) {
            abort(403, 'Accès non autorisé à cette recommandation.');
        }

        $recommandation->load(['structure', 'its', 'plansAction']);

        return view('point_focal.recommandations.show', compact('recommandation'));
    }

    // Nouveau méthode pour afficher le dossier complet d'un ITS
    public function dossierIts(User $its)
    {
        $userId = Auth::id();

        // Vérifier que l'utilisateur a des recommandations de cet ITS
        $recommandations = Recommandation::where('point_focal_id', $userId)
            ->where('its_id', $its->id)
            ->with(['structure', 'plansAction'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Si aucune recommandation, rediriger
        if ($recommandations->count() === 0) {
            return redirect()->route('point_focal.recommandations.index')
                ->with('error', 'Aucune recommandation trouvée pour cet inspecteur.');
        }

        return view('point_focal.recommandations.dossier-its', compact('its', 'recommandations'));
    }

    /**
     * Formulaire d'édition des informations de planification
     */
    public function edit(Recommandation $recommandation)
    {
        // Vérifier que l'utilisateur est bien le point focal assigné
        if ($recommandation->point_focal_id !== Auth::id()) {
            abort(403, 'Accès non autorisé à cette recommandation.');
        }

        // Vérifier que la recommandation est dans un statut éditable
        if (!in_array($recommandation->statut, ['point_focal_assigne', 'plan_en_redaction'])) {
            return redirect()->route('point_focal.recommandations.show', $recommandation)
                ->with('error', 'Cette recommandation ne peut plus être modifiée.');
        }

        return view('point_focal.recommandations.edit', compact('recommandation'));
    }

    /**
     * Mise à jour des informations de planification
     */
    public function update(Request $request, Recommandation $recommandation)
    {
        // Vérifier que l'utilisateur est bien le point focal assigné
        if ($recommandation->point_focal_id !== Auth::id()) {
            abort(403, 'Accès non autorisé.');
        }

        // Vérifier le statut
        if (!in_array($recommandation->statut, ['point_focal_assigne', 'plan_en_redaction'])) {
            return redirect()->route('point_focal.recommandations.show', $recommandation)
                ->with('error', 'Cette recommandation ne peut plus être modifiée.');
        }

        $validated = $request->validate([
            'indicateurs' => 'required|string|max:1000',
            'incidence_financiere' => 'required|in:faible,moyen,eleve',
            'delai_mois' => 'required|integer|min:0|max:60',
            'date_debut_prevue' => 'required|date',
            'date_fin_prevue' => 'required|date|after_or_equal:date_debut_prevue',
        ]);

        // Vérifier que la date de fin n'excède pas la date limite
        $finPrevue = new \DateTime($validated['date_fin_prevue']);
        $dateLimite = new \DateTime($recommandation->date_limite->format('Y-m-d'));

        if ($finPrevue > $dateLimite) {
            return back()
                ->withErrors(['date_fin_prevue' => 'La date de fin prévue ne peut pas dépasser la date limite (' . $recommandation->date_limite->format('d/m/Y') . ')'])
                ->withInput();
        }

        $recommandation->update($validated);

        // Effacer les motifs de rejet quand le Point Focal modifie (ils ont corrigé)
        // Clear au niveau recommandation (rejet Responsable)
        $recommandation->update([
            'motif_rejet_responsable' => null,
            'date_rejet_responsable' => null
        ]);
        // Clear au niveau plans (rejet IG)
        $recommandation->plansAction()->update([
            'motif_rejet_ig' => null
        ]);

        // Mettre à jour le statut si c'était 'point_focal_assigne'
        if ($recommandation->statut === 'point_focal_assigne') {
            $recommandation->update(['statut' => 'plan_en_redaction']);
        }

        return redirect()->route('point_focal.recommandations.show', $recommandation)
            ->with('success', 'Informations de planification mises à jour. Les motifs de rejet antérieurs ont été effacés. Vous pouvez maintenant créer les actions.');
    }
}

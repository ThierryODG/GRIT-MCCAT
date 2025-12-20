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

         return view('point_focal.recommandations.show', [
        'recommandation' => $recommandation,
        'peutSoumettre' => $recommandation->peutEtreSoumiseParPointFocal(), // Méthode que tu as ajoutée au modèle
        'aEteRejetee' => $recommandation->aEteRejeteeParResponsable(),     // Méthode que tu as ajoutée au modèle
    ]);

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

        // Vérifier que la recommandation est dans un statut éditable (inclure le cas 'rejeté' pour permettre corrections)
        if (!in_array($recommandation->statut, ['point_focal_assigne', 'plan_en_redaction', 'plan_rejete_responsable'])) {
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

        // Vérifier le statut (autoriser aussi le cas où le responsable a rejeté)
        if (!in_array($recommandation->statut, ['point_focal_assigne', 'plan_en_redaction', 'plan_rejete_responsable'])) {
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

        // Detecter s'il y a réellement des changements par rapport aux valeurs en base
        $original = $recommandation->only(['indicateurs', 'incidence_financiere', 'delai_mois', 'date_debut_prevue', 'date_fin_prevue']);

        // Normaliser les dates pour comparaison (Y-m-d)
        $origDates = [
            'date_debut_prevue' => $original['date_debut_prevue'] ? optional($original['date_debut_prevue'])->format('Y-m-d') : null,
            'date_fin_prevue' => $original['date_fin_prevue'] ? optional($original['date_fin_prevue'])->format('Y-m-d') : null,
        ];

        $changed = false;
        foreach (['indicateurs', 'incidence_financiere', 'delai_mois'] as $key) {
            if (($original[$key] ?? null) != ($validated[$key] ?? null)) {
                $changed = true;
                break;
            }
        }

        if (! $changed) {
            if (($origDates['date_debut_prevue'] ?? null) != ($validated['date_debut_prevue'] ?? null) ||
                ($origDates['date_fin_prevue'] ?? null) != ($validated['date_fin_prevue'] ?? null)) {
                $changed = true;
            }
        }

        if (! $changed) {
            return back()->with('warning', 'Aucune modification détectée — rien n\'a été sauvegardé.');
        }

        // Appliquer les changements
        $recommandation->update($validated);

        // Effacer les motifs de rejet quand le Point Focal modifie (ils ont corrigé)
        $recommandation->update([
            'motif_rejet_responsable' => null,
            'date_rejet_responsable' => null
        ]);
        // Clear au niveau plans (rejet IG)
        $recommandation->plansAction()->update([
            'motif_rejet_ig' => null
        ]);

        // Si la recommandation était en état 'rejeté par responsable', repasser en rédaction
        if (in_array($recommandation->statut, ['point_focal_assigne', 'plan_rejete_responsable'])) {
            $recommandation->update(['statut' => 'plan_en_redaction']);
        }

        return redirect()->route('point_focal.recommandations.show', $recommandation)
            ->with('success', 'Informations de planification mises à jour. Les motifs de rejet antérieurs ont été effacés. Vous pouvez maintenant créer les actions.');
    }

    /**
     * Soumettre la planification au responsable (Point Focal)
     */
    public function soumettrePlanification(Recommandation $recommandation)
    {
        // Vérifier que l'utilisateur est bien le point focal assigné
        if ($recommandation->point_focal_id !== Auth::id()) {
            abort(403, 'Accès non autorisé.');
        }

        // Vérifier que la recommandation est dans un statut éditable
        if (!in_array($recommandation->statut, ['point_focal_assigne', 'plan_en_redaction', 'plan_rejete_responsable'])) {
            return redirect()->route('point_focal.recommandations.show', $recommandation)
                ->with('error', 'Cette recommandation ne peut pas être soumise dans son état actuel.');
        }

        // Vérifier que les informations de planification sont complètes
        if (empty($recommandation->indicateurs)
            || empty($recommandation->incidence_financiere)
            || empty($recommandation->delai_mois)
            || empty($recommandation->date_debut_prevue)
            || empty($recommandation->date_fin_prevue)) {

            return back()->with('error', 'Complétez les informations de planification avant de soumettre.');
        }

        // Vérifier qu'il y a au moins une action renseignée
        if (!$recommandation->plansAction()->whereNotNull('action')->exists()) {
            return back()->with('error', 'Ajoutez au moins une action avant de soumettre la planification.');
        }

        // Changer le statut pour indiquer la soumission au responsable
        $recommandation->update([
            'statut' => 'plan_soumis_responsable',
            // Effacer l'ancien motif de rejet Responsable si présent (le PF a corrigé)
            'motif_rejet_responsable' => null,
            'date_rejet_responsable' => null,
        ]);

        // Effacer l'ancien motif de rejet IG (si présent) car le PF a corrigé
        $recommandation->update([
            'motif_rejet_ig' => null,
        ]);

        // Notifier le responsable (Notifications Laravel)
        try {
            if ($recommandation->responsable) {
                $recommandation->responsable->notify(new \App\Notifications\PlanningSubmitted($recommandation));
            } else {
                // si pas de responsable précis, notifier les responsables de la structure
                $responsables = \App\Models\User::where('structure_id', $recommandation->structure_id)
                    ->where('role', 'responsable')
                    ->get();
                foreach ($responsables as $resp) {
                    $resp->notify(new \App\Notifications\PlanningSubmitted($recommandation));
                }
            }
        } catch (\Throwable $e) {
            // Ne pas bloquer le flux si la notification échoue; enregistrer un log léger
            logger()->warning('Notification PlanningSubmitted failed: ' . $e->getMessage());
        }

        return redirect()->route('point_focal.recommandations.show', $recommandation)
            ->with('success', 'Planification soumise au responsable.');
    }
}

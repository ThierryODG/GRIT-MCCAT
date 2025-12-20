<?php

namespace App\Http\Controllers\ITS;

use Illuminate\Http\Request;
use App\Models\Recommandation;
use App\Models\Structure;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class RecommandationController extends Controller
{
    /**
     * Liste des recommandations de l'ITS connecté
     */
    public function index(Request $request)
    {
        $query = Recommandation::where('its_id', Auth::id());

        // ==================== FILTRES ====================
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('priorite')) {
            $query->where('priorite', $request->priorite);
        }

        if ($request->filled('date_debut')) {
            $query->whereDate('created_at', '>=', $request->date_debut);
        }

        if ($request->filled('date_fin')) {
            $query->whereDate('created_at', '<=', $request->date_fin);
        }

        // Recherche par titre ou référence
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('titre', 'like', '%' . $request->search . '%')
                  ->orWhere('reference', 'like', '%' . $request->search . '%');
            });
        }

        $recommandations = $query->with([ 'structure:id,nom,sigle', 'inspecteurGeneral:id,name', 'pointFocal:id,name'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('its.recommandations.index', compact('recommandations'));
    }

    /**
     * Formulaire de création
     */
    public function create()
    {
        $structures = Structure::active()->get();
        return view('its.recommandations.create', compact('structures'));
    }

    /**
     * Créer une nouvelle recommandation
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'structure_id' => 'required|exists:structures,id',
            'priorite' => 'required|in:haute,moyenne,basse',
            'date_limite' => 'required|date|after:today',
        ]);

        // La référence sera générée automatiquement par le modèle
        $recommandation = Recommandation::create([
            'titre' => $validated['titre'],
            'description' => $validated['description'],
            'structure_id' => $validated['structure_id'], // ✅ CORRECTION ICI
            'priorite' => $validated['priorite'],
            'date_limite' => $validated['date_limite'],
            'its_id' => Auth::id(),
            'statut' => 'brouillon',
        ]);

        return redirect()->route('its.recommandations.show', $recommandation)
            ->with('success', 'Recommandation créée en brouillon. Vous pouvez la soumettre à l\'IG.');
    }

    /**
     * Supprimer une recommandation
     */
    public function destroy(Recommandation $recommandation)
    {
        // Vérifier que l'utilisateur peut supprimer
        if ($recommandation->its_id !== Auth::id()) {
            abort(403, 'Action non autorisée.');
        }

        // On ne peut supprimer que les brouillons OU les rejetées
        if (!in_array($recommandation->statut, ['brouillon', 'rejetee_ig'])) {
            return redirect()->route('its.recommandations.index')
                ->with('error', 'Seules les recommandations en brouillon ou rejetées peuvent être supprimées.');
        }

        $recommandation->delete();

        return redirect()->route('its.recommandations.index')
            ->with('success', 'Recommandation supprimée avec succès.');
    }

    /**
     * Détails d'une recommandation
     */
    public function show(Recommandation $recommandation)
    {
        // Vérifier que l'utilisateur peut voir cette recommandation
        if ($recommandation->its_id !== Auth::id()) {
            abort(403, 'Action non autorisée.');
        }

        $recommandation->load([
            'structure', // ✅ AJOUTER ICI
            'inspecteurGeneral:id,name',
            'responsable:id,name',
            'pointFocal:id,name,telephone',
            'plansAction'
        ]);

        return view('its.recommandations.show', compact('recommandation'));
    }

    /**
     * Formulaire d'édition
     */
    public function edit(Recommandation $recommandation)
    {
        // Vérifier que l'utilisateur peut modifier
        if ($recommandation->its_id !== Auth::id()) {
            abort(403, 'Action non autorisée.');
        }

        // On ne peut modifier que les brouillons ou rejetées
        if (!in_array($recommandation->statut, ['brouillon', 'rejetee_ig'])) {
            return redirect()->route('its.recommandations.show', $recommandation)
                ->with('error', 'Cette recommandation ne peut plus être modifiée.');
        }

        $structures = Structure::active()->get(); // ✅ AJOUTER ICI
        return view('its.recommandations.edit', compact('recommandation', 'structures'));
    }

   /**
     * Mettre à jour une recommandation
     */
    public function update(Request $request, Recommandation $recommandation)
    {
        // Vérifier que l'utilisateur peut modifier
        if ($recommandation->its_id !== Auth::id()) {
            abort(403, 'Action non autorisée.');
        }

        // On ne peut modifier que les brouillons ou rejetées
        if (!in_array($recommandation->statut, ['brouillon', 'rejetee_ig'])) {
            return redirect()->route('its.recommandations.show', $recommandation)
                ->with('error', 'Cette recommandation ne peut plus être modifiée.');
        }

        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'structure_id' => 'required|exists:structures,id',
            'priorite' => 'required|in:haute,moyenne,basse',
            'date_limite' => 'required|date|after:today',
        ]);

        // Gestion des actions spécifiques
        if ($request->has('action')) {
            switch ($request->action) {
                case 'resoumettre':
                    if ($recommandation->statut === 'rejetee_ig') {
                        // Sauvegarder d'abord les modifications
                        $recommandation->update([
                            'titre' => $validated['titre'],
                            'description' => $validated['description'],
                            'structure_id' => $validated['structure_id'],
                            'priorite' => $validated['priorite'],
                            'date_limite' => $validated['date_limite'],
                            'statut' => 'soumise_ig',
                            'motif_rejet_ig' => null,
                            'commentaire_ig' => null,
                        ]);

                        return redirect()->route('its.recommandations.index')
                            ->with('success', 'Recommandation modifiée et renvoyée à l\'Inspecteur Général.');
                    }
                    break;

                case 'soumettre':
                    if ($recommandation->statut === 'brouillon') {
                        $recommandation->update([
                            'statut' => 'soumise_ig'
                        ]);

                        return redirect()->route('its.recommandations.index')
                            ->with('success', 'Recommandation soumise à l\'Inspecteur Général avec succès.');
                    }
                    break;

                default:
                    // Simple mise à jour sans changement de statut
                    $recommandation->update($validated);
                    return redirect()->route('its.recommandations.show', $recommandation)
                        ->with('success', 'Recommandation mise à jour avec succès.');
            }
        }

        // Mise à jour simple si pas d'action spécifique
        $recommandation->update($validated);
        return redirect()->route('its.recommandations.show', $recommandation)
            ->with('success', 'Recommandation mise à jour avec succès.');
    }

    /**
     * ✅ NOUVELLE MÉTHODE : Soumettre à l'IG
     */
    public function soumettre(Recommandation $recommandation)
    {
        // Vérifications
        if ($recommandation->its_id !== Auth::id()) {
            abort(403, 'Action non autorisée.');
        }

        if ($recommandation->statut !== 'brouillon') {
            return back()->with('error', 'Cette recommandation a déjà été soumise.');
        }

        // Changer le statut
        $recommandation->update([
            'statut' => 'soumise_ig'
        ]);

        // TODO: Envoyer une notification à l'IG

        return redirect()->route('its.recommandations.index')
            ->with('success', 'Recommandation soumise à l\'Inspecteur Général avec succès.');
    }
    public function suivi(Recommandation $recommandation)
    {
        // Vérifier que l'utilisateur peut voir cette recommandation
        if ($recommandation->its_id !== Auth::id()) {
            abort(403, 'Action non autorisée.');
        }

        $recommandation->load(['plansAction', 'pointFocal']);

        // Calcul de la progression globale
        $totalActions = $recommandation->plansAction->count();
        $completedActions = $recommandation->plansAction->where('statut_execution', 'termine')->count();
        $globalProgress = $totalActions > 0 ? round(($completedActions / $totalActions) * 100) : 0;

        return view('its.recommandations.suivi', compact('recommandation', 'globalProgress'));
    }

    /**
     * Envoyer un rappel
     */
    public function rappel(Request $request, Recommandation $recommandation)
    {
        if ($recommandation->its_id !== Auth::id()) {
            abort(403, 'Action non autorisée.');
        }

        $validated = $request->validate([
            'destinataire' => 'required|in:point_focal,responsable,inspecteur_general',
            'message' => 'nullable|string|max:1000',
        ]);

        // Créer le commentaire de type "rappel"
        $recommandation->commentaires()->create([
            'user_id' => Auth::id(),
            'destinataire_role' => $validated['destinataire'],
            'contenu' => $validated['message'] ?? 'Rappel envoyé.',
            'type' => 'rappel',
        ]);

        // TODO: Envoyer une notification réelle (Email/DB Notification)

        $destinataireLabel = match($validated['destinataire']) {
            'point_focal' => 'Point Focal',
            'responsable' => 'Responsable',
            'inspecteur_general' => 'Inspecteur Général',
        };

        return back()->with('success', "Rappel envoyé avec succès au {$destinataireLabel}.");
    }
}

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

        // ==================== GÉNÉRATION RÉFÉRENCE ====================
        $annee = date('Y');
        $dernierNumero = Recommandation::whereYear('created_at', $annee)->count();
        $reference = 'REC-' . $annee . '-' . str_pad($dernierNumero + 1, 4, '0', STR_PAD_LEFT);

        // ==================== CRÉATION ====================
        $recommandation = Recommandation::create([
            'reference' => $reference,
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
            'responsable:id,name,direction',
            'pointFocal:id,name,telephone',
            'planAction'
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
            'structure_id' => 'required|exists:structures,id', // ✅ AJOUTER ICI
            'priorite' => 'required|in:haute,moyenne,basse',
            'date_limite' => 'required|date|after:today',
        ]);

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
}

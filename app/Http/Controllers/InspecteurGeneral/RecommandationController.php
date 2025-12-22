<?php

namespace App\Http\Controllers\InspecteurGeneral;

use App\Http\Controllers\Controller;
use App\Models\Recommandation;
use App\Models\Structure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RecommandationController extends Controller
{
    /**
     * Liste des recommandations en attente de validation initiale IG
     */
    public function index()
    {
        $query = Recommandation::with(['its', 'its.structure'])
            ->orderBy('created_at', 'asc');

        // Filtres
        if (request('statut')) {
            $query->where('statut', request('statut'));
        } else {
            // Par défaut, celles soumises à l'IG pour validation initiale
            $query->where('statut', 'soumise_ig');
        }

        if (request('priorite')) {
            $query->where('priorite', request('priorite'));
        }

        if (request('structure_id')) {
            $query->where('structure_id', request('structure_id'));
        }

        $recommandations = $query->paginate(15);
        $structures = Structure::orderBy('nom')->get();

        return view('inspecteur_general.recommandations.index', compact('recommandations', 'structures'));
    }

    /**
     * Détails d'une recommandation pour validation initiale
     */
    public function show(Recommandation $recommandation)
    {
        $recommandation->load(['its', 'structure']);
        return view('inspecteur_general.recommandations.show', compact('recommandation'));
    }

    /**
     * Valider la recommandation initialement (permet aux structures de commencer à planifier)
     */
    public function valider(Request $request, Recommandation $recommandation)
    {
        if ($recommandation->statut !== 'soumise_ig') {
            return back()->with('error', 'Cette recommandation ne peut pas être validée.');
        }

        $recommandation->update([
            'statut' => 'validee_ig',
            'inspecteur_general_id' => Auth::id(),
            'date_validation_ig' => now(),
            'commentaire_ig' => $request->commentaire
        ]);

        return redirect()->route('inspecteur_general.recommandations.index')
            ->with('success', 'Recommandation validée avec succès.');
    }

    /**
     * Rejeter la recommandation initialement (retour à l'ITS)
     */
    public function rejeter(Request $request, Recommandation $recommandation)
    {
        $request->validate([
            'motif' => 'required|string|max:1000'
        ]);

        if ($recommandation->statut !== 'soumise_ig') {
            return back()->with('error', 'Cette recommandation ne peut pas être rejetée.');
        }

        $recommandation->update([
            'statut' => 'rejetee_ig',
            'inspecteur_general_id' => Auth::id(),
            'date_validation_ig' => now(),
            'motif_rejet_ig' => $request->motif
        ]);

        return redirect()->route('inspecteur_general.recommandations.index')
            ->with('success', 'Recommandation rejetée.');
    }
}

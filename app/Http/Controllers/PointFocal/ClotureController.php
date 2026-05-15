<?php

namespace App\Http\Controllers\PointFocal;

use App\Http\Controllers\Controller;
use App\Models\Recommandation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClotureController extends Controller
{
    /**
     * Liste des recommandations éligibles à la clôture
     */
    public function index()
    {
        $recommandations = Recommandation::where('point_focal_id', Auth::id())
            ->where('statut', 'execution_terminee') // Exécution terminée
            ->with(['its:id,name', 'plansAction'])
            ->orderBy('updated_at', 'desc')
            ->paginate(15);

        return view('point_focal.cloture.index', compact('recommandations'));
    }

    /**
     * Demander la clôture d'une recommandation
     */
    public function demander(Request $request, Recommandation $recommandation)
    {
        $request->validate([
            'commentaire_cloture' => 'nullable|string|max:1000',
            'documents_justificatifs' => 'nullable|string' // URL ou path
        ]);

        // Vérifications
        if ($recommandation->point_focal_id !== Auth::id()) {
            abort(403, 'Cette recommandation ne vous est pas assignée.');
        }

        if ($recommandation->statut !== 'execution_terminee') {
            return back()->with('error', 'L\'exécution doit être terminée à 100% avant de demander la clôture.');
        }

        // Vérifier que tous les plans d'action sont bien à 100%
        if ($recommandation->plansAction->isEmpty() || $recommandation->plansAction->min('pourcentage_avancement') < 100) {
            return back()->with('error', 'Tous les plans d\'action doivent être terminés à 100%.');
        }

        // Demander la clôture
        $recommandation->update([
            'statut' => 'demande_cloture',
            'commentaire_demande_cloture' => $request->commentaire_cloture,
            'documents_justificatifs' => $request->documents_justificatifs
        ]);

        // TODO: Notifier l'ITS

        return redirect()->route('point_focal.cloture.index')
            ->with('success', 'Demande de clôture envoyée à l\'ITS avec succès.');
    }
}

<?php

namespace App\Http\Controllers\ITS;

use Illuminate\Http\Request;
use App\Models\Recommandation;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ClotureController extends Controller
{
    /**
     * Liste des recommandations en demande de clôture
     */
    public function index()
    {
        $recommandations = Recommandation::where('its_id', Auth::id())
            ->where('statut', 'demande_cloture') // ✅ CORRIGÉ
            ->with(['pointFocal:id,name,telephone', 'responsable:id,name'])
            ->orderBy('updated_at', 'desc')
            ->paginate(15);

        return view('its.cloture.index', compact('recommandations'));
    }

    /**
     * Clôturer une recommandation
     */
    public function cloturer(Request $request, Recommandation $recommandation)
    {
        $request->validate([
            'commentaire_cloture' => 'nullable|string|max:1000'
        ]);

        // Vérifications
        if ($recommandation->its_id !== Auth::id()) {
            abort(403, 'Action non autorisée.');
        }

        if ($recommandation->statut !== 'demande_cloture') {
            return back()->with('error', 'Cette recommandation n\'est pas en demande de clôture.');
        }

        // Clôturer
        $recommandation->update([
            'statut' => 'cloturee',
            'date_cloture' => now(),
            'commentaire_cloture' => $request->commentaire_cloture
        ]);

        // TODO: Notifier le Point Focal et le Responsable

        return back()->with('success', 'Recommandation clôturée avec succès.');
    }

    /**
     * Rejeter la clôture et rouvrir
     */
    public function rejeter(Request $request, Recommandation $recommandation)
    {
        $request->validate([
            'motif_rejet_cloture' => 'required|string|max:1000'
        ]);

        // Vérifications
        if ($recommandation->its_id !== Auth::id()) {
            abort(403, 'Action non autorisée.');
        }

        if ($recommandation->statut !== 'demande_cloture') {
            return back()->with('error', 'Cette recommandation n\'est pas en demande de clôture.');
        }

        // Rejeter et rouvrir
        $recommandation->update([
            'statut' => 'en_execution',
            'motif_rejet_cloture' => $request->motif_rejet_cloture
        ]);

        // TODO: Notifier le Point Focal

        return back()->with('success', 'Demande de clôture rejetée. La recommandation est rouverte.');
    }
}

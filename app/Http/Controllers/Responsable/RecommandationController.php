<?php

namespace App\Http\Controllers\Responsable;

use App\Http\Controllers\Controller;
use App\Models\Recommandation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RecommandationController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $recommandations = Recommandation::where('structure_id', $user->structure_id)
            ->with(['its', 'pointFocal', 'structure'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('responsable.recommandations.index', compact('recommandations'));
    }

    public function assigner(Recommandation $recommandation)
    {
        // Vérifier que la recommandation appartient à la structure du responsable
        if ($recommandation->structure_id !== Auth::user()->structure_id) {
            abort(403, 'Action non autorisée.');
        }

        // Récupérer les points focaux de la structure
        $pointsFocaux = User::where('structure_id', Auth::user()->structure_id)
            ->where('role', 'point_focal')
            ->get();

        return view('responsable.recommandations.assigner', compact('recommandation', 'pointsFocaux'));
    }

    public function storeAssignation(Request $request, Recommandation $recommandation)
    {
        // Vérifications de sécurité
        if ($recommandation->structure_id !== Auth::user()->structure_id) {
            abort(403);
        }

        $validated = $request->validate([
            'point_focal_id' => 'required|exists:users,id',
        ]);

        // Vérifier que le point focal appartient à la même structure
        $pointFocal = User::find($validated['point_focal_id']);
        if ($pointFocal->structure_id !== Auth::user()->structure_id) {
            return back()->with('error', 'Le point focal sélectionné ne fait pas partie de votre structure.');
        }

        // Mettre à jour la recommandation
        $recommandation->update([
            'point_focal_id' => $validated['point_focal_id'],
            'responsable_id' => Auth::id(),
            'date_assignation_pf' => now(),
            'statut' => 'point_focal_assigne'
        ]);

        return redirect()->route('responsable.dashboard')
            ->with('success', 'Point focal assigné avec succès à la recommandation ' . $recommandation->reference);
    }
}

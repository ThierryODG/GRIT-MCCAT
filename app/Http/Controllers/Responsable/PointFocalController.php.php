<?php

namespace App\Http\Controllers\Responsable;

use App\Models\User;
use App\Models\PlanAction;
use Illuminate\Http\Request;
use App\Models\Recommandation;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PointFocalController extends Controller
{
    /**
     * Liste des recommandations à assigner
     */
    public function index()
    {
        $recommandations = Recommandation::where('responsable_id', Auth::id())
            ->where('statut', 'validee_ig')
            ->whereNull('point_focal_id') // Pas encore assignées
            ->with(['its:id,name,structure_id', 'inspecteurGeneral:id,name'])
            ->orderBy('date_limite', 'asc')
            ->paginate(15);

        // Liste des Points Focaux de ma structure
        $pointsFocaux = User::whereHas('role', function($q) {
                $q->where('nom', 'point_focal');
            })
            ->where('structure_id', Auth::user()->structure_id)
            ->get();

        return view('responsable.points_focaux.index', compact('recommandations', 'pointsFocaux'));
    }

    /**
     * Assigner un Point Focal à une recommandation
     */
    public function assigner(Request $request, Recommandation $recommandation)
    {
        $request->validate([
            'point_focal_id' => 'required|exists:users,id',
            'instructions' => 'nullable|string|max:1000'
        ]);

        // Vérifications
        if ($recommandation->responsable_id !== Auth::id()) {
            abort(403, 'Cette recommandation ne vous est pas assignée.');
        }

        if ($recommandation->statut !== 'validee_ig') {
            return back()->with('error', 'Cette recommandation n\'est pas encore validée par l\'IG.');
        }

        // Vérifier que le Point Focal est bien de ma structure
        $pointFocal = User::findOrFail($request->point_focal_id);
        if ($pointFocal->structure_id !== Auth::user()->structure_id) {
            return back()->with('error', 'Ce Point Focal n\'appartient pas à votre structure.');
        }

        // Assigner le Point Focal
        $recommandation->update([
            'point_focal_id' => $request->point_focal_id,
            'statut' => 'point_focal_assigne'
        ]);

        // Créer le plan d'action vide (à remplir par le Point Focal)
        PlanAction::create([
            'recommandation_id' => $recommandation->id,
            'point_focal_id' => $request->point_focal_id,
            'responsable_id' => Auth::id(),
            'statut_validation' => 'en_attente_responsable',
            'statut_execution' => 'non_demarre',
        ]);

        // Mettre à jour le statut de la recommandation
        $recommandation->update([
            'statut' => 'plan_en_redaction'
        ]);

        // TODO: Notifier le Point Focal

        return redirect()->route('responsable.points_focaux.index')
            ->with('success', 'Point Focal assigné avec succès. Il a été notifié.');
    }

    /**
     * Réassigner un Point Focal
     */
    public function reassigner(Request $request, Recommandation $recommandation)
    {
        $request->validate([
            'nouveau_point_focal_id' => 'required|exists:users,id',
            'motif' => 'required|string|max:1000'
        ]);

        if ($recommandation->responsable_id !== Auth::id()) {
            abort(403);
        }

        // Mettre à jour
        $recommandation->update([
            'point_focal_id' => $request->nouveau_point_focal_id
        ]);

        if ($recommandation->planAction) {
            $recommandation->planAction->update([
                'point_focal_id' => $request->nouveau_point_focal_id
            ]);
        }

        // TODO: Notifier l'ancien et le nouveau Point Focal

        return back()->with('success', 'Point Focal réassigné avec succès.');
    }
}

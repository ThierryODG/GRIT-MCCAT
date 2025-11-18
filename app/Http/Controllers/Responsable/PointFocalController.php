<?php

namespace App\Http\Controllers\Responsable;

use App\Http\Controllers\Controller;
use App\Models\Recommandation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PointFocalController extends Controller
{
    public function index()
    {
        $structureId = Auth::user()->structure_id;

        // 1. ITS ayant formulé des recommandations VALIDÉES pour cette structure
        $itsList = User::whereHas('recommandationsCreees', function($query) use ($structureId) {
            $query->where('structure_id', $structureId)
                  ->where('statut', 'validee_ig'); // Uniquement les recommandations validées par l'IG
        })
        ->withCount(['recommandationsCreees as nb_recommandations' => function($query) use ($structureId) {
            $query->where('structure_id', $structureId)
                  ->where('statut', 'validee_ig');
        }])
        ->whereHas('role', function($query) {
            $query->where('nom', 'its');
        })
        ->get();

        // 2. Points focaux de la structure (uniquement ceux de la même structure)
        $pointsFocaux = User::where('structure_id', $structureId)
            ->whereHas('role', function($query) {
                $query->where('nom', 'point_focal');
            })
            ->get();

        // 3. Assignations existantes - UNIQUEMENT si le responsable les a faites
        $assignations = Recommandation::where('structure_id', $structureId)
            ->where('statut', 'point_focal_assigne') // Seulement les assignations faites
            ->whereNotNull('point_focal_id')
            ->with(['its', 'pointFocal'])
            ->get()
            ->groupBy('its_id')
            ->map(function($recommandations) {
                return [
                    'point_focal' => $recommandations->first()->pointFocal,
                    'nb_recommandations' => $recommandations->count(),
                    'its' => $recommandations->first()->its,
                    'date_assignation' => $recommandations->first()->date_assignation_pf
                ];
            });

        return view('responsable.points_focaux.index', compact(
            'itsList',
            'pointsFocaux',
            'assignations'
        ));
    }

    public function assigner(Request $request)
    {
        $request->validate([
            'its_id' => 'required|exists:users,id',
            'point_focal_id' => 'required|exists:users,id',
        ]);

        $structureId = Auth::user()->structure_id;

        // Vérifier que le point focal appartient à la même structure
        $pointFocal = User::where('id', $request->point_focal_id)
            ->where('structure_id', $structureId)
            ->whereHas('role', function($query) {
                $query->where('nom', 'point_focal');
            })
            ->firstOrFail();

        // Vérifier que l'ITS a des recommandations validées pour cette structure
        $recommandationsCount = Recommandation::where('structure_id', $structureId)
            ->where('its_id', $request->its_id)
            ->where('statut', 'validee_ig')
            ->count();

        if ($recommandationsCount === 0) {
            return redirect()->back()->with('error', 'Cet ITS n\'a aucune recommandation validée pour votre structure.');
        }

        // Assigner le point focal à TOUTES les recommandations validées de cet ITS
        $updated = Recommandation::where('structure_id', $structureId)
            ->where('its_id', $request->its_id)
            ->where('statut', 'validee_ig')
            ->update([
                'point_focal_id' => $request->point_focal_id,
                'responsable_id' => Auth::id(), // AJOUT DU RESPONSABLE_ID
                'statut' => 'point_focal_assigne',
                'date_assignation_pf' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);

        return redirect()->back()->with('success', "Point focal assigné avec succès à {$updated} recommandation(s).");
    }

    public function reassigner(Request $request, $itsId)
    {
        $request->validate([
            'point_focal_id' => 'required|exists:users,id',
        ]);

        $structureId = Auth::user()->structure_id;

        // Vérifier que le point focal appartient à la même structure
        $pointFocal = User::where('id', $request->point_focal_id)
            ->where('structure_id', $structureId)
            ->whereHas('role', function($query) {
                $query->where('nom', 'point_focal');
            })
            ->firstOrFail();

        // Réassigner toutes les recommandations de cet ITS
        $updated = Recommandation::where('structure_id', $structureId)
            ->where('its_id', $itsId)
            ->where('statut', 'point_focal_assigne')
            ->update([
                'point_focal_id' => $request->point_focal_id,
                'responsable_id' => Auth::id(), // RESPONSABLE_ID BIEN PRÉSENT
                'statut' => 'point_focal_assigne',
                'date_assignation_pf' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);

        return redirect()->back()->with('success', "Point focal réassigné avec succès à {$updated} recommandation(s).");
    }

    public function retirer($itsId)
    {
        $structureId = Auth::user()->structure_id;

        // Retirer l'assignation pour toutes les recommandations de cet ITS
        $updated = Recommandation::where('structure_id', $structureId)
            ->where('its_id', $itsId)
            ->where('statut', 'point_focal_assigne')
            ->update([
                'point_focal_id' => null,
                'date_assignation_pf' => null,
                'responsable_id' => null, // REMISE À NULL DU RESPONSABLE_ID
                'statut' => 'validee_ig', // Retour au statut "validée par IG"
                'updated_at' => Carbon::now()
            ]);

        return redirect()->back()->with('success', "Assignation retirée avec succès pour {$updated} recommandation(s).");
    }
}

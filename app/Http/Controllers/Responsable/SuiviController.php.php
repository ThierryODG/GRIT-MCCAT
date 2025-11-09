<?php

namespace App\Http\Controllers\Responsable;

use Illuminate\Http\Request;
use App\Models\Recommandation;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class SuiviController extends Controller
{
    /**
     * Suivi de toutes mes recommandations
     */
    public function index(Request $request)
    {
        $query = Recommandation::where('responsable_id', Auth::id());

        // Filtres
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('point_focal_id')) {
            $query->where('point_focal_id', $request->point_focal_id);
        }

        if ($request->filled('priorite')) {
            $query->where('priorite', $request->priorite);
        }

        $recommandations = $query->with([
                'its:id,name',
                'inspecteurGeneral:id,name',
                'pointFocal:id,name',
                'planAction'
            ])
            ->orderBy('date_limite', 'asc')
            ->paginate(20);

        // Liste des Points Focaux pour le filtre
        $pointsFocaux = \App\Models\User::whereHas('role', function($q) {
                $q->where('nom', 'point_focal');
            })
            ->where('direction', Auth::user()->direction)
            ->get();

        return view('responsable.suivi.index', compact('recommandations', 'pointsFocaux'));
    }

    /**
     * Détails d'une recommandation
     */
    public function show(Recommandation $recommandation)
    {
        if ($recommandation->responsable_id !== Auth::id()) {
            abort(403);
        }

        $recommandation->load([
            'its:id,name,direction',
            'inspecteurGeneral:id,name',
            'pointFocal:id,name,telephone',
            'planAction'
        ]);

        return view('responsable.suivi.show', compact('recommandation'));
    }

    /**
     * Export Excel des recommandations
     */
    public function export()
    {
        // TODO: Implémenter l'export Excel
        return back()->with('info', 'Export Excel à implémenter.');
    }
}

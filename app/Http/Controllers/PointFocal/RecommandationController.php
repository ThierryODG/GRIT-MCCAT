<?php

namespace App\Http\Controllers\PointFocal;

use App\Http\Controllers\Controller;
use App\Models\Recommandation;
use Illuminate\Http\Request;

class RecommandationController extends Controller
{
    /**
     * Liste de toutes mes recommandations assignées
     */
    public function index(Request $request)
    {
        $query = Recommandation::where('point_focal_id', auth()->id());

        // Filtres
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('priorite')) {
            $query->where('priorite', $request->priorite);
        }

        $recommandations = $query->with([
                'its:id,name,direction',
                'inspecteurGeneral:id,name',
                'responsable:id,name',
                'planAction'
            ])
            ->orderBy('date_limite', 'asc')
            ->paginate(15);

        return view('point_focal.recommandations.index', compact('recommandations'));
    }

    /**
     * Détails d'une recommandation
     */
    public function show(Recommandation $recommandation)
    {
        if ($recommandation->point_focal_id !== auth()->id()) {
            abort(403, 'Cette recommandation ne vous est pas assignée.');
        }

        $recommandation->load([
            'its:id,name,direction,telephone',
            'inspecteurGeneral:id,name',
            'responsable:id,name,telephone',
            'planAction'
        ]);

        return view('point_focal.recommandations.show', compact('recommandation'));
    }
}

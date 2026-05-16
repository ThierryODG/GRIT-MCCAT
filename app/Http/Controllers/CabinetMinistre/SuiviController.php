<?php

namespace App\Http\Controllers\CabinetMinistre;

use App\Http\Controllers\Controller;
use App\Models\Recommandation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SuiviController extends Controller
{
    /**
     * Liste des recommandations avec filtres
     */
    public function index(Request $request)
    {
        $query = Recommandation::with(['its:id,name', 'inspecteurGeneral:id,name', 'pointFocal:id,name']);

        // ==================== FILTRES ====================

        // Filtre par statut
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        // Filtre par priorité
        if ($request->filled('priorite')) {
            $query->where('priorite', $request->priorite);
        }

        // Filtre par date
        if ($request->filled('date_debut') && $request->filled('date_fin')) {
            $query->whereBetween('created_at', [$request->date_debut, $request->date_fin]);
        }

        // Filtre par structure ITS
        if ($request->filled('its_id')) {
            $query->where('its_id', $request->its_id);
        }

        // Afficher uniquement les recommandations en retard
        if ($request->boolean('en_retard')) {
            $query->where('date_limite', '<', now())
                  ->whereNotIn('statut', ['cloturee', 'terminee']);
        }

        $recommandations = $query->orderBy('created_at', 'desc')
            ->paginate(20);

        // Liste des ITS pour le filtre
        $listITS = \App\Models\User::whereHas('role', function($q) {
            $q->where('nom', 'its');
        })->pluck('name', 'id');

        return view('cabinet_ministre.suivi.index', compact('recommandations', 'listITS'));
    }

    /**
     * Détails d'une recommandation
     */
    public function show(Recommandation $recommandation)
    {
        $recommandation->load([
            'its:id,name,telephone',
            'inspecteurGeneral:id,name',
            'pointFocal:id,name,telephone',
            'structure',
            'documents',
            'plansAction.preuvesExecution'
        ]);

        // Calcul progression (comme Inspecteur Général)
        $totalActions = $recommandation->plansAction->count();
        $completedActions = $recommandation->plansAction->where('statut_execution', 'termine')->count();
        $globalProgress = $totalActions > 0 ? round(($completedActions / $totalActions) * 100) : 0;

        return view('cabinet_ministre.suivi.show', compact('recommandation', 'globalProgress'));
    }

    /**
     * Télécharger un fichier lié à une recommandation
     */
    public function download(int $documentId)
    {
        $document = \App\Models\RecommandationDocument::findOrFail($documentId);

        $recommandation = $document->recommandation;
        /** @var \App\Models\User $user */
        $user = \Illuminate\Support\Facades\Auth::user();

        $isAllowed = $user->hasRole('admin') ||
                     $user->hasRole('cabinet_ministre') ||
                     $recommandation->its_id === $user->id ||
                     $recommandation->inspecteur_general_id === $user->id ||
                     $recommandation->responsable_id === $user->id ||
                     $recommandation->point_focal_id === $user->id;

        if (!$isAllowed) {
            abort(403, 'Action non autorisée.');
        }

        /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
        $disk = Storage::disk('public');

        if (!$disk->exists($document->file_path)) {
            abort(404, 'Le fichier n\'existe pas.');
        }

        return $disk->download($document->file_path, $document->file_name);
    }
}

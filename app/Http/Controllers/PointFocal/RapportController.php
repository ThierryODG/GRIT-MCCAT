<?php

namespace App\Http\Controllers\PointFocal;

use App\Http\Controllers\Controller;
use App\Models\Rapport;
use App\Models\Recommandation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class RapportController extends Controller
{
    /**
     * Liste des rapports (Vue dossier/année)
     */
    public function index()
    {
        // On récupère les rapports groupés par année
        $rapportsParAnnee = Rapport::with('user')
            ->orderBy('annee', 'desc')
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('annee');

        return view('rapports.index', compact('rapportsParAnnee'));
    }

    /**
     * Formulaire de création de rapport (Point Focal uniquement)
     */
    public function create()
    {
        if (!Auth::user()->isPointFocal()) {
            abort(403, 'Seul le Point Focal peut générer des rapports.');
        }

        // On récupère les recommandations du point focal pour le formulaire
        $recommandations = Recommandation::where('point_focal_id', Auth::id())->get();

        return view('rapports.create', compact('recommandations'));
    }

    /**
     * Génération et sauvegarde du rapport
     */
    public function store(Request $request)
    {
        if (!Auth::user()->isPointFocal()) {
            abort(403);
        }

        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'type' => 'required|in:execution,cloture,global',
            'recommandation_id' => 'nullable|exists:recommandations,id',
            'description' => 'nullable|string',
        ]);

        // Préparation des données pour le PDF
        $data = [
            'titre' => $validated['titre'],
            'type' => $validated['type'],
            'description' => $validated['description'] ?? '',
            'date' => now()->format('d/m/Y'),
            'user' => Auth::user(),
        ];

        if (!empty($validated['recommandation_id'])) {
            $recommandation = Recommandation::with('plansAction')->find($validated['recommandation_id']);
            $data['recommandation'] = $recommandation;
        } else {
            // Si rapport global, on peut récupérer toutes les recommandations
            $data['recommandations'] = Recommandation::where('point_focal_id', Auth::id())->get();
        }

        // Génération du PDF
        $pdf = Pdf::loadView('rapports.pdf', $data);
        
        // Nom du fichier
        $fileName = 'rapport_' . time() . '.pdf';
        $path = 'rapports/' . date('Y') . '/' . $fileName;

        // Sauvegarde sur le disque
        Storage::put($path, $pdf->output());

        // Enregistrement en base
        Rapport::create([
            'titre' => $validated['titre'],
            'path' => $path,
            'annee' => date('Y'),
            'type' => $validated['type'],
            'user_id' => Auth::id(),
            'recommandation_id' => $validated['recommandation_id'] ?? null,
            'description' => $validated['description'],
        ]);

        return redirect()->route('point_focal.rapports.index')->with('success', 'Rapport généré avec succès.');
    }

    /**
     * Télécharger le rapport
     */
    public function show(Rapport $rapport)
    {
        // Vérification des droits d'accès si nécessaire
        // Pour l'instant, tout utilisateur connecté peut voir les rapports (sauf cabinet ministre exclu via middleware/routes)
        
        if (!Storage::exists($rapport->path)) {
            abort(404, 'Fichier introuvable.');
        }

        return Storage::download($rapport->path, $rapport->titre . '.pdf');
    }
}

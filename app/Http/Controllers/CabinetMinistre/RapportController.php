<?php

namespace App\Http\Controllers\CabinetMinistre;

use App\Http\Controllers\Controller;
use App\Models\Rapport;
use App\Models\Recommandation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;

class RapportController extends Controller
{
    /**
     * Liste des rapports archivés (Consultation)
     */
    public function index()
    {
        // Le Cabinet Ministre doit pouvoir consulter tous les rapports générés
        $rapportsParAnnee = Rapport::orderBy('annee', 'desc')
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('annee');

        return view('cabinet_ministre.rapports.index', compact('rapportsParAnnee'));
    }

    /**
     * Formulaire de création de nouveau rapport
     */
    public function create()
    {
        return view('cabinet_ministre.rapports.create');
    }

    /**
     * Générer et enregistrer un rapport
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut',
            'statut' => 'nullable|string',
            'format' => 'required|in:web,pdf,archive'
        ]);

        // 1. Récupération des données
        $query = Recommandation::with(['its', 'structure', 'plansAction']);
        $query->whereBetween('created_at', [$validated['date_debut'], $validated['date_fin']]);

        if ($request->filled('statut')) {
            $query->where('statut', $validated['statut']);
        }

        $recommandations = $query->get();

        // Statistiques
        $statistiques = [
            'total' => $recommandations->count(),
            'validees' => $recommandations->where('statut', 'validee_ig')->count(),
            'en_cours' => $recommandations->where('statut', 'en_cours')->count(),
            'cloturees' => $recommandations->where('statut', 'cloturee')->count(),
            'en_retard' => $recommandations->filter->estEnRetard()->count(),
        ];

        // Répartition par structure
        $parStructure = $recommandations->groupBy(function($r) {
            return $r->structure ? $r->structure->nom : 'Inconnu';
        })->map(function ($group) {
            return $group->count();
        })->sortDesc();

        // 2. Logique selon le format
        if ($validated['format'] === 'web') {
            return view('cabinet_ministre.rapports.resultat', compact('recommandations', 'statistiques', 'parStructure'));
        }

        // 3. Génération PDF et Archivage
        if ($recommandations->isEmpty()) {
            return back()->with('error', 'Aucune recommandation trouvée pour ces critères. Impossible de générer un rapport.');
        }

        // Calcul Taux Global
        $taux_global = $recommandations->count() > 0 
            ? round($recommandations->avg('taux_avancement')) 
            : 0;

        $titre_rapport = 'Rapport Stratégique - ' . now()->format('d/m/Y');

        $data = [
            'recommandations' => $recommandations,
            'stats' => $statistiques,
            'parStructure' => $parStructure,
            'date_debut' => $validated['date_debut'],
            'date_fin' => $validated['date_fin'],
            'date_generation' => now(),
            'auteur_generation' => Auth::user()->name ?? 'Cabinet Ministre',
            'rapport_titre' => $titre_rapport,
            'taux_global' => $taux_global,
            'logo_path' => public_path('images/logo-mccat-300x300.jpg'),
        ];

        // --- NETTOYAGE DES DONNEES (Encodage) ---
        // Copié et adapté de PointFocal/RapportController pour éviter les erreurs UTF-8 dans DomPDF
        $cleanValue = function ($value) {
            if (is_string($value)) {
                return mb_convert_encoding($value, 'UTF-8', 'UTF-8, Windows-1252, ISO-8859-1');
            }
            return $value;
        };

        $cleanModel = function ($model) use ($cleanValue) {
            if (!$model) return;
            $attributes = $model->getAttributes();
            foreach ($attributes as $key => $value) {
                $attributes[$key] = $cleanValue($value);
            }
            $model->setRawAttributes($attributes);
        };

        // On nettoie chaque recommandation et ses relations
        foreach ($recommandations as $rec) {
            $cleanModel($rec);
            $cleanModel($rec->structure);
            // Nettoyage sommaire des plans d'action (pour le PDF global on n'affiche pas tout le détail mais on charge la relation)
            foreach($rec->plansAction as $action) {
               $cleanModel($action);
            }
        }
        $data['rapport_titre'] = $cleanValue($data['rapport_titre']);
        // --- FIN NETTOYAGE ---

        $pdf = Pdf::loadView('cabinet_ministre.rapports.pdf', $data);
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);

        $content = $pdf->output();

        // Enregistrement
        $fileName = 'Rapport_Strategique_' . date('Ymd_His') . '.pdf';
        $path = 'rapports/' . date('Y') . '/' . $fileName;

        Storage::disk('public')->put($path, $content);

        Rapport::create([
            'titre' => $titre_rapport,
            'path' => $path,
            'annee' => date('Y'),
            'type' => 'global', // Distinct from 'execution'
            'user_id' => Auth::id(),
            'description' => 'Synthèse stratégique du ' . $validated['date_debut'] . ' au ' . $validated['date_fin'],
        ]);

        return redirect()->route('cabinet_ministre.rapports.index')->with('success', 'Le rapport stratégique a été généré et archivé avec succès.');
    }

    /**
     * Télécharger un rapport archivé
     */
    public function show(Rapport $rapport)
    {
        /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
        $disk = Storage::disk('public');

        if (!$disk->exists($rapport->path)) {
            abort(404, 'Fichier introuvable.');
        }
        return $disk->download($rapport->path, $rapport->titre . '.pdf');
    }
    
    // Legacy methods kept but redirected/unused
    public function generer(Request $request) {
        return $this->store($request);
    }

    public function exportPDF() { return back(); }
    public function exportExcel() { return back(); }
}

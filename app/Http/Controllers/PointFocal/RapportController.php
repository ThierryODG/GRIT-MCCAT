<?php

namespace App\Http\Controllers\PointFocal;

use App\Http\Controllers\Controller;
use App\Models\Rapport;
use App\Models\Recommandation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Str;

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
            'type' => 'required|in:execution,global', // Reduced types for now
            'recommandation_id' => 'required_if:type,execution|exists:recommandations,id', // MANDATORY for execution
            'description' => 'nullable|string',
        ]);

        // Logic for Execution Report (The main focus)
        if ($validated['type'] === 'execution') {
            $recommandation = Recommandation::with([
                'structure',
                'its', 
                'inspecteurGeneral', 
                'responsable', 
                'pointFocal',
                'plansAction.preuvesExecution',
                'plansAction.preuvesExecution',
                'commentaires.auteur'
            ])->find($validated['recommandation_id']);

            // Use the NEW high-quality view
            $data = [
                'recommandation' => $recommandation,
                'date_generation' => now(), // Pass Carbon object directly to avoid parsing issues in view
                'auteur_generation' => Auth::user()->name,
                'logo_path' => public_path('images/logo-mccat-300x300.jpg'),
                // Extra metadata from the form
                'rapport_titre' => $validated['titre'],
                'rapport_description' => $validated['description']
            ];

            $pdfView = 'point_focal.avancement.rapport_pdf';
            
            // NETTOYAGE DES DONNÉES - "NUCLEAR OPTION"
            // Cette stratégie parcourt récursivement tous les attributs des modèles pour forcer l'encodage UTF-8
            // et gère les encodings Windows/ISO souvent responsables des plantages.
            
            $cleanValue = function ($value) {
                if (is_string($value)) {
                    // Tente de détecter et convertir depuis Windows-1252 ou ISO-8859-1 si ce n'est pas du UTF-8 valide
                    return mb_convert_encoding($value, 'UTF-8', 'UTF-8, Windows-1252, ISO-8859-1');
                }
                return $value;
            };

            $cleanModel = function ($model) use ($cleanValue) {
                if (!$model) return;
                
                // Nettoyer les attributs bruts
                $attributes = $model->getAttributes();
                foreach ($attributes as $key => $value) {
                    $attributes[$key] = $cleanValue($value);
                }
                $model->setRawAttributes($attributes);

                // Nettoyer les relations chargées (récursion manuelle pour les clés connues pour éviter les boucles infinies)
                foreach ($model->getRelations() as $relationName => $relation) {
                    if ($relation instanceof \Illuminate\Database\Eloquent\Collection) {
                        foreach ($relation as $item) {
                            $attributes = $item->getAttributes();
                            foreach ($attributes as $k => $v) {
                                $attributes[$k] = $cleanValue($v);
                            }
                            $item->setRawAttributes($attributes);
                            
                            // Deep clean specific relations of list items
                            if ($relationName === 'plansAction') {
                                foreach ($item->getRelations() as $subRelName => $subRel) {
                                    if ($subRel instanceof \Illuminate\Database\Eloquent\Collection) {
                                        foreach ($subRel as $subItem) { // Preuves
                                            $subAttrs = $subItem->getAttributes();
                                            foreach ($subAttrs as $sk => $sv) $subAttrs[$sk] = $cleanValue($sv);
                                            $subItem->setRawAttributes($subAttrs);
                                        }
                                    }
                                }
                            }
                        }
                    } elseif ($relation instanceof \Illuminate\Database\Eloquent\Model) {
                       $attrs = $relation->getAttributes();
                       foreach ($attrs as $k => $v) $attrs[$k] = $cleanValue($v);
                       $relation->setRawAttributes($attrs);
                    }
                }
            };

            // Appliquer le nettoyage
            $cleanModel($recommandation); // Nettoie structure, its, inspecteur, etc (relations directes)
            
            // Nettoyage manuel supplémentaire pour la collection plansAction et ses sous-relations (preuves)
            // (géré partiellement par la fonction générique mais on assure le coup)
            foreach($recommandation->plansAction as $action) {
                // Les preuves sont dans action->preuvesExecution
                foreach($action->preuvesExecution as $preuve) {
                    $preuve->file_name = $cleanValue($preuve->file_name);
                }
            }

            // Nettoyage des données du formulaire
            $data['rapport_titre'] = $cleanValue($data['rapport_titre']);
            $data['rapport_description'] = $cleanValue($data['rapport_description']);
            // FIN NETTOYAGE

            // Generate PDF
            $pdf = Pdf::loadView($pdfView, $data);
            $pdf->setPaper('a4', 'portrait');
            $pdf->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);

            $content = $pdf->output();
        } else {
            // Global Report Logic (Simplified placeholder for now to prevent errors)
            // TODO: Implement a proper global report view if needed later
            return back()->with('error', 'Le rapport global n\'est pas encore disponible. Veuillez choisir "Rapport d\'exécution".');
        }

        // Filename and Storage
        $fileName = 'Rapport_' . Str::slug($validated['titre']) . '_' . time() . '.pdf';
        $path = 'rapports/' . date('Y') . '/' . $fileName;

        Storage::disk('public')->put($path, $content); // Store in public disk to be accessible if needed

        // Save to DB
        Rapport::create([
            'titre' => $validated['titre'],
            'path' => $path,
            'annee' => date('Y'),
            'type' => $validated['type'],
            'user_id' => Auth::id(),
            'recommandation_id' => $validated['recommandation_id'] ?? null,
            'description' => $validated['description'],
        ]);

        return redirect()->route('point_focal.rapports.index')->with('success', 'Rapport généré et archivé avec succès.');
    }

    /**
     * Télécharger le rapport
     */
    public function show(Rapport $rapport)
    {
        // Vérification des droits d'accès si nécessaire
        // Pour l'instant, tout utilisateur connecté peut voir les rapports (sauf cabinet ministre exclu via middleware/routes)
        
        if (!Storage::disk('public')->exists($rapport->path)) {
            abort(404, 'Fichier introuvable.');
        }

        return Storage::disk('public')->download($rapport->path, $rapport->titre . '.pdf');
    }

    /**
     * Supprimer un rapport
     */
    public function destroy(Rapport $rapport)
    {
        // Seul le propriétaire (celui qui a généré) peut supprimer
        if ($rapport->user_id !== Auth::id()) {
            abort(403, 'Vous ne pouvez supprimer que les rapports que vous avez générés.');
        }

        // Suppression du fichier physique
        if (Storage::disk('public')->exists($rapport->path)) {
            Storage::disk('public')->delete($rapport->path);
        }

        // Suppression en base
        $rapport->delete();

        return redirect()->route('point_focal.rapports.index')->with('success', 'Rapport supprimé avec succès.');
    }
}

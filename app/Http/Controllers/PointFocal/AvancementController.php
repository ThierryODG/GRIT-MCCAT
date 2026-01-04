<?php

namespace App\Http\Controllers\PointFocal;

use App\Http\Controllers\Controller;
use App\Models\PlanAction;
use App\Models\Recommandation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AvancementController extends Controller
{
    /**
     * Liste des dossiers (Recommandations) en cours d'exécution
     */
    public function index()
    {
        // On récupère les recommandations assignées au Point Focal connecté
        // et qui sont dans une phase d'exécution (ou validées par IG pour commencer)
        $recommandations = Recommandation::where('point_focal_id', Auth::id())
            ->whereIn('statut', [
                Recommandation::STATUT_PLAN_VALIDE_IG,
                Recommandation::STATUT_EN_EXECUTION,
                Recommandation::STATUT_EXECUTION_TERMINEE,
                Recommandation::STATUT_DEMANDE_CLOTURE
            ])
            ->with(['plansAction', 'its:id,name']) // Eager load pour calculer la progression et groupement
            ->orderBy('updated_at', 'desc')
            ->paginate(10);

        // Calcul des statistiques pour le dashboard
        $total = $recommandations->total();
        $enCours = Recommandation::where('point_focal_id', Auth::id())
            ->where('statut', Recommandation::STATUT_EN_EXECUTION)->count();
        $termines = Recommandation::where('point_focal_id', Auth::id())
            ->whereIn('statut', [Recommandation::STATUT_EXECUTION_TERMINEE, Recommandation::STATUT_DEMANDE_CLOTURE])->count();

        return view('point_focal.avancement.index', compact('recommandations', 'total', 'enCours', 'termines'));
    }

    /**
     * Interface d'exécution (Stepper) pour une recommandation
     */
    public function show(Recommandation $recommandation)
    {
        // Vérifications
        if ($recommandation->point_focal_id !== Auth::id()) {
            abort(403, 'Ce dossier ne vous est pas assigné.');
        }

        if (!in_array($recommandation->statut, [
            Recommandation::STATUT_PLAN_VALIDE_IG,
            Recommandation::STATUT_EN_EXECUTION,
            Recommandation::STATUT_EXECUTION_TERMINEE
        ])) {
             // Si déjà clôturé ou en demande, on peut rediriger ou afficher en lecture seule (à voir)
             // Pour l'instant on laisse l'accès mais on bloquera les modifs dans la vue si nécessaire
        }

        $recommandation->load(['plansAction.preuvesExecution']);
        
        // Calcul de la progression globale
        $totalActions = $recommandation->plansAction->count();
        $completedActions = $recommandation->plansAction->where('statut_execution', 'termine')->count();
        $globalProgress = $totalActions > 0 ? round(($completedActions / $totalActions) * 100) : 0;

        return view('point_focal.avancement.show', compact('recommandation', 'globalProgress'));
    }

    /**
     * Mettre à jour une action spécifique (API / Ajax)
     */
    public function updateAction(Request $request, PlanAction $planAction)
    {
        // Vérifications
        if ($planAction->point_focal_id !== Auth::id()) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        $validated = $request->validate([
            'statut_execution' => 'required|in:non_demarre,en_cours,termine',
            'commentaire_avancement' => 'nullable|string|max:1000',
            'preuves.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx|max:10240', // 10MB max
            'preuves_descriptions.*' => 'nullable|string|max:255',
        ]);

        // Validation supplémentaire : Si on termine l'action, vérifier qu'il y a des preuves
        if ($validated['statut_execution'] === 'termine') {
            $hasExistingProofs = $planAction->preuvesExecution()->exists();
            $hasNewProofs = $request->hasFile('preuves');

            if (!$hasExistingProofs && !$hasNewProofs) {
                return response()->json([
                    'errors' => ['statut_execution' => ['Impossible de terminer l\'action sans joindre au moins une preuve (document, photo, etc.).']]
                ], 422);
            }
        }

        // Gestion des preuves d'exécution
        if ($request->hasFile('preuves')) {
            foreach ($request->file('preuves') as $index => $file) {
                $path = $file->store('preuves_execution', 'public');
                $originalName = $file->getClientOriginalName();
                
                \App\Models\PreuveExecution::create([
                    'plan_action_id' => $planAction->id,
                    'file_path' => $path,
                    'file_name' => $originalName,
                    'description' => "Preuve jointe le " . now()->format('d/m/Y H:i'),
                ]);
            }
        }

        // Mise à jour de l'action
        $planAction->update([
            'statut_execution' => $validated['statut_execution'],
            'commentaire_avancement' => $validated['commentaire_avancement'],
            // On met à jour le pourcentage individuel pour garder la cohérence (100% si terminé, 0 sinon)
            'pourcentage_avancement' => $validated['statut_execution'] === 'termine' ? 100 : ($validated['statut_execution'] === 'en_cours' ? 50 : 0),
        ]);

        // Mise à jour du statut global de la recommandation si c'est le début
        if ($planAction->recommandation->statut === Recommandation::STATUT_PLAN_VALIDE_IG) {
            $planAction->recommandation->update(['statut' => Recommandation::STATUT_EN_EXECUTION]);
        }

        // Recalculer la progression globale
        $recommandation = $planAction->recommandation;

        // Réajustement intelligent des échéances si une action est terminée
        if ($validated['statut_execution'] === 'termine') {
            try {
                \App\Services\ExecutionSchedulingService::readjustSchedules($recommandation);
            } catch (\Throwable $e) {
                logger()->error('Dynamic rescheduling failed: ' . $e->getMessage());
            }
        }

        $totalActions = $recommandation->plansAction()->count();
        $completedActions = $recommandation->plansAction()->where('statut_execution', 'termine')->count();
        $globalProgress = $totalActions > 0 ? round(($completedActions / $totalActions) * 100) : 0;

        // Si 100%, on peut passer en EXECUTION_TERMINEE (optionnel, ou attendre la demande de clôture)
        if ($globalProgress == 100 && $recommandation->statut !== Recommandation::STATUT_EXECUTION_TERMINEE) {
             $recommandation->update(['statut' => Recommandation::STATUT_EXECUTION_TERMINEE]);
        } elseif ($globalProgress < 100 && $recommandation->statut === Recommandation::STATUT_EXECUTION_TERMINEE) {
             // Si on revient en arrière
             $recommandation->update(['statut' => Recommandation::STATUT_EN_EXECUTION]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Action mise à jour et échéances réajustées.',
            'action_statut' => $planAction->statut_execution,
            'global_progress' => $globalProgress,
            'can_close' => $globalProgress == 100,
            'updated_actions' => $recommandation->plansAction()->with('preuvesExecution')->get() 
        ]);
    }

    /**
     * Supprimer une preuve d'exécution
     */
    public function deletePreuve(\App\Models\PreuveExecution $preuve)
    {
        // Vérification des droits : SEUL le point focal propriétaire de l'action peut supprimer
        if ($preuve->planAction->point_focal_id !== Auth::id()) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        // Suppression du fichier
        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($preuve->file_path)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($preuve->file_path);
        }

        $preuve->delete();

        return response()->json(['success' => true, 'message' => 'Preuve supprimée.']);
    }

    /**
     * Télécharger une preuve (Accessible aux superviseurs)
     */
    public function downloadPreuve(\App\Models\PreuveExecution $preuve)
    {
        // TODO: Vérifier les permissions (ex: Point Focal, Superviseur)
        // Pour l'instant, on laisse ouvert aux authentifiés comme demandé
        
        if (!Storage::disk('public')->exists($preuve->file_path)) {
            abort(404, 'Fichier non trouvé');
        }

        return Storage::disk('public')->download($preuve->file_path, $preuve->file_name);
    }

    public function downloadReport(Recommandation $recommandation)
    {
        // Chargement complet des relations pour le rapport détaillé
        $recommandation->load([
            'structure',
            'its', 
            'inspecteurGeneral', 
            'responsable', 
            'pointFocal',
            'plansAction.preuvesExecution',
            'plansAction.preuvesExecution',
            'commentaires.auteur' // Pour l'historique des échanges
        ]);

        $data = [
            'recommandation' => $recommandation,
            'date_generation' => now()->translatedFormat('d F Y à H:i'),
            'auteur_generation' => Auth::user()->name,
            'logo_path' => public_path('images/logo-mccat-300x300.jpg'),
        ];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('point_focal.avancement.rapport_pdf', $data);
        
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);

        return $pdf->download('Rapport_Execution_' . $recommandation->reference . '.pdf');
    }

    /**
     * Demander la clôture du dossier
     */
    public function demanderCloture(Request $request, Recommandation $recommandation)
    {
        if ($recommandation->point_focal_id !== Auth::id()) {
            abort(403);
        }

        // Vérifier que tout est terminé et que chaque action terminée a une preuve (Optionnel selon règle de gestion)
        // Pour l'instant on vérifie juste le statut.
        $totalActions = $recommandation->plansAction()->count();
        $completedActions = $recommandation->plansAction()->where('statut_execution', 'termine')->count();
        
        if ($totalActions === 0 || $completedActions < $totalActions) {
            return back()->with('error', 'Toutes les actions doivent être terminées avant de demander la clôture.');
        }

        $recommandation->update([
            'statut' => Recommandation::STATUT_DEMANDE_CLOTURE,
            // 'commentaire_demande_cloture' => $request->input('commentaire_cloture'), // Obsolète, utiliser Commentaire
        ]);

        return redirect()->route('point_focal.avancement.index')
            ->with('success', 'La demande de clôture a été envoyée avec succès.');
    }

    /**
     * Envoyer un rappel (via Commentaire)
     */
    public function rappel(Request $request, Recommandation $recommandation)
    {
        if ($recommandation->point_focal_id !== Auth::id()) {
            abort(403, 'Action non autorisée.');
        }

        $validated = $request->validate([
            'destinataire' => 'required|in:responsable,inspecteur_general',
            'message' => 'nullable|string|max:1000',
        ]);

        // Créer le commentaire de type "rappel"
        $recommandation->commentaires()->create([
            'user_id' => Auth::id(),
            'destinataire_role' => $validated['destinataire'],
            'contenu' => $validated['message'] ?? 'Rappel envoyé.',
            'type' => 'rappel',
        ]);

        // TODO: Envoyer une notification réelle (Email/DB Notification)

        $destinataireLabel = match($validated['destinataire']) {
            'responsable' => 'Responsable',
            'inspecteur_general' => 'Inspecteur Général',
        };

        return back()->with('success', "Rappel envoyé avec succès au {$destinataireLabel}.");
    }
}

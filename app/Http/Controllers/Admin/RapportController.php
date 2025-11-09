<?php

namespace App\Http\Controllers\ITS;

use App\Http\Controllers\Controller;
use App\Models\Recommandation;
use App\Models\PlanAction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RapportController extends Controller
{
    /**
     * Afficher la page principale des rapports
     */
    public function index()
    {
        $filters = $this->getDefaultFilters();
        $stats = $this->getRapportStats($filters);

        return view('its.rapports.index', compact('filters', 'stats'));
    }

    /**
     * Générer un rapport avec filtres
     */
    public function generer(Request $request)
    {
        $request->validate([
            'date_debut' => 'nullable|date',
            'date_fin' => 'nullable|date|after_or_equal:date_debut',
            'statut' => 'nullable|string',
            'priorite' => 'nullable|string',
            'its_id' => 'nullable|integer|exists:its,id',
        ]);

        $filters = $request->only(['date_debut', 'date_fin', 'statut', 'priorite', 'its_id']);
        $stats = $this->getRapportStats($filters);
        $donnees = $this->getDonneesRapport($filters);

        return view('its.rapports.index', compact('filters', 'stats', 'donnees'));
    }

    /**
     * Exporter en PDF
     */
    public function exportPDF(Request $request)
    {
        $filters = $request->only(['date_debut', 'date_fin', 'statut', 'priorite', 'its_id']);
        $stats = $this->getRapportStats($filters);
        $donnees = $this->getDonneesRapport($filters);

        $pdf = app('dompdf.wrapper');

        return $pdf->loadView('its.rapports.pdf', compact('stats', 'donnees', 'filters'))
                  ->download('rapport-' . now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Exporter en Excel
     */
    public function exportExcel(Request $request)
    {
        $filters = $request->only(['date_debut', 'date_fin', 'statut', 'priorite', 'its_id']);
        $donnees = $this->getDonneesRapport($filters);

        return response()->streamDownload(function () use ($donnees) {
            echo $this->generateExcelContent($donnees);
        }, 'rapport-' . now()->format('Y-m-d') . '.csv');
    }

    /**
     * Obtenir les statistiques du rapport
     */
    private function getRapportStats(array $filters): array
    {
        $query = Recommandation::query();
        $this->applyFilters($query, $filters);

        return [
            'total_recommandations' => $query->count(),
            'par_statut' => $this->getStatsParStatut($filters),
            'par_priorite' => $this->getStatsParPriorite($filters),
            'taux_avancement' => $this->getTauxAvancement($filters),
            'moyenne_temps_traitement' => $this->getMoyenneTempsTraitement($filters),
            'recommandations_urgentes' => $this->getRecommandationsUrgentes($filters),
        ];
    }

    /**
     * Obtenir les données détaillées du rapport
     */
    private function getDonneesRapport(array $filters)
    {
        $query = Recommandation::with(['its', 'inspecteurGeneral', 'planActions'])
                    ->withCount('planActions');

        $this->applyFilters($query, $filters);

        return $query->orderBy('created_at', 'desc')->get();
    }

    /**
     * Appliquer les filtres à la requête
     */
    private function applyFilters($query, array $filters): void
    {
        if (!empty($filters['date_debut'])) {
            $query->whereDate('created_at', '>=', $filters['date_debut']);
        }

        if (!empty($filters['date_fin'])) {
            $query->whereDate('created_at', '<=', $filters['date_fin']);
        }

        if (!empty($filters['statut'])) {
            $query->where('statut', $filters['statut']);
        }

        if (!empty($filters['priorite'])) {
            $query->where('priorite', $filters['priorite']);
        }

        if (!empty($filters['its_id'])) {
            $query->where('its_id', $filters['its_id']);
        }
    }

    /**
     * Statistiques par statut
     */
    private function getStatsParStatut(array $filters): array
    {
        $query = Recommandation::query();
        $this->applyFilters($query, $filters);

        return $query->select('statut', DB::raw('COUNT(*) as total'))
                    ->groupBy('statut')
                    ->pluck('total', 'statut')
                    ->toArray();
    }

    /**
     * Statistiques par priorité
     */
    private function getStatsParPriorite(array $filters): array
    {
        $query = Recommandation::query();
        $this->applyFilters($query, $filters);

        return $query->select('priorite', DB::raw('COUNT(*) as total'))
                    ->groupBy('priorite')
                    ->pluck('total', 'priorite')
                    ->toArray();
    }

    /**
     * Taux d'avancement global
     */
    private function getTauxAvancement(array $filters): float
    {
        $query = Recommandation::query();
        $this->applyFilters($query, $filters);

        $total = $query->count();
        $terminees = clone $query;
        $terminees = $terminees->whereIn('statut', ['cloturee', 'terminee'])->count();

        return $total > 0 ? round(($terminees / $total) * 100, 1) : 0;
    }

    /**
     * Moyenne du temps de traitement
     */
    private function getMoyenneTempsTraitement(array $filters): string
    {
        $query = Recommandation::whereIn('statut', ['cloturee', 'terminee']);
        $this->applyFilters($query, $filters);

        $moyenne = $query->selectRaw('AVG(EXTRACT(EPOCH FROM (updated_at - created_at))/86400) as moyenne_jours')
                        ->value('moyenne_jours');

        return $moyenne ? round($moyenne, 1) . ' jours' : 'N/A';
    }

    /**
     * Recommandations urgentes en cours
     */
    private function getRecommandationsUrgentes(array $filters): int
    {
        $query = Recommandation::where('priorite', 'haute')
                    ->whereNotIn('statut', ['cloturee', 'terminee']);

        $this->applyFilters($query, $filters);

        return $query->count();
    }

    /**
     * Filtres par défaut (30 derniers jours)
     */
    private function getDefaultFilters(): array
    {
        return [
            'date_debut' => now()->subDays(30)->format('Y-m-d'),
            'date_fin' => now()->format('Y-m-d'),
            'statut' => '',
            'priorite' => '',
            'its_id' => '',
        ];
    }

    /**
     * Générer le contenu Excel/CSV
     */
    private function generateExcelContent($donnees): string
    {
        $output = fopen('php://output', 'w');

        // En-têtes
        fputcsv($output, [
            'ID', 'Titre', 'Statut', 'Priorité', 'ITS',
            'Date Création', 'Date Limite', 'Nb Plans Action'
        ]);

        // Données
        foreach ($donnees as $recommandation) {
            fputcsv($output, [
                $recommandation->id,
                $recommandation->titre,
                $recommandation->statut,
                $recommandation->priorite,
                $recommandation->its->name ?? 'N/A',
                $recommandation->created_at->format('d/m/Y'),
                $recommandation->date_limite?->format('d/m/Y') ?? 'N/A',
                $recommandation->plan_actions_count
            ]);
        }

        fclose($output);
        return ob_get_clean();
    }
}

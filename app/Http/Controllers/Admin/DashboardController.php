<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Recommandation;
use App\Models\PlanAction;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = $this->getStats();
        $charts = $this->getChartsData();
        $recentActivity = $this->getRecentActivity();
        $topStructures = $this->getTopStructures();

        return view('admin.dashboard', compact('stats', 'charts', 'recentActivity', 'topStructures'));
    }

    private function getStats(): array
    {
        return [
            'users' => [
                'total' => User::count(),
                'monthly' => User::whereMonth('created_at', now()->month)->count(),
            ],
            'recommandations' => [
                'total' => Recommandation::count(),
                'en_retard' => Recommandation::where('date_limite', '<', now())
                    ->whereNotIn('statut', ['cloturee', 'terminee'])
                    ->count(),
                'urgentes' => Recommandation::where('priorite', 'haute')
                    ->whereNotIn('statut', ['cloturee', 'terminee'])
                    ->count(),
            ],
            'validation' => [
                'taux' => $this->calculateTauxValidation(),
            ]
        ];
    }

    private function getChartsData(): array
    {
        return [
            'statut_repartition' => $this->getStatutRepartition(),
            'monthly_evolution' => $this->getMonthlyEvolution(),
            'users_by_role' => $this->getUsersByRole(),
        ];
    }

    private function getStatutRepartition(): array
    {
        return Recommandation::select('statut', DB::raw('COUNT(*) as total'))
            ->groupBy('statut')
            ->pluck('total', 'statut')
            ->toArray();
    }

    private function getMonthlyEvolution(): array
    {
        return Recommandation::select(
                DB::raw("TO_CHAR(created_at, 'YYYY-MM') as mois"),
                DB::raw('COUNT(*) as total')
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('mois')
            ->orderBy('mois')
            ->pluck('total', 'mois')
            ->toArray();
    }

    private function getUsersByRole(): array
    {
        return User::with('role:id,nom')
            ->select('role_id', DB::raw('COUNT(*) as total'))
            ->groupBy('role_id')
            ->get()
            ->mapWithKeys(fn($item) => [$item->role->nom ?? 'Non assigné' => $item->total])
            ->toArray();
    }

    private function getRecentActivity()
    {
        return Recommandation::with([
                'its:id,name,email',
                'inspecteurGeneral:id,name',
                'structure:id,nom'  // CORRIGÉ : ajout de la structure
            ])
            ->latest()
            ->take(5)
            ->get();
    }

    private function getTopStructures()
    {
        // CORRIGÉ : Utilisation de structure_id au lieu de direction
        return Recommandation::with(['structure:id,nom'])  // CORRIGÉ : structure au lieu de its->direction
            ->select('structure_id', DB::raw('COUNT(*) as total'))
            ->groupBy('structure_id')
            ->orderByDesc('total')
            ->take(5)
            ->get()
            ->map(function($item) {
                return [
                    'structure' => $item->structure,
                    'total' => $item->total
                ];
            });
    }

    private function calculateTauxValidation(): float
    {
        $soumises = Recommandation::where('statut', 'soumise_ig')->count();  // CORRIGÉ : statut correct
        $validees = Recommandation::whereIn('statut', ['validee_ig', 'en_analyse_structure'])->count();

        return $soumises > 0 ? round(($validees / $soumises) * 100, 1) : 0;
    }
}

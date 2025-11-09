<?php

namespace App\Http\Controllers\ITS;

use App\Models\Recommandation;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // Statistiques principales
        $totalRecommandations = Recommandation::where('its_id', $userId)->count();
        $brouillonsCount = Recommandation::where('its_id', $userId)->where('statut', 'brouillon')->count();
        $soumisesCount = Recommandation::where('its_id', $userId)->where('statut', 'soumise_ig')->count();
        $enRetardCount = Recommandation::where('its_id', $userId)->enRetard()->count();

        // Répartition par statut
        $statuts = Recommandation::where('its_id', $userId)
            ->selectRaw('statut, COUNT(*) as count')
            ->groupBy('statut')
            ->get()
            ->map(function ($item) {
                $colors = [
                    'brouillon' => 'bg-yellow-100 text-yellow-800',
                    'soumise_ig' => 'bg-blue-100 text-blue-800',
                    'validee_ig' => 'bg-green-100 text-green-800',
                    'rejetee_ig' => 'bg-red-100 text-red-800',
                    'transmise_structure' => 'bg-indigo-100 text-indigo-800',
                    'point_focal_assigne' => 'bg-purple-100 text-purple-800',
                    'plan_en_redaction' => 'bg-pink-100 text-pink-800',
                    'plan_soumis_responsable' => 'bg-orange-100 text-orange-800',
                    'plan_valide_responsable' => 'bg-teal-100 text-teal-800',
                    'plan_soumis_ig' => 'bg-cyan-100 text-cyan-800',
                    'plan_valide_ig' => 'bg-emerald-100 text-emerald-800',
                    'plan_rejete_ig' => 'bg-rose-100 text-rose-800',
                    'en_execution' => 'bg-sky-100 text-sky-800',
                    'execution_terminee' => 'bg-lime-100 text-lime-800',
                    'demande_cloture' => 'bg-amber-100 text-amber-800',
                    'cloturee' => 'bg-gray-100 text-gray-800'
                ];

                $labels = [
                    'brouillon' => 'Brouillon',
                    'soumise_ig' => 'Soumise à l\'IG',
                    'validee_ig' => 'Validée par l\'IG',
                    'rejetee_ig' => 'Rejetée par l\'IG',
                    'transmise_structure' => 'Transmise Structure',
                    'point_focal_assigne' => 'Point Focal assigné',
                    'plan_en_redaction' => 'Plan en rédaction',
                    'plan_soumis_responsable' => 'Plan soumis Responsable',
                    'plan_valide_responsable' => 'Plan validé Responsable',
                    'plan_soumis_ig' => 'Plan soumis IG',
                    'plan_valide_ig' => 'Plan validé IG',
                    'plan_rejete_ig' => 'Plan rejeté IG',
                    'en_execution' => 'En exécution',
                    'execution_terminee' => 'Exécution terminée',
                    'demande_cloture' => 'Demande clôture',
                    'cloturee' => 'Clôturée'
                ];

                return (object)[
                    'statut' => $item->statut,
                    'count' => $item->count,
                    'color_class' => $colors[$item->statut] ?? 'bg-gray-100 text-gray-800',
                    'label' => $labels[$item->statut] ?? $item->statut
                ];
            });

        // Répartition par priorité
        $priorites = Recommandation::where('its_id', $userId)
            ->selectRaw('priorite, COUNT(*) as count')
            ->groupBy('priorite')
            ->get();

        // Recommandations récentes
        $recentRecommandations = Recommandation::where('its_id', $userId)
            ->with('structure')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('its.dashboard', compact(
            'totalRecommandations',
            'brouillonsCount',
            'soumisesCount',
            'enRetardCount',
            'statuts',
            'priorites',
            'recentRecommandations'
        ));
    }
}

<?php

namespace App\Services;

use App\Models\Recommandation;
use App\Models\PlanAction;
use Carbon\Carbon;

class ExecutionSchedulingService
{
    /**
     * Initialise les échéances des actions d'un plan fraîchement validé.
     * Répartit équitablement le temps entre la date de début et la date de fin prévue.
     */
    public static function initializeSchedules(Recommandation $recommandation)
    {
        $startDate = $recommandation->date_debut_prevue ?: now();
        $endDate = $recommandation->date_fin_prevue ?: ($recommandation->date_limite ?: now()->addMonths(1));
        
        $actions = $recommandation->plansAction()->orderBy('id', 'asc')->get();
        $count = $actions->count();

        if ($count === 0) {
            return;
        }

        $totalDays = max(1, $startDate->diffInDays($endDate));
        $daysPerAction = floor($totalDays / $count);
        $extraDays = $totalDays % $count;

        $currentStart = Carbon::parse($startDate);

        foreach ($actions as $index => $action) {
            $duration = $daysPerAction + ($index < $extraDays ? 1 : 0);
            
            $actionEndDate = $currentStart->copy()->addDays($duration);
            
            $action->update([
                'date_debut_prevue' => $currentStart,
                'date_fin_prevue' => $actionEndDate,
            ]);

            $currentStart = $actionEndDate->copy();
        }
    }

    /**
     * Réajuste les échéances des actions restantes après qu'une action a été terminée.
     * Cette logique est "intelligente" et s'adapte à la vitesse réelle d'exécution.
     */
    public static function readjustSchedules(Recommandation $recommandation)
    {
        // 1. Récupérer les actions non terminées
        $actionsRestantes = $recommandation->plansAction()
            ->where('statut_execution', '!=', 'termine')
            ->orderBy('id', 'asc')
            ->get();

        if ($actionsRestantes->isEmpty()) {
            return;
        }

        // 2. Définir le nouveau point de départ (Maintenant)
        $now = now();
        
        // 3. Définir la date de fin cible (On essaie de tenir le plan initial)
        $targetEndDate = $recommandation->date_fin_prevue ?: $recommandation->date_limite;

        // Si on est déjà en retard par rapport au plan initial, on peut éventuellement "déborder" 
        // vers la date_limite (ITS) pour donner un peu d'air, tout en restant dans les limites absolues.
        if ($now->gt($targetEndDate) && $recommandation->date_limite && $now->lt($recommandation->date_limite)) {
            $targetEndDate = $recommandation->date_limite;
        }

        $count = $actionsRestantes->count();
        $totalDaysRemaining = max(1, $now->diffInDays($targetEndDate));
        
        // Si la date limite est déjà dépassée, on donne 1 jour par action par défaut (mode urgence)
        if ($now->gt($targetEndDate)) {
            $totalDaysRemaining = $count; 
        }

        $daysPerAction = floor($totalDaysRemaining / $count);
        $extraDays = $totalDaysRemaining % $count;

        $currentStart = $now->copy();

        foreach ($actionsRestantes as $index => $action) {
            $duration = max(1, $daysPerAction + ($index < $extraDays ? 1 : 0));
            
            $actionEndDate = $currentStart->copy()->addDays($duration);
            
            $action->update([
                'date_debut_prevue' => $currentStart,
                'date_fin_prevue' => $actionEndDate,
            ]);

            $currentStart = $actionEndDate->copy();
        }
    }
}

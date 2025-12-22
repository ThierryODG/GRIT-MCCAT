<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PlanAction;
use App\Notifications\ActionDeadlineReminder;

class CheckActionDeadlines extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-action-deadlines';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Vérifier les échéances des actions et envoyer des rappels aux Points Focaux';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = now()->startOfDay();
        $inTwoDays = now()->addDays(2)->startOfDay();

        // 1. Actions dont l'échéance est dans 2 jours (Rappel normal)
        $approachingActions = PlanAction::where('statut_execution', '!=', 'termine')
            ->whereDate('date_fin_prevue', $inTwoDays)
            ->with(['recommandation', 'pointFocal'])
            ->get();

        foreach ($approachingActions as $action) {
            if ($action->pointFocal) {
                $action->pointFocal->notify(new ActionDeadlineReminder($action, false));
                $this->info("Rappel envoyé pour l'action #{$action->id} à {$action->pointFocal->name}");
            }
        }

        // 2. Actions dont l'échéance est aujourd'hui ou passée (Urgence)
        $urgentActions = PlanAction::where('statut_execution', '!=', 'termine')
            ->whereDate('date_fin_prevue', '<=', $today)
            ->with(['recommandation', 'pointFocal'])
            ->get();

        foreach ($urgentActions as $action) {
            if ($action->pointFocal) {
                $action->pointFocal->notify(new ActionDeadlineReminder($action, true));
                $this->warn("Alerte URGENTE envoyée pour l'action #{$action->id} à {$action->pointFocal->name}");
            }
        }

        $this->info('Vérification des échéances terminée.');
    }
}

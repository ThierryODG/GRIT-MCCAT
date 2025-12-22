<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Models\Recommandation;

class RecommandationRejetee extends Notification
{
    use Queueable;

    protected $recommandation;
    protected $motif;
    protected $etape; // 'ig_validation', 'plan_validation', etc.

    public function __construct(Recommandation $recommandation, $motif, $etape = 'default')
    {
        $this->recommandation = $recommandation;
        $this->motif = $motif;
        $this->etape = $etape;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $message = match($this->etape) {
            'ig_validation' => "Recommandation rejetée par l'IG.",
            'plan_validation_ig' => "Plans d'actions rejetés par l'IG.",
            'plan_validation_responsable' => "Plan d'action rejeté par le Responsable.",
            default => "Votre soumission a été rejetée."
        };

        return [
            'type' => 'recommandation_rejetee',
            'recommandation_id' => $this->recommandation->id,
            'message' => $message,
            'motif' => $this->motif,
            'action_url' => $this->getActionUrl(),
            'icon' => 'x-circle'
        ];
    }

    protected function getActionUrl()
    {
        // Redirection contextuelle
        if ($this->etape === 'ig_validation') {
            // C'est l'ITS qui reçoit -> Redirection vers la modification
            return route('its.recommandations.edit', $this->recommandation->id);
        } elseif ($this->etape === 'plan_validation_ig') {
            // C'est le Responsable qui reçoit -> Redirection vers la saisie des plans
            // (Supposons une route existante ou dashboard pour l'instant)
            return route('responsable.validation_plans.index'); // ou show
        }

        return route('dashboard'); 
    }
}

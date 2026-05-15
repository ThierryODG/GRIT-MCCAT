<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Recommandation;
use App\Models\User;

class ManualReminderReceived extends Notification
{
    use Queueable;

    protected $recommandation;
    protected $sender;
    protected $message;

    public function __construct(Recommandation $recommandation, User $sender, $message = null)
    {
        $this->recommandation = $recommandation;
        $this->sender = $sender;
        $this->message = $message;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'manual_reminder',
            'recommandation_id' => $this->recommandation->id,
            'message' => "Rappel de " . $this->sender->name . " pour : " . $this->recommandation->titre,
            'description' => $this->message ?? "Un rappel a été envoyé concernant cette recommandation.",
            'action_url' => $this->getActionUrl($notifiable),
            'icon' => 'clock-rotate-left'
        ];
    }

    protected function getActionUrl($user): string
    {
        if ($user->hasRole('point_focal')) {
            return route('point_focal.recommandations.show', $this->recommandation->id);
        } elseif ($user->hasRole('responsable')) {
            $validationStatuts = [
                Recommandation::STATUT_PLAN_SOUMIS_RESPONSABLE,
                Recommandation::STATUT_PLAN_VALIDE_RESPONSABLE,
                Recommandation::STATUT_PLAN_REJETE_RESPONSABLE,
                Recommandation::STATUT_PLAN_SOUMIS_IG,
                Recommandation::STATUT_PLAN_REJETE_IG,
            ];

            if (in_array($this->recommandation->statut, $validationStatuts)) {
                return route('responsable.validation_plans.dossier', $this->recommandation->id);
            }
            return route('responsable.suivi.show', $this->recommandation->id);
        } elseif ($user->hasRole('inspecteur_general')) {
            return route('inspecteur_general.recommandations.show', $this->recommandation->id);
        } elseif ($user->hasRole('its')) {
            return route('its.recommandations.show', $this->recommandation->id);
        }
        return '#';
    }
}

<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Recommandation;

class RecommandationSoumise extends Notification
{
    use Queueable;

    protected $recommandation;

    /**
     * Create a new notification instance.
     */
    public function __construct(Recommandation $recommandation)
    {
        $this->recommandation = $recommandation;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'recommandation_soumise',
            'recommandation_id' => $this->recommandation->id,
            'message' => "Nouvelle recommandation soumise par " . ($this->recommandation->its->name ?? 'ITS'),
            'action_url' => route('inspecteur_general.plan_actions.recommandation_dossier', $this->recommandation->id),
            'icon' => 'check-circle'
        ];
    }
}

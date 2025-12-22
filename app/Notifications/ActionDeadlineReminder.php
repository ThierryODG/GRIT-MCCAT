<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\PlanAction; // Added use statement for PlanAction
use Illuminate\Support\Str; // Added use statement for Str

class ActionDeadlineReminder extends Notification
{
    use Queueable;

    protected $action;
    protected $isUrgent;

    /**
     * Create a new notification instance.
     */
    public function __construct(PlanAction $action, bool $isUrgent = false)
    {
        $this->action = $action;
        $this->isUrgent = $isUrgent;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $subject = $this->isUrgent 
            ? "[URGENT] Échéance dépassée : " . $this->action->action 
            : "Rappel : Échéance approche pour l'action " . $this->action->action;

        return (new MailMessage)
                    ->subject($subject)
                    ->greeting('Bonjour ' . $notifiable->name . ',')
                    ->line($this->isUrgent 
                        ? 'L\'échéance prévue pour l\'action suivante est dépassée ou très proche.' 
                        : 'L\'échéance prévue pour l\'action suivante approche.')
                    ->line('Action : ' . $this->action->action)
                    ->line('Dossier : ' . $this->action->recommandation->reference)
                    ->line('Échéance calculée : ' . $this->action->date_fin_prevue->format('d/m/Y'))
                    ->action('Gérer l\'exécution', route('point_focal.avancement.show', $this->action->recommandation_id))
                    ->line('Merci de vous organiser pour finaliser cette étape dans les temps.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'recommandation_id' => $this->action->recommandation_id,
            'plan_action_id' => $this->action->id,
            'message' => ($this->isUrgent ? '[URGENT] ' : '') . 'Échéance pour : ' . \Illuminate\Support\Str::limit($this->action->action, 50),
            'type' => 'deadline_reminder',
            'is_urgent' => $this->isUrgent,
            'action_url' => route('point_focal.avancement.show', $this->action->recommandation_id),
            'icon' => 'clock'
        ];
    }
}

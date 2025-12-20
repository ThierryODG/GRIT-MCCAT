<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Recommandation;

class PlanningReviewed extends Notification
{
    use Queueable;

    public $recommandation;
    public $approved;
    public $comment;

    public function __construct(Recommandation $recommandation, bool $approved, ?string $comment = null)
    {
        $this->recommandation = $recommandation;
        $this->approved = $approved;
        $this->comment = $comment;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'planning_reviewed',
            'recommandation_id' => $this->recommandation->id,
            'reference' => $this->recommandation->reference,
            'approved' => $this->approved,
            'comment' => $this->comment,
            'message' => $this->approved ? 'Votre planification a été validée par le Responsable.' : 'Votre planification a été rejetée par le Responsable. Motif : ' . ($this->comment ?? '')
        ];
    }
}

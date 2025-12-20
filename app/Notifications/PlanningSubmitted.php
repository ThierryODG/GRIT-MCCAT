<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Recommandation;

class PlanningSubmitted extends Notification
{
    use Queueable;

    public $recommandation;

    public function __construct(Recommandation $recommandation)
    {
        $this->recommandation = $recommandation;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'planning_submitted',
            'recommandation_id' => $this->recommandation->id,
            'reference' => $this->recommandation->reference,
            'message' => 'La planification a été soumise par le Point Focal.'
        ];
    }
}

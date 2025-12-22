<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Models\Recommandation;

class RecommandationValidee extends Notification
{
    use Queueable;

    protected $recommandation;

    public function __construct(Recommandation $recommandation)
    {
        $this->recommandation = $recommandation;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'recommandation_validee',
            'recommandation_id' => $this->recommandation->id,
            'message' => "Recommandation validée par l'IG : " . $this->recommandation->titre,
            'action_url' => route('responsable.points_focaux.index'), // Ajuster la route si nécessaire
            'icon' => 'check-double'
        ];
    }
}

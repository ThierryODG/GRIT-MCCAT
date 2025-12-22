<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Models\Recommandation;

class PointFocalAssigne extends Notification
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
            'type' => 'point_focal_assigne',
            'recommandation_id' => $this->recommandation->id,
            'message' => "Vous avez été désigné Point Focal pour la recommandation : " . $this->recommandation->titre,
            'action_url' => route('point_focal.recommandations.index'), // Ajuster selon les routes existantes
            'icon' => 'user-plus'
        ];
    }
}

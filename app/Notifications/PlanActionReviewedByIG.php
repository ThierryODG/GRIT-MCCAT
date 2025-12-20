<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\PlanAction;

class PlanActionReviewedByIG extends Notification
{
    use Queueable;

    public $planAction;
    public $approved;
    public $comment;

    public function __construct(PlanAction $planAction, bool $approved, ?string $comment = null)
    {
        $this->planAction = $planAction;
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
            'type' => 'plan_action_reviewed_ig',
            'plan_action_id' => $this->planAction->id,
            'recommandation_id' => $this->planAction->recommandation_id,
            'approved' => $this->approved,
            'comment' => $this->comment,
            'message' => $this->approved ? 'Votre plan d\'action a été validé par l\'IG.' : 'Votre plan d\'action a été rejeté par l\'IG. Motif : ' . ($this->comment ?? '')
        ];
    }
}

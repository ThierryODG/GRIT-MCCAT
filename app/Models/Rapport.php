<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Rapport extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'contenu',
        'date_generation',
        'utilisateur_id'
    ];

    protected $casts = [
        'date_generation' => 'datetime',
    ];

    /**
     * Relation avec le plan d'action
     */
    public function planAction()
    {
        return $this->belongsTo(PlanAction::class, 'plan_action_id');
    }
    /**
     * Relation avec l'utilisateur
     */
    public function utilisateur()
    {
        return $this->belongsTo(User::class, 'utilisateur_id');
    }

    // Méthodes métier
    public function generer()
    {
        $this->date_generation = now();
        $this->save();
    }

    public function exporter($format = 'pdf')
    {
        // Logique d'exportation
        return "Rapport exporté en {$format}";
    }
}

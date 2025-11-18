<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PlanAction extends Model
{
    use HasFactory;

    protected $fillable = [
        // Contenu du plan
        'action',

        // Workflow validation
        'statut_validation',
        'validateur_responsable_id',
        'date_validation_responsable',
        'commentaire_validation_responsable',
        'motif_rejet_responsable',
        'validateur_ig_id',
        'date_validation_ig',
        'commentaire_validation_ig',
        'motif_rejet_ig',

        // Exécution
        'statut_execution',
        'pourcentage_avancement',
        'commentaire_avancement',

        // Relations
        'recommandation_id',
        'point_focal_id',
        'responsable_id',
    ];

    protected $casts = [
        'date_validation_responsable' => 'datetime',
        'date_validation_ig' => 'datetime',
        'pourcentage_avancement' => 'integer',
        'delai_mois' => 'integer',
    ];

    // ==================== RELATIONS ====================

    public function recommandation()
    {
        return $this->belongsTo(Recommandation::class);
    }

    public function pointFocal()
    {
        return $this->belongsTo(User::class, 'point_focal_id');
    }

    public function responsable()
    {
        return $this->belongsTo(User::class, 'responsable_id');
    }

    public function validateurResponsable()
    {
        return $this->belongsTo(User::class, 'validateur_responsable_id');
    }

    public function validateurIG()
    {
        return $this->belongsTo(User::class, 'validateur_ig_id');
    }

    // ==================== SCOPES ====================

    public function scopeEnAttenteValidationResponsable($query)
    {
        return $query->where('statut_validation', 'en_attente_responsable')
            ->whereNotNull('action');
    }

    public function scopeEnAttenteValidationIG($query)
    {
        return $query->where('statut_validation', 'en_attente_ig');
    }

    public function scopeValides($query)
    {
        return $query->where('statut_validation', 'valide_ig');
    }

    public function scopeEnExecution($query)
    {
        return $query->where('statut_execution', 'en_cours');
    }

    // ==================== ACCESSEURS ====================

    public function getStatutValidationLabelAttribute()
    {
        $labels = [
            'en_attente_responsable' => 'En attente Responsable',
            'valide_responsable' => 'Validé par le Responsable',
            'rejete_responsable' => 'Rejeté par le Responsable',
            'en_attente_ig' => 'En attente IG',
            'valide_ig' => 'Validé par l\'IG',
            'rejete_ig' => 'Rejeté par l\'IG',
        ];

        return $labels[$this->statut_validation] ?? $this->statut_validation;
    }

    public function getStatutExecutionLabelAttribute()
    {
        $labels = [
            'non_demarre' => 'Non démarré',
            'en_cours' => 'En cours',
            'termine' => 'Terminé'
        ];

        return $labels[$this->statut_execution] ?? $this->statut_execution;
    }

    // ==================== MÉTHODES MÉTIER ====================

    public function estRempli(): bool
    {
        return !empty($this->action);
    }

    public function peutEtreValideParResponsable(): bool
    {
        return $this->statut_validation === 'en_attente_responsable'
            && $this->estRempli();
    }

    public function peutEtreValideParIG(): bool
    {
        return $this->statut_validation === 'en_attente_ig';
    }

    public function peutEtreModifie(): bool
    {
        return in_array($this->statut_validation, [
            'en_attente_responsable',
            'rejete_responsable',
            'rejete_ig'
        ]);
    }

    public function estValide(): bool
    {
        return $this->statut_validation === 'valide_ig';
    }

    public function estEnRetard(): bool
    {
        return $this->recommandation->date_fin_prevue < now()
            && $this->statut_execution !== 'termine';
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Recommandation;

class PlanAction extends Model
{
    use HasFactory;

    protected $fillable = [
        // Contenu du plan
        'action',

        // Workflow validation (moved to recommendation level)
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

        // DÉLAIS ET DATES (AUTOMATISÉS)
        'delai_mois',
        'date_debut_prevue',
        'date_fin_prevue',

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
        'date_debut_prevue' => 'date',
        'date_fin_prevue' => 'date',
    ];

    // ==================== RELATIONS ====================

    public function recommandation()
    {
        return $this->belongsTo(Recommandation::class);
    }

    public function preuvesExecution()
    {
        return $this->hasMany(PreuveExecution::class);
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
        // deprecated: validation is now at recommendation level
        return $query->whereNotNull('action');
    }

    public function scopeEnAttenteValidationIG($query)
    {
        // deprecated: validation is now at recommendation level
        return $query;
    }

    public function scopeValides($query)
    {
        // deprecated: validation is now at recommendation level
        return $query;
    }

    public function scopeEnExecution($query)
    {
        return $query->where('statut_execution', 'en_cours');
    }

    // ==================== ACCESSEURS ====================

    public function getStatutValidationLabelAttribute()
    {
        // Derive a validation label from the parent recommendation (validation is at recommendation level)
        if (!empty($this->motif_rejet_ig)) {
            return 'Rejeté par l\'IG';
        }

        if ($this->recommandation && !empty($this->recommandation->motif_rejet_responsable)) {
            return 'Rejeté par le Responsable';
        }

        $statut = $this->recommandation->statut ?? null;
        $map = [
            'plan_en_redaction' => 'Plan en rédaction',
            'plan_soumis_responsable' => 'En attente Responsable',
            'plan_valide_responsable' => 'Validé par le Responsable',
            'plan_soumis_ig' => 'En attente IG',
            'plan_valide_ig' => 'Validé par l\'IG',
            'plan_rejete_ig' => 'Plan rejeté par l\'IG',
            'plan_rejete_responsable' => 'Plan rejeté par le Responsable',
        ];

        return $map[$statut] ?? 'Statut inconnu';
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
        return $this->recommandation
            && $this->recommandation->statut === Recommandation::STATUT_PLAN_SOUMIS_RESPONSABLE
            && $this->estRempli();
    }

    public function peutEtreValideParIG(): bool
    {
        return $this->recommandation
            && $this->recommandation->statut === Recommandation::STATUT_PLAN_SOUMIS_IG;
    }

    public function peutEtreModifie(): bool
    {
        if (!$this->recommandation) {
            return false;
        }

        return in_array($this->recommandation->statut, [
            Recommandation::STATUT_PLAN_SOUMIS_RESPONSABLE,
            Recommandation::STATUT_PLAN_REJETE_RESPONSABLE,
            Recommandation::STATUT_PLAN_REJETE_IG,
        ]);
    }

    public function estValide(): bool
    {
        return $this->recommandation
            && $this->recommandation->statut === Recommandation::STATUT_PLAN_VALIDE_IG;
    }

    public function estEnRetard(): bool
    {
        return $this->recommandation->date_fin_prevue < now()
            && $this->statut_execution !== 'termine';
    }
}

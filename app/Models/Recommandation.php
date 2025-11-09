<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recommandation extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference',
        'titre',
        'description',
        'structure_id',
        'priorite',
        'date_limite',
        'statut',
        'its_id',
        'inspecteur_general_id',
        'responsable_id',
        'point_focal_id',
        'date_validation_ig',
        'date_cloture',
        'commentaire_ig',
        'motif_rejet_ig',
        'commentaire_demande_cloture',
        'documents_justificatifs',
        'motif_rejet_cloture',
        'commentaire_cloture',
    ];

    protected $casts = [
        'date_limite' => 'date',
        'date_validation_ig' => 'datetime',
        'date_cloture' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ==================== BOOT ====================

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($recommandation) {
            if (empty($recommandation->reference)) {
                $annee = now()->format('Y');
                $count = self::whereYear('created_at', $annee)->count() + 1;
                $recommandation->reference = 'REC-' . $annee . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    // ==================== RELATIONS ====================

    public function its()
    {
        return $this->belongsTo(User::class, 'its_id');
    }

    public function inspecteurGeneral()
    {
        return $this->belongsTo(User::class, 'inspecteur_general_id');
    }

    public function responsable()
    {
        return $this->belongsTo(User::class, 'responsable_id');
    }

    public function pointFocal()
    {
        return $this->belongsTo(User::class, 'point_focal_id');
    }

    public function planAction()
    {
        return $this->hasOne(PlanAction::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function structure()
    {
        return $this->belongsTo(Structure::class);
    }

    // ==================== SCOPES ====================

    public function scopeEnRetard($query)
    {
        return $query->where('date_limite', '<', now())
            ->whereNotIn('statut', ['cloturee', 'execution_terminee']);
    }

    public function scopeParStatut($query, $statut)
    {
        return $query->where('statut', $statut);
    }

    public function scopeParPriorite($query, $priorite)
    {
        return $query->where('priorite', $priorite);
    }

    // ==================== ACCESSEURS ====================

    public function getStatutLabelAttribute()
    {
        $labels = [
            'brouillon' => 'Brouillon',
            'soumise_ig' => 'Soumise à l\'IG',
            'validee_ig' => 'Validée par l\'IG',
            'rejetee_ig' => 'Rejetée par l\'IG',
            'transmise_structure' => 'Transmise à la Structure',
            'point_focal_assigne' => 'Point Focal assigné',
            'plan_en_redaction' => 'Plan en rédaction',
            'plan_soumis_responsable' => 'Plan soumis au Responsable',
            'plan_valide_responsable' => 'Plan validé par le Responsable',
            'plan_soumis_ig' => 'Plan soumis à l\'IG',
            'plan_valide_ig' => 'Plan validé par l\'IG',
            'plan_rejete_ig' => 'Plan rejeté par l\'IG',
            'en_execution' => 'En cours d\'exécution',
            'execution_terminee' => 'Exécution terminée',
            'demande_cloture' => 'Demande de clôture',
            'cloturee' => 'Clôturée'
        ];

        return $labels[$this->statut] ?? $this->statut;
    }

    public function getPrioriteColorAttribute()
    {
        return [
            'basse' => 'green',
            'moyenne' => 'yellow',
            'haute' => 'red'
        ][$this->priorite] ?? 'gray';
    }

    // ==================== MÉTHODES MÉTIER ====================

    public function estEnRetard(): bool
    {
        return $this->date_limite < now()
            && !in_array($this->statut, ['cloturee', 'execution_terminee']);
    }

    public function peutEtreModifiee(): bool
    {
        return in_array($this->statut, ['brouillon', 'rejetee_ig']);
    }

    public function peutEtreSoumise(): bool
    {
        return $this->statut === 'brouillon';
    }

    public function peutEtreValideeParIG(): bool
    {
        return $this->statut === 'soumise_ig';
    }

    public function peutEtreClôturee(): bool
    {
        return $this->statut === 'demande_cloture';
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recommandation extends Model
{
    use HasFactory;

    // Statut constants (centraliser pour éviter les chaînes magiques)
    public const STATUT_BROUILLON = 'brouillon';
    public const STATUT_SOUMISE_IG = 'soumise_ig';
    public const STATUT_VALIDE_IG = 'validee_ig';
    public const STATUT_REJETEE_IG = 'rejetee_ig';
    public const STATUT_TRANSMISE_STRUCTURE = 'transmise_structure';
    public const STATUT_POINT_FOCAL_ASSIGNE = 'point_focal_assigne';
    public const STATUT_PLAN_EN_REDACTION = 'plan_en_redaction';
    public const STATUT_PLAN_SOUMIS_RESPONSABLE = 'plan_soumis_responsable';
    public const STATUT_PLAN_VALIDE_RESPONSABLE = 'plan_valide_responsable';
    public const STATUT_PLAN_REJETE_RESPONSABLE = 'plan_rejete_responsable';
    public const STATUT_PLAN_SOUMIS_IG = 'plan_soumis_ig';
    public const STATUT_PLAN_VALIDE_IG = 'plan_valide_ig';
    public const STATUT_PLAN_REJETE_IG = 'plan_rejete_ig';
    public const STATUT_EN_EXECUTION = 'en_execution';
    public const STATUT_EXECUTION_TERMINEE = 'execution_terminee';
    public const STATUT_DEMANDE_CLOTURE = 'demande_cloture';
    public const STATUT_CLOTUREE = 'cloturee';

    protected $fillable = [
        'reference',
        'titre',
        'description',
        'structure_id',
        'priorite',
        'date_limite',
        'indicateurs',
        'incidence_financiere',
        'delai_mois',
        'date_debut_prevue',
        'date_fin_prevue',
        'statut',
        'its_id',
        'inspecteur_general_id',
        'responsable_id',
        'point_focal_id',
        'date_assignation_pf',
        'date_validation_ig',
        'date_cloture',
        'commentaire_ig',
        'motif_rejet_ig',
        'commentaire_demande_cloture',
        'documents_justificatifs',
        'motif_rejet_cloture',
        'commentaire_cloture',
        'motif_rejet_responsable',
        'commentaire_validation_responsable',
        'date_rejet_responsable',
        'date_validation_responsable',
    ];

    protected $casts = [
        'date_limite' => 'date',
        'date_debut_prevue' => 'date',
        'date_fin_prevue' => 'date',
        'date_validation_ig' => 'datetime',
        'date_validation_responsable' => 'datetime',
        'date_rejet_responsable' => 'datetime',
        'date_cloture' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($recommandation) {
            if (empty($recommandation->reference)) {
                $recommandation->reference = self::generateUniqueReference();
            }
        });
    }

    private static function generateUniqueReference(): string
    {
        $annee = now()->format('Y');
        $lastRecommandation = self::whereYear('created_at', $annee)
            ->orderBy('id', 'desc')
            ->first();

        if ($lastRecommandation && preg_match('/REC-'.$annee.'-(\d+)/', $lastRecommandation->reference, $matches)) {
            $lastNumber = (int) $matches[1];
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        $reference = 'REC-' . $annee . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        $counter = 0;
        while (self::where('reference', $reference)->exists() && $counter < 10) {
            $nextNumber++;
            $reference = 'REC-' . $annee . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
            $counter++;
        }

        return $reference;
    }

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

    public function plansAction()
    {
        return $this->hasMany(PlanAction::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function structure()
    {
        return $this->belongsTo(Structure::class);
    }

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
            'plan_rejete_responsable' => 'Plan rejeté par le Responsable', // UNIQUEMENT UNE FOIS
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

    public function getStatutColorAttribute()
    {
        $map = [
            self::STATUT_BROUILLON => 'bg-gray-100 text-gray-800',
            self::STATUT_SOUMISE_IG => 'bg-blue-100 text-blue-800',
            self::STATUT_VALIDE_IG => 'bg-green-100 text-green-800',
            self::STATUT_REJETEE_IG => 'bg-red-100 text-red-800',
            self::STATUT_TRANSMISE_STRUCTURE => 'bg-indigo-100 text-indigo-800',
            self::STATUT_POINT_FOCAL_ASSIGNE => 'bg-yellow-100 text-yellow-800',
            self::STATUT_PLAN_EN_REDACTION => 'bg-yellow-100 text-yellow-800',
            self::STATUT_PLAN_SOUMIS_RESPONSABLE => 'bg-orange-100 text-orange-800',
            self::STATUT_PLAN_REJETE_RESPONSABLE => 'bg-red-100 text-red-800', // UNIQUEMENT ICI
            self::STATUT_PLAN_VALIDE_RESPONSABLE => 'bg-green-100 text-green-800',
            self::STATUT_PLAN_SOUMIS_IG => 'bg-indigo-100 text-indigo-800',
            self::STATUT_PLAN_VALIDE_IG => 'bg-green-100 text-green-800',
            self::STATUT_PLAN_REJETE_IG => 'bg-red-100 text-red-800',
            self::STATUT_EN_EXECUTION => 'bg-blue-100 text-blue-800',
            self::STATUT_EXECUTION_TERMINEE => 'bg-green-100 text-green-800',
            self::STATUT_DEMANDE_CLOTURE => 'bg-indigo-100 text-indigo-800',
            self::STATUT_CLOTUREE => 'bg-gray-800 text-white',
        ];

        return $map[$this->statut] ?? 'bg-gray-100 text-gray-800';
    }

    public function summarizePlansValidation(): array
    {
        $total = $this->plansAction()->count();
        $enAttente = 0;
        $valide = 0;
        $rejete = 0;

        if ($this->statut === self::STATUT_PLAN_SOUMIS_RESPONSABLE) {
            $enAttente = $total;
        } elseif (in_array($this->statut, [self::STATUT_PLAN_VALIDE_RESPONSABLE, self::STATUT_PLAN_VALIDE_IG])) {
            $valide = $total;
        } elseif (in_array($this->statut, [self::STATUT_PLAN_REJETE_RESPONSABLE, self::STATUT_PLAN_REJETE_IG])) {
            $rejete = $total;
        }

        return [
            'total' => $total,
            'en_attente' => $enAttente,
            'valide' => $valide,
            'rejete' => $rejete,
        ];
    }

    public function computeStatusFromPlans(): string
    {
        $total = $this->plansAction()->count();

        if ($total === 0) {
            return self::STATUT_PLAN_EN_REDACTION;
        }

        if ($this->plansAction()->whereNotNull('motif_rejet_ig')->exists()) {
            return self::STATUT_PLAN_REJETE_IG;
        }

        if (!empty($this->motif_rejet_responsable)) {
            return self::STATUT_PLAN_REJETE_RESPONSABLE;
        }

        if (in_array($this->statut, [self::STATUT_PLAN_VALIDE_RESPONSABLE, self::STATUT_PLAN_VALIDE_IG])) {
            return $this->statut;
        }

        return self::STATUT_PLAN_SOUMIS_RESPONSABLE;
    }

    public function planificationEstComplete(): bool
    {
        return !empty($this->indicateurs)
            && !empty($this->incidence_financiere)
            && !empty($this->delai_mois)
            && !empty($this->date_debut_prevue)
            && !empty($this->date_fin_prevue);
    }

    public function aDesPlansAction(): bool
    {
        return $this->plansAction()->exists();
    }

    public function peutEtreSoumiseParPointFocal(): bool
    {
        $statutsAutorises = [
            self::STATUT_POINT_FOCAL_ASSIGNE,
            self::STATUT_PLAN_EN_REDACTION
        ];

        return in_array($this->statut, $statutsAutorises)
            && $this->planificationEstComplete()
            && $this->aDesPlansAction();
    }

    public function estEditableParPointFocal(): bool
    {
        return in_array($this->statut, [
            self::STATUT_POINT_FOCAL_ASSIGNE,
            self::STATUT_PLAN_EN_REDACTION
        ]);
    }

    // CORRECTION ICI : Maintenant on vérifie le bon statut
    public function aEteRejeteeParResponsable(): bool
    {
        return $this->statut === self::STATUT_PLAN_REJETE_RESPONSABLE
            && !empty($this->motif_rejet_responsable);
    }

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

    public function estEnAttenteValidationResponsable(): bool
    {
        return $this->statut === self::STATUT_PLAN_SOUMIS_RESPONSABLE;
    }

    public function estValideeParResponsable(): bool
    {
        return $this->statut === self::STATUT_PLAN_VALIDE_RESPONSABLE;
    }

    // CORRECTION ICI : Maintenant on utilise le bon statut
    public function estRejeteeParResponsable(): bool
    {
        return $this->statut === self::STATUT_PLAN_REJETE_RESPONSABLE
            && !empty($this->motif_rejet_responsable);
    }

    public function estSoumiseALIG(): bool
    {
        return $this->statut === self::STATUT_PLAN_SOUMIS_IG;
    }

    public function peutEtreValideeMaintenant(): bool
    {
        return $this->estEnAttenteValidationResponsable()
            && $this->planificationEstComplete()
            && $this->aDesPlansAction();
    }

    public function peutEtreRejeteeMaintenant(): bool
    {
        return $this->estEnAttenteValidationResponsable()
            && $this->planificationEstComplete()
            && $this->aDesPlansAction();
    }

    public function estCompletePourValidation(): bool
    {
        return $this->planificationEstComplete() && $this->aDesPlansAction();
    }

    public function getDateValidationResponsableFormateeAttribute()
    {
        if (empty($this->date_validation_responsable)) {
            return null;
        }

        $dt = $this->date_validation_responsable instanceof \DateTimeInterface
            ? $this->date_validation_responsable
            : \Carbon\Carbon::parse($this->date_validation_responsable);

        return $dt->format('d/m/Y à H:i');
    }

    public function getDateRejetResponsableFormateeAttribute()
    {
        if (empty($this->date_rejet_responsable)) {
            return null;
        }

        $dt = $this->date_rejet_responsable instanceof \DateTimeInterface
            ? $this->date_rejet_responsable
            : \Carbon\Carbon::parse($this->date_rejet_responsable);

        return $dt->format('d/m/Y à H:i');
    }
}
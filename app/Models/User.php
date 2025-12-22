<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'structure_id', // ✅ CHANGÉ : 'direction' → 'structure_id'
        'role_id',
        'telephone'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // ==================== MUTATEURS ====================

    public function setTelephoneAttribute($value)
    {
        $this->attributes['telephone'] = $value ? preg_replace('/[^0-9+]/', '', $value) : null;
    }

    // ==================== RELATIONS ====================

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function structure() // ✅ NOUVELLE RELATION
    {
        return $this->belongsTo(Structure::class);
    }

    // Recommandations créées par cet utilisateur (si ITS)
    public function recommandationsCreees()
    {
        return $this->hasMany(Recommandation::class, 'its_id');
    }

    // Recommandations validées par cet utilisateur (si IG)
    public function recommandationsValidees()
    {
        return $this->hasMany(Recommandation::class, 'inspecteur_general_id');
    }

    // Recommandations supervisées (si Responsable)
    public function recommandationsResponsable()
    {
        return $this->hasMany(Recommandation::class, 'responsable_id');
    }

    // Recommandations assignées (si Point Focal)
    public function recommandationsAssignees()
    {
        return $this->hasMany(Recommandation::class, 'point_focal_id');
    }

    // Plans d'action en tant que Point Focal
    public function plansActionPointFocal()
    {
        return $this->hasMany(PlanAction::class, 'point_focal_id');
    }

    // Plans d'action supervisés (si Responsable)
    public function plansActionResponsable()
    {
        return $this->hasMany(PlanAction::class, 'responsable_id');
    }

    // Plans d'action validés par IG
    public function plansActionValides()
    {
        return $this->hasMany(PlanAction::class, 'validateur_ig_id');
    }

    // RELATION SUPPRIMÉE POUR UTILISER LE TRAIT NOTIFIABLE STANDARD
    // public function notifications()
    // {
    //     return $this->hasMany(Notification::class);
    // }

    // ==================== MÉTHODES RÔLES ====================

    public function hasRole($role): bool
    {
        if (is_array($role)) {
            return $this->role && in_array($this->role->nom, $role);
        }

        return $this->role && $this->role->nom === $role;
    }

    public function canDo($permission): bool
    {
        return $this->role && $this->role->hasPermission($permission);
    }

    // ==================== HELPERS RÔLES ====================

    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    public function isITS(): bool
    {
        return $this->hasRole('its');
    }

    public function isInspecteurGeneral(): bool
    {
        return $this->hasRole('inspecteur_general');
    }

    public function isPointFocal(): bool
    {
        return $this->hasRole('point_focal');
    }

    public function isResponsable(): bool
    {
        return $this->hasRole('responsable');
    }

    public function isCabinetMinistre(): bool
    {
        return $this->hasRole('cabinet_ministre');
    }
}

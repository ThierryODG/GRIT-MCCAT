<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model
{
    use HasFactory;

    protected $fillable = ['nom'];

    // ==================== RELATIONS ====================

    public function utilisateurs()
    {
        return $this->hasMany(User::class);
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'permission_role');
    }

    // ==================== MÉTHODES PERMISSIONS ====================

    /**
     * Vérifie si le rôle a une permission spécifique
     */
    public function hasPermission(string $permissionName): bool
    {
        // ✅ CORRECTION : Utilisez la relation "permissions" (pluriel)
        return $this->permissions()->where('nom', $permissionName)->exists();
    }

    /**
     * Assigner une permission au rôle
     */
    public function assignPermission($permissionName)
    {
        $permission = Permission::where('nom', $permissionName)->first();

        if ($permission && !$this->hasPermission($permissionName)) {
            $this->permissions()->attach($permission);
        }
    }

    /**
     * Retirer une permission du rôle
     */
    public function removePermission($permissionName)
    {
        $permission = Permission::where('nom', $permissionName)->first();

        if ($permission) {
            $this->permissions()->detach($permission);
        }
    }

    /**
     * Synchroniser les permissions du rôle
     */
    public function syncPermissions(array $permissionNames)
    {
        $permissionIds = Permission::whereIn('nom', $permissionNames)
            ->pluck('id')
            ->toArray();

        $this->permissions()->sync($permissionIds);
    }
}

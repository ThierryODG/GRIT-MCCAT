<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Structure extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'nom',
        'sigle',
        'description',
        'active'
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    // Relations
    public function recommandations()
    {
        return $this->hasMany(Recommandation::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    // Scope pour structures actives
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }
}

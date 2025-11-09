<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'contenu',
        'date_envoi',
        'statut',
        'user_id',
        'recommandation_id'
    ];

    protected $casts = [
    'date_envoi' => 'datetime',
    'lu' => 'boolean', // Si vous avez ce champ
];

    // Marquer comme lue
    public function marquerCommeLue()
    {
        $this->update(['statut' => 'lu']);
    }

    // Scope pour non lues
    public function scopeNonLues($query)
    {
        return $query->where('statut', 'non_lu');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function recommandation()
    {
        return $this->belongsTo(Recommandation::class);
    }
}

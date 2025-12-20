<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Commentaire extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'recommandation_id',
        'destinataire_role',
        'contenu',
        'type',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function recommandation()
    {
        return $this->belongsTo(Recommandation::class);
    }
}

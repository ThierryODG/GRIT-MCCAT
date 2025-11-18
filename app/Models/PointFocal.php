<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PointFocal extends Model
{
    protected $fillable = [
        'its_id',           // Ajoutez ce champ
        'user_id',
        'structure_id',     // Ajoutez ce champ
        'designation_par',
        'date_designation',
        'motivation',
        'statut'
    ];

    protected $casts = [
        'date_designation' => 'datetime'
    ];

    public function recommandation()
    {
        return $this->belongsTo(Recommandation::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function designateur()
    {
        return $this->belongsTo(User::class, 'designation_par');
    }

    // NOUVELLE RELATION : L'ITS assigné à ce point focal
    public function its()
    {
        return $this->belongsTo(User::class, 'its_id');
    }

    // NOUVELLE RELATION : La structure
    public function structure()
    {
        return $this->belongsTo(Structure::class);
    }

    // SCOPE : Point focaux par structure
    public function scopeParStructure($query, $structureId)
    {
        return $query->where('structure_id', $structureId);
    }

    // SCOPE : Point focaux pour un ITS spécifique
    public function scopePourITS($query, $itsId)
    {
        return $query->where('its_id', $itsId);
    }
}

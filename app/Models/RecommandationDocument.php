<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecommandationDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'recommandation_id',
        'file_path',
        'file_name',
        'description',
    ];

    public function recommandation()
    {
        return $this->belongsTo(Recommandation::class);
    }
}

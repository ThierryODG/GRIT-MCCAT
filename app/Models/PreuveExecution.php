<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreuveExecution extends Model
{
    use HasFactory;

    protected $fillable = [
        'plan_action_id',
        'file_path',
        'file_name',
        'description',
    ];

    public function planAction()
    {
        return $this->belongsTo(PlanAction::class);
    }
}

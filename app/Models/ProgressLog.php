<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgressLog extends Model
{
    protected $fillable = [
        'user_id',
        'progress_id',
        'workout_id',
        'set_number',
        'reps',
        'weight',
        'kcal_burned'
    ];

    public function workout()
    {
        return $this->belongsTo(Workout::class);
    }
}

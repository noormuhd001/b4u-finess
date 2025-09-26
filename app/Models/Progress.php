<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Progress extends Model
{
    protected $fillable = [
        'user_id',
        // 'workouts_completed',
        // // 'part',
        'avg_kcal_burned',
        'current_weight',
        'workout_on'
    ];

    public function logs()
    {
        return $this->hasMany(ProgressLog::class, 'progress_id');
    }

    public function workout()
    {
        return $this->belongsTo(Workout::class, 'workout_id');
    }

    protected $casts = [
        'workout_on' => 'date',
    ];
}

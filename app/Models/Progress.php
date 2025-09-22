<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Progress extends Model
{
    protected $fillable = [
        'user_id',
        'workouts_completed',
        // 'part',
        'avg_kcal_burned',
        'current_weight',
        'workout_on'
    ];

    protected $casts = [
        'workouts_completed' => 'array', // converts JSON to PHP array automatically
        'workout_on' => 'date',
    ];
}

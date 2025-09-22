<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Workout extends Model
{
    protected $fillable = [
        'workout_name',
        'body_part',
        'kcal_per_minute',
        'image'
    ];
}

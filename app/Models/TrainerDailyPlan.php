<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrainerDailyPlan extends Model
{
    protected $fillable = [
        'trainer_id',
        'user_id',
        'plan_date',
        'daily_instruction',
        'diet_plan',
        'progress_feedback',
    ];

    protected function casts(): array
    {
        return [
            'plan_date' => 'date',
        ];
    }

    public function trainer(): BelongsTo
    {
        return $this->belongsTo(Trainer::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

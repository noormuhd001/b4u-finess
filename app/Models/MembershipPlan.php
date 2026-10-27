<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MembershipPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'duration_months',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'duration_months' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}

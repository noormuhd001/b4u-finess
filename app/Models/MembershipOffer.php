<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MembershipOffer extends Model
{
    protected $fillable = [
        'title',
        'description',
        'discount_type',
        'discount_value',
        'applicable_durations',
        'starts_at',
        'expires_at',
        'coupon_code',
        'maximum_usage',
        'usage_count',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'discount_value' => 'decimal:2',
            'applicable_durations' => 'array',
            'starts_at' => 'date',
            'expires_at' => 'date',
            'maximum_usage' => 'integer',
            'usage_count' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function plans(): BelongsToMany
    {
        return $this->belongsToMany(MembershipPlan::class, 'membership_offer_plan', 'membership_offer_id', 'membership_plan_id');
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function isApplicableTo(MembershipPlan $plan, int $durationMonths): bool
    {
        return $this->is_active
            && $this->starts_at->toDateString() <= today()->toDateString()
            && $this->expires_at->toDateString() >= today()->toDateString()
            && ($this->maximum_usage === null || $this->usage_count < $this->maximum_usage)
            && in_array($durationMonths, $this->applicable_durations ?? [], true)
            && $this->plans->contains('id', $plan->id);
    }
}

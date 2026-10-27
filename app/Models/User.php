<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'weight',
        'height',
        'gender',
        'goal',
        'image',
        'dark_mode',
        'forgot_token',
        'expires_at',
        'role',
        'status',
        'membership_plan_id',
        'cash_payment_status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'registration_otp_hash',
    ];

    protected $appends = ['formatted_role_name'];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'status' => 'boolean',
            'registration_otp_expires_at' => 'datetime',
            'membership_started_at' => 'datetime',
            'membership_expires_at' => 'datetime',
        ];
    }

    public function membershipPlan(): BelongsTo
    {
        return $this->belongsTo(MembershipPlan::class);
    }

    public function attendanceRecords(): HasMany
    {
        return $this->hasMany(AttendanceRecord::class);
    }

    public function hasActiveMembership(): bool
    {
        return $this->status
            && (int) $this->role === 0
            && $this->cash_payment_status === 'received'
            && $this->membership_plan_id !== null
            && $this->membership_expires_at?->isFuture() === true;
    }

    public function badges()
    {
        return $this->belongsToMany(Badges::class, 'badge_users', 'user_id', 'badge_id')->withTimestamps();
    }

    public function progress()
    {
        return $this->hasMany(Progress::class);
    }

    public function getFormattedRoleNameAttribute()
    {
        $roles = config('constant.role');
        return $roles[$this->role] ?? 'Unknown';
    }
}

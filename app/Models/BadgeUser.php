<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BadgeUser extends Model
{
    protected $fillable =
    [
        'badge_id',
        'user_id'
    ];
}

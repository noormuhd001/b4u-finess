<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Workout extends Model
{
    protected $fillable = [
        'workout_name',
        'body_part',
        'kcal_per_minute',
        'image'
    ];

    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        $image = str_replace('\\', '/', $this->image);

        // // Old images stored in public folder
        // if (str_starts_with($image, 'public/')) {
        //     return asset(str_replace('public/', '/', $image));
        // }


        // Images stored via Storage::put()
        if (Storage::disk('public')->exists($image)) {
            return asset('storage/' . $image);
        }

        return asset(str_replace('public/', '', str_replace('\\', '/', $this->image)));
    }
}

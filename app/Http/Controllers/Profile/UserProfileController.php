<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $genders = config('constant.gender');
        $goals = config('constant.goal');

        return view('profile.index', [
            'user' => $user,
            'genders' => $genders,
            'goals' => $goals,
            'isAdmin' => false,
            'profileUpdateRoute' => route('profile.update'),
        ]);
    }

    public function update(ProfileUpdateRequest $request)
    {
        $user = Auth::user();
        $user->fill($request->safe()->except('profile_picture'));

        if ($request->hasFile('profile_picture')) {
            if ($user->image) {
                Storage::disk('public')->delete($user->image);
            }

            $user->image = $request->file('profile_picture')->store('profile_images', 'public');
        }

        $user->save();

        return back()->with('success', 'User details changed successfully.');
    }
}

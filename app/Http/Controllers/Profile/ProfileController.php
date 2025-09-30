<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $genders = config('constant.gender');
            $goals = config('constant.goal');
            $user = Auth::user();
            return view('profile.index', compact('genders', 'goals', 'user'));
        } catch (Exception $e) {
            report($e);
            return back()->with('error', 'Something went wrong. Please try again.');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProfileUpdateRequest $request)
    {
        try {
            $user = User::findOrFail($request->id);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->gender = $request->gender;
            $user->weight = $request->weight;
            $user->height = $request->height;
            $user->goal = $request->goal;

            if ($request->hasFile('profile_picture')) {
                // Delete old image if exists
                if ($user->profile_picture && Storage::exists($user->profile_picture)) {
                    Storage::delete($user->profile_picture);
                }
                $path = $request->file('profile_picture')->store('profile_images', 'public');
                $user->image = $path;
            }
            $user->save();
            return back()->with('success', 'User details changed successfully.');
        } catch (Exception $e) {
            report($e);
            return back()->with('error', 'Something went wrong. Please try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function upload(Request $request)
    {
        try {
            if ($request->hasFile('img')) {
                $file = $request->file('img');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $path = $file->move(public_path('/img/icon', $fileName));
            }
        } catch (Exception $e) {
            report($e);
            return back()->withErrors(['error' => 'eddd'], 400);
        }
    }
}

<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            return view('settings.index');
        } catch (Exception $e) {
            report($e);
            return back()->with('error', 'Something went wrong!');
        }
    }


    public function updateMode(Request $request)
    {
        try {
            $user = User::findOrFail(Auth::user()->id);
            $user->dark_mode = $request->dark_mode;
            $user->save();

            return response()->json(['message' => 'Dark mode updated successfully']);
        } catch (Exception $e) {
            report($e);
            return back()->with('error', 'something went wrong!');
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

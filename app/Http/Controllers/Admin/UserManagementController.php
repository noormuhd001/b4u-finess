<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            // Fetch all users
            $users = User::all(); // retrieves all rows

            // Return to view
            return view('admin.user-management.index', compact('users'));
        } catch (Exception $e) {
            report($e);
            return back()->with('error', 'Something went wrong!');
        }
    }

    public function updateRole(Request $request)
    {
        try {
            $user = User::findOrFail($request->input('user_id'));
            $user->role = $request->input('role');
            $user->save();  

            return redirect()->route('admin.user-management.index')->with('success', 'User role updated successfully.');
        } catch (Exception $e) {
            report($e);
            return back()->with('error', 'Failed to update user role.');
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

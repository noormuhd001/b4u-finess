<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProgressLog;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {

            $userData = Auth::user();

            $leaderboard = ProgressLog::leftJoin('workouts as w', 'progress_logs.workout_id', '=', 'w.id')
                ->leftJoin('users as u', 'progress_logs.user_id', '=', 'u.id')
                ->select(
                    'u.name as user_name',
                    'w.workout_name',
                    'progress_logs.weight',
                    'progress_logs.set_number',
                    'progress_logs.reps'
                )
                ->orderByDesc('progress_logs.weight')
                ->limit(5)
                ->get();

            return view('admin.dashboard', compact('leaderboard', 'userData'));
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

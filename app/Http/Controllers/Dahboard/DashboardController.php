<?php

namespace App\Http\Controllers\Dahboard;

use App\Http\Controllers\Controller;
use App\Models\Progress;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $userId = Auth::id();
            $today = Carbon::today();

            // Today's progress
            $todayProgress = Progress::where('user_id', $userId)
                ->whereDate('workout_on', $today)
                ->with(['logs.workout'])
                ->first();

            // All progress for graph
            $progresses = Progress::where('user_id', $userId)
                ->orderBy('workout_on', 'asc')
                ->get();

            $dates = $progresses->pluck('workout_on')->map(function ($date) {
                return $date->format('d M');
            });

            $weights = $progresses->pluck('current_weight');

            return view('dashboard.index', compact('todayProgress', 'dates', 'weights'));
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

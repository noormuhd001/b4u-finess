<?php

namespace App\Http\Controllers\Progress;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProgressStoreRequest;
use App\Models\Progress;
use App\Models\Workout;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProgressController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $progress = Progress::where('user_id', Auth::id())->latest()->first();
            $workouts = Workout::get();
            return view('progress.index', compact('progress', 'workouts'));
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
    public function store(ProgressStoreRequest $request)
    {
        try {
            $userId = Auth::id();
            $existing = Progress::where('user_id', $userId)
                ->where('workout_on', $request->workout_on)
                ->first();

            if ($existing) {
                return back()->with('error', 'You already have a progress entry for this date.');
            }
            $progress = new Progress();
            $progress->user_id = Auth::id();

            // Save workouts as JSON
            $progress->workouts_completed = json_encode($request->workouts_completed);

            $progress->current_weight = $request->weight;
            $progress->workout_on = $request->workout_on;

            // Default duration per workout in minutes
            $defaultDurationPerWorkout = 10;

            $workoutIds = $request->workouts_completed;

            // Fetch kcal_per_minute for each workout
            $workouts = Workout::whereIn('id', $workoutIds)->get();

            $totalKcal = 0;
            foreach ($workouts as $workout) {
                $totalKcal += $workout->kcal_per_minute * $defaultDurationPerWorkout;
            }

            $progress->avg_kcal_burned = $totalKcal;

            $progress->save();

            return back()->with('success', 'Workout Added Successfully');
        } catch (\Exception $e) {
            report($e);
            return back()->with('error', 'Something went wrong. Please try again.');
        }
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

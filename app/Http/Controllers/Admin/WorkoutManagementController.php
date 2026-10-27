<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Workout;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class WorkoutManagementController extends Controller
{
    private const BODY_PARTS = ['Chest', 'Back', 'Legs', 'Arms', 'Shoulders', 'Core'];

    public function index(Request $request)
    {
        $requestedBodyPart = $request->query('body_part');
        $selectedBodyPart = is_string($requestedBodyPart) && in_array($requestedBodyPart, self::BODY_PARTS, true)
            ? $requestedBodyPart
            : '';

        $workoutsQuery = Workout::query()->orderBy('body_part')->orderBy('workout_name');
        if ($selectedBodyPart !== '') {
            $workoutsQuery->where('body_part', $selectedBodyPart);
        }

        $workouts = $workoutsQuery->paginate(12)->withQueryString();

        return view('admin.workouts.index', [
            'workouts' => $workouts,
            'bodyParts' => self::BODY_PARTS,
            'selectedBodyPart' => $selectedBodyPart,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'workout_name' => ['required', 'string', 'max:255'],
            'body_part' => ['required', 'string', Rule::in(self::BODY_PARTS)],
            'kcal_per_minute' => ['required', 'numeric', 'min:0', 'max:1000'],
            'image' => ['required', 'image', 'max:11192'],
        ]);

        $validated['image'] = $request->file('image')->store('workouts', 'public');
        Workout::create($validated);

        return redirect()->route('admin.workouts.index')->with('success', 'Workout added successfully.');
    }

    public function update(Request $request, Workout $workout)
    {
        $validated = $request->validate([
            'workout_name' => ['required', 'string', 'max:255'],
            'body_part' => ['required', 'string', Rule::in(self::BODY_PARTS)],
            'kcal_per_minute' => ['required', 'numeric', 'min:0', 'max:1000'],
            'image' => ['nullable', 'image', 'max:5120'],
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('workouts', 'public');
        } else {
            unset($validated['image']);
        }

        $workout->update($validated);

        return redirect()->route('admin.workouts.index')->with('success', 'Workout updated successfully.');
    }
}

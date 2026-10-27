<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Progress;
use App\Models\ProgressLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProgressController extends Controller
{
    public function update(Request $request, Progress $progress)
    {
        $validated = $request->validate([
            'current_weight' => ['required', 'numeric', 'min:0', 'max:1000'],
            'avg_kcal_burned' => ['required', 'numeric', 'min:0', 'max:100000'],
            'workout_on' => ['required', 'date'],
        ]);

        $progress->update($validated);

        return redirect()->route('admin.user-management.show', $progress->user_id)
            ->with('success', 'Progress record updated successfully.');
    }

    public function updateLog(Request $request, ProgressLog $progressLog)
    {
        $validated = $request->validate([
            'set_number' => ['required', 'integer', 'min:1', 'max:100'],
            'reps' => ['required', 'integer', 'min:0', 'max:1000'],
            'weight' => ['required', 'numeric', 'min:0', 'max:1000'],
            'kcal_burned' => ['required', 'numeric', 'min:0', 'max:100000'],
        ]);

        $progress = Progress::findOrFail($progressLog->progress_id);

        DB::transaction(function () use ($progressLog, $progress, $validated) {
            $progressLog->update($validated);
            $progress->avg_kcal_burned = $progress->logs()->sum('kcal_burned');
            $progress->save();
        });

        return redirect()->route('admin.user-management.show', $progress->user_id)
            ->with('success', 'Workout set updated successfully.');
    }
}

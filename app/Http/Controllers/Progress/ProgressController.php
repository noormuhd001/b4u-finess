<?php

namespace App\Http\Controllers\Progress;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProgressStoreRequest;
use App\Models\Badges;
use App\Models\Progress;
use App\Models\ProgressLog;
use App\Models\User;
use App\Models\Workout;
use Barryvdh\DomPDF\Facade\Pdf;
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
            $parts = Workout::select('body_part')->distinct()->pluck('body_part');
            $workouts = Workout::get();

            $todaysLogs = null;
            $totalKcal = null;
            if ($progress) {
                $today = \Carbon\Carbon::today()->toDateString();
                if ($progress->workout_on->toDateString() === $today) {
                    $todaysLogs = $progress->logs;
                    $totalKcal = $progress->avg_kcal_burned;
                }
            }

            return view('progress.index', compact('progress', 'parts', 'workouts', 'todaysLogs', 'totalKcal'));
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

            // Create progress entry
            $progress = Progress::create([
                'user_id' => $userId,
                'current_weight' => $request->weight,
                'workout_on' => $request->workout_on,
            ]);

            $totalKcal = 0;

            foreach ($request->workouts_completed as $log) {
                $workout = Workout::find($log['workout_id']);
                if (!$workout) continue;

                $sets = $log['sets'] ?? 0;
                $reps = $log['reps'] ?? 0;
                $weight = $log['weight'] ?? 0;

                $kcal = ($weight * $reps * $sets * 0.1);
                $totalKcal += $kcal;

                ProgressLog::create([
                    'progress_id' => $progress->id,
                    'user_id' => $userId,
                    'workout_id' => $log['workout_id'],
                    'set_number' => $sets,
                    'reps' => $reps,
                    'weight' => $weight,
                    'kcal_burned' => $kcal,
                ]);
            }

            $progress->update(['avg_kcal_burned' => $totalKcal]);

            //Handle badges
            $earnedBadges = $this->checkAndAssignBadges($userId, $request->workouts_completed);

            if ($earnedBadges->isNotEmpty()) {
                return redirect()->route('progress.index')->with([
                    'success' => 'Workout added successfully!',
                    'earned_badges' => $earnedBadges,
                ]);
            }

            return redirect()->route('progress.index')->with('success', 'Workout added successfully!');
        } catch (\Exception $e) {
            report($e);
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    private function checkAndAssignBadges($userId, $workoutsCompleted)
    {
        $user = User::findOrFail($userId);
        $badgeIdsToAttach = [];

        // First Workout
        $firstBadge = Badges::where('name', 'First Workout')->first();
        if ($firstBadge && !$user->badges()->where('badge_id', $firstBadge->id)->exists()) {
            $badgeIdsToAttach[] = $firstBadge->id;
        }

        // Consistency King (7-day streak)
        $streakCount = Progress::where('user_id', $userId)
            ->where('workout_on', '>=', now()->subDays(7))
            ->count();

        $consistencyBadge = Badges::where('name', 'Consistency King')->first();
        if ($consistencyBadge && $streakCount >= 7 && !$user->badges()->where('badge_id', $consistencyBadge->id)->exists()) {
            $badgeIdsToAttach[] = $consistencyBadge->id;
        }

        // Strength Beast (≥100kg lift)
        foreach ($workoutsCompleted as $log) {
            if (($log['weight'] ?? 0) >= 100) {
                $strengthBadge = Badges::where('name', 'Strength Beast')->first();
                if ($strengthBadge && !$user->badges()->where('badge_id', $strengthBadge->id)->exists()) {
                    $badgeIdsToAttach[] = $strengthBadge->id;
                }
                break;
            }
        }

        // Attach new badges
        if (!empty($badgeIdsToAttach)) {
            $user->badges()->attach($badgeIdsToAttach);
            return Badges::whereIn('id', $badgeIdsToAttach)
                ->select('name', 'description', 'icon')
                ->get();
        }

        return collect(); // empty collection
    }

    public function track()
    {
        try {
            $progressList = Progress::where('user_id', Auth::id())
                ->orderBy('workout_on', 'desc')
                ->get();

            return view('progress.track', compact('progressList'));
        } catch (Exception $e) {
            report($e);
            return back()->with('error', 'Something went wrong. Please try again.');
        }
    }

    public function trackDetailById(Progress $progress)
    {
        try {
            $logs = $progress->logs()->with('workout')->get();
            return view('progress.trackDetail', compact('progress', 'logs'));
        } catch (Exception $e) {
            report($e);
            return back()->with('error', 'Something went wrong. Please try again.');
        }
    }


    public function export(Request $request)
    {
        try {
            $user = Auth::user();

            $query = Progress::where('user_id', $user->id)
                ->with(['logs.workout']);

            if ($request->filled('start_date')) {
                $query->whereDate('workout_on', '>=', $request->start_date);
            }

            if ($request->filled('end_date')) {
                $query->whereDate('workout_on', '<=', $request->end_date);
            }

            $progressList = $query->get();

            $pdf = Pdf::loadView('progress.export_pdf', [
                'user' => $user,
                'progressList' => $progressList,
            ]);

            return $pdf->download('progress_report.pdf');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to export: ' . $e->getMessage());
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

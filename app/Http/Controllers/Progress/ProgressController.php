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
            return view('progress.index', compact('progress', 'workouts', 'todaysLogs', 'totalKcal'));
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

            $totalKcal = 0;

            $progress = Progress::create([
                'user_id' => $userId,
                'current_weight' => $request->weight,
                'workout_on' => $request->workout_on,
            ]);

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

            $progress->update([
                'avg_kcal_burned' => $totalKcal,
            ]);

            $user = User::findOrFail($userId);
            $badgeIdsToAttach = [];

            // 1. First Workout Badge
            $firstBadge = Badges::where('name', 'First Workout')->first();
            if ($firstBadge && !$user->badges()->where('badge_id', $firstBadge->id)->exists()) {
                $badgeIdsToAttach[] = $firstBadge->id;
            }

            // 2. Consistency Badge (7-day streak)
            $streakCount = Progress::where('user_id', $userId)
                ->where('workout_on', '>=', now()->subDays(7))
                ->count();

            $consistencyBadge = Badges::where('name', 'Consistency King')->first();
            if ($consistencyBadge && $streakCount >= 7 && !$user->badges()->where('badge_id', $consistencyBadge->id)->exists()) {
                $badgeIdsToAttach[] = $consistencyBadge->id;
            }

            // 3. Strength Beast Badge (>100kg in any single workout)
            foreach ($request->workouts_completed as $log) {
                if (($log['weight'] ?? 0) >= 100) {
                    $strengthBadge = Badges::where('name', 'Strength Beast')->first();
                    if ($strengthBadge && !$user->badges()->where('badge_id', $strengthBadge->id)->exists()) {
                        $badgeIdsToAttach[] = $strengthBadge->id;
                    }
                    break; // only award once per progress
                }
            }

            // Attach all badges at once
            if (!empty($badgeIdsToAttach)) {
                $user->badges()->attach($badgeIdsToAttach);

                // Get badge details (icon, name, description)
                $earnedBadges = Badges::whereIn('id', $badgeIdsToAttach)
                    ->select('name', 'description', 'icon') // make sure your table has `icon` column
                    ->get();

                return back()->with([
                    'success' => 'Workout added successfully!',
                    'earned_badges' => $earnedBadges
                ]);
            }

            return back()->with('success', 'Workout added successfully!');
        } catch (\Exception $e) {
            report($e);
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
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


    public function export()
    {
        try {
            $user = Auth::user();

            // Get all progress entries for the user
            $progressList = Progress::where('user_id', $user->id)
                ->with(['logs.workout']) // eager load logs and workouts
                ->get();

            $pdf = Pdf::loadView('progress.export_pdf', [
                'user' => $user,
                'progressList' => $progressList,
            ]);

            return $pdf->download('progress_report.pdf');
        } catch (Exception $e) {
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

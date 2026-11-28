<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use App\Models\Trainer;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PortalController extends Controller
{
    public function dashboard(): View
    {
        /** @var Trainer $trainer */
        $trainer = Auth::guard('trainer')->user();
        $members = $trainer->users()
            ->with('membershipPlan')
            ->withCount('progress')
            ->orderBy('name')
            ->paginate(12);

        return view('trainer.dashboard', compact('trainer', 'members'));
    }

    public function showMember(Request $request, User $user): View
    {
        $trainer = $this->currentTrainer();
        $this->ensureAssigned($trainer, $user);

        $user->load('membershipPlan');
        $progress = $user->progress()
            ->with('logs.workout')
            ->latest('workout_on')
            ->paginate(10);
        $dailyPlans = $trainer->dailyPlans()
            ->where('user_id', $user->id)
            ->latest('plan_date')
            ->limit(20)
            ->get();

        $editingPlan = null;
        $editDate = $request->query('edit_plan');
        if (is_string($editDate) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $editDate)) {
            $editingPlan = $trainer->dailyPlans()
                ->where('user_id', $user->id)
                ->whereDate('plan_date', $editDate)
                ->first();
        }

        return view('trainer.members.show', compact('trainer', 'user', 'progress', 'dailyPlans', 'editingPlan'));
    }

    public function saveDailyPlan(Request $request, User $user): RedirectResponse
    {
        $trainer = $this->currentTrainer();
        $this->ensureAssigned($trainer, $user);

        $validated = $request->validate([
            'plan_date' => ['required', 'date_format:Y-m-d'],
            'daily_instruction' => ['required', 'string', 'max:5000'],
            'diet_plan' => ['nullable', 'string', 'max:10000'],
            'progress_feedback' => ['nullable', 'string', 'max:5000'],
        ]);

        $plan = $trainer->dailyPlans()->updateOrCreate(
            ['user_id' => $user->id, 'plan_date' => $validated['plan_date']],
            [
                'daily_instruction' => $validated['daily_instruction'],
                'diet_plan' => $validated['diet_plan'] ?? null,
                'progress_feedback' => $validated['progress_feedback'] ?? null,
            ],
        );

        return redirect()->route('trainer.members.show', [
            'user' => $user,
            'edit_plan' => $plan->plan_date->format('Y-m-d'),
        ])->with('success', 'Daily coaching plan saved.');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $trainer = $this->currentTrainer();
        if (! Hash::check($validated['current_password'], $trainer->password)) {
            throw ValidationException::withMessages([
                'current_password' => 'The current password is incorrect.',
            ]);
        }

        $trainer->update(['password' => $validated['password']]);

        return redirect()->route('trainer.dashboard')->with('success', 'Your password has been updated.');
    }

    private function currentTrainer(): Trainer
    {
        /** @var Trainer $trainer */
        $trainer = Auth::guard('trainer')->user();

        return $trainer;
    }

    private function ensureAssigned(Trainer $trainer, User $user): void
    {
        abort_unless($trainer->users()->whereKey($user->id)->exists(), 404);
    }
}

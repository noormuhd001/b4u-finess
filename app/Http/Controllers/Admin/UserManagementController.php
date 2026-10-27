<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MembershipPlan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->string('search')->trim()->toString();
        $users = User::query()
            ->whereKeyNot(Auth::id())
            ->with('membershipPlan')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $counts = [
            'all' => User::count(),
            'active' => User::where('status', true)->count(),
            'admins' => User::where('role', 1)->count(),
        ];
        $membershipPlans = MembershipPlan::orderBy('name')->get();

        return view('admin.user-management.index', compact('users', 'counts', 'search', 'membershipPlans'));
    }

    public function show(User $user)
    {
        $user->load('membershipPlan');
        $lastAttendance = $user->attendanceRecords()->latest('checked_in_at')->first();
        $attendanceCount = $user->attendanceRecords()->count();
        $progress = $user->progress()
            ->with('logs.workout')
            ->latest('workout_on')
            ->paginate(10);

        return view('admin.user-management.show', compact('user', 'progress', 'lastAttendance', 'attendanceCount'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', Rule::in(array_keys(config('constant.role')))],
            'membership_plan_id' => ['nullable', 'integer', Rule::exists('membership_plans', 'id')],
        ]);

        User::create($validated + ['status' => true]);

        return redirect()->route('admin.user-management.index')->with('success', 'User created successfully.');
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'role' => ['required', Rule::in(array_keys(config('constant.role')))],
            'membership_plan_id' => ['nullable', 'integer', Rule::exists('membership_plans', 'id')],
            'cash_payment_status' => ['required', Rule::in(['pending', 'received'])],
        ]);

        if ($validated['cash_payment_status'] === 'received' && ! $validated['membership_plan_id']) {
            return back()->withErrors([
                'membership_plan_id' => 'Assign a membership plan before marking payment as received.',
            ])->withInput();
        }

        if ($user->is(Auth::user()) && (int) $validated['role'] !== 1) {
            return back()->with('error', 'You cannot change your own administrator role.');
        }

        $planChanged = (int) $user->membership_plan_id !== (int) ($validated['membership_plan_id'] ?? 0);
        $wasPaid = $user->cash_payment_status === 'received';
        $user->update($validated);
        $this->syncMembershipDates($user, $wasPaid, $planChanged);

        return redirect()->route('admin.user-management.index')->with('success', 'User details updated successfully.');
    }

    public function updatePaymentStatus(Request $request, User $user)
    {
        $validated = $request->validate([
            'cash_payment_status' => ['required', Rule::in(['pending', 'received'])],
        ]);

        if ((int) $user->role !== 0) {
            return back()->with('error', 'Cash membership payments can only be recorded for member accounts.');
        }

        if ($validated['cash_payment_status'] === 'received' && ! $user->membership_plan_id) {
            return back()->with('error', 'Assign a membership plan before marking payment as received.');
        }

        $wasPaid = $user->cash_payment_status === 'received';
        $user->update($validated);
        $this->syncMembershipDates($user, $wasPaid, false);

        return redirect()->route('admin.user-management.index')->with(
            'success',
            $user->cash_payment_status === 'received' ? 'Cash payment marked as received.' : 'Cash payment marked as pending.'
        );
    }

    public function renewMembership(User $user)
    {
        if ((int) $user->role !== 0) {
            return back()->with('error', 'Only member accounts can be renewed.');
        }

        $user->load('membershipPlan');
        if (! $user->membershipPlan) {
            return back()->with('error', 'Assign a membership plan before recording a renewal.');
        }

        $renewalStartsAt = $user->membership_expires_at?->isFuture()
            ? $user->membership_expires_at->copy()
            : now();

        if (! $user->membership_started_at) {
            $user->membership_started_at = $renewalStartsAt;
        }
        $user->membership_expires_at = $renewalStartsAt->copy()
            ->addMonthsNoOverflow($user->membershipPlan->duration_months);
        $user->cash_payment_status = 'received';
        $user->save();

        return redirect()->route('admin.user-management.show', $user)
            ->with('success', 'Cash payment recorded and membership renewed.');
    }

    public function updateStatus(Request $request, User $user)
    {
        $validated = $request->validate([
            'status' => ['required', 'boolean'],
        ]);

        if ($user->is(Auth::user()) && ! $validated['status']) {
            return back()->with('error', 'You cannot deactivate your own administrator account.');
        }

        $user->update(['status' => $validated['status']]);

        return redirect()->route('admin.user-management.index')->with(
            'success',
            $user->status ? 'User account activated.' : 'User account deactivated.'
        );
    }

    private function syncMembershipDates(User $user, bool $wasPaid, bool $planChanged): void
    {
        if ($user->cash_payment_status !== 'received') {
            return;
        }

        $user->load('membershipPlan');
        if (! $user->membershipPlan) {
            return;
        }

        if (! $wasPaid || ! $user->membership_started_at) {
            $user->membership_started_at = now();
        }

        if (! $wasPaid || ! $user->membership_expires_at || $planChanged) {
            $user->membership_expires_at = $user->membership_started_at
                ->copy()
                ->addMonthsNoOverflow($user->membershipPlan->duration_months);
        }

        $user->save();
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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

        return view('admin.user-management.index', compact('users', 'counts', 'search'));
    }

    public function show(User $user)
    {
        $progress = $user->progress()
            ->with('logs.workout')
            ->latest('workout_on')
            ->paginate(10);

        return view('admin.user-management.show', compact('user', 'progress'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', Rule::in(array_keys(config('constant.role')))],
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
        ]);

        if ($user->is(Auth::user()) && (int) $validated['role'] !== 1) {
            return back()->with('error', 'You cannot change your own administrator role.');
        }

        $user->update($validated);

        return redirect()->route('admin.user-management.index')->with('success', 'User details updated successfully.');
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
}

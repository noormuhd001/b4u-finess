@extends('layouts.admin.app')

@section('title', 'Admin overview')
@section('page-heading', 'Overview')

@section('content')
    <div class="page-heading">
        <div>
            <h1>Good to see you, {{ $userData->name }} <span aria-hidden="true">✦</span></h1>
            <p>Here’s what’s happening across your fitness community today.</p>
        </div>
        <div class="page-heading-actions">
            <a class="admin-btn admin-btn-light" href="{{ route('admin.user-management.index') }}">
                <i class="bi bi-people"></i> Manage users
            </a>
            <a class="admin-btn" href="{{ route('admin.workouts.index') }}">
                <i class="bi bi-plus-lg"></i> Manage workouts
            </a>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-6 col-xl">
            <a class="text-decoration-none" href="{{ route('admin.user-management.index') }}">
                <div class="stat-card">
                    <div class="stat-top"><span class="stat-label">Total users</span><span class="stat-icon"><i class="bi bi-people"></i></span></div>
                    <div class="stat-value">{{ number_format($stats['users']) }}</div>
                    <div class="stat-note">{{ number_format($stats['active_users']) }} active accounts</div>
                </div>
            </a>
        </div>
        <div class="col-6 col-xl">
            <a class="text-decoration-none" href="{{ route('admin.workouts.index') }}">
                <div class="stat-card">
                    <div class="stat-top"><span class="stat-label">Workout library</span><span class="stat-icon"><i class="bi bi-activity"></i></span></div>
                    <div class="stat-value">{{ number_format($stats['workouts']) }}</div>
                    <div class="stat-note">Exercises available to users</div>
                </div>
            </a>
        </div>
        <div class="col-6 col-xl">
            <a class="text-decoration-none" href="{{ route('admin.badges.index') }}">
                <div class="stat-card">
                    <div class="stat-top"><span class="stat-label">Badge types</span><span class="stat-icon"><i class="bi bi-award"></i></span></div>
                    <div class="stat-value">{{ number_format($stats['badges']) }}</div>
                    <div class="stat-note">Rewards in your program</div>
                </div>
            </a>
        </div>
        <div class="col-6 col-xl">
            <a class="text-decoration-none" href="{{ route('admin.attendance.index', ['period' => 'daily']) }}">
                <div class="stat-card">
                    <div class="stat-top"><span class="stat-label">Today’s check-ins</span><span class="stat-icon"><i class="bi bi-calendar-check"></i></span></div>
                    <div class="stat-value">{{ number_format($stats['today_check_ins']) }}</div>
                    <div class="stat-note">Gym visits today</div>
                </div>
            </a>
        </div>
        <div class="col-6 col-xl">
            <a class="text-decoration-none" href="{{ route('admin.attendance.index') }}">
                <div class="stat-card">
                    <div class="stat-top"><span class="stat-label">Members to follow up</span><span class="stat-icon"><i class="bi bi-person-exclamation"></i></span></div>
                    <div class="stat-value">{{ number_format($stats['inactive_members']) }}</div>
                    <div class="stat-note">No visits in the past 30 days</div>
                </div>
            </a>
        </div>
    </div>

    <section class="admin-card">
        <div class="admin-card-header">
            <div>
                <h2>Recently added users</h2>
                <p>New accounts across your fitness community</p>
            </div>
            <a href="{{ route('admin.user-management.index') }}" class="admin-btn admin-btn-light">View all users <i class="bi bi-arrow-right"></i></a>
        </div>
        <div class="table-responsive">
            <table class="table admin-table">
                <thead><tr><th>User</th><th>Email</th><th>Role</th><th>Status</th><th>Joined</th></tr></thead>
                <tbody>
                    @forelse ($recentUsers as $user)
                        <tr>
                            <td><a class="entity-name text-decoration-none" href="{{ route('admin.user-management.show', $user) }}">{{ $user->name }}</a></td>
                            <td>{{ $user->email }}</td>
                            <td><span class="role-badge">{{ $user->formatted_role_name }}</span></td>
                            <td><span class="state-badge {{ $user->status ? '' : 'inactive' }}">{{ $user->status ? 'Active' : 'Inactive' }}</span></td>
                            <td>{{ $user->created_at?->format('M d, Y') ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="table-empty"><i class="bi bi-person-x"></i>No users have registered yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection

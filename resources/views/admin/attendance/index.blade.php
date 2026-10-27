@extends('layouts.admin.app')

@section('title', 'Attendance')
@section('page-heading', 'Attendance')

@section('content')
    <div class="page-heading">
        <div>
            <h1>Gym attendance</h1>
            <p>Review check-ins and follow up with active members who have not visited recently.</p>
        </div>
        <form method="GET" action="{{ route('admin.attendance.index') }}" class="attendance-period-filter">
            <label for="attendance-period">Report period</label>
            <select id="attendance-period" name="period" class="form-select admin-form-control" onchange="this.form.submit()">
                <option value="daily" @selected($period === 'daily')>Today</option>
                <option value="weekly" @selected($period === 'weekly')>This week</option>
                <option value="monthly" @selected($period === 'monthly')>This month</option>
            </select>
        </form>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-4">
            <div class="stat-card">
                <div class="stat-top"><span class="stat-label">Check-ins</span><span class="stat-icon"><i class="bi bi-box-arrow-in-right"></i></span></div>
                <div class="stat-value">{{ number_format($counts['check_ins']) }}</div>
                <div class="stat-note">{{ $rangeStart->format('M d') }}–{{ $rangeEnd->format('M d, Y') }}</div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-4">
            <div class="stat-card">
                <div class="stat-top"><span class="stat-label">Members visited</span><span class="stat-icon"><i class="bi bi-people"></i></span></div>
                <div class="stat-value">{{ number_format($counts['members']) }}</div>
                <div class="stat-note">Unique members in this period</div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-4">
            <div class="stat-card">
                <div class="stat-top"><span class="stat-label">Inactive members</span><span class="stat-icon"><i class="bi bi-person-exclamation"></i></span></div>
                <div class="stat-value">{{ number_format($inactiveMembers->total()) }}</div>
                <div class="stat-note">Active paid memberships with no visit in 30+ days</div>
            </div>
        </div>
    </div>

    <section class="admin-card mb-4">
        <div class="admin-card-header">
            <div><h2>Check-in history</h2><p>{{ ucfirst($period) }} report · {{ $rangeStart->format('M d') }}–{{ $rangeEnd->format('M d, Y') }}</p></div>
        </div>
        <div class="table-responsive">
            <table class="table admin-table">
                <thead><tr><th>Member</th><th>Email</th><th>Check-in date</th><th>Time</th></tr></thead>
                <tbody>
                    @forelse ($records as $record)
                        <tr>
                            <td><a class="entity-name text-decoration-none" href="{{ route('admin.user-management.show', $record->user) }}">{{ $record->user->name }}</a></td>
                            <td>{{ $record->user->email }}</td>
                            <td>{{ $record->checked_in_at->format('M d, Y') }}</td>
                            <td>{{ $record->checked_in_at->format('g:i A') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="table-empty"><i class="bi bi-calendar2-x"></i>No visits recorded for this period.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($records->hasPages())
            <div class="admin-pagination">{{ $records->links('pagination.admin') }}</div>
        @endif
    </section>

    <section class="admin-card">
        <div class="admin-card-header">
            <div><h2>Members to follow up</h2><p>Active paid members with no recorded gym visit in the last 30 days.</p></div>
        </div>
        <div class="table-responsive">
            <table class="table admin-table">
                <thead><tr><th>Member</th><th>Membership</th><th>Last visit</th><th>Valid until</th><th>Follow-up</th></tr></thead>
                <tbody>
                    @forelse ($inactiveMembers as $member)
                        <tr>
                            <td><a class="entity-name text-decoration-none" href="{{ route('admin.user-management.show', $member) }}">{{ $member->name }}</a><span class="entity-meta">{{ $member->email }}</span></td>
                            <td>{{ $member->membershipPlan?->name ?? '—' }}</td>
                            <td>{{ $member->attendance_records_max_checked_in_at ? \Illuminate\Support\Carbon::parse($member->attendance_records_max_checked_in_at)->format('M d, Y') : 'Never visited' }}</td>
                            <td>{{ $member->membership_expires_at?->format('M d, Y') ?? '—' }}</td>
                            <td><a class="admin-btn admin-btn-light btn-sm" href="mailto:{{ $member->email }}"><i class="bi bi-envelope"></i> Contact</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="table-empty"><i class="bi bi-emoji-smile"></i>No inactive members need follow-up.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($inactiveMembers->hasPages())
            <div class="admin-pagination">{{ $inactiveMembers->links('pagination.admin') }}</div>
        @endif
    </section>
@endsection

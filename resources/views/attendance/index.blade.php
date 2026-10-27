@extends('layouts.app')

@section('title', 'Gym attendance')

@section('content')
    <div class="container">
        <div class="user-page-heading">
            <div>
                <h1>Gym attendance</h1>
                <p>Check in when you arrive and keep track of your gym visits.</p>
            </div>
            <a class="btn btn-outline-secondary" href="{{ route('dashboard') }}"><i class="bi bi-arrow-left"></i> Dashboard</a>
        </div>

        @if (session('success'))
            <div class="alert alert-success"><i class="bi bi-check-circle me-2"></i>{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger"><i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}</div>
        @endif

        <div class="row g-4 mb-4">
            <div class="col-lg-7">
                <section class="card attendance-checkin-card h-100">
                    <div class="card-body p-4 p-lg-5">
                        <span class="attendance-icon"><i class="bi bi-geo-alt"></i></span>
                        <h2 class="h4 mt-3">Ready to train?</h2>
                        @if ($checkedInToday)
                            <p class="text-muted">You’re checked in for today. Have a great workout!</p>
                            <button class="btn btn-primary" type="button" disabled><i class="bi bi-check2-circle"></i> Checked in today</button>
                        @elseif ($canCheckIn)
                            <p class="text-muted">Check in once per day when you arrive at the gym.</p>
                            <form method="POST" action="{{ route('attendance.check-in') }}">
                                @csrf
                                <button class="btn btn-primary" type="submit"><i class="bi bi-box-arrow-in-right"></i> Check in now</button>
                            </form>
                        @else
                            <p class="text-muted mb-3">Your membership must be paid and current before you can check in.</p>
                            @if ($user->cash_payment_status !== 'received')
                                <span class="state-badge inactive"><i class="bi bi-cash-coin"></i> Payment pending</span>
                            @elseif (! $user->membershipPlan)
                                <span class="state-badge inactive"><i class="bi bi-card-checklist"></i> No plan assigned</span>
                            @else
                                <span class="state-badge inactive"><i class="bi bi-clock-history"></i> Membership expired</span>
                            @endif
                            <p class="small text-muted mt-3 mb-0">Please speak with the gym manager about your membership.</p>
                        @endif
                    </div>
                </section>
            </div>
            <div class="col-lg-5">
                <section class="card h-100">
                    <div class="card-body p-4">
                        <div class="dashboard-panel-title">
                            <div><h2>Your membership</h2><p>Payment and access details</p></div>
                            <span class="today-stat-icon"><i class="bi bi-card-checklist"></i></span>
                        </div>
                        <div class="attendance-detail-row">
                            <span>Plan</span><strong>{{ $user->membershipPlan?->name ?? 'Not assigned' }}</strong>
                        </div>
                        @if ($user->membershipPlan)
                            <div class="attendance-detail-row">
                                <span>Price</span><strong>₹{{ number_format((float) $user->membershipPlan->price, 2) }} / {{ $user->membershipPlan->duration_months }} {{ \Illuminate\Support\Str::plural('month', $user->membershipPlan->duration_months) }}</strong>
                            </div>
                        @endif
                        <div class="attendance-detail-row">
                            <span>Payment</span><strong>{{ ucfirst($user->cash_payment_status ?? 'pending') }}</strong>
                        </div>
                        <div class="attendance-detail-row">
                            <span>Valid until</span><strong>{{ $user->membership_expires_at?->format('M d, Y') ?? 'Not active' }}</strong>
                        </div>
                        <div class="attendance-detail-row">
                            <span>Last visit</span><strong>{{ $lastVisit?->checked_in_at->format('M d, Y · g:i A') ?? 'No visits yet' }}</strong>
                        </div>
                    </div>
                </section>
            </div>
        </div>

        <section class="card">
            <div class="card-body p-4">
                <div class="dashboard-panel-title">
                    <div><h2>Visit history</h2><p>{{ number_format($attendance->total()) }} gym {{ \Illuminate\Support\Str::plural('visit', $attendance->total()) }} recorded</p></div>
                    <span class="soft-badge"><i class="bi bi-calendar-check me-1"></i>{{ $attendance->total() }} visits</span>
                </div>
                @if ($attendance->isEmpty())
                    <div class="py-4 text-center">
                        <span class="today-stat-icon mx-auto mb-3"><i class="bi bi-calendar2-week"></i></span>
                        <h3 class="h6">Your visit history starts here</h3>
                        <p class="text-muted small">Check in when you arrive at the gym to record your first visit.</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="progress-table">
                            <thead><tr><th>Visit date</th><th>Check-in time</th><th>Status</th></tr></thead>
                            <tbody>
                                @foreach ($attendance as $record)
                                    <tr>
                                        <td><strong>{{ $record->checked_in_at->format('l, M d, Y') }}</strong></td>
                                        <td>{{ $record->checked_in_at->format('g:i A') }}</td>
                                        <td><span class="state-badge"><i class="bi bi-check-circle"></i> Checked in</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if ($attendance->hasPages())
                        <div class="mt-3">{{ $attendance->links() }}</div>
                    @endif
                @endif
            </div>
        </section>
    </div>
@endsection

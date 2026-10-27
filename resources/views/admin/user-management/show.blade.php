@extends('layouts.admin.app')

@section('title', $user->name)
@section('page-heading', 'User details')

@section('content')
    <div class="page-heading">
        <div>
            <h1>{{ $user->name }}</h1>
            <p>Account details and progress history for this user.</p>
        </div>
        <div class="page-heading-actions">
            @if ((int) $user->role === 0 && $user->membershipPlan)
                <button class="admin-btn" type="button" data-bs-toggle="modal" data-bs-target="#recordRenewalModal">
                    <i class="bi bi-arrow-repeat"></i> Record renewal
                </button>
            @endif
            <a class="admin-btn admin-btn-light" href="{{ route('admin.user-management.index') }}"><i class="bi bi-arrow-left"></i> Back to users</a>
        </div>
    </div>

    <section class="admin-card user-detail-card mb-4">
        <div class="user-detail-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
        <div class="user-detail-info">
            <h2>{{ $user->name }}</h2>
            <p>{{ $user->email }}</p>
            <div class="d-flex flex-wrap gap-2">
                <span class="role-badge">{{ $user->formatted_role_name }}</span>
                <span class="state-badge {{ $user->status ? '' : 'inactive' }}">{{ $user->status ? 'Active account' : 'Inactive account' }}</span>
                <span class="soft-badge">Plan: {{ $user->membershipPlan?->name ?? 'Not assigned' }}</span>
                <span class="state-badge {{ $user->cash_payment_status === 'received' ? '' : 'inactive' }}">Cash payment {{ $user->cash_payment_status ?? 'pending' }}</span>
                @if ($user->membership_expires_at)
                    <span class="state-badge {{ $user->membership_expires_at->isFuture() ? '' : 'inactive' }}">
                        {{ $user->membership_expires_at->isFuture() ? 'Valid until' : 'Expired' }} {{ $user->membership_expires_at->format('M d, Y') }}
                    </span>
                @endif
                <span class="soft-badge">{{ number_format($attendanceCount) }} gym {{ \Illuminate\Support\Str::plural('visit', $attendanceCount) }}</span>
                <span class="soft-badge">Last visit {{ $lastAttendance?->checked_in_at->format('M d, Y') ?? 'never' }}</span>
                <span class="soft-badge">Joined {{ $user->created_at?->format('M d, Y') ?? '—' }}</span>
            </div>
        </div>
    </section>

    @if ((int) $user->role === 0 && $user->membershipPlan)
        <div class="modal fade" id="recordRenewalModal" tabindex="-1" aria-labelledby="recordRenewalTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <form class="modal-content" method="POST" action="{{ route('admin.user-management.renew', $user) }}">
                    @csrf
                    @method('PATCH')
                    <div class="modal-header">
                        <div>
                            <h2 class="modal-title" id="recordRenewalTitle">Record membership renewal</h2>
                            <p class="text-secondary small mb-0 mt-1">{{ $user->name }} · {{ $user->membershipPlan->name }}</p>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="d-flex align-items-start gap-3">
                            <span class="today-stat-icon flex-shrink-0"><i class="bi bi-cash-coin"></i></span>
                            <div>
                                <p class="mb-2">Confirm that you received the cash payment from this member.</p>
                                <p class="text-secondary small mb-0">
                                    This marks the payment as received and extends the
                                    {{ $user->membershipPlan->duration_months }}-month membership.
                                    @if ($user->membership_expires_at?->isFuture())
                                        The new period will start after the current expiry date ({{ $user->membership_expires_at->format('M d, Y') }}).
                                    @else
                                        The new period will start today.
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="admin-btn admin-btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button class="admin-btn" type="submit"><i class="bi bi-check2-circle"></i> Confirm payment &amp; renew</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <section class="admin-card">
        <div class="admin-card-header">
            <div>
                <h2>Progress history</h2>
                <p>{{ number_format($progress->total()) }} workout check-ins recorded for {{ $user->name }}.</p>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table admin-table">
                <thead><tr><th>Workout date</th><th>Weight</th><th>Calories</th><th>Workout sets</th><th class="text-end">Actions</th></tr></thead>
                <tbody>
                    @forelse ($progress as $entry)
                        <tr>
                            <td>{{ $entry->workout_on?->format('M d, Y') ?? '—' }}</td>
                            <td>{{ $entry->current_weight }} kg</td>
                            <td>{{ number_format((float) $entry->avg_kcal_burned) }} kcal</td>
                            <td>{{ $entry->logs->count() }}</td>
                            <td>
                                <div class="table-actions justify-content-end">
                                    <button class="icon-action" type="button" title="View workout sets" aria-label="View workout sets for {{ $entry->workout_on?->format('M d, Y') }}" data-bs-toggle="collapse" data-bs-target="#userProgressLogs{{ $entry->id }}" aria-expanded="false"><i class="bi bi-list-ul"></i></button>
                                    <button class="icon-action" type="button" title="Edit progress" aria-label="Edit progress from {{ $entry->workout_on?->format('M d, Y') }}" data-bs-toggle="modal" data-bs-target="#editUserProgress{{ $entry->id }}"><i class="bi bi-pencil"></i></button>
                                </div>
                            </td>
                        </tr>
                        <tr class="collapse" id="userProgressLogs{{ $entry->id }}">
                            <td colspan="5" class="p-0">
                                <div class="p-3 bg-light">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <span class="admin-form-label mb-0">Workout sets</span>
                                        <span class="entity-meta">{{ $entry->logs->count() }} entries · edits recalculate total calories</span>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table admin-table bg-white">
                                            <thead><tr><th>Workout</th><th>Sets</th><th>Reps</th><th>Weight</th><th>Calories</th><th class="text-end">Action</th></tr></thead>
                                            <tbody>
                                                @forelse ($entry->logs as $log)
                                                    <tr>
                                                        <td>{{ $log->workout?->workout_name ?? 'Deleted workout' }}</td>
                                                        <td>{{ $log->set_number }}</td>
                                                        <td>{{ $log->reps }}</td>
                                                        <td>{{ $log->weight }} kg</td>
                                                        <td>{{ $log->kcal_burned }} kcal</td>
                                                        <td><div class="table-actions justify-content-end"><button class="icon-action" type="button" title="Edit set" aria-label="Edit {{ $log->workout?->workout_name ?? 'workout' }} set" data-bs-toggle="modal" data-bs-target="#editUserProgressLog{{ $log->id }}"><i class="bi bi-pencil"></i></button></div></td>
                                                    </tr>
                                                @empty
                                                    <tr><td colspan="6" class="table-empty">No individual workout sets are recorded.</td></tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </td>
                        </tr>

                    @empty
                        <tr><td colspan="5" class="table-empty"><i class="bi bi-clipboard2-pulse"></i>No progress records are available for this user.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($progress->hasPages())<div class="admin-pagination">{{ $progress->links('pagination.admin') }}</div>@endif
    </section>

    @foreach ($progress as $entry)
        <div class="modal fade" id="editUserProgress{{ $entry->id }}" tabindex="-1" aria-labelledby="editUserProgressTitle{{ $entry->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <form class="modal-content" method="POST" action="{{ route('admin.progress.update', $entry->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="modal-header"><div><h2 class="modal-title" id="editUserProgressTitle{{ $entry->id }}">Edit progress record</h2><p class="text-secondary small mb-0 mt-1">{{ $user->name }} · #{{ $entry->id }}</p></div><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
                    <div class="modal-body">
                        <div class="mb-3"><label class="admin-form-label" for="progress-weight{{ $entry->id }}">Current weight (kg)</label><input class="form-control admin-form-control" id="progress-weight{{ $entry->id }}" name="current_weight" type="number" step="0.1" min="0" value="{{ $entry->current_weight }}" required></div>
                        <div class="mb-3"><label class="admin-form-label" for="progress-date{{ $entry->id }}">Workout date</label><input class="form-control admin-form-control" id="progress-date{{ $entry->id }}" name="workout_on" type="date" value="{{ $entry->workout_on?->format('Y-m-d') }}" required></div>
                        <div><label class="admin-form-label" for="progress-calories{{ $entry->id }}">Calories burned</label><input class="form-control admin-form-control" id="progress-calories{{ $entry->id }}" name="avg_kcal_burned" type="number" step="1" min="0" value="{{ $entry->avg_kcal_burned }}" required></div>
                        <p class="entity-meta mt-3">This adjusts the progress summary only; individual workout sets remain unchanged.</p>
                    </div>
                    <div class="modal-footer"><button type="button" class="admin-btn admin-btn-light" data-bs-dismiss="modal">Cancel</button><button class="admin-btn" type="submit">Save changes</button></div>
                </form>
            </div>
        </div>

        @foreach ($entry->logs as $log)
            <div class="modal fade" id="editUserProgressLog{{ $log->id }}" tabindex="-1" aria-labelledby="editUserProgressLogTitle{{ $log->id }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <form class="modal-content" method="POST" action="{{ route('admin.progress.logs.update', $log->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="modal-header"><div><h2 class="modal-title" id="editUserProgressLogTitle{{ $log->id }}">Edit workout set</h2><p class="text-secondary small mb-0 mt-1">{{ $log->workout?->workout_name ?? 'Workout' }} · {{ $user->name }}</p></div><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-6"><label class="admin-form-label" for="log-sets{{ $log->id }}">Sets</label><input class="form-control admin-form-control" id="log-sets{{ $log->id }}" name="set_number" type="number" min="1" max="100" value="{{ $log->set_number }}" required></div>
                                <div class="col-6"><label class="admin-form-label" for="log-reps{{ $log->id }}">Reps</label><input class="form-control admin-form-control" id="log-reps{{ $log->id }}" name="reps" type="number" min="0" max="1000" value="{{ $log->reps }}" required></div>
                                <div class="col-6"><label class="admin-form-label" for="log-weight{{ $log->id }}">Weight (kg)</label><input class="form-control admin-form-control" id="log-weight{{ $log->id }}" name="weight" type="number" step="0.1" min="0" max="1000" value="{{ $log->weight }}" required></div>
                                <div class="col-6"><label class="admin-form-label" for="log-kcal{{ $log->id }}">Calories</label><input class="form-control admin-form-control" id="log-kcal{{ $log->id }}" name="kcal_burned" type="number" step="0.1" min="0" value="{{ $log->kcal_burned }}" required></div>
                            </div>
                            <p class="entity-meta mt-3">The progress summary will be recalculated using all recorded sets.</p>
                        </div>
                        <div class="modal-footer"><button type="button" class="admin-btn admin-btn-light" data-bs-dismiss="modal">Cancel</button><button class="admin-btn" type="submit">Save set</button></div>
                    </form>
                </div>
            </div>
        @endforeach
    @endforeach
@endsection

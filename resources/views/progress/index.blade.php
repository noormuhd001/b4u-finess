@extends('layouts.app')

@section('title', 'Log your progress')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet">
@endpush

@section('content')
    @php
        $today = \Carbon\Carbon::today()->toDateString();
        $todaysProgress = $progress && $progress->workout_on->toDateString() === $today ? $progress : null;
    @endphp

    <div class="container">
        <div class="user-page-heading">
            <div><h1>Progress check-in</h1><p>Log a workout, keep your history, and make every session count.</p></div>
            <a href="{{ route('progress.track') }}" class="btn btn-outline-secondary"><i class="bi bi-clock-history"></i> Workout history</a>
        </div>

        @if (session('success'))
            <div class="alert alert-success"><i class="bi bi-check-circle me-2"></i>{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger"><i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Please check the form details:</strong>
                <ul class="mb-0 mt-2">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif

        @if (session('earned_badges'))
            <div class="modal fade" id="badgeModal" tabindex="-1" aria-labelledby="badgeModalTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content text-center p-4">
                        <span class="today-stat-icon mx-auto mb-3"><i class="bi bi-award"></i></span>
                        <h2 class="h4 mb-3" id="badgeModalTitle">Achievement unlocked!</h2>
                        @foreach (session('earned_badges') as $badge)
                            <div class="mb-3">
                                @if ($badge->icon)<img src="{{ asset($badge->icon) }}" alt="" class="badge-icon img-fluid mb-2">@endif
                                <h3 class="h6 mb-1">{{ $badge->name }}</h3>
                                <p class="text-muted small">{{ $badge->description }}</p>
                            </div>
                        @endforeach
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Keep it up</button>
                    </div>
                </div>
            </div>
        @endif

        @if ($todaysProgress)
            <section class="card mb-4">
                <div class="card-body p-4">
                    <div class="dashboard-panel-title">
                        <div><h2>Today’s workout is in</h2><p>{{ $todaysProgress->workout_on->format('l, F j, Y') }}</p></div>
                        <span class="today-stat-icon"><i class="bi bi-check2-circle"></i></span>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-sm-6"><div class="settings-card h-100"><div><small class="text-muted">Current weight</small><h2 class="mb-0 mt-1">{{ $todaysProgress->current_weight }} kg</h2></div><span class="today-stat-icon"><i class="bi bi-speedometer2"></i></span></div></div>
                        <div class="col-sm-6"><div class="settings-card h-100"><div><small class="text-muted">Calories logged</small><h2 class="mb-0 mt-1">{{ number_format((float) ($totalKcal ?? $todaysProgress->avg_kcal_burned)) }} kcal</h2></div><span class="today-stat-icon"><i class="bi bi-fire"></i></span></div></div>
                    </div>
                    <h3 class="h6 mb-3">Exercises completed</h3>
                    @if ($todaysLogs && $todaysLogs->count())
                        <ul class="list-group">
                            @foreach ($todaysLogs as $log)
                                <li class="list-group-item d-flex flex-wrap align-items-center justify-content-between gap-2">
                                    <strong>{{ $log->workout->workout_name ?? 'Workout' }}</strong>
                                    <span class="text-muted">{{ $log->set_number }} sets × {{ $log->reps }} reps · {{ $log->weight }} kg · {{ $log->kcal_burned }} kcal</span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted">No individual exercises were added.</p>
                    @endif
                </div>
            </section>
        @else
            <form action="{{ route('progress.store') }}" method="POST">
                @csrf
                <section class="card progress-form-card mb-4">
                    <div class="dashboard-panel-title">
                        <div><h2>Session details</h2><p>Start with a date and your current weight.</p></div>
                        <span class="today-stat-icon"><i class="bi bi-journal-check"></i></span>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label" for="current-weight">Current weight (kg)</label>
                            <input id="current-weight" type="number" step="0.1" min="20" max="500" name="weight" class="form-control" value="{{ old('weight', auth()->user()->weight) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="workout-date">Workout date</label>
                            <input id="workout-date" type="date" name="workout_on" class="form-control" value="{{ old('workout_on', $today) }}" max="{{ $today }}" required>
                        </div>
                    </div>
                </section>

                <section class="card progress-form-card mb-4">
                    <div class="dashboard-panel-title">
                        <div><h2>Exercises</h2><p>Add each exercise with its sets, reps, and working weight.</p></div>
                    </div>
                    <div id="workouts-wrapper" class="d-grid gap-3">
                        <div class="workout-item">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">Muscle group</label>
                                    <select name="workouts_completed[0][part]" class="form-select workout-part" required>
                                        <option value="">Select a group</option>
                                        @foreach ($parts as $part)<option value="{{ $part }}">{{ ucfirst($part) }}</option>@endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Exercise</label>
                                    <select name="workouts_completed[0][workout_id]" class="form-select workout-select" required>
                                        <option value="">Select an exercise</option>
                                        @foreach ($workouts as $workout)
                                            <option value="{{ $workout->id }}" data-part="{{ $workout->body_part }}">{{ $workout->workout_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-4 col-md-1">
                                    <label class="form-label">Sets</label>
                                    <input type="number" name="workouts_completed[0][sets]" class="form-control" min="1" max="100" required>
                                </div>
                                <div class="col-4 col-md-1">
                                    <label class="form-label">Reps</label>
                                    <input type="number" name="workouts_completed[0][reps]" class="form-control" min="1" max="1000" required>
                                </div>
                                <div class="col-4 col-md-2">
                                    <label class="form-label">Weight (kg)</label>
                                    <input type="number" step="0.5" min="0" name="workouts_completed[0][weight]" class="form-control" value="0">
                                </div>
                            </div>
                            <button type="button" class="btn btn-danger btn-sm removeWorkoutBtn mt-3" style="display:none">
                                <i class="bi bi-trash3"></i> Remove exercise
                            </button>
                        </div>
                    </div>
                    <div class="d-flex flex-wrap gap-2 mt-3">
                        <button type="button" class="btn btn-outline-secondary" id="addWorkoutBtn"><i class="bi bi-plus-lg"></i> Add another exercise</button>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-check2"></i> Save progress</button>
                    </div>
                </section>
            </form>
        @endif
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const badgeModalElement = document.getElementById('badgeModal');
            if (badgeModalElement) {
                badgeModalElement.addEventListener('shown.bs.modal', function () {
                    if (typeof confetti === 'function') {
                        confetti({ particleCount: 90, spread: 65, origin: { y: 0.65 }, colors: ['#c9f36a', '#1c392b', '#ec8a4b'] });
                    }
                });
                bootstrap.Modal.getOrCreateInstance(badgeModalElement).show();
            }

            const wrapper = document.getElementById('workouts-wrapper');
            const addWorkoutBtn = document.getElementById('addWorkoutBtn');
            if (!wrapper || !addWorkoutBtn) return;

            let workoutIndex = 1;

            function filterWorkouts(partSelect) {
                const workoutSelect = partSelect.closest('.workout-item').querySelector('.workout-select');
                workoutSelect.value = '';
                workoutSelect.querySelectorAll('option').forEach(function (option) {
                    if (option.value) {
                        option.hidden = option.dataset.part !== partSelect.value;
                    }
                });
            }

            wrapper.addEventListener('change', function (event) {
                if (event.target.classList.contains('workout-part')) filterWorkouts(event.target);
            });

            addWorkoutBtn.addEventListener('click', function () {
                const firstItem = wrapper.querySelector('.workout-item');
                const newItem = firstItem.cloneNode(true);
                newItem.querySelectorAll('input, select').forEach(function (field) {
                    field.name = field.name.replace(/\[\d+\]/, '[' + workoutIndex + ']');
                    if (field.tagName === 'SELECT') {
                        field.selectedIndex = 0;
                        field.querySelectorAll('option').forEach(option => option.hidden = false);
                    } else if (field.name.endsWith('[weight]')) {
                        field.value = '0';
                    } else {
                        field.value = '';
                    }
                });
                const removeButton = newItem.querySelector('.removeWorkoutBtn');
                removeButton.style.display = 'inline-flex';
                removeButton.addEventListener('click', function () { newItem.remove(); });
                wrapper.appendChild(newItem);
                workoutIndex++;
            });
        });
    </script>
@endpush

@extends('layouts.app')
@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
        rel="stylesheet" />
    <style>
        .btn {
            padding: 12px 25px;
            background: #FF8C00;
            color: #fff;
            text-decoration: none;
            border: none;
            border-radius: 8px;
            font-size: 16px;
        }

        .btn:hover {
            background: #e07b00;
        }

        .workout-item {
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 10px;
        }

        .workout-item .form-label {
            font-weight: 500;
        }

        .removeWorkoutBtn {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: red
        }

        .material-symbols-outlined {
            vertical-align: middle;
            margin-right: 6px;
            font-size: 22px;
        }

        .dark-mode .card,
        .dark-mode .workout-item,
        .dark-mode .modal-content {
            background-color: #1e1e1e;
            color: #e0e0e0;
            border: 1px solid #333;
        }

        .dark-mode .form-label,
        .dark-mode label {
            color: #e0e0e0;
        }

        .dark-mode .form-control,
        .dark-mode .form-select {
            background-color: #2c2c2c;
            color: #e0e0e0;
            border: 1px solid #555;
        }

        .dark-mode .form-control:focus,
        .dark-mode .form-select:focus {
            background-color: #2c2c2c;
            color: #fff;
            border-color: #FF8C00;
            box-shadow: none;
        }

        .dark-mode .btn-orange,
        .dark-mode .btn {
            background-color: #FF8C00;
            color: #121212;
        }

        .dark-mode .btn-orange:hover,
        .dark-mode .btn:hover {
            background-color: #e07b00;
            color: #fff;
        }

        .dark-mode .btn-outline-secondary {
            color: #e0e0e0;
            border-color: #555;
        }

        .dark-mode .btn-outline-secondary:hover {
            background-color: #333;
            color: #fff;
        }

        .dark-mode .list-group-item {
            background-color: #1e1e1e;
            color: #e0e0e0;
            border-color: #333;
        }

        .dark-mode .material-symbols-outlined.text-primary {
            color: #4dabf7 !important;
        }

        .dark-mode .material-symbols-outlined.text-danger {
            color: #ff6b6b !important;
        }

        .dark-mode .material-symbols-outlined.text-info {
            color: #17a2b8 !important;
        }

        .dark-mode .material-symbols-outlined.text-secondary {
            color: #adb5bd !important;
        }
    </style>
@endpush

@section('content')
    <div class="container my-4">
        <h2 class="mb-4">
            <span class="material-symbols-outlined text-primary">monitor_heart</span>
            Today’s Progress
        </h2>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if (session('earned_badges'))
            <div class="modal fade" id="badgeModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content text-center p-4">
                        @foreach (session('earned_badges') as $badge)
                            <div class="mb-3">
                                <img src="{{ asset($badge->icon) }}" alt="{{ $badge->name }}" class="img-fluid mb-2"
                                    style="max-width: 80px;">
                                <h5 class="fw-bold">{{ $badge->name }}</h5>
                                <p class="text-muted">{{ $badge->description }}</p>
                            </div>
                        @endforeach
                        <button type="button" class="btn btn-primary mt-2" data-bs-dismiss="modal">Awesome!</button>
                    </div>
                </div>
            </div>
        @endif

        @php
            $today = \Carbon\Carbon::today()->toDateString();
            $todaysProgress = $progress && $progress->workout_on->toDateString() === $today ? $progress : null;
        @endphp

        @if ($todaysProgress)
            <div class="card p-4 mb-3">
                <h5>Today's Workout ({{ $today }})</h5>
                <p><span class="material-symbols-outlined text-info">monitor_weight</span><strong>Weight:</strong>
                    {{ $todaysProgress->current_weight }} kg</p>
                <p><span class="material-symbols-outlined text-danger">local_fire_department</span><strong>Total Kcal
                        Burned:</strong> {{ $totalKcal ?? $todaysProgress->avg_kcal_burned }}</p>

                <h6 class="mt-3">Workout Details:</h6>
                @if ($todaysLogs && $todaysLogs->count())
                    <ul class="list-group">
                        @foreach ($todaysLogs as $log)
                            <li class="list-group-item">
                                <strong>{{ $log->workout->workout_name ?? 'Workout' }}</strong> — Sets:
                                {{ $log->set_number }}, Reps: {{ $log->reps }}, Weight: {{ $log->weight }} kg, Kcal
                                Burned: {{ $log->kcal_burned }}
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p>No workouts logged yet for today.</p>
                @endif
            </div>
        @else
            <p><span class="material-symbols-outlined text-secondary">info</span>No progress recorded for today. You can log
                your workout below:</p>

            <form action="{{ route('progress.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Current Weight (kg)</label>
                    <input type="number" step="0.1" name="weight" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Workout Date</label>
                    <input type="date" name="workout_on" class="form-control" value="{{ $today }}"
                        max="{{ $today }}" required>
                </div>

                <h5>Workouts</h5>
                <div id="workouts-wrapper">
                    <div class="workout-item position-relative">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Workout</label>
                                <select name="workouts_completed[0][workout_id]" class="form-select" required>
                                    <option value="">-- Select Workout --</option>
                                    @foreach ($workouts as $workout)
                                        <option value="{{ $workout->id }}">{{ $workout->workout_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Sets</label>
                                <input type="number" name="workouts_completed[0][sets]" class="form-control" min="1"
                                    required>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Reps</label>
                                <input type="number" name="workouts_completed[0][reps]" class="form-control" min="1"
                                    required>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Weight (kg)</label>
                                <input type="number" step="0.5" name="workouts_completed[0][weight]"
                                    class="form-control">
                            </div>
                        </div>
                        <button type="button" class="btn btn-danger removeWorkoutBtn mt-2"
                            style="display:none; position:absolute; top:10px; right:10px;">Remove</button>
                    </div>
                </div>

                <button type="button" class="btn btn-outline-secondary btn-sm mb-3" id="addWorkoutBtn">+ Add
                    Workout</button>
                <br>
                <button type="submit" class="btn btn-orange">Save Progress</button>
            </form>
        @endif

        {{-- Track Workout Button --}}
        <a href="{{ route('progress.track') }}" class="btn mt-2"  style="background-color: #0056b3">
            <span class="material-symbols-outlined">monitoring</span>
            Track Workout
        </a>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if (session('earned_badges'))
                var badgeModal = new bootstrap.Modal(document.getElementById('badgeModal'));
                badgeModal.show();
            @endif
            let workoutIndex = 1;
            const addWorkoutBtn = document.getElementById('addWorkoutBtn');
            const wrapper = document.getElementById('workouts-wrapper');

            if (addWorkoutBtn) {
                addWorkoutBtn.addEventListener('click', function() {
                    let newItem = document.querySelector('.workout-item').cloneNode(true);

                    // Update input names and reset values
                    newItem.querySelectorAll('input, select').forEach(el => {
                        el.name = el.name.replace(/\d+/, workoutIndex);
                        el.value = '';
                    });

                    // Show remove button for cloned item
                    let removeBtn = newItem.querySelector('.removeWorkoutBtn');
                    removeBtn.style.display = 'block';
                    removeBtn.addEventListener('click', function() {
                        newItem.remove();
                    });

                    wrapper.appendChild(newItem);
                    workoutIndex++;
                });
            }
        });
    </script>
@endpush

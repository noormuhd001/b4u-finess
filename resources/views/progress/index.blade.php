@extends('layouts.app')
@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
@endpush
@push('styles')
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
    </style>
@endpush
@section('content')
    <div class="container">
        <h2><i class="bi bi-activity"></i> Today’s Progress</h2>

        {{-- Success Message --}}
        @if (session('success'))
            <div class="alert alert-success">
                <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
            </div>
        @endif

        {{-- Error Message --}}
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @php
            $today = \Carbon\Carbon::today()->toDateString();
            $todaysProgress = $progress && $progress->workout_on->toDateString() === $today ? $progress : null;
        @endphp

        @if ($todaysProgress)
            <div class="card p-3 mb-3">
                <h5><i class="bi bi-calendar-check"></i> Today's Workout ({{ $today }})</h5>
                <p><strong><i class="bi bi-list-task"></i> Workouts Completed:</strong>
                    @php
                        $completedWorkouts = json_decode($todaysProgress->workouts_completed, true);
                        $workoutNames = \App\Models\Workout::whereIn('id', $completedWorkouts)
                            ->pluck('workout_name')
                            ->toArray();
                    @endphp
                    {{ implode(', ', $workoutNames) }}
                </p>
                <p><strong><i class="bi bi-fire"></i> Average Kcal Burned:</strong>
                    {{ $todaysProgress->avg_kcal_burned }}
                    <small class="text-muted">(10 min per workout)</small>
                </p>
                <p><strong><i class="bi bi-person-lines-fill"></i> Weight:</strong> {{ $todaysProgress->current_weight }} kg
                </p>
                <p><strong><i class="bi bi-clock-history"></i> Workout On:</strong>
                    {{ $todaysProgress->workout_on->format('d M Y') }}</p>
            </div>
        @else
            <p><i class="bi bi-info-circle"></i> No progress recorded for today. You can log your workout below:</p>

            <form action="{{ route('progress.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label><i class="bi bi-check2-square"></i> Select Workouts Completed</label>
                    <select name="workouts_completed[]" id="workouts_completed" class="form-control select2" multiple>
                        @foreach ($workouts as $workout)
                            <option value="{{ $workout->id }}">{{ $workout->workout_name }}</option>
                        @endforeach
                    </select>
                    @error('workouts_completed')
                        <small class="text-danger"><i class="bi bi-x-circle"></i> {{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3">
                    <label><i class="bi bi-activity"></i> Current Weight (kg)</label>
                    <input type="number" step="0.1" name="weight" class="form-control" value="{{ old('weight') }}">
                    @error('weight')
                        <small class="text-danger"><i class="bi bi-x-circle"></i> {{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3">
                    <label><i class="bi bi-calendar-date"></i> Workout Date</label>
                    <input type="date" name="workout_on" class="form-control" max="{{ $today }}"
                        value="{{ $today }}">
                    <small class="text-muted">You cannot log workouts for future dates.</small>
                    @error('workout_on')
                        <small class="text-danger"><i class="bi bi-x-circle"></i> {{ $message }}</small>
                    @enderror
                </div>

                <button class="btn"><i class="bi bi-save"></i> Save Progress</button>
            </form>
        @endif

        {{-- Track Workout Button --}}
        <a href="{{ route('progress.track') }}" class="btn mt-2">
            <i class="bi bi-graph-up"></i> Track Workout
        </a>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#workouts_completed').select2({
                placeholder: "Select Workout",
                allowClear: true,
                width: '100%',
                theme: 'bootstrap-5'
            });
        });
    </script>
@endpush

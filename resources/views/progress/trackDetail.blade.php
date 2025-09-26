@extends('layouts.app')
@section('content')
    <div class="container">
        <h2>
            <i class="bi bi-clipboard-check"></i> Workout Details for {{ $progress->workout_on->format('d M Y') }}
        </h2>

        <p>
            <strong><i class="bi bi-person-lines-fill"></i> Weight:</strong>
            {{ $progress->current_weight }} kg
        </p>

        <p>
            <strong><i class="bi bi-fire"></i> Total Kcal Burned:</strong>
            {{ $progress->avg_kcal_burned }}
        </p>

        <h4><i class="bi bi-list-task"></i> Workouts Completed</h4>
        <ul class="list-group mb-3">
            @foreach ($logs as $log)
                <li class="list-group-item">
                    <i class="bi bi-check-circle-fill text-success"></i>
                    {{ $log->workout->workout_name ?? 'Workout' }}
                    <br>
                    Sets: {{ $log->set_number }}, Reps: {{ $log->reps }},
                    Weight: {{ $log->weight }} kg,
                    Kcal Burned: {{ $log->kcal_burned }}
                </li>
            @endforeach
        </ul>

        <a href="{{ route('progress.track') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left-circle"></i> Back to History
        </a>
    </div>
@endsection

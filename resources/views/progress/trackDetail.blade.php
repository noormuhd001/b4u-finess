@extends('layouts.app')

@push('styles')
    <style>
        .material-symbols-outlined {
            vertical-align: middle;
            font-size: 20px;
            margin-right: 4px;
        }
    </style>
@endpush

@section('content')
    <div class="container">
        <h2>
            <span class="material-symbols-outlined">assignment</span>
            Workout Details for {{ $progress->workout_on->format('d M Y') }}
        </h2>

        <p>
            <strong>
                <span class="material-symbols-outlined">person</span> Weight:
            </strong>
            {{ $progress->current_weight }} kg
        </p>

        <p>
            <strong>
                <span class="material-symbols-outlined">local_fire_department</span> Total Kcal Burned:
            </strong>
            {{ $progress->avg_kcal_burned }}
        </p>

        <h4>
            <span class="material-symbols-outlined">checklist</span> Workouts Completed
        </h4>
        <ul class="list-group mb-3">
            @foreach ($logs as $log)
                <li class="list-group-item">
                    <span class="material-symbols-outlined text-success">check_circle</span>
                    {{ $log->workout->workout_name ?? 'Workout' }}
                    <br>
                    Sets: {{ $log->set_number }}, Reps: {{ $log->reps }},
                    Weight: {{ $log->weight }} kg,
                    Kcal Burned: {{ $log->kcal_burned }}
                </li>
            @endforeach
        </ul>

        <a href="{{ route('progress.track') }}" class="btn btn-secondary">
            <span class="material-symbols-outlined">arrow_back</span> Back to History
        </a>
    </div>
@endsection

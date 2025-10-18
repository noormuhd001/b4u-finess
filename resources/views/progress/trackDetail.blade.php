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
            Workout Details for {{ $progress->workout_on->format('d M Y') }}
        </h2>

        <p>
            <strong>
                Weight:
            </strong>
            {{ $progress->current_weight }} kg
        </p>

        <p>
            <strong>
                Total Kcal Burned:
            </strong>
            {{ $progress->avg_kcal_burned }}
        </p>

        <h4>
            Workouts Completed
        </h4>
        <ul class="list-group mb-3">
            @foreach ($logs as $log)
                <li class="list-group-item">
                    {{ $log->workout->workout_name ?? 'Workout' }}
                    <br>
                    Sets: {{ $log->set_number }}, Reps: {{ $log->reps }},
                    Weight: {{ $log->weight }} kg,
                    Kcal Burned: {{ $log->kcal_burned }}
                </li>
            @endforeach
        </ul>

        <a href="{{ route('progress.track') }}" class="btn btn-secondary">
            <span class="material-symbols-outlined">arrow_back_ios</span>Back
        </a>
    </div>
@endsection

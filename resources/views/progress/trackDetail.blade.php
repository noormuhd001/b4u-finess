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
        <strong><i class="bi bi-fire"></i> Avg Kcal Burned:</strong>
        {{ $progress->avg_kcal_burned }}
        <small>(10 min per workout)</small>
    </p>

    <h4><i class="bi bi-list-task"></i> Workouts Completed</h4>
    <ul class="list-group mb-3">
        @foreach($workouts as $workout)
            <li class="list-group-item">
                <i class="bi bi-check-circle-fill text-success"></i>
                {{ $workout->workout_name }}
                <span class="badge bg-secondary float-end">
                    <i class="bi bi-fire"></i> {{ $workout->kcal_per_minute }} kcal/min
                </span>
            </li>
        @endforeach
    </ul>

    <a href="{{ route('progress.track') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left-circle"></i> Back to History
    </a>
</div>
@endsection

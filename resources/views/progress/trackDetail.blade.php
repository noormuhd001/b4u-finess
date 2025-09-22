@extends('layouts.app')
@section('content')
<div class="container">
    <h2>Workout Details for {{ $progress->workout_on->format('d M Y') }}</h2>

    <p><strong>Weight:</strong> {{ $progress->current_weight }} kg</p>
    <p><strong>Avg Kcal Burned:</strong> {{ $progress->avg_kcal_burned }} <small>(10 min per workout)</small></p>

    <h4>Workouts Completed</h4>
    <ul class="list-group mb-3">
        @foreach($workouts as $workout)
            <li class="list-group-item">
                {{ $workout->workout_name }}
                <span class="badge bg-secondary float-end">{{ $workout->kcal_per_minute }} kcal/min</span>
            </li>
        @endforeach
    </ul>

    <a href="{{ route('progress.track') }}" class="btn btn-secondary">Back to History</a>
</div>
@endsection

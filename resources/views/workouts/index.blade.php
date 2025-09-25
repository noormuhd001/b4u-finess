@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h2 class="mb-3">All Workouts</h2>
        <div class="row">
            @foreach ($workouts as $workout)
                <div class="col-md-3 mb-4">
                    <div class="card h-100 shadow-sm" data-bs-toggle="modal" data-bs-target="#workoutModal{{ $workout->id }}"
                        style="cursor:pointer;">
                        <img src="{{ $workout->image }}" class="card-img-top" alt="{{ $workout->workout_name }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ $workout->workout_name }}</h5>
                            <p class="mb-1">
                                <i class="bi bi-fire text-danger"></i>
                                {{ $workout->kcal_per_minute ?? 'N/A' }} kcal/min
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Workout Modal -->
                <div class="modal fade" id="workoutModal{{ $workout->id }}" tabindex="-1"
                    aria-labelledby="modalLabel{{ $workout->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalLabel{{ $workout->id }}">{{ $workout->workout_name }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <img src="{{ $workout->image }}" class="img-fluid mb-3">
                                <p><i class="bi bi-person-bounding-box text-primary"></i> Body Part:
                                    {{ $workout->body_part }}</p>
                                <p><i class="bi bi-fire text-danger"></i> {{ $workout->kcal_per_minute ?? 'N/A' }} kcal/min</p>
                                {{-- <p>Sets: {{ $workout->sets }}, Reps: {{ $workout->reps }}</p>
                        <p>{{ $workout->description ?? 'No description available.' }}</p> --}}
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection

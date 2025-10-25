@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h2 class="mb-3">All Workouts by Body Part</h2>

        {{-- Tabs for body parts --}}
        <ul class="nav nav-tabs mb-3" id="bodyPartTabs" role="tablist">
            @foreach ($workoutsByPart as $bodyPart => $partWorkouts)
                <li class="nav-item" role="presentation">
                    <button class="nav-link @if ($loop->first) active @endif" id="tab-{{ $loop->index }}"
                        data-bs-toggle="tab" data-bs-target="#part-{{ $loop->index }}" type="button" style="color: #ff9800"
                        role="tab">
                        {{ $bodyPart }}
                    </button>
                </li>
            @endforeach
        </ul>

        {{-- Tab panes --}}
        <div class="tab-content" id="bodyPartTabContent">
            @foreach ($workoutsByPart as $bodyPart => $partWorkouts)
                <div class="tab-pane fade @if ($loop->first) show active @endif"
                    id="part-{{ $loop->index }}" role="tabpanel">

                    <div class="row">
                        @foreach ($partWorkouts as $workout)
                            <div class="col-md-3 mb-4">
                                <div class="card h-100 shadow-sm" data-bs-toggle="modal"
                                    data-bs-target="#workoutModal{{ $workout->id }}" style="cursor:pointer;">
                                    <img src="{{ asset($workout->image) }}" class="card-img-top"
                                        alt="{{ $workout->workout_name }}">

                                    <div class="card-body">
                                        <h5 class="card-title">{{ $workout->workout_name }}</h5>
                                        <p class="mb-1">
                                            <span class="material-symbols-outlined text-danger">local_fire_department</span>
                                            {{ $workout->kcal_per_minute ?? 'N/A' }} kcal/min
                                        </p>
                                    </div>
                                </div>
                            </div>

                            {{-- Workout Modal --}}
                            <div class="modal fade" id="workoutModal{{ $workout->id }}" tabindex="-1"
                                aria-labelledby="modalLabel{{ $workout->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="modalLabel{{ $workout->id }}">
                                                {{ $workout->workout_name }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <img src="{{ asset($workout->image) }}" class="img-fluid mb-3">
                                            <p><span class="material-symbols-outlined text-primary">fitness_center</span>
                                                Body Part: {{ $workout->body_part }}</p>
                                            <p><span
                                                    class="material-symbols-outlined text-danger">local_fire_department</span>
                                                {{ $workout->kcal_per_minute ?? 'N/A' }} kcal/min</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection

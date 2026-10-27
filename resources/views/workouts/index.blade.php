@extends('layouts.app')

@section('title', 'Workout library')

@section('content')
    <div class="container">
        <div class="user-page-heading">
            <div>
                <h1>Find your next workout</h1>
                <p>Explore exercises by muscle group and build a routine that works for you.</p>
            </div>
            <span class="soft-badge text-muted">{{ $workouts->count() }} exercises</span>
        </div>

        @if ($workoutsByPart->isNotEmpty())
            <ul class="nav workout-tabs mb-4" id="bodyPartTabs" role="tablist">
                @foreach ($workoutsByPart as $bodyPart => $partWorkouts)
                    <li class="nav-item" role="presentation">
                        <button class="nav-link @if ($loop->first) active @endif" id="tab-{{ $loop->index }}"
                            data-bs-toggle="tab" data-bs-target="#part-{{ $loop->index }}" type="button"
                            role="tab" aria-controls="part-{{ $loop->index }}"
                            aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                            {{ $bodyPart }} <span class="ms-1 opacity-75">{{ $partWorkouts->count() }}</span>
                        </button>
                    </li>
                @endforeach
            </ul>

            <div class="tab-content" id="bodyPartTabContent">
                @foreach ($workoutsByPart as $bodyPart => $partWorkouts)
                    <div class="tab-pane fade @if ($loop->first) show active @endif"
                        id="part-{{ $loop->index }}" role="tabpanel" aria-labelledby="tab-{{ $loop->index }}">
                        <div class="row g-3">
                            @foreach ($partWorkouts as $workout)
                                <div class="col-sm-6 col-lg-4 col-xl-3">
                                    <button class="workout-card text-start w-100 p-0" type="button"
                                        data-bs-toggle="modal" data-bs-target="#workoutModal{{ $workout->id }}"
                                        aria-label="View {{ $workout->workout_name }} details">
                                        <img src="{{ $workout->image_url }}" class="workout-card-image"
                                            alt="{{ $workout->workout_name }}" loading="lazy">
                                        <div class="workout-card-body">
                                            <h3>{{ $workout->workout_name }}</h3>
                                            <div class="workout-meta">
                                                <span><i class="bi bi-bullseye me-1"></i>{{ $workout->body_part }}</span>
                                                <span><i class="bi bi-fire me-1"></i>{{ $workout->kcal_per_minute ?? 'N/A' }} kcal/min</span>
                                            </div>
                                        </div>
                                    </button>
                                </div>

                                <div class="modal fade" id="workoutModal{{ $workout->id }}" tabindex="-1"
                                    aria-labelledby="modalLabel{{ $workout->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <div>
                                                    <h2 class="modal-title fs-5" id="modalLabel{{ $workout->id }}">{{ $workout->workout_name }}</h2>
                                                    <span class="text-muted small">{{ $workout->body_part }}</span>
                                                </div>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <img src="{{ $workout->image_url }}" class="workout-modal-image mb-3" alt="{{ $workout->workout_name }}">
                                                <div class="d-flex align-items-center gap-2">
                                                    <span class="today-stat-icon"><i class="bi bi-fire"></i></span>
                                                    <span><strong>{{ $workout->kcal_per_minute ?? 'N/A' }} kcal/min</strong><br><small class="text-muted">Estimated calories per minute</small></span>
                                                </div>
                                            </div>
                                            <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Done</button></div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <section class="card">
                <div class="card-body py-5 text-center">
                    <span class="today-stat-icon mx-auto mb-3"><i class="bi bi-activity"></i></span>
                    <h2 class="h5">The workout library is getting ready</h2>
                    <p class="text-muted">Check back soon for exercises to add to your routine.</p>
                </div>
            </section>
        @endif
    </div>
@endsection

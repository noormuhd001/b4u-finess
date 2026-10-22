@extends('layouts.app')

@section('title', 'Your fitness overview')

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@section('content')
    <div class="container">
        <section class="dashboard-hero mb-4">
            <span class="hero-kicker">Your fitness journey</span>
            <h1>Good to see you, {{ $userData->name }}.</h1>
            <p>Progress is built one session at a time. Check in, stay consistent, and celebrate how far you’ve come.</p>
            <div class="d-flex flex-wrap gap-2 mt-4">
                <a href="{{ route('progress.index') }}" class="btn"><i class="bi bi-plus-lg"></i> Log a workout</a>
                <a href="{{ route('workout') }}" class="btn btn-outline-light">Explore workouts</a>
            </div>
        </section>

        <div class="row g-4">
            <div class="col-lg-5">
                <section class="card h-100">
                    <div class="card-body p-4">
                        <div class="dashboard-panel-title">
                            <div>
                                <h2>Today’s session</h2>
                                <p>{{ now()->format('l, F j') }}</p>
                            </div>
                            <span class="today-stat-icon"><i class="bi bi-lightning-charge"></i></span>
                        </div>

                        @if ($todayProgress)
                            <div class="today-stat">
                                <span class="today-stat-icon"><i class="bi bi-speedometer2"></i></span>
                                <div><strong>{{ $todayProgress->current_weight }} kg</strong><br><span class="text-muted">Current weight</span></div>
                            </div>
                            <div class="today-stat">
                                <span class="today-stat-icon"><i class="bi bi-fire"></i></span>
                                <div><strong>{{ number_format((float) $todayProgress->avg_kcal_burned) }} kcal</strong><br><span class="text-muted">Calories logged</span></div>
                            </div>
                            <div class="today-stat mb-0">
                                <span class="today-stat-icon"><i class="bi bi-activity"></i></span>
                                <div>
                                    <strong>{{ $todayProgress->logs->count() }} {{ \Illuminate\Support\Str::plural('exercise', $todayProgress->logs->count()) }}</strong><br>
                                    <span class="text-muted">
                                        @php
                                            $workoutNames = $todayProgress->logs
                                                ->map(fn ($log) => $log->workout->workout_name ?? 'Workout')
                                                ->toArray();
                                        @endphp
                                        {{ implode(', ', $workoutNames) ?: 'No exercises logged' }}
                                    </span>
                                </div>
                            </div>
                        @else
                            <div class="py-4 text-center">
                                <span class="today-stat-icon mx-auto mb-3"><i class="bi bi-calendar2-check"></i></span>
                                <h3 class="h6">Your day is wide open</h3>
                                <p class="text-muted small mb-3">No workout logged today. Make the first move.</p>
                                <a href="{{ route('progress.index') }}" class="btn btn-primary">Start a check-in</a>
                            </div>
                        @endif

                        @if ($userData->goal)
                            <div class="mt-4 pt-3 border-top">
                                <span class="text-muted small">Your current goal</span>
                                <div class="fw-bold mt-1"><i class="bi bi-bullseye me-1"></i>{{ config('constant.goal.' . $userData->goal, 'Build a consistent routine') }}</div>
                            </div>
                        @endif
                    </div>
                </section>
            </div>

            <div class="col-lg-7">
                <section class="card h-100">
                    <div class="card-body p-4">
                        <div class="dashboard-panel-title">
                            <div><h2>Weight trend</h2><p>A clear view of your check-ins over time</p></div>
                            <span class="soft-badge text-muted"><i class="bi bi-graph-up me-1"></i>{{ count($weights) }} check-ins</span>
                        </div>
                        @if (count($weights))
                            <div style="height: 260px"><canvas id="weightChart" aria-label="Weight progress chart"></canvas></div>
                        @else
                            <div class="py-5 text-center">
                                <span class="today-stat-icon mx-auto mb-3"><i class="bi bi-graph-up"></i></span>
                                <h3 class="h6">Your progress takes shape here</h3>
                                <p class="text-muted small">Log your first check-in to start seeing your trend.</p>
                                <a href="{{ route('progress.index') }}" class="btn btn-primary">Log your first check-in</a>
                            </div>
                        @endif
                    </div>
                </section>
            </div>

            <div class="col-lg-6">
                <section class="card h-100">
                    <div class="card-body p-4">
                        <div class="dashboard-panel-title">
                            <div><h2>Community top lifts</h2><p>Big efforts from the NR community</p></div>
                            <span class="today-stat-icon"><i class="bi bi-trophy"></i></span>
                        </div>
                        @forelse ($leaderboard as $index => $record)
                            <div class="leader-item">
                                <div class="d-flex align-items-center gap-3 min-w-0">
                                    <span class="rank-circle {{ $index < 3 ? 'highlight' : '' }}">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                                    <div class="text-truncate">
                                        <strong class="d-block text-truncate">{{ $record->user_name ?? 'Member' }}</strong>
                                        <small class="text-muted">{{ $record->workout_name ?? 'Workout' }} · {{ $record->set_number }} × {{ $record->reps }}</small>
                                    </div>
                                </div>
                                <strong class="text-nowrap">{{ $record->weight }} kg</strong>
                            </div>
                        @empty
                            <p class="text-center text-muted py-4 mb-0">The leaderboard will appear as members log lifts.</p>
                        @endforelse
                    </div>
                </section>
            </div>

            <div class="col-lg-6">
                <section class="card h-100">
                    <div class="card-body p-4">
                        <div class="dashboard-panel-title">
                            <div><h2>Your achievements</h2><p>Every milestone is worth celebrating</p></div>
                            <span class="today-stat-icon"><i class="bi bi-award"></i></span>
                        </div>
                        @if ($allBadges->isNotEmpty())
                            <div class="badge-grid">
                                @foreach ($allBadges as $badge)
                                    @php($isEarned = $earnedBadges->contains('id', $badge->id))
                                    <article class="badge-tile {{ $isEarned ? '' : 'badge-locked' }}">
                                        @if ($badge->icon)
                                            <img src="{{ asset($badge->icon) }}" alt="">
                                        @else
                                            <span class="today-stat-icon mx-auto mb-3"><i class="bi bi-award"></i></span>
                                        @endif
                                        <h3>{{ $badge->name }}</h3>
                                        <p>{{ $isEarned ? 'Earned' : ($badge->description ?: 'Keep training to unlock this badge.') }}</p>
                                    </article>
                                @endforeach
                            </div>
                        @else
                            <p class="text-center text-muted py-4 mb-0">New achievements will show up here.</p>
                        @endif
                    </div>
                </section>
            </div>
        </div>
    </div>

    @if (count($weights))
        @push('scripts')
            <script>
                const weightCanvas = document.getElementById('weightChart');
                if (weightCanvas) {
                    new Chart(weightCanvas, {
                        type: 'line',
                        data: {
                            labels: @json($dates),
                            datasets: [{
                                label: 'Weight (kg)',
                                data: @json($weights),
                                borderColor: '#345a40',
                                backgroundColor: 'rgba(201, 243, 106, .24)',
                                fill: true,
                                tension: .36,
                                borderWidth: 2.5,
                                pointRadius: 4,
                                pointHoverRadius: 6,
                                pointBackgroundColor: '#c9f36a',
                                pointBorderColor: '#345a40',
                            }]
                        },
                        options: {
                            maintainAspectRatio: false,
                            plugins: { legend: { display: false } },
                            scales: {
                                y: { beginAtZero: false, grid: { color: '#edf1ec' }, ticks: { color: '#77847c' } },
                                x: { grid: { display: false }, ticks: { color: '#77847c' } }
                            }
                        }
                    });
                }
            </script>
        @endpush
    @endif
@endsection

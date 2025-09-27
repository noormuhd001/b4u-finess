@extends('layouts.app')

@push('styles')
    <style>
        .dashboard-title {
            font-weight: 700;
            margin-bottom: 1.5rem;
        }

        .card {
            border-radius: 16px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        }

        .card h4 {
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .table thead {
            background: #f8f9fa;
            font-weight: 600;
        }

        .leaderboard td strong {
            font-size: 1.1rem;
        }

        .material-symbols-outlined {
            vertical-align: middle;
            margin-right: 6px;
            font-size: 22px;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@section('content')
    <div class="container my-4">
        <h2 class="dashboard-title">
            <span class="material-symbols-outlined text-primary">speed</span> Dashboard
        </h2>

        <div class="row g-4">
            {{-- Today’s Progress --}}
            <div class="col-lg-6">
                <div class="card p-4">
                    <h4>
                        <span class="material-symbols-outlined text-danger">local_fire_department</span>
                        Today's Workout
                    </h4>

                    @if ($todayProgress)
                        <p>
                            <span class="material-symbols-outlined text-info">person</span>
                            <strong>Weight:</strong> {{ $todayProgress->current_weight }} kg
                        </p>

                        <p>
                            <span class="material-symbols-outlined text-warning">bolt</span>
                            <strong>Total Kcal Burned:</strong> {{ $todayProgress->avg_kcal_burned }}
                        </p>

                        <p>
                            <span class="material-symbols-outlined text-success">checklist</span>
                            <strong>Workouts:</strong>
                            @php
                                $workoutNames = $todayProgress->logs
                                    ->map(fn($log) => $log->workout->workout_name ?? 'Workout')
                                    ->toArray();
                            @endphp
                            {{ implode(', ', $workoutNames) }}
                        </p>

                        <p>
                            <span class="material-symbols-outlined text-warning">flag</span>
                            <strong>Goal:</strong> {{ config('constant.goal.' . $userData->goal) }}
                        </p>
                    @else
                        <h5 class="text-muted">
                            <span class="material-symbols-outlined">event_busy</span>
                            No workout logged today
                        </h5>
                    @endif
                </div>
            </div>

            {{-- Weight Progress Chart --}}
            <div class="col-lg-6">
                <div class="card p-4">
                    <h4>
                        <span class="material-symbols-outlined text-success">monitoring</span>
                        Weight Progress
                    </h4>
                    <canvas id="weightChart" height="150"></canvas>
                </div>
            </div>
        </div>

        <div class="row g-4 mt-3">
            {{-- Leaderboard --}}
            <div class="col-12">
                <div class="card p-4">
                    <h4 class="text-success mb-3">
                        <span class="material-symbols-outlined">trophy</span>
                        Top Lifts Leaderboard
                    </h4>
                    <div class="table-responsive">
                        <table class="table table-striped table-sm align-middle leaderboard">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Workout</th>
                                    <th>Weight (kg)</th>
                                    <th>Sets</th>
                                    <th>Reps</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($leaderboard as $record)
                                    <tr>
                                        <td>
                                            <span class="material-symbols-outlined text-primary">account_circle</span>
                                            {{ $record->user_name }}
                                        </td>
                                        <td>{{ $record->workout_name }}</td>
                                        <td><strong class="text-danger">{{ $record->weight }}</strong></td>
                                        <td>{{ $record->set_number }}</td>
                                        <td>{{ $record->reps }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-muted text-center">No records yet</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Chart Script --}}
    @push('scripts')
        <script>
            const ctx = document.getElementById('weightChart').getContext('2d');
            const weightChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json($dates),
                    datasets: [{
                        label: 'Weight (kg)',
                        data: @json($weights),
                        borderColor: '#FF5733',
                        backgroundColor: 'rgba(255, 87, 51, 0.2)',
                        tension: 0.3,
                        fill: true,
                        pointRadius: 5,
                        pointHoverRadius: 7
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const prev = context.dataIndex > 0 ? context.dataset.data[context.dataIndex -
                                        1] : null;
                                    const curr = context.parsed.y;
                                    let diff = '';
                                    if (prev !== null) {
                                        const change = (curr - prev).toFixed(1);
                                        diff = change > 0 ? ` (+${change} kg)` : ` (${change} kg)`;
                                    }
                                    return `Weight: ${curr} kg${diff}`;
                                }
                            }
                        },
                        legend: {
                            display: true
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: false
                        }
                    }
                }
            });
        </script>
    @endpush
@endsection

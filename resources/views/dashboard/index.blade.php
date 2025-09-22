@extends('layouts.app')

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@section('content')
    <div class="container">
        <h2>Dashboard</h2>

        {{-- Today's Progress --}}
        <div class="card mb-4 p-3">
            @if ($todayProgress)
                <h4>Today's Workout</h4>
                <p><strong>Weight:</strong> {{ $todayProgress->current_weight }} kg</p>
                <p><strong>Avg Kcal Burned:</strong> {{ $todayProgress->avg_kcal_burned }} <small>(10 min per
                        workout)</small></p>
                <p><strong>Workouts:</strong>
                    @php
                        $completedWorkouts = json_decode($todayProgress->workouts_completed, true);
                        $workoutNames = \App\Models\Workout::whereIn('id', $completedWorkouts)
                            ->pluck('workout_name')
                            ->toArray();
                    @endphp
                    {{ implode(', ', $workoutNames) }}
                </p>
            @else
                <h4>No workout logged today</h4>
                <a href="{{ route('progress.index') }}" class="btn btn-primary">Add Progress</a>
            @endif
        </div>

        {{-- Weight Progress Chart --}}
        <div class="card p-3">
            <h4>Weight Progress</h4>
            <canvas id="weightChart" height="150"></canvas>
        </div>
    </div>

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

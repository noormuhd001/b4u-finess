@extends('layouts.app')

@section('title', 'Workout details')

@section('content')
    <div class="container">
        <div class="user-page-heading">
            <div><h1>Session details</h1><p>{{ $progress->workout_on->format('l, F j, Y') }}</p></div>
            <a href="{{ route('progress.track') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Back to history</a>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-sm-6">
                <section class="card h-100"><div class="card-body d-flex align-items-center gap-3">
                    <span class="today-stat-icon"><i class="bi bi-speedometer2"></i></span>
                    <div><small class="text-muted">Body weight</small><h2 class="h4 mb-0">{{ $progress->current_weight }} kg</h2></div>
                </div></section>
            </div>
            <div class="col-sm-6">
                <section class="card h-100"><div class="card-body d-flex align-items-center gap-3">
                    <span class="today-stat-icon"><i class="bi bi-fire"></i></span>
                    <div><small class="text-muted">Calories logged</small><h2 class="h4 mb-0">{{ number_format((float) $progress->avg_kcal_burned) }} kcal</h2></div>
                </div></section>
            </div>
        </div>

        <section class="card">
            <div class="card-body p-4">
                <div class="dashboard-panel-title"><div><h2>Exercises completed</h2><p>{{ $logs->count() }} recorded {{ \Illuminate\Support\Str::plural('exercise', $logs->count()) }}</p></div></div>
                @if ($logs->isEmpty())
                    <p class="text-muted py-3">No individual exercises were recorded in this session.</p>
                @else
                    <div class="table-responsive">
                        <table class="progress-table">
                            <thead><tr><th>Exercise</th><th>Sets</th><th>Reps</th><th>Weight</th><th>Calories</th></tr></thead>
                            <tbody>
                                @foreach ($logs as $log)
                                    <tr>
                                        <td><strong>{{ $log->workout->workout_name ?? 'Workout' }}</strong></td>
                                        <td>{{ $log->set_number }}</td>
                                        <td>{{ $log->reps }}</td>
                                        <td>{{ $log->weight }} kg</td>
                                        <td>{{ $log->kcal_burned }} kcal</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </section>
    </div>
@endsection

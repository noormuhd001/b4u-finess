@extends('layouts.app')
@push('styles')
    <style>
        .table-striped {
            background-color: #FF8C00;
        }

        .btn {
            /* display: inline-block; */
            padding: 12px 25px;
            background: #FF8C00;
            color: #fff;
            text-decoration: none;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            /* cursor: pointer; */
            /* transition: 0.3s; */
        }
    </style>
@endpush
@section('content')
    <div class="container">
        <a href="{{ route('progress.index') }}" class="btn">
            <i class="bi bi-arrow-left-circle"></i> Back
        </a>
        <a href="{{ route('progress.export') }}" class="btn">
            <i class="bi bi-file-earmark-arrow-down"></i> Export
        </a>

        <h2><i class="bi bi-activity"></i> Workout History</h2>

        @if ($progressList->isEmpty())
            <p><i class="bi bi-exclamation-circle"></i> You have not logged any workouts yet.</p>
        @else
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th><i class="bi bi-calendar-event"></i> Date</th>
                        <th><i class="bi bi-person-lines-fill"></i> Weight (kg)</th>
                        <th><i class="bi bi-fire"></i> Total Kcal Burned</th>
                        <th><i class="bi bi-gear"></i> Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($progressList as $progress)
                        <tr>
                            <td>{{ $progress->workout_on->format('d M Y') }}</td>
                            <td>{{ $progress->current_weight }}</td>
                            <td>{{ $progress->avg_kcal_burned }}</td>
                            <td>
                                <a href="{{ route('progress.trackDetailById', $progress->id) }}" class="btn">
                                    <i class="bi bi-eye"></i> View
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

@endsection

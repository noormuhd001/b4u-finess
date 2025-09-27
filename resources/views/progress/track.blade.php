@extends('layouts.app')

@push('styles')
    <style>
        .table-striped {
            background-color: #FF8C00;
        }

        .btn {
            padding: 12px 25px;
            background: #FF8C00;
            color: #fff;
            text-decoration: none;
            border: none;
            border-radius: 8px;
            font-size: 16px;
        }

        .btn-right {
            float: right;
            padding: 12px 25px;
            background: #FF8C00;
            color: #fff;
            text-decoration: none;
            border: none;
            border-radius: 8px;
            font-size: 16px;
        }

        .material-symbols-outlined {
            vertical-align: middle;
            font-size: 20px;
            margin-right: 4px;
        }
    </style>
@endpush

@section('content')
    <div class="container">
        <a href="{{ route('progress.index') }}" class="btn">
            <span class="material-symbols-outlined">arrow_back</span> Back
        </a>
        <a href="{{ route('progress.export') }}" class="btn-right">
            <span class="material-symbols-outlined">download</span> Export
        </a>

        <h2>
            <span class="material-symbols-outlined">fitness_center</span> Workout History
        </h2>

        @if ($progressList->isEmpty())
            <p>
                <span class="material-symbols-outlined">error_outline</span> You have not logged any workouts yet.
            </p>
        @else
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th><span class="material-symbols-outlined">event</span> Date</th>
                        <th><span class="material-symbols-outlined">person</span> Weight (kg)</th>
                        <th><span class="material-symbols-outlined">local_fire_department</span> Total Kcal Burned</th>
                        <th><span class="material-symbols-outlined">settings</span> Action</th>
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
                                    <span class="material-symbols-outlined">visibility</span> View
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection

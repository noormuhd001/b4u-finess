@extends('layouts.app')
@push('styles')
<style>
    .table-striped{
        background-color: #FF8C00;
    }
</style>
@endpush
@section('content')
<div class="container">
    <h2>Workout History</h2>

    @if($progressList->isEmpty())
        <p>You have not logged any workouts yet.</p>
    @else
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Workouts Completed</th>
                    <th>Weight (kg)</th>
                    <th>Avg Kcal Burned</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($progressList as $progress)
                    @php
                        $workoutNames = \App\Models\Workout::whereIn('id', json_decode($progress->workouts_completed, true))
                            ->pluck('workout_name')
                            ->toArray();
                    @endphp
                    <tr>
                        <td>{{ $progress->workout_on->format('d M Y') }}</td>
                        <td>{{ implode(', ', $workoutNames) }}</td>
                        <td>{{ $progress->current_weight }}</td>
                        <td>{{ $progress->avg_kcal_burned }}</td>
                        <td><a href="{{ route('progress.trackDetailById', $progress->id) }}" class="btn btn-sm btn-primary">View</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection

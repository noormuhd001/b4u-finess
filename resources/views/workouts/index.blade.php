@extends('layouts.app')

@section('content')
<div class="container">
    @foreach($workoutsByPart as $bodyPart => $partWorkouts)
        <h2 class="mt-5 mb-3 text-primary">{{ $bodyPart }}</h2>
        <div class="row">
            @foreach($partWorkouts as $workout)
                <div class="col-sm-6 col-md-4 col-lg-3 mb-4">
                    <div class="card h-100 shadow-sm border-0 workout-card">
                        @if($workout->image)
                            <img src="{{ asset($workout->image) }}"
                                 class="card-img-top workout-img"
                                 alt="{{ $workout->workout_name }}">
                        @endif
                        <div class="card-body text-center">
                            <h5 class="card-title">{{ $workout->workout_name }}</h5>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endforeach
</div>
@endsection

@section('styles')
<style>
    .workout-card {
        transition: transform 0.3s, box-shadow 0.3s;
        border-radius: 15px;
        overflow: hidden;
    }

    .workout-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.2);
    }

    .workout-img {
        width: 100%;
        height: 200px; /* Fixed height for uniformity */
        object-fit: cover; /* Ensures image fills the area without distortion */
    }

    .card-title {
        font-weight: 600;
        color: #333;
    }

    h2 {
        border-bottom: 2px solid #007bff;
        padding-bottom: 5px;
    }
</style>
@endsection

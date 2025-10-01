@extends('layouts.app')

@section('content')
    @push('styles')
        <style>
            .same-height-img {
                height: 400px;
                object-fit: cover;
                width: 100%;
                border-radius: 0.5rem;
            }


            .dark-mode h1,
            .dark-mode h2,
            .dark-mode h3,
            .dark-mode h5,
            .dark-mode h6 {
                color: #fff;
            }

            .dark-mode p,
            .dark-mode li,
            .dark-mode .card-text {
                color: #e0e0e0;
            }
        </style>
    @endpush
    <div class="container py-5">
        <!-- Page Header -->
        <div class="text-center mb-5">
            <h1 class="display-4">About Us</h1>
            <p class="lead text-muted">Learn more about our mission, values, and team.</p>
        </div>

        <!-- Our Mission -->
        <div class="row align-items-center mb-5">
            <div class="col-md-6">
                <img src="{{ asset('img/mission.png') }}" alt="Our Mission" class="img-fluid rounded shadow">
            </div>
            <div class="col-md-6">
                <h2>Our Mission</h2>
                <p>
                    At B4U Fitness, our mission is to empower individuals to achieve their fitness goals
                    through a combination of expert guidance, innovative tools, and a supportive community. We
                    strive to make wellness accessible and enjoyable for everyone.
                </p>
            </div>
        </div>

        <!-- Our Values -->
        <div class="row align-items-center mb-5 flex-md-row-reverse">
            <div class="col-md-6">
                <img src="{{ asset('img/values.png') }}" alt="Our Values" class="img-fluid rounded shadow">
            </div>
            <div class="col-md-6">
                <h2>Our Values</h2>
                <ul>
                    <li><strong>Integrity:</strong> We uphold honesty and transparency in everything we do.</li>
                    <li><strong>Innovation:</strong> We constantly improve to provide cutting-edge solutions.</li>
                    <li><strong>Community:</strong> We build a supportive environment for all our members.</li>
                    <li><strong>Excellence:</strong> We aim to deliver top-quality services and experiences.</li>
                </ul>
            </div>
        </div>

        <!-- Our Team -->
        <div class="text-center mb-5">
            <h2>Meet Our Team</h2>
            <p class="text-muted">A passionate group dedicated to your fitness journey.</p>
        </div>
        <div class="row text-center">
            <div class="col-sm-6 col-md-4 mb-4">
                <div class="card border-0 shadow">
                    <img src="{{ asset('img/team1.png') }}" class="card-img-top same-height-img" alt="Team Member 1">
                    <div class="card-body">
                        <h5 class="card-title">John Doe</h5>
                        <p class="card-text text-muted">Founder & Head Trainer</p>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-4 mb-4">
                <div class="card border-0 shadow">
                    <img src="{{ asset('img/team2.png') }}" class="card-img-top same-height-img" alt="Team Member 2">
                    <div class="card-body">
                        <h5 class="card-title">Jane Smith</h5>
                        <p class="card-text text-muted">Nutrition Expert</p>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-4 mb-4">
                <div class="card border-0 shadow">
                    <img src="{{ asset('img/team3.png') }}" class="card-img-top same-height-img" alt="Team Member 3">
                    <div class="card-body">
                        <h5 class="card-title">Alex Johnson</h5>
                        <p class="card-text text-muted">Fitness Coach</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Call to Action -->
        <div class="text-center mt-5">
            <h3>Join Us Today!</h3>
            <p class="lead text-muted">Start your fitness journey with B4U Fitness.</p>
            <a href="{{ route('register') }}" class="btn btn-primary btn-lg">Get Started</a>
        </div>
    </div>
@endsection

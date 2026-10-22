@extends('layouts.app')

@section('title', 'About NR Fitness')

@section('content')
    <div class="container">
        <section class="dashboard-hero mb-5">
            <span class="hero-kicker">Movement for everyone</span>
            <h1>Fitness that meets you where you are.</h1>
            <p>NR Fitness helps make healthy progress feel achievable with simple tools, clear tracking, and a community that keeps showing up.</p>
            <a href="{{ route('workout') }}" class="btn mt-3">Explore workouts</a>
        </section>

        <section class="row g-4 align-items-center mb-5">
            <div class="col-lg-6">
                <img src="{{ asset('img/mission.png') }}" alt="People working toward their fitness goals" class="img-fluid rounded-4 shadow-sm">
            </div>
            <div class="col-lg-6">
                <span class="hero-kicker text-success">Our mission</span>
                <h2 class="mt-2">Make progress personal.</h2>
                <p class="text-muted">At NR Fitness, our mission is to empower people to reach their fitness goals through expert guidance, useful tools, and a supportive community. We want wellness to feel accessible, sustainable, and rewarding.</p>
            </div>
        </section>

        <section class="card mb-5">
            <div class="card-body p-4 p-lg-5">
                <div class="dashboard-panel-title">
                    <div><h2>What we believe</h2><p>Better habits are built together.</p></div>
                </div>
                <div class="row g-3">
                    <div class="col-sm-6 col-lg-3"><div class="settings-card h-100"><div><h2><i class="bi bi-shield-check me-2"></i>Integrity</h2><p class="mb-0">Honesty and transparency in everything we do.</p></div></div></div>
                    <div class="col-sm-6 col-lg-3"><div class="settings-card h-100"><div><h2><i class="bi bi-lightbulb me-2"></i>Innovation</h2><p class="mb-0">Thoughtful improvements for a better training experience.</p></div></div></div>
                    <div class="col-sm-6 col-lg-3"><div class="settings-card h-100"><div><h2><i class="bi bi-people me-2"></i>Community</h2><p class="mb-0">A welcoming space for every level and every goal.</p></div></div></div>
                    <div class="col-sm-6 col-lg-3"><div class="settings-card h-100"><div><h2><i class="bi bi-stars me-2"></i>Excellence</h2><p class="mb-0">Care and quality in every part of the journey.</p></div></div></div>
                </div>
            </div>
        </section>

        <section class="text-center mb-4">
            <h2>Here for your next step</h2>
            <p class="text-muted">Build a routine, find your pace, and keep moving forward.</p>
            <a href="{{ route('progress.index') }}" class="btn btn-primary mt-2">Log your progress</a>
        </section>
    </div>
@endsection

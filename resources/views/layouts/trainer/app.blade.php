<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Trainer portal') | NR Fitness</title>
    <link rel="icon" type="image/png" href="{{ asset('img/favicon-rounded.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Oxanium:wght@400;500;600;700;800&family=Rajdhani:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/trainer-portal.css') }}">
</head>
<body class="trainer-portal">
    <header class="trainer-topbar">
        <div class="trainer-topbar-inner">
            <a class="trainer-brand" href="{{ route('trainer.dashboard') }}">
                <img src="{{ asset('img/logo-rm.png') }}" alt="">
                <span>NR FITNESS <small>TRAINER PORTAL</small></span>
            </a>
            <nav class="trainer-nav" aria-label="Trainer navigation">
                <a class="{{ request()->routeIs('trainer.dashboard') ? 'active' : '' }}" href="{{ route('trainer.dashboard') }}">My members</a>
            </nav>
            <div class="trainer-user">
                <span class="trainer-avatar">{{ strtoupper(substr(Auth::guard('trainer')->user()->name, 0, 1)) }}</span>
                <span class="trainer-user-name">{{ Auth::guard('trainer')->user()->name }}</span>
                <form method="POST" action="{{ route('trainer.logout') }}">
                    @csrf
                    <button class="trainer-logout" type="submit"><i class="bi bi-box-arrow-right"></i><span>Sign out</span></button>
                </form>
            </div>
        </div>
    </header>
    <main class="trainer-main">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Please review the form:</strong>
                <ul class="mb-0 mt-1">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif
        @yield('content')
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

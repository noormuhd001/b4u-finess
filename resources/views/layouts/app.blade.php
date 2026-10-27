<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'NR Fitness') | NR Fitness</title>
    <link rel="icon" type="image/png" href="{{ asset('img/logo-rm.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    @stack('styles')
</head>

<body class="user-app d-flex flex-column min-vh-100 {{ auth()->check() && auth()->user()->dark_mode ? 'dark-mode' : '' }}">
    @include('layouts.navbar')

    <main class="user-main flex-fill">
        @yield('content')
    </main>

    <footer class="user-footer mt-auto">
        <div class="container">
            <div class="row align-items-center gy-3">
                <div class="col-md-6">
                    <div class="footer-brand">NR FITNESS</div>
                    <small>Track your progress. Build your strongest self.</small>
                </div>
                <div class="col-md-6 text-md-end">
                    <div class="d-flex flex-wrap justify-content-md-end gap-3">
                        <a href="{{ route('about-us') }}">About</a>
                        <a href="{{ route('termsAndConditions') }}">Terms</a>
                        <a href="{{ route('privacyPolicy') }}">Privacy</a>
                    </div>
                    <small class="d-block mt-2">&copy; {{ now()->year }} NR Fitness</small>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    @stack('scripts')
</body>

</html>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'B4U Fitness')</title>
    <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Material Symbols -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />

    <!-- Select2 CSS -->
    {{-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet" /> --}}
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    @stack('styles')
</head>

<body class=" d-flex flex-column min-vh-100 {{ auth()->check() && auth()->user()->dark_mode ? 'dark-mode' : '' }}">

    <!-- Navbar -->
    @include('layouts.navbar')

    <!-- Main Content -->
    <main class="flex-fill py-5">
        <div class="container">
            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <!-- Footer -->
    <!-- Footer -->
    <footer class="bg-dark text-white pt-4 pb-3 mt-auto">
        <div class="container">
            <div class="row align-items-center">

                <!-- Left: App Info / Tagline -->
                <div class="col-md-6 text-center text-md-start mb-2 mb-md-0">
                    &copy; {{ date('Y') }} <strong>B4U Fitness</strong> <br>
                    <small class="text-muted">Track • Create • Conquer</small>
                </div>

                <!-- Right: Developer Credit + Social -->
                <div class="col-md-6 text-center text-md-end">
                    <div class="mb-1">
                        Developed by
                        <a href="https://github.com/noormuhammed" target="_blank"
                            class="text-warning text-decoration-none">
                            Noor Muhammed
                        </a>
                    </div>
                    <!-- Social Icons (optional) -->
                    <div>
                        <a href="https://github.com/noormuhd001" target="_blank" class="text-white me-2">
                            <i class="bi bi-github" style="font-size:1.2rem;"></i>
                        </a>
                        <a href="https://www.linkedin.com/in/noormuhd/" target="_blank" class="text-white me-2">
                            <i class="bi bi-linkedin" style="font-size:1.2rem;"></i>
                        </a>
                        <a href="https://www.instagram.com/noor.muhd_?igsh=YjlraGxvZzc1aW10&utm_source=qr"
                            target="_blank" class="text-white">
                            <i class="bi bi-instagram" style="font-size:1.2rem;"></i>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </footer>



    <!-- jQuery, Bootstrap JS, Select2 JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>

    @stack('scripts')
</body>

</html>

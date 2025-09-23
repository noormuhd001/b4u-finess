<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'B4U Fitness')</title>
    <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet" />
    @stack('styles')
    <style>
        .navbar-custom {
            background: linear-gradient(90deg, #B22222, #FF8C00);
            border-radius: 12px;
        }

        .navbar-brand span {
            font-weight: 700;
            font-size: 1.2rem;
        }

        .dropdown-menu {
            min-width: 180px;
        }

        footer {
            background: linear-gradient(90deg, #B22222, #FF8C00);
            border-radius: 12px;
        }

        img {
            border-radius: 10px;
        }
    </style>
</head>

<body class="bg-light d-flex flex-column min-vh-100">

    <!-- Navbar -->
    @include('layouts.navbar')

    <!-- Main Content -->
    <main class="flex-fill py-5">
        <div class="container">
            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer class="text-white text-center py-3 mt-auto">
        &copy; {{ date('Y') }} B4U Fitness. Track • Create • Conquer.
    </footer>

    <!-- jQuery, Bootstrap JS, Select2 JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>

    @stack('scripts')
</body>

</html>

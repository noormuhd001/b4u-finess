<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'B4U Fitness')</title>

    <link rel="icon" href="{{ asset('img/logo-rm.png') }}">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Google Material Symbols -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">



    <!-- Admin CSS -->
    <link rel="stylesheet" href="{{ asset('css/admin-style.css') }}">

    @stack('styles')
</head>

<body>

    <div id="wrapper">

        <!-- Sidebar -->
        @include('layouts.admin.sidebar')

        <!-- Page Content -->
        <div id="content">

            <!-- Topbar -->
            <nav class="topbar">
                <button id="menu-toggle" class="toggle-btn">
                    <i class="bi bi-list"></i>
                </button>

                <div class="ms-auto d-flex align-items-center">
                    <span class="me-3">{{ Auth::user()->name }}</span>
                </div>
            </nav>

            <!-- Main -->
            <main class="p-4">
                @yield('content')
            </main>

        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Pure JS toggle -->
    <script>
        document.getElementById("menu-toggle").addEventListener("click", function() {
            document.getElementById("wrapper").classList.toggle("collapsed");
        });
    </script>

    @stack('scripts')

</body>

</html>

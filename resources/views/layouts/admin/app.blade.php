<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Console') | NR Fitness</title>
    <link rel="icon" type="image/png" href="{{ asset('img/favicon-rounded.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/admin-style.css') }}">
    @stack('styles')
</head>

<body>
    <div id="wrapper">
        @include('layouts.admin.sidebar')
        <button id="sidebar-overlay" class="sidebar-overlay" type="button" aria-label="Close navigation"></button>

        <div id="content">
            <header class="topbar">
                <button id="menu-toggle" class="toggle-btn" type="button" aria-label="Toggle navigation" aria-expanded="true">
                    <i class="bi bi-list"></i>
                </button>
                <div class="topbar-context">
                    <span class="eyebrow">NR FITNESS / ADMIN</span>
                    <span class="topbar-title">@yield('page-heading', 'Admin overview')</span>
                </div>
                <div class="topbar-right">
                    <span class="status-indicator"><span></span> System online</span>
                    <div class="topbar-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                </div>
            </header>

            <main class="admin-main">
                @if (session('success'))
                    <div class="alert admin-alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle-fill"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert admin-alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-circle-fill"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if ($errors->any())
                    <div class="alert admin-alert alert-danger" role="alert">
                        <i class="bi bi-exclamation-circle-fill"></i>
                        <div>
                            <strong>Please review the form:</strong>
                            <ul class="mb-0 mt-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
                @yield('content')
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const adminWrapper = document.getElementById('wrapper');
        const menuToggle = document.getElementById('menu-toggle');
        const sidebarOverlay = document.getElementById('sidebar-overlay');
        const mobileNavigation = window.matchMedia('(max-width: 767.98px)');

        function updateNavigationState() {
            const isOpen = mobileNavigation.matches
                ? adminWrapper.classList.contains('collapsed')
                : !adminWrapper.classList.contains('collapsed');
            menuToggle.setAttribute('aria-expanded', String(isOpen));
        }

        menuToggle.addEventListener('click', function () {
            adminWrapper.classList.toggle('collapsed');
            updateNavigationState();
        });

        sidebarOverlay.addEventListener('click', function () {
            adminWrapper.classList.remove('collapsed');
            updateNavigationState();
        });

        mobileNavigation.addEventListener('change', updateNavigationState);
        updateNavigationState();
    </script>
    @stack('scripts')
</body>

</html>

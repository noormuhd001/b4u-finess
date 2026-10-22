<nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="{{ Auth::user()?->role === 1 ? route('admin.dashboard') : route('dashboard') }}">
            <img src="{{ asset('img/logo-rm.png') }}" alt="" class="me-2">
            <span>NR FITNESS</span>
        </a>

        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            @auth
                @if (Auth::user()->role === 0)
                    <ul class="navbar-nav mx-auto">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">Overview</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('workout') ? 'active' : '' }}" href="{{ route('workout') }}">Workouts</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('progress.*') ? 'active' : '' }}" href="{{ route('progress.index') }}">Progress</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('about-us') ? 'active' : '' }}" href="{{ route('about-us') }}">About</a>
                        </li>
                    </ul>
                @else
                    <ul class="navbar-nav mx-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.dashboard') }}">Admin console</a>
                        </li>
                    </ul>
                @endif

                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link user-menu-link dropdown-toggle {{ request()->routeIs('profile.*', 'admin.profile.*') ? 'active' : '' }}"
                            href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="user-menu-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                            <span>{{ Auth::user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li>
                                <a class="dropdown-item" href="{{ Auth::user()->role === 1 ? route('admin.profile.index') : route('profile.index') }}">
                                    <i class="bi bi-person me-2"></i>My profile
                                </a>
                            </li>
                            @if (Auth::user()->role === 0)
                                <li><a class="dropdown-item" href="{{ route('settings.index') }}"><i class="bi bi-sliders me-2"></i>Settings</a></li>
                            @endif
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}" class="m-0">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i>Sign out</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            @else
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Sign in</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('signUp') }}">Create account</a></li>
                </ul>
            @endauth
        </div>
    </div>
</nav>

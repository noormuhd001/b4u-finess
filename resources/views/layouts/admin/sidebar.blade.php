<div id="sidebar">

    <!-- Logo -->
    <div class="sidebar-header text-center">
        <img src="{{ asset('img/logo-rm.png') }}" width="60">
        <h5 class="mt-2 menu-text">B4U Fitness</h5>
    </div>

    <!-- Menu -->
    <ul class="sidebar-menu">

        <li class="{{ request()->routeIs('admin.dashboard*') ? 'active' : '' }}">
            <a href="{{ route('admin.dashboard') }}">
                <i class="bi bi-speedometer2"></i>
                <span class="menu-text">Dashboard</span>
            </a>
        </li>

        <li class="{{ request()->routeIs('admin.progress.reviews*') ? 'active' : '' }}">
            <a href="{{ route('admin.progress.reviews') }}">
                <i class="bi bi-graph-up"></i>
                <span class="menu-text">Progress Review</span>
            </a>
        </li>

        <li class="{{ request()->routeIs('admin.user-management*') ? 'active' : '' }}">
            <a href="{{ route('admin.user-management.index') }}">
                <i class="bi bi-people"></i>
                <span class="menu-text">User Management</span>
            </a>
        </li>

        <hr>

        <li>
            <a href="{{ route('profile.index') }}">
                <i class="bi bi-person"></i>
                <span class="menu-text">Profile</span>
            </a>
        </li>

        <li>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit">
                    <i class="bi bi-box-arrow-right"></i>
                    <span class="menu-text">Logout</span>
                </button>
            </form>
        </li>

    </ul>
</div>

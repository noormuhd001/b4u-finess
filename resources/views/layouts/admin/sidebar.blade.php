<aside id="sidebar" aria-label="Admin navigation">
    <a class="admin-brand" href="{{ route('admin.dashboard') }}">
        <span class="brand-mark"><img src="{{ asset('img/logo-rm.png') }}" alt=""></span>
        <span class="menu-text brand-copy">
            <strong>NR <span>FITNESS</span></strong>
            <small>ADMIN CONSOLE</small>
        </span>
    </a>

    <div class="sidebar-caption menu-text">WORKSPACE</div>
    <ul class="sidebar-menu">
        <li class="{{ request()->routeIs('admin.dashboard*') ? 'active' : '' }}">
            <a href="{{ route('admin.dashboard') }}">
                <i class="bi bi-grid-1x2"></i><span class="menu-text">Overview</span>
            </a>
        </li>
        <li class="{{ request()->routeIs('admin.user-management*') ? 'active' : '' }}">
            <a href="{{ route('admin.user-management.index') }}">
                <i class="bi bi-people"></i><span class="menu-text">Users</span>
            </a>
        </li>
        <li class="{{ request()->routeIs('admin.attendance*') ? 'active' : '' }}">
            <a href="{{ route('admin.attendance.index') }}">
                <i class="bi bi-calendar-check"></i><span class="menu-text">Attendance</span>
            </a>
        </li>
        <li class="{{ request()->routeIs('admin.membership-plans*') ? 'active' : '' }}">
            <a href="{{ route('admin.membership-plans.index') }}">
                <i class="bi bi-card-checklist"></i><span class="menu-text">Membership plans</span>
            </a>
        </li>
        <li class="{{ request()->routeIs('admin.workouts*') ? 'active' : '' }}">
            <a href="{{ route('admin.workouts.index') }}">
                <i class="bi bi-activity"></i><span class="menu-text">Workouts</span>
            </a>
        </li>
        <li class="{{ request()->routeIs('admin.badges*') ? 'active' : '' }}">
            <a href="{{ route('admin.badges.index') }}">
                <i class="bi bi-award"></i><span class="menu-text">Badges</span>
            </a>
        </li>
    </ul>

    <div class="sidebar-footer">
        <div class="admin-profile">
            <span class="profile-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
            <span class="menu-text profile-copy">
                <strong>{{ Auth::user()->name }}</strong>
                <small>Administrator</small>
            </span>
        </div>
        <a class="sidebar-logout" href="{{ route('admin.profile.index') }}">
            <i class="bi bi-person"></i><span class="menu-text">My profile</span>
        </a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="sidebar-logout" type="submit">
                <i class="bi bi-box-arrow-right"></i><span class="menu-text">Sign out</span>
            </button>
        </form>
    </div>
</aside>

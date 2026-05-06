<ul class="navbar-nav wedding-sidebar sidebar accordion" id="accordionSidebar">
    <div class="sidebar-brand text-center py-4">
        <div class="brand-title">
            {{ env('APP_NAME') }}
        </div>
    </div>

    <li class="nav-item {{ Request::is('modules/dashboard') ? 'active' : '' }}">
        <a class="nav-link" href="{{ url('/modules/dashboard') }}">
            <i class="fas fa-chart-line"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <li class="nav-item {{ Request::is('modules/kategori*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ url('/modules/kategori') }}">
            <i class="fas fa-layer-group"></i>
            <span>Kategori</span>
        </a>
    </li>

    <li class="nav-item {{ Request::is('modules/guest') || Request::is('modules/guest/*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ url('/modules/guest') }}">
            <i class="fas fa-user-friends"></i>
            <span>Tamu Undangan</span>
        </a>
    </li>

    <li class="nav-item {{ Request::is('modules/guest-public*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ url('/modules/guest-public') }}">
            <i class="fas fa-user"></i>
            <span>Tamu Luar</span>
        </a>
    </li>

    <li class="nav-item {{ Request::is('modules/scan-qr-guest') ? 'active' : '' }}">
        <a class="nav-link" href="{{ url('/modules/scan-qr-guest') }}">
            <i class="fas fa-qrcode"></i>
            <span>Scan QR Tamu</span>
        </a>
    </li>

    <li class="nav-item {{ Request::is('modules/input-attendance') ? 'active' : '' }}">
        <a class="nav-link" href="{{ url('/modules/input-attendance') }}">
            <i class="fas fa-pen"></i>
            <span>Input Kehadiran</span>
        </a>
    </li>

    <li class="nav-item {{ Request::is('modules/history-guest') ? 'active' : '' }}">
        <a class="nav-link" href="{{ url('/modules/history-guest') }}">
            <i class="fas fa-history"></i>
            <span>Riwayat Tamu</span>
        </a>
    </li>

    <li class="nav-item {{ Request::is('modules/role*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ url('/modules/role') }}">
            <i class="fas fa-user-shield"></i>
            <span>Role</span>
        </a>
    </li>

    <li class="nav-item {{ Request::is('modules/users*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ url('/modules/users') }}">
            <i class="fas fa-users"></i>
            <span>Users</span>
        </a>
    </li>
</ul>

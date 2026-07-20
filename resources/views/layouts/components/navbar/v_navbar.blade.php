@php
    $pageTitle = trim($__env->yieldPushContent('title-modules')) ?: 'Dashboard';
    $user = Auth::user();
    $pageSubtitle = 'Ringkasan modul dan akses cepat operasional';
@endphp

<nav class="navbar navbar-expand navbar-light topbar static-top">
    <button id="sidebarToggleTop" class="btn btn-link text-decoration-none topbar-toggle mr-3">
        <i class="fa fa-bars"></i>
    </button>

    <div class="topbar-page mr-3 overflow-hidden">
        <div class="topbar-title text-truncate">{{ $pageTitle }}</div>
        <div class="topbar-subtitle text-truncate">{{ $pageSubtitle }}</div>
    </div>

    <ul class="navbar-nav ml-auto align-items-center">
        <li class="nav-item d-none d-lg-flex align-items-center mr-2">
            <div class="topbar-date-simple">
                {{ now()->translatedFormat('l, d F Y') }}
            </div>
        </li>

        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle px-2 topbar-user-link" href="#" id="userDropdown" role="button"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <div class="d-flex align-items-center">
                    <div class="text-right mr-2 d-none d-md-block">
                        <div class="font-weight-bold text-dark mb-0 topbar-user-name">{{ $user->nama }}</div>
                    </div>
                    <img class="img-profile rounded-circle" src="{{ asset('templating/img/undraw_profile.svg') }}"
                        style="border: 3px solid rgba(33,85,75,.12);">
                </div>
            </a>

            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in topbar-profile-menu" aria-labelledby="userDropdown">
                <div class="topbar-profile-head">
                    <img class="rounded-circle" src="{{ asset('templating/img/undraw_profile.svg') }}" alt="Profile">
                    <div>
                        <div class="font-weight-bold text-dark">{{ $user->nama }}</div>
                        <div class="small text-muted">{{ $user->username }}</div>
                    </div>
                </div>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="{{ url('/modules/dashboard') }}">
                    <i class="fas fa-home fa-sm fa-fw mr-2 text-gray-400"></i>
                    Dashboard
                </a>
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                    Logout
                </a>
            </div>
        </li>
    </ul>
</nav>

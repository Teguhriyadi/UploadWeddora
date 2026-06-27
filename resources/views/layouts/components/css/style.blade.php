<link href="{{ asset('templating/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
<link
    href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
    rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<link href="{{ asset('templating/css/sb-admin-2.min.css') }}" rel="stylesheet">

<style>
    :root {
        --app-bg: #f4f7fb;
        --app-surface: #ffffff;
        --app-border: rgba(148, 163, 184, 0.18);
        --app-text: #18212f;
        --app-muted: #6b7280;
        --app-primary: #21554b;
        --app-primary-soft: rgba(33, 85, 75, 0.1);
        --app-accent: #d8b46a;
        --app-shadow: 0 18px 48px rgba(15, 23, 42, 0.08);
        --sidebar-bg-top: #132c27;
        --sidebar-bg-bottom: #0d1d1a;
        --sidebar-text: rgba(255, 255, 255, 0.78);
    }

    html {
        scroll-behavior: smooth;
    }

    body {
        background:
            radial-gradient(circle at top right, rgba(216, 180, 106, 0.08), transparent 28%),
            linear-gradient(180deg, #f7f9fc 0%, #eef3f8 100%);
        font-family: 'Inter', sans-serif;
        color: var(--app-text);
        overflow-x: hidden;
    }

    #wrapper {
        background: transparent;
    }

    #content-wrapper {
        min-height: 100vh;
        background: transparent;
    }

    #content {
        padding-bottom: 16px;
    }

    .container-fluid {
        padding-left: 1.5rem;
        padding-right: 1.5rem;
    }

    .card,
    .topbar,
    .dropdown-menu {
        border: 1px solid var(--app-border);
    }

    .card {
        border-radius: 22px;
        box-shadow: var(--app-shadow);
        overflow: hidden;
    }

    .card-header {
        padding: 1rem 1.25rem;
        background: linear-gradient(180deg, rgba(255, 255, 255, 0.96), rgba(248, 250, 252, 0.9));
        border-bottom: 1px solid rgba(148, 163, 184, 0.14);
    }

    .card-footer {
        background: rgba(248, 250, 252, 0.85);
        border-top: 1px solid rgba(148, 163, 184, 0.14);
    }

    .sidebar {
        background: linear-gradient(180deg, var(--sidebar-bg-top), var(--sidebar-bg-bottom)) !important;
        box-shadow: 14px 0 40px rgba(15, 23, 42, 0.16);
    }

    .sidebar .sidebar-brand {
        height: auto;
        padding: 1.4rem 1rem 1.1rem;
        color: #fff !important;
        font-weight: 700;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 0.2rem;
    }

    .sidebar-brand-icon {
        width: 40px;
        height: 40px;
        border-radius: 14px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 0.35rem;
        color: var(--app-accent);
        background: rgba(255, 255, 255, 0.1);
    }

    .sidebar-brand-title {
        display: block;
        font-size: 1rem;
        line-height: 1.2;
        letter-spacing: 0.01em;
    }

    .sidebar-brand-subtitle {
        display: block;
        margin-top: 0.35rem;
        font-size: 0.72rem;
        font-weight: 500;
        color: rgba(255, 255, 255, 0.6);
        letter-spacing: 0.03em;
    }

    .sidebar-heading {
        padding: 0.75rem 1rem 0.45rem;
        font-size: 0.69rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        color: rgba(255, 255, 255, 0.42);
    }

    .sidebar .nav-item {
        padding: 0 0.75rem;
        margin-bottom: 0.15rem;
    }

    .sidebar .nav-item .nav-link {
        min-height: 46px;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.8rem 0.95rem;
        border-radius: 14px;
        color: var(--sidebar-text) !important;
        transition: all 0.22s ease;
    }

    .sidebar .nav-item .nav-link i {
        width: 18px;
        min-width: 18px;
        color: rgba(255, 255, 255, 0.44) !important;
        transition: all 0.22s ease;
    }

    .sidebar .nav-item .nav-link span {
        display: inline-block;
        white-space: nowrap;
    }

    .sidebar .nav-item .nav-link:hover {
        color: #fff !important;
        transform: translateX(2px);
        background: rgba(255, 255, 255, 0.08);
    }

    .sidebar .nav-item .nav-link:hover i {
        color: var(--app-accent) !important;
    }

    .sidebar .nav-item.active .nav-link {
        color: #fff !important;
        font-weight: 700;
        background: linear-gradient(135deg, rgba(216, 180, 106, 0.2), rgba(216, 180, 106, 0.08));
        box-shadow: inset 0 0 0 1px rgba(216, 180, 106, 0.2);
    }

    .sidebar .nav-item.active .nav-link i {
        color: var(--app-accent) !important;
    }

    .sidebar hr.sidebar-divider {
        margin: 1rem;
        border-top: 1px solid rgba(255, 255, 255, 0.08);
    }

    .sidebar {
        scrollbar-width: thin;
        scrollbar-color: var(--app-accent) transparent;
    }

    .sidebar.toggled .nav-item {
        padding: 0 0.45rem;
    }

    .sidebar.toggled .nav-item .nav-link {
        justify-content: center;
        gap: 0;
        padding-left: 0;
        padding-right: 0;
        text-align: center;
    }

    .sidebar.toggled .sidebar-heading,
    .sidebar.toggled .sidebar-brand-subtitle,
    .sidebar.toggled .sidebar-brand-title,
    .sidebar.toggled .nav-item .nav-link span {
        display: none;
    }

    .sidebar.toggled .sidebar-brand {
        align-items: center;
        justify-content: center;
        padding-left: 0.5rem;
        padding-right: 0.5rem;
        text-align: center;
    }

    .sidebar.toggled .sidebar-brand-icon {
        margin-bottom: 0;
    }

    .sidebar.toggled .nav-item .nav-link i {
        width: auto;
        min-width: 0;
        margin: 0;
        font-size: 1rem;
    }

    .sidebar.toggled .nav-item.active .nav-link {
        box-shadow: inset 0 0 0 1px rgba(216, 180, 106, 0.2);
    }

    .topbar {
        margin: 0.85rem 1.25rem 0.9rem;
        padding: 0.6rem 0.85rem;
        border-radius: 16px;
        background: rgba(255, 255, 255, 0.9) !important;
        backdrop-filter: blur(10px);
        box-shadow: 0 8px 20px rgba(15, 23, 42, 0.05);
        position: relative;
        z-index: 1040;
    }

    .topbar .dropdown-list .dropdown-header,
    .topbar .dropdown .dropdown-menu {
        border-radius: 16px;
    }

    .topbar .nav-item.dropdown {
        position: relative;
    }

    .topbar .dropdown-menu {
        top: calc(100% + 10px);
        right: 0;
        left: auto;
        min-width: 210px;
        padding: 0.5rem;
        border: 1px solid rgba(148, 163, 184, 0.16);
        box-shadow: 0 18px 34px rgba(15, 23, 42, 0.1);
        z-index: 1080;
    }

    .topbar-title {
        font-size: 0.98rem;
        line-height: 1.2;
        font-weight: 700;
    }

    .topbar-subtitle {
        font-size: 0.7rem;
        line-height: 1.35;
        color: var(--app-muted);
    }

    .topbar-toggle {
        width: 34px;
        height: 34px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #64748b !important;
        background: rgba(148, 163, 184, 0.12);
    }

    .topbar-page {
        flex: 1 1 auto;
        min-width: 0;
    }

    .topbar-date-simple {
        font-size: 0.78rem;
        font-weight: 700;
        color: #475569;
        padding: 0.2rem 0;
    }

    .topbar-user-link {
        padding: 0 !important;
        border-radius: 999px;
        background: transparent;
        border: 0;
        box-shadow: none;
    }

    .topbar-user-link:hover {
        background: transparent;
    }

    .topbar-user-link .d-flex {
        gap: 0.55rem;
        padding: 0.12rem 0;
    }

    .topbar-user-link::after {
        display: none;
    }

    .topbar-user-link .img-profile {
        width: 34px;
        height: 34px;
    }

    .topbar-user-name {
        font-size: 0.84rem;
        line-height: 1.1;
    }

    .topbar-user-role {
        font-size: 0.72rem;
        line-height: 1.2;
    }

    .topbar-profile-menu {
        margin-top: 0;
    }

    .topbar-profile-head {
        display: flex;
        align-items: center;
        gap: 0.8rem;
        padding: 0.55rem 0.7rem 0.35rem;
    }

    .topbar-profile-head img {
        width: 40px;
        height: 40px;
        border: 2px solid rgba(33, 85, 75, 0.1);
    }

    .topbar .dropdown-item {
        border-radius: 12px;
        padding: 0.72rem 0.8rem;
        color: #334155;
    }

    .topbar .dropdown-item:hover {
        background: rgba(33, 85, 75, 0.06);
        color: #0f172a;
    }

    .content-page-header {
        margin-bottom: 1.15rem;
    }

    .content-page-label {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 7px 12px;
        margin-bottom: 12px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 700;
        color: var(--app-primary);
        background: var(--app-primary-soft);
    }

    .content-page-title {
        margin: 0;
        font-size: clamp(1.35rem, 2vw, 2rem);
        font-weight: 800;
        color: var(--app-text);
        letter-spacing: -0.02em;
    }

    .content-page-subtitle {
        margin: 0.45rem 0 0;
        color: var(--app-muted);
        font-size: 0.95rem;
        line-height: 1.7;
    }

    .btn {
        border-radius: 12px;
        font-weight: 600;
    }

    .btn-sm {
        border-radius: 10px;
    }

    .form-control,
    .custom-select,
    .form-select,
    textarea {
        border-radius: 14px !important;
        border-color: rgba(148, 163, 184, 0.28) !important;
        min-height: 44px;
        box-shadow: none !important;
    }

    .form-control:focus,
    .custom-select:focus,
    .form-select:focus,
    textarea:focus {
        border-color: rgba(33, 85, 75, 0.4) !important;
    }

    .table thead th {
        color: #475569;
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        border-top: 0;
    }

    .table td {
        color: #334155;
    }

    .badge {
        border-radius: 999px;
        padding: 0.5em 0.75em;
    }

    .scroll-to-top {
        background: linear-gradient(135deg, var(--app-primary), #2f6c61) !important;
        box-shadow: 0 12px 25px rgba(33, 85, 75, 0.26);
    }

    .sticky-footer {
        width: 100%;
        margin-top: auto;
    }

    .logout-modal {
        border: 0;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 24px 60px rgba(15, 23, 42, 0.16);
    }

    .logout-modal-body {
        position: relative;
        padding: 2rem 1.75rem 1.6rem;
        text-align: center;
        background:
            radial-gradient(circle at top center, rgba(33, 85, 75, 0.08), transparent 38%),
            linear-gradient(180deg, #ffffff 0%, #fbfcfd 100%);
    }

    .logout-modal-close {
        position: absolute;
        top: 14px;
        right: 14px;
        width: 34px;
        height: 34px;
        border: 0;
        border-radius: 10px;
        background: rgba(148, 163, 184, 0.12);
        color: #64748b;
        z-index: 2;
    }

    .logout-modal-icon {
        width: 72px;
        height: 72px;
        margin: 0 auto 1rem;
        border-radius: 22px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        color: #21554b;
        background: rgba(33, 85, 75, 0.1);
        box-shadow: inset 0 0 0 1px rgba(33, 85, 75, 0.08);
    }

    .logout-modal-title {
        font-size: 1.25rem;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.2;
    }

    .logout-modal-text {
        max-width: 360px;
        margin: 0.85rem auto 0;
        color: #64748b;
        line-height: 1.7;
        font-size: 0.95rem;
    }

    .logout-modal-actions {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        flex-wrap: wrap;
        margin-top: 1.5rem;
    }

    .logout-btn-secondary {
        min-width: 130px;
        border: 1px solid rgba(148, 163, 184, 0.2);
        background: #fff;
        color: #475569;
    }

    .logout-btn-primary {
        min-width: 130px;
        background: linear-gradient(135deg, #21554b, #2c6a5e);
        border-color: transparent;
        box-shadow: 0 12px 24px rgba(33, 85, 75, 0.18);
    }

    .app-modal .modal-content {
        border: 0;
        border-radius: 22px;
        overflow: hidden;
        box-shadow: 0 24px 60px rgba(15, 23, 42, 0.16);
    }

    .app-modal .modal-header {
        padding: 1rem 1.25rem 0.8rem;
        border-bottom: 1px solid rgba(226, 232, 240, 0.9);
        background: linear-gradient(180deg, #ffffff 0%, #fbfcfd 100%);
    }

    .app-modal .modal-title {
        display: flex;
        align-items: center;
        gap: 0.7rem;
        font-size: 1rem;
        font-weight: 800;
        color: #0f172a;
    }

    .app-modal .modal-title-icon {
        width: 38px;
        height: 38px;
        border-radius: 12px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: rgba(33, 85, 75, 0.1);
        color: #21554b;
        font-size: 0.95rem;
        flex: 0 0 auto;
    }

    .app-modal .modal-header .close {
        margin: 0;
        padding: 0;
        width: 34px;
        height: 34px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: rgba(148, 163, 184, 0.12);
        color: #64748b;
        opacity: 1;
        text-shadow: none;
        transition: background 0.2s ease, color 0.2s ease;
    }

    .app-modal .modal-header .close:hover {
        background: rgba(148, 163, 184, 0.18);
        color: #334155;
    }

    .app-modal .modal-body {
        padding: 1.2rem 1.25rem;
        background: linear-gradient(180deg, #ffffff 0%, #fcfcfd 100%);
    }

    .app-modal .modal-footer {
        padding: 0.85rem 1.25rem 1.2rem;
        border-top: 1px solid rgba(226, 232, 240, 0.9);
        background: linear-gradient(180deg, #fcfcfd 0%, #ffffff 100%);
    }

    .app-modal .btn {
        border-radius: 12px;
        padding: 0.55rem 1rem;
        font-weight: 600;
    }

    .app-modal .modal-note {
        margin-top: 1rem;
        padding: 0.8rem 0.9rem;
        border-radius: 14px;
        background: rgba(248, 250, 252, 0.9);
        border: 1px solid rgba(226, 232, 240, 0.9);
        color: #64748b;
        font-size: 0.82rem;
        line-height: 1.6;
    }

    .app-modal-preview .modal-body {
        text-align: center;
        padding-top: 1rem;
    }

    .app-modal-preview img {
        border-radius: 18px;
        box-shadow: 0 18px 40px rgba(15, 23, 42, 0.12);
    }

    @media (max-width: 991.98px) {
        .container-fluid {
            padding-left: 1rem;
            padding-right: 1rem;
        }

        .topbar {
            margin: 0.75rem 1rem 0.85rem;
            padding: 0.58rem 0.72rem;
            border-radius: 14px;
        }

        .topbar-title {
            font-size: 0.92rem;
        }
    }

    @media (max-width: 767.98px) {
        .topbar {
            margin-left: 0.75rem;
            margin-right: 0.75rem;
        }

        .container-fluid {
            padding-left: 0.75rem;
            padding-right: 0.75rem;
        }

        .card {
            border-radius: 18px;
        }

        .topbar-subtitle {
            display: none;
        }

        .topbar-date-simple {
            display: none !important;
        }

        .logout-modal-body {
            padding: 1.8rem 1.2rem 1.3rem;
        }

        .app-modal .modal-header,
        .app-modal .modal-body,
        .app-modal .modal-footer {
            padding-left: 1rem;
            padding-right: 1rem;
        }
    }
</style>

@stack('style-css')

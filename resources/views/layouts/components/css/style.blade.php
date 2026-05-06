<link href="{{ asset('templating/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
<link
    href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
    rel="stylesheet">

<link href="{{ asset('templating/css/sb-admin-2.min.css') }}" rel="stylesheet">

<style>
    body {
        background: #f8f9fc;
        font-family: 'Poppins', sans-serif;
        overflow-x: hidden;
    }

    .sidebar {
        background: #0f0f0f !important;
    }
    .sidebar .sidebar-brand {
        color: #d4af37 !important;
        font-weight: 600;
    }

    .sidebar .nav-item .nav-link {
        color: #cfcfcf !important;
        transition: all 0.3s ease;
    }

    .sidebar .nav-item .nav-link i {
        color: #888 !important;
    }
    .sidebar .nav-item .nav-link:hover {
        color: #d4af37 !important;
    }

    .sidebar .nav-item .nav-link:hover i {
        color: #d4af37 !important;
    }

    .sidebar .nav-item.active .nav-link {
        color: #d4af37 !important;
        font-weight: 600;
    }

    .sidebar .nav-item.active {
        background: rgba(212, 175, 55, 0.1);
        border-left: 4px solid #d4af37;
    }

    .sidebar hr.sidebar-divider {
        border-top: 1px solid #222;
    }

    .sidebar {
        scrollbar-width: thin;
        scrollbar-color: #d4af37 #0f0f0f;
    }
</style>

@stack('style-css')

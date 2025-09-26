<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tour365 Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body { min-height: 100vh; }
        .sidebar { width: 260px; }
        .sidebar .nav-link.active { background: #0d6efd; color: #fff; }
        .content { margin-left: 260px; }
        @media (max-width: 992px) {
            .sidebar { position: fixed; z-index: 1040; height: 100vh; left: -260px; top: 0; transition: left .2s; }
            .sidebar.show { left: 0; }
            .content { margin-left: 0; }
        }
    </style>
    @stack('head')
    @vite(["resources/css/app.css","resources/js/app.js"])
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-primary sticky-top">
    <div class="container-fluid">
        <button class="btn btn-outline-light d-lg-none me-2" id="sidebarToggle"><i class="bi bi-list"></i></button>
        <a class="navbar-brand" href="{{ route('admin.dashboard') }}">Tour365 Admin</a>
        <div class="ms-auto d-flex align-items-center gap-3">
            <span class="text-white-50 small">{{ now()->format('d/m/Y') }}</span>
        </div>
    </div>
    </nav>

<div class="d-flex">
    <aside class="sidebar bg-white border-end p-3">
        <div class="d-flex align-items-center mb-3">
            <i class="bi bi-compass text-primary fs-3 me-2"></i>
            <strong>Tour365</strong>
        </div>
        <nav class="nav flex-column gap-1">
            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
            <a class="nav-link {{ request()->routeIs('admin.tours') ? 'active' : '' }}" href="{{ route('admin.tours') }}"><i class="bi bi-map me-2"></i>Tours</a>
            <a class="nav-link {{ request()->routeIs('admin.bookings') ? 'active' : '' }}" href="{{ route('admin.bookings') }}"><i class="bi bi-journal-check me-2"></i>Đặt tour</a>
            <a class="nav-link {{ request()->routeIs('admin.customers') ? 'active' : '' }}" href="{{ route('admin.customers') }}"><i class="bi bi-people me-2"></i>Khách hàng</a>
            <a class="nav-link {{ request()->routeIs('admin.settings') ? 'active' : '' }}" href="{{ route('admin.settings') }}"><i class="bi bi-gear me-2"></i>Cấu hình</a>
        </nav>
    </aside>

    <main class="content p-3 p-lg-4 w-100">
        @yield('content')
        <footer class="pt-4 text-muted small">© {{ date('Y') }} Tour365</footer>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const toggle = document.getElementById('sidebarToggle');
    const sidebar = document.querySelector('.sidebar');
    if (toggle && sidebar) {
        toggle.addEventListener('click', () => sidebar.classList.toggle('show'));
    }
</script>
@stack('scripts')
</body>
</html>



<!doctype html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Tour365 Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
        }

        .sidebar {
            width: 260px;
        }

        .sidebar .nav-link.active {
            background: #0d6efd;
            color: #fff;
        }

        .content {
            margin-left: 260px;
        }

        @media (max-width: 992px) {
            .sidebar {
                position: fixed;
                z-index: 1040;
                height: 100vh;
                left: -260px;
                top: 0;
                transition: left .2s;
            }

            .sidebar.show {
                left: 0;
            }

            .content {
                margin-left: 0;
            }
        }
    </style>
    @stack('head')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Fallback CSS nếu cần -->
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
    <link href="{{ asset('css/admin-icons.css') }}" rel="stylesheet">
    
    <style>
        /* Form elements styling */
        .form-control, .form-select {
            border-radius: 0.375rem;
            border: 1px solid #d1d5db;
            padding: 0.5rem 0.75rem;
            font-size: 0.875rem;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

        .form-control:focus, .form-select:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.25);
        }

        /* Icons styling */
        .nav-link i, .btn i {
            margin-right: 0.5rem;
        }

        /* Ensure icons are visible */
        .bi, .fas, .far, .fab {
            font-family: "bootstrap-icons", "Font Awesome 6 Free", "Font Awesome 6 Brands" !important;
        }

        /* Card styling consistency */
        .card {
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        }

        .card-header {
            background-color: #f9fafb;
            border-bottom: 1px solid #e5e7eb;
            font-weight: 600;
        }

        /* Button styling consistency */
        .btn {
            border-radius: 0.375rem;
            font-weight: 500;
            transition: all 0.15s ease-in-out;
        }

        .btn-primary {
            background-color: #3b82f6;
            border-color: #3b82f6;
        }

        .btn-primary:hover {
            background-color: #2563eb;
            border-color: #2563eb;
        }

        .btn-success {
            background-color: #10b981;
            border-color: #10b981;
        }

        .btn-success:hover {
            background-color: #059669;
            border-color: #059669;
        }

        .btn-warning {
            background-color: #f59e0b;
            border-color: #f59e0b;
        }

        .btn-warning:hover {
            background-color: #d97706;
            border-color: #d97706;
        }

        .btn-danger {
            background-color: #ef4444;
            border-color: #ef4444;
        }

        .btn-danger:hover {
            background-color: #dc2626;
            border-color: #dc2626;
        }

        .btn-secondary {
            background-color: #6b7280;
            border-color: #6b7280;
        }

        .btn-secondary:hover {
            background-color: #4b5563;
            border-color: #4b5563;
        }

        /* Statistics cards styling */
        .border-left-primary {
            border-left: 0.25rem solid #3b82f6 !important;
        }

        .border-left-success {
            border-left: 0.25rem solid #10b981 !important;
        }

        .border-left-info {
            border-left: 0.25rem solid #06b6d4 !important;
        }

        .border-left-warning {
            border-left: 0.25rem solid #f59e0b !important;
        }

        /* Table styling */
        .table th {
            background-color: #f8f9fc;
            border-color: #e3e6f0;
            color: #5a5c69;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
        }

        .table td {
            vertical-align: middle;
        }

        /* Badge styling */
        .badge {
            font-size: 0.75rem;
            font-weight: 600;
        }

        /* Avatar styling */
        .avatar-sm {
            width: 40px;
            height: 40px;
            font-size: 14px;
        }

        .avatar-lg {
            width: 60px;
            height: 60px;
            font-size: 24px;
        }
    </style>
</head>

<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary sticky-top">
        <div class="container-fluid">
            <button class="btn btn-outline-light d-lg-none me-2" id="sidebarToggle"><i class="bi bi-list"></i></button>
            <a class="navbar-brand" href="{{ route('admin.dashboard') }}">Tour365 Admin</a>
            <div class="ms-auto d-flex align-items-center gap-3">
                <a href="/" class="btn btn-outline-light btn-sm">
                    <i class="bi bi-house me-1"></i>Trang chủ
                </a>
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
                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                    href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
                {{-- <a class="nav-link {{ request()->routeIs('admin.tours') ? 'active' : '' }}" href="{{ route('admin.tours.index') }}"><i class="bi bi-map me-2"></i>Tours</a> --}}
                <a class="nav-link {{ request()->routeIs('admin.tours.*') ? 'active' : '' }}"
                    href="{{ route('admin.tours') }}">
                    <i class="bi bi-map me-2"></i>Tours
                </a>

                <a class="nav-link {{ request()->routeIs('admin.bookings') ? 'active' : '' }}"
                    href="{{ route('admin.bookings') }}"><i class="bi bi-journal-check me-2"></i>Đặt tour</a>
                <a class="nav-link {{ request()->routeIs('admin.customers') ? 'active' : '' }}"
                    href="{{ route('admin.customers') }}"><i class="bi bi-people me-2"></i>Khách hàng</a>
                <a class="nav-link {{ request()->routeIs('admin.banners') ? 'active' : '' }}"
                    href="{{ route('admin.banners') }}"><i class="bi bi-image me-2"></i>Banner</a>
                <a class="nav-link {{ request()->routeIs('admin.check-in-out.*') ? 'active' : '' }}"
                    href="{{ route('admin.check-in-out.index') }}"><i class="bi bi-clock me-2"></i>Check-in/out</a>
                <a class="nav-link {{ request()->routeIs('admin.reports') ? 'active' : '' }}"
                    href="{{ route('admin.reports') }}"><i class="bi bi-gear me-2"></i>Báo cáo</a>

                <hr class="my-3">

                <div class="nav-item">
                    <div class="nav-link text-muted small">
                        <i class="bi bi-person-circle me-2"></i>{{ Auth::user()->name }}
                    </div>
                </div>

                <form method="POST" action="{{ route('logout') }}" class="nav-item">
                    @csrf
                    <button type="submit" class="nav-link text-danger border-0 bg-transparent w-100 text-start">
                        <i class="bi bi-box-arrow-right me-2"></i>Đăng xuất
                    </button>
                </form>
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

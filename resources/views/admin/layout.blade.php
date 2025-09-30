<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tour365 Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('admin_assets/style.css') }}">
    @stack('head')
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top" style="z-index: 1050;">
    <div class="container-fluid px-4">
        <button class="btn btn-outline-light d-lg-none me-3" id="sidebarToggle">
            <i class="bi bi-list"></i>
        </button>
        <a class="navbar-brand d-flex align-items-center" href="{{ route('admin.dashboard') }}">
            <i class="bi bi-compass-fill me-2"></i>
            Tour365 Admin
        </a>
        <div class="ms-auto d-flex align-items-center gap-4">
            <div class="dropdown">
                <button class="btn btn-outline-light btn-sm dropdown-toggle d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown">
                    <i class="bi bi-person-circle fs-5"></i>
                    <div class="text-start">
                        <div class="fw-bold">{{ auth()->user()->name }}</div>
                        @if(auth()->user()->isAdmin())
                            <span class="badge bg-danger me-1">Admin</span>
                        @elseif(auth()->user()->isStaff())
                            <span class="badge bg-warning me-1">Staff</span>
                        @else
                            <span class="badge bg-info me-1">Customer</span>
                        @endif

                    </div>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item py-2" href="{{ route('admin.users.show', auth()->user()) }}">
                        <i class="bi bi-person me-2"></i>Thông tin cá nhân
                    </a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}" class="d-inline w-100">
                            @csrf
                            <button type="submit" class="dropdown-item py-2"><i class="bi bi-box-arrow-right me-2"></i>Đăng xuất</button>
                        </form>
                    </li>
                </ul>
            </div>
            <small class="text-white-50 d-none d-md-block">{{ now()->format('d/m/Y H:i') }}</small>
        </div>
    </div>
</nav>

<div class="d-flex">
    <aside class="sidebar">
        <div class="brand-section">
            <i class="bi bi-compass"></i>
            <div>
                <strong>Tour365</strong><br>
                <small class="text-white-50">Hệ thống quản lý</small>
            </div>
        </div>

        <nav class="nav flex-column px-3">
            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                <i class="bi bi-speedometer2 me-3"></i>Dashboard
            </a>
            <a class="nav-link {{ request()->routeIs('admin.tours') ? 'active' : '' }}" href="{{ route('admin.tours') }}">
                <i class="bi bi-map me-3"></i>Tours
            </a>
            <a class="nav-link {{ request()->routeIs('admin.bookings') ? 'active' : '' }}" href="{{ route('admin.bookings') }}">
                <i class="bi bi-journal-check me-3"></i>Đặt tour
            </a>

            @if(auth()->user()->isStaff() || auth()->user()->isAdmin())
                <a class="nav-link {{ request()->routeIs('admin.users.index') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                    <i class="bi bi-people me-3"></i>
                    @if(auth()->user()->isStaff() && !auth()->user()->isAdmin())
                        Khách hàng
                    @else
                        Người dùng
                    @endif
                </a>
            @endif

            @if(auth()->user()->isAdmin())
                <a class="nav-link {{ request()->routeIs('admin.settings') ? 'active' : '' }}" href="{{ route('admin.settings') }}">
                    <i class="bi bi-gear me-3"></i>Cấu hình
                </a>
            @endif
        </nav>
    </aside>

    <main class="content p-4 w-100" style="background-color: #f8f9fa;">
        @yield('content')

        <footer class="mt-5 pt-4 text-center">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <p class="mb-0 text-muted">
                            © {{ date('Y') }} Tour365 - Hệ thống quản lý tour du lịch |
                            <span class="text-primary">{{ auth()->user()->name }}</span>
                            @if(auth()->user()->isAdmin())
                                <span class="badge bg-danger ms-1">Administrator</span>
                            @elseif(auth()->user()->isStaff())
                                <span class="badge bg-warning ms-1">Staff Member</span>
                            @else
                                <span class="badge bg-info ms-1">Customer</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </footer>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const toggle = document.getElementById('sidebarToggle');
    const sidebar = document.querySelector('.sidebar');
    const body = document.body;

    if (toggle && sidebar) {
        toggle.addEventListener('click', () => {
            sidebar.classList.toggle('show');

            if (window.innerWidth < 992) {
                body.classList.toggle('sidebar-open');
            }
        });

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', (e) => {
            if (window.innerWidth < 992 &&
                !sidebar.contains(e.target) &&
                !toggle.contains(e.target) &&
                sidebar.classList.contains('show')) {
                sidebar.classList.remove('show');
                body.classList.remove('sidebar-open');
            }
        });

        // Close sidebar when resizing to desktop
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 992) {
                sidebar.classList.remove('show');
                body.classList.remove('sidebar-open');
            }
        });
    }

</script>
@stack('scripts')
</body>
</html>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Hướng dẫn viên - Tour365')</title>
    <meta name="description" content="Trang quản lý cho hướng dẫn viên - Tour365">
    <meta name="theme-color" content="#0EA5E9">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
<style>
        :root {
            --guide-primary: #0EA5E9;
            --guide-primary-dark: #0284C7;
            --guide-bg: #F8FAFC;
            --guide-sidebar-bg: #FFFFFF;
            --guide-text: #1F2937;
            --guide-text-muted: #6B7280;
            --guide-border: #E5E7EB;
            --guide-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
            --guide-shadow-md: 0 10px 30px rgba(0, 0, 0, 0.08);
        }

        body {
            background-color: var(--guide-bg);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            color: var(--guide-text);
        }

        /* Sidebar */
        .guide-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 280px;
            background: var(--guide-sidebar-bg);
            box-shadow: var(--guide-shadow);
            z-index: 1000;
            overflow-y: auto;
            transition: all 0.3s ease;
        }

        .guide-sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--guide-border);
            background: linear-gradient(135deg, var(--guide-primary) 0%, var(--guide-primary-dark) 100%);
        }

        .guide-sidebar-header .brand {
            color: white;
            font-size: 1.5rem;
            font-weight: 700;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .guide-sidebar-header .brand i {
            font-size: 1.8rem;
        }

        .guide-sidebar-nav {
            padding: 1rem 0;
        }

        .guide-nav-item {
            margin: 0.25rem 1rem;
        }

        .guide-nav-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            color: var(--guide-text);
            text-decoration: none;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .guide-nav-link:hover {
            background: rgba(14, 165, 233, 0.1);
            color: var(--guide-primary);
            transform: translateX(4px);
        }

        .guide-nav-link.active {
            background: linear-gradient(135deg, var(--guide-primary) 0%, var(--guide-primary-dark) 100%);
            color: white;
            box-shadow: var(--guide-shadow);
        }

        .guide-nav-link i {
            width: 20px;
            text-align: center;
        }

        /* Main Content */
        .guide-main {
            margin-left: 280px;
            min-height: 100vh;
            transition: all 0.3s ease;
        }

        .guide-header {
            background: white;
            padding: 1.5rem 2rem;
            box-shadow: var(--guide-shadow);
            position: sticky;
            top: 0;
            z-index: 100;
            border-bottom: 1px solid var(--guide-border);
        }

        .guide-header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .guide-header-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--guide-text);
            margin: 0;
        }

        .guide-header-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .guide-user-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem 1rem;
            background: var(--guide-bg);
            border-radius: 2rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .guide-user-info:hover {
            background: rgba(14, 165, 233, 0.1);
        }

        .guide-user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--guide-primary) 0%, var(--guide-primary-dark) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
        }

        .guide-content {
            padding: 2rem;
        }

        /* Cards */
        .guide-card {
            background: white;
            border-radius: 1rem;
            box-shadow: var(--guide-shadow);
            border: 1px solid var(--guide-border);
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .guide-card:hover {
            box-shadow: var(--guide-shadow-md);
            transform: translateY(-2px);
        }

        .guide-card-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--guide-border);
            background: var(--guide-bg);
            font-weight: 600;
            color: var(--guide-text);
        }

        .guide-card-body {
            padding: 1.5rem;
        }

        /* Stats Cards */
        .guide-stat-card {
            background: white;
            border-radius: 1rem;
            padding: 1.5rem;
            box-shadow: var(--guide-shadow);
            border: 1px solid var(--guide-border);
            transition: all 0.3s ease;
        }

        .guide-stat-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--guide-shadow-md);
        }

        .guide-stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        .guide-stat-icon.primary {
            background: rgba(14, 165, 233, 0.1);
            color: var(--guide-primary);
        }

        .guide-stat-icon.success {
            background: rgba(16, 185, 129, 0.1);
            color: #10B981;
        }

        .guide-stat-icon.warning {
            background: rgba(245, 158, 11, 0.1);
            color: #F59E0B;
        }

        .guide-stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--guide-text);
            margin: 0.5rem 0;
        }

        .guide-stat-label {
            color: var(--guide-text-muted);
            font-size: 0.9rem;
            margin: 0;
        }

        /* Buttons */
        .btn-guide-primary {
            background: linear-gradient(135deg, var(--guide-primary) 0%, var(--guide-primary-dark) 100%);
            border: none;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: var(--guide-shadow);
        }

        .btn-guide-primary:hover {
            transform: translateY(-2px);
            box-shadow: var(--guide-shadow-md);
            color: white;
        }

        /* Badges */
        .guide-badge {
            padding: 0.5rem 1rem;
            border-radius: 2rem;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .guide-badge.upcoming {
            background: rgba(14, 165, 233, 0.1);
            color: var(--guide-primary);
        }

        .guide-badge.completed {
            background: rgba(16, 185, 129, 0.1);
            color: #10B981;
        }

        .guide-badge.cancelled {
            background: rgba(239, 68, 68, 0.1);
            color: #EF4444;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .guide-sidebar {
                transform: translateX(-100%);
            }

            .guide-sidebar.show {
                transform: translateX(0);
            }

            .guide-main {
                margin-left: 0;
            }

            .guide-mobile-toggle {
                display: block !important;
            }
        }

        .guide-mobile-toggle {
            display: none;
            background: var(--guide-primary);
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-size: 1.2rem;
        }

        /* Table */
        .guide-table {
            background: white;
            border-radius: 1rem;
            overflow: hidden;
        }

        .guide-table thead {
            background: var(--guide-bg);
        }

        .guide-table thead th {
            border: none;
            padding: 1rem;
            font-weight: 600;
            color: var(--guide-text);
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }

        .guide-table tbody td {
            padding: 1rem;
            border-top: 1px solid var(--guide-border);
            vertical-align: middle;
        }

        .guide-table tbody tr:hover {
            background: var(--guide-bg);
        }
</style>
@yield('styles')
</head>

<body>
    <!-- Sidebar -->
    <aside class="guide-sidebar" id="guideSidebar">
 <div class="guide-sidebar-header">
    <a href="{{ route('guide.dashboard') }}" class="brand">
        <i class="fas fa-user-tie"></i>
        <span>HDV Dashboard</span>
    </a>
</div>




        <nav class="guide-sidebar-nav">
            <div class="guide-nav-item">
                <a href="{{ route('guide.dashboard') }}" 
                   class="guide-nav-link {{ request()->routeIs('guide.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home"></i>
                    <span>Tổng quan</span>
                </a>
            </div>

            <div class="guide-nav-item">
    <a href="{{ route('guide.salary.index') }}"
       class="guide-nav-link {{ request()->routeIs('guide.salary.*') ? 'active' : '' }}">
        <i class="fas fa-wallet"></i>
        <span>Thu nhập</span>
    </a>
</div>

            <div class="guide-nav-item">
                <a href="{{ route('guide.departures') }}" 
                   class="guide-nav-link {{ request()->routeIs('guide.departures*') ? 'active' : '' }}">
                    <i class="fas fa-calendar-alt"></i>
                    <span>Lịch khởi hành</span>
                </a>
            </div>
            <div class="guide-nav-item">
                <a href="{{ route('guide.check-in-out.index') }}" 
                   class="guide-nav-link {{ request()->routeIs('guide.check-in-out*') ? 'active' : '' }}">
                    <i class="fas fa-clipboard-check"></i>
                    <span>Check-in/Check-out</span>
                </a>
            </div>
            <div class="guide-nav-item">
                <a href="{{ route('profile.index') }}" 
                   class="guide-nav-link">
                    <i class="fas fa-user"></i>
                    <span>Thông tin cá nhân</span>
                </a>
            </div>
            <div class="guide-nav-item">
                <a href="{{ route('notifications.index') }}" 
                   class="guide-nav-link">
                    <i class="fas fa-bell"></i>
                    <span>Thông báo</span>
                </a>
            </div>
            <div class="guide-nav-item mt-4">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="guide-nav-link w-100 text-start border-0 bg-transparent" style="cursor: pointer;">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Đăng xuất</span>
                    </button>
                </form>
            </div>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="guide-main">
        <!-- Header -->
        <header class="guide-header">
            <div class="guide-header-content">
                <div class="d-flex align-items-center gap-3">
                    <button class="guide-mobile-toggle" onclick="toggleSidebar()">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h1 class="guide-header-title">@yield('page-title', 'Dashboard')</h1>
                </div>
                <div class="guide-header-actions">
                    <div class="guide-user-info">
                        <div class="guide-user-avatar">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div>
                            <div style="font-weight: 600; font-size: 0.9rem;">{{ auth()->user()->name }}</div>
                            <div style="font-size: 0.75rem; color: var(--guide-text-muted);">Hướng dẫn viên</div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Content -->
        <div class="guide-content">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleSidebar() {
            document.getElementById('guideSidebar').classList.toggle('show');
        }

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('guideSidebar');
            const toggle = document.querySelector('.guide-mobile-toggle');
            
            if (window.innerWidth <= 992) {
                if (!sidebar.contains(event.target) && !toggle.contains(event.target)) {
                    sidebar.classList.remove('show');
                }
            }
        });
    </script>
    @yield('scripts')
</body>

</html>


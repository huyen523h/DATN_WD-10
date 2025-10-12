<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard - Tour365')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Custom Admin CSS -->
    <style>
        :root {
            --admin-primary: #4F46E5;
            --admin-secondary: #6B7280;
            --admin-success: #10B981;
            --admin-warning: #F59E0B;
            --admin-danger: #EF4444;
            --admin-info: #3B82F6;
            --admin-light: #F9FAFB;
            --admin-dark: #1F2937;
            --sidebar-width: 280px;
            --header-height: 70px;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: #F8FAFC;
            margin: 0;
            padding: 0;
        }

        /* Sidebar */
        .admin-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: linear-gradient(180deg, #1E293B 0%, #334155 100%);
            z-index: 1000;
            transition: all 0.3s ease;
            overflow-y: auto;
        }

        .admin-sidebar.collapsed {
            width: 70px;
        }

        .admin-sidebar .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .admin-sidebar .sidebar-brand {
            color: white;
            text-decoration: none;
            font-size: 1.5rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .admin-sidebar .sidebar-brand i {
            font-size: 2rem;
            color: var(--admin-primary);
        }

        .admin-sidebar .sidebar-nav {
            padding: 1rem 0;
        }

        .admin-sidebar .nav-item {
            margin: 0.25rem 0;
        }

        .admin-sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 0.75rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            transition: all 0.3s ease;
            border-radius: 0;
        }

        .admin-sidebar .nav-link:hover {
            color: white;
            background: rgba(255, 255, 255, 0.1);
        }

        .admin-sidebar .nav-link.active {
            color: white;
            background: var(--admin-primary);
            border-right: 3px solid #F59E0B;
        }

        .admin-sidebar .nav-link i {
            width: 20px;
            text-align: center;
        }

        .admin-sidebar .nav-dropdown {
            background: rgba(0, 0, 0, 0.2);
        }

        .admin-sidebar .nav-dropdown .nav-link {
            padding-left: 3rem;
            font-size: 0.9rem;
        }

        /* Main Content */
        .admin-main {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: all 0.3s ease;
        }

        .admin-main.sidebar-collapsed {
            margin-left: 70px;
        }

        /* Header */
        .admin-header {
            height: var(--header-height);
            background: white;
            border-bottom: 1px solid #E5E7EB;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
            position: sticky;
            top: 0;
            z-index: 999;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .admin-header .header-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .admin-header .sidebar-toggle {
            background: none;
            border: none;
            font-size: 1.25rem;
            color: var(--admin-secondary);
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
        }

        .admin-header .sidebar-toggle:hover {
            background: var(--admin-light);
            color: var(--admin-primary);
        }

        .admin-header .breadcrumb {
            margin: 0;
            background: none;
            padding: 0;
        }

        .admin-header .breadcrumb-item {
            color: var(--admin-secondary);
        }

        .admin-header .breadcrumb-item.active {
            color: var(--admin-dark);
            font-weight: 600;
        }

        .admin-header .header-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .admin-header .search-box {
            position: relative;
        }

        .admin-header .search-box input {
            width: 300px;
            padding: 0.5rem 1rem 0.5rem 2.5rem;
            border: 1px solid #D1D5DB;
            border-radius: 2rem;
            font-size: 0.9rem;
        }

        .admin-header .search-box i {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--admin-secondary);
        }

        .admin-header .notification-btn {
            position: relative;
            background: none;
            border: none;
            color: var(--admin-secondary);
            font-size: 1.25rem;
            padding: 0.5rem;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .admin-header .notification-btn:hover {
            background: var(--admin-light);
            color: var(--admin-primary);
        }

        .admin-header .notification-badge {
            position: absolute;
            top: 0.25rem;
            right: 0.25rem;
            background: var(--admin-danger);
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 0.7rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .admin-header .user-dropdown {
            position: relative;
        }

        .admin-header .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--admin-primary), #8B5CF6);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .admin-header .user-avatar:hover {
            transform: scale(1.05);
        }

        /* Content */
        .admin-content {
            padding: 2rem;
        }

        /* Cards */
        .admin-card {
            background: white;
            border-radius: 0.75rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border: 1px solid #E5E7EB;
            overflow: hidden;
        }

        .admin-card .card-header {
            background: var(--admin-light);
            border-bottom: 1px solid #E5E7EB;
            padding: 1.5rem;
        }

        .admin-card .card-body {
            padding: 1.5rem;
        }

        /* Stats Cards */
        .stats-card {
            background: white;
            border-radius: 0.75rem;
            padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border: 1px solid #E5E7EB;
            transition: all 0.3s ease;
        }

        .stats-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .stats-card .stats-icon {
            width: 60px;
            height: 60px;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
        }

        .stats-card .stats-number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--admin-dark);
            line-height: 1;
        }

        .stats-card .stats-label {
            color: var(--admin-secondary);
            font-size: 0.875rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .stats-card .stats-change {
            font-size: 0.75rem;
            font-weight: 500;
        }

        /* Tables */
        .admin-table {
            background: white;
            border-radius: 0.75rem;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .admin-table table {
            margin: 0;
        }

        .admin-table th {
            background: var(--admin-light);
            border: none;
            font-weight: 600;
            color: var(--admin-dark);
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 1rem;
        }

        .admin-table td {
            border: none;
            padding: 1rem;
            vertical-align: middle;
            border-bottom: 1px solid #F3F4F6;
        }

        .admin-table tbody tr:hover {
            background: #F9FAFB;
        }

        /* Buttons */
        .btn-admin-primary {
            background: var(--admin-primary);
            border-color: var(--admin-primary);
            color: white;
        }

        .btn-admin-primary:hover {
            background: #3730A3;
            border-color: #3730A3;
            color: white;
        }

        .btn-admin-success {
            background: var(--admin-success);
            border-color: var(--admin-success);
            color: white;
        }

        .btn-admin-warning {
            background: var(--admin-warning);
            border-color: var(--admin-warning);
            color: white;
        }

        .btn-admin-danger {
            background: var(--admin-danger);
            border-color: var(--admin-danger);
            color: white;
        }

        /* Badges */
        .badge-admin {
            padding: 0.375rem 0.75rem;
            border-radius: 0.5rem;
            font-size: 0.75rem;
            font-weight: 600;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .admin-sidebar {
                transform: translateX(-100%);
            }

            .admin-sidebar.show {
                transform: translateX(0);
            }

            .admin-main {
                margin-left: 0;
            }

            .admin-header .search-box input {
                width: 200px;
            }
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .fade-in {
            animation: fadeIn 0.5s ease-out;
        }

        /* Custom Scrollbar */
        .admin-sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .admin-sidebar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
        }

        .admin-sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 3px;
        }

        .admin-sidebar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.5);
        }
    </style>
    
    @yield('styles')
</head>
<body>
    <!-- Sidebar -->
    <nav class="admin-sidebar" id="adminSidebar">
        <div class="sidebar-header">
            <a href="{{ route('admin.dashboard') }}" class="sidebar-brand">
                <i class="fas fa-plane"></i>
                <span class="brand-text">Tour365 Admin</span>
            </a>
        </div>
        
        <div class="sidebar-nav">
            <!-- Dashboard -->
            <div class="nav-item">
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt"></i>
                    <span class="nav-text">Dashboard</span>
                </a>
            </div>

            <!-- Tours Management -->
            <div class="nav-item">
                <a href="{{ route('admin.tours') }}" class="nav-link {{ request()->routeIs('admin.tours*') ? 'active' : '' }}">
                    <i class="fas fa-map-marked-alt"></i>
                    <span class="nav-text">Quản lý Tours</span>
                </a>
                    </div>

            <!-- Bookings Management -->
            <div class="nav-item">
                <a href="{{ route('admin.bookings') }}" class="nav-link {{ request()->routeIs('admin.bookings*') ? 'active' : '' }}">
                    <i class="fas fa-calendar-check"></i>
                    <span class="nav-text">Quản lý Đặt tour</span>
                </a>
            </div>

        <!-- Users Management -->
        <div class="nav-item">
            <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                <i class="fas fa-users"></i>
                <span class="nav-text">Quản lý Người dùng</span>
            </a>
        </div>

        <!-- Employees Management -->
        <div class="nav-item">
            <a href="{{ route('admin.employees.index') }}" class="nav-link {{ request()->routeIs('admin.employees*') ? 'active' : '' }}">
                <i class="fas fa-user-tie"></i>
                <span class="nav-text">Quản lý Nhân viên</span>
            </a>
        </div>

            <!-- Categories Management -->
            <div class="nav-item">
                <a href="{{ route('admin.categories') }}" class="nav-link {{ request()->routeIs('admin.categories*') ? 'active' : '' }}">
                    <i class="fas fa-tags"></i>
                    <span class="nav-text">Quản lý Danh mục</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="{{ route('admin.promotions') }}" class="nav-link {{ request()->routeIs('admin.promotions*') ? 'active' : '' }}">
                    <i class="fas fa-percent"></i>
                    <span class="nav-text">Quản lý mã giảm giá</span>
                </a>
            </div>

            <!-- Reviews Management -->
            <div class="nav-item">
                <a href="{{ route('admin.reviews') }}" class="nav-link {{ request()->routeIs('admin.reviews*') ? 'active' : '' }}">
                    <i class="fas fa-star"></i>
                    <span class="nav-text">Quản lý Đánh giá</span>
                </a>
    </div>

            <!-- Payments Management -->
            <div class="nav-item">
                <a href="{{ route('admin.payments') }}" class="nav-link {{ request()->routeIs('admin.payments*') ? 'active' : '' }}">
                    <i class="fas fa-credit-card"></i>
                    <span class="nav-text">Quản lý Thanh toán</span>
                </a>
            </div>

            <!-- Reports -->
            <div class="nav-item">
                <a href="{{ route('admin.reports') }}" class="nav-link {{ request()->routeIs('admin.reports*') ? 'active' : '' }}">
                    <i class="fas fa-chart-bar"></i>
                    <span class="nav-text">Báo cáo & Thống kê</span>
                </a>
        </div>

            <!-- Notifications -->
            <div class="nav-item">
                <a href="{{ route('admin.notifications') }}" class="nav-link {{ request()->routeIs('admin.notifications*') ? 'active' : '' }}">
                    <i class="fas fa-bell"></i>
                    <span class="nav-text">Thông báo</span>
                    <span class="badge badge-admin bg-danger ms-auto">3</span>
                </a>
            </div>

            <!-- Support Tickets -->
            <div class="nav-item">
                <a href="{{ route('admin.support') }}" class="nav-link {{ request()->routeIs('admin.support*') ? 'active' : '' }}">
                    <i class="fas fa-headset"></i>
                    <span class="nav-text">Hỗ trợ khách hàng</span>
                    <span class="badge badge-admin bg-warning ms-auto">5</span>
                </a>
            </div>

            <!-- Settings -->
            <div class="nav-item">
                <a href="{{ route('admin.settings') }}" class="nav-link {{ request()->routeIs('admin.settings*') ? 'active' : '' }}">
                    <i class="fas fa-cog"></i>
                    <span class="nav-text">Cài đặt hệ thống</span>
                </a>
            </div>

            <!-- Divider -->
            <hr style="border-color: rgba(255, 255, 255, 0.1); margin: 1rem 0;">

            <!-- Back to Website -->
            <div class="nav-item">
                <a href="{{ route('tours.index') }}" class="nav-link" target="_blank">
                    <i class="fas fa-external-link-alt"></i>
                    <span class="nav-text">Xem website</span>
                </a>
            </div>

            <!-- Logout -->
            <div class="nav-item">
                <a href="#" class="nav-link" onclick="logout()">
                    <i class="fas fa-sign-out-alt"></i>
                    <span class="nav-text">Đăng xuất</span>
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="admin-main" id="adminMain">
        <!-- Header -->
        <header class="admin-header">
            <div class="header-left">
                <button class="sidebar-toggle" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Admin</a></li>
                        @yield('breadcrumb')
                    </ol>
        </nav>
            </div>
            
            <div class="header-right">
                <!-- Search -->
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Tìm kiếm..." class="form-control">
                </div>

                <!-- Notifications -->
                <button class="notification-btn" data-bs-toggle="dropdown">
                    <i class="fas fa-bell"></i>
                    <span class="notification-badge">3</span>
                </button>
                <div class="dropdown-menu dropdown-menu-end" style="width: 300px;">
                    <div class="dropdown-header">
                        <h6 class="mb-0">Thông báo mới</h6>
                    </div>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item">
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-calendar-check text-primary"></i>
                            </div>
                            <div class="flex-grow-1 ms-2">
                                <div class="fw-bold">Đặt tour mới</div>
                                <small class="text-muted">Khách hàng đã đặt tour Đà Nẵng</small>
                            </div>
                        </div>
                    </a>
                    <a href="#" class="dropdown-item">
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-star text-warning"></i>
                            </div>
                            <div class="flex-grow-1 ms-2">
                                <div class="fw-bold">Đánh giá mới</div>
                                <small class="text-muted">Tour Phú Quốc được đánh giá 5 sao</small>
                            </div>
                        </div>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item text-center">Xem tất cả thông báo</a>
                </div>

                <!-- User Dropdown -->
                <div class="user-dropdown">
                    <div class="user-avatar" data-bs-toggle="dropdown">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div class="dropdown-menu dropdown-menu-end">
                        <div class="dropdown-header">
                            <div class="fw-bold">{{ Auth::user()->name }}</div>
                            <small class="text-muted">{{ Auth::user()->email }}</small>
                        </div>
                        <div class="dropdown-divider"></div>
                        <a href="{{ route('profile.index') }}" class="dropdown-item">
                            <i class="fas fa-user me-2"></i> Thông tin cá nhân
                        </a>
                        <a href="{{ route('admin.settings') }}" class="dropdown-item">
                            <i class="fas fa-cog me-2"></i> Cài đặt
                        </a>
                        <div class="dropdown-divider"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item">
                                <i class="fas fa-sign-out-alt me-2"></i> Đăng xuất
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <!-- Content -->
        <main class="admin-content fade-in">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
    </main>
</div>

    <!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom Admin JS -->
<script>
        document.addEventListener('DOMContentLoaded', function() {
            // Sidebar toggle
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('adminSidebar');
            const main = document.getElementById('adminMain');

            sidebarToggle.addEventListener('click', function() {
                sidebar.classList.toggle('collapsed');
                main.classList.toggle('sidebar-collapsed');
                
                // Save state to localStorage
                const isCollapsed = sidebar.classList.contains('collapsed');
                localStorage.setItem('adminSidebarCollapsed', isCollapsed);
            });

            // Load saved state
            const isCollapsed = localStorage.getItem('adminSidebarCollapsed') === 'true';
            if (isCollapsed) {
                sidebar.classList.add('collapsed');
                main.classList.add('sidebar-collapsed');
            }

            // Mobile sidebar toggle
            if (window.innerWidth <= 768) {
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('show');
                });
            }

            // Auto-hide alerts
            setTimeout(function() {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(function(alert) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);

            // Search functionality
            const searchInput = document.querySelector('.search-box input');
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    const query = this.value.trim();
                    if (query) {
                        window.location.href = `{{ route('admin.tours') }}?search=${encodeURIComponent(query)}`;
                    }
                }
            });
        });

        // Global functions
        function confirmDelete(message = 'Bạn có chắc chắn muốn xóa?') {
            return confirm(message);
        }

        function showLoading() {
            const loading = document.createElement('div');
            loading.id = 'loadingOverlay';
            loading.innerHTML = `
                <div style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; display: flex; align-items: center; justify-content: center;">
                    <div class="text-center text-white">
                        <div class="spinner-border mb-3" role="status"></div>
                        <div>Đang xử lý...</div>
                    </div>
                </div>
            `;
            document.body.appendChild(loading);
        }

        function hideLoading() {
            const loading = document.getElementById('loadingOverlay');
            if (loading) {
                loading.remove();
            }
        }

        function logout() {
            if (confirm('Bạn có chắc chắn muốn đăng xuất?')) {
                // Use GET logout to avoid CSRF issues
                window.location.href = '{{ route("logout.get") }}';
            }
        }
</script>
    
    @yield('scripts')
</body>
</html>

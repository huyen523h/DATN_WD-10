<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Hệ thống Nhân viên') - Tour365</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2563eb;
            --secondary-color: #64748b;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --dark-color: #1e293b;
            --light-color: #f8fafc;
        }

        body {
            background-color: var(--light-color);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .employee-sidebar {
            background: linear-gradient(135deg, var(--primary-color) 0%, #1e40af 100%);
            min-height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            width: 280px;
            z-index: 1000;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }

        .employee-sidebar .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .employee-sidebar .sidebar-header h4 {
            color: white;
            margin: 0;
            font-weight: 600;
        }

        .employee-sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 0.75rem 1.5rem;
            border-radius: 0;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }

        .employee-sidebar .nav-link:hover,
        .employee-sidebar .nav-link.active {
            color: white;
            background-color: rgba(255,255,255,0.1);
            border-left-color: white;
        }

        .employee-sidebar .nav-link i {
            width: 20px;
            margin-right: 0.75rem;
        }

        .employee-content {
            margin-left: 280px;
            min-height: 100vh;
        }

        .employee-header {
            background: white;
            padding: 1rem 2rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .employee-main {
            padding: 2rem;
        }

        .employee-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            border: 1px solid #e2e8f0;
            margin-bottom: 1.5rem;
        }

        .employee-card .card-header {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            border-bottom: 1px solid #e2e8f0;
            padding: 1rem 1.5rem;
            border-radius: 12px 12px 0 0;
        }

        .employee-card .card-body {
            padding: 1.5rem;
        }

        .btn-employee-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, #1e40af 100%);
            border: none;
            color: white;
            padding: 0.5rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-employee-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
            color: white;
        }

        .employee-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            border-left: 4px solid var(--primary-color);
        }

        .stat-card .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
        }

        .stat-card .stat-icon.primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, #1e40af 100%);
            color: white;
        }

        .stat-card .stat-icon.success {
            background: linear-gradient(135deg, var(--success-color) 0%, #059669 100%);
            color: white;
        }

        .stat-card .stat-icon.warning {
            background: linear-gradient(135deg, var(--warning-color) 0%, #d97706 100%);
            color: white;
        }

        .stat-card .stat-icon.danger {
            background: linear-gradient(135deg, var(--danger-color) 0%, #dc2626 100%);
            color: white;
        }

        .employee-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }

        .employee-dropdown {
            position: relative;
        }

        .employee-dropdown .dropdown-menu {
            border: none;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            border-radius: 8px;
            padding: 0.5rem 0;
        }

        .employee-dropdown .dropdown-item {
            padding: 0.5rem 1rem;
            transition: all 0.3s ease;
        }

        .employee-dropdown .dropdown-item:hover {
            background-color: #f8fafc;
        }

        @media (max-width: 768px) {
            .employee-sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }

            .employee-sidebar.show {
                transform: translateX(0);
            }

            .employee-content {
                margin-left: 0;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="employee-sidebar">
        <div class="sidebar-header">
            <h4><i class="fas fa-user-tie me-2"></i>Nhân viên</h4>
        </div>
        
        <nav class="nav flex-column">
            <a href="{{ route('employee.dashboard') }}" class="nav-link {{ request()->routeIs('employee.dashboard') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
            
            <a href="{{ route('employee.profile') }}" class="nav-link {{ request()->routeIs('employee.profile*') ? 'active' : '' }}">
                <i class="fas fa-user"></i>
                <span>Thông tin cá nhân</span>
            </a>
            
            <a href="#" class="nav-link">
                <i class="fas fa-calendar-alt"></i>
                <span>Lịch làm việc</span>
            </a>
            
            <a href="#" class="nav-link">
                <i class="fas fa-tasks"></i>
                <span>Nhiệm vụ</span>
            </a>
            
            <a href="#" class="nav-link">
                <i class="fas fa-chart-line"></i>
                <span>Báo cáo</span>
            </a>
            
            <hr class="my-2" style="border-color: rgba(255,255,255,0.2);">
            
            <a href="{{ route('tours.index') }}" class="nav-link" target="_blank">
                <i class="fas fa-globe"></i>
                <span>Xem trang web</span>
            </a>
        </nav>
    </div>

    <div class="employee-content">
        <div class="employee-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0">@yield('page-title', 'Dashboard')</h5>
                    <small class="text-muted">@yield('page-description', 'Tổng quan hệ thống')</small>
                </div>
                
                <div class="d-flex align-items-center">
                    <a href="{{ route('tours.index') }}" 
                       class="btn btn-outline-primary me-3" 
                       target="_blank"
                       style="border-radius: 8px; padding: 0.5rem 1rem;">
                        <i class="fas fa-globe me-2"></i>
                        Xem trang web
                    </a>
                    
                    <div class="employee-dropdown">
                        <button class="btn btn-link d-flex align-items-center" type="button" data-bs-toggle="dropdown">
                            <img src="{{ session('employee_avatar') ?? asset('images/default-avatar.png') }}" 
                                 alt="Avatar" class="employee-avatar me-2">
                            <span>{{ session('employee_name') ?? 'Nhân viên' }}</span>
                            <i class="fas fa-chevron-down ms-2"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('employee.profile') }}">
                                <i class="fas fa-user me-2"></i>Thông tin cá nhân
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('employee.logout') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="fas fa-sign-out-alt me-2"></i>Đăng xuất
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="employee-main">
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
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>

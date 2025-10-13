<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard - Tour365')</title>
    <meta name="description" content="Admin Dashboard - Tour365">
    <meta name="theme-color" content="#6366F1">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/feather-icons@4.29.0/dist/feather.min.css" rel="stylesheet">
    
    <!-- CSS -->
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
    <style>
        :root {
            /* Primary Colors */
            --primary-50: #EEF2FF;
            --primary-100: #E0E7FF;
            --primary-200: #C7D2FE;
            --primary-300: #A5B4FC;
            --primary-400: #818CF8;
            --primary-500: #6366F1;
            --primary-600: #4F46E5;
            --primary-700: #4338CA;
            --primary-800: #3730A3;
            --primary-900: #312E81;
            
            /* Gray Colors */
            --gray-50: #F9FAFB;
            --gray-100: #F3F4F6;
            --gray-200: #E5E7EB;
            --gray-300: #D1D5DB;
            --gray-400: #9CA3AF;
            --gray-500: #6B7280;
            --gray-600: #4B5563;
            --gray-700: #374151;
            --gray-800: #1F2937;
            --gray-900: #111827;
            
            /* Success Colors */
            --success-50: #ECFDF5;
            --success-500: #10B981;
            --success-600: #059669;
            
            /* Warning Colors */
            --warning-50: #FFFBEB;
            --warning-500: #F59E0B;
            --warning-600: #D97706;
            
            /* Error Colors */
            --error-50: #FEF2F2;
            --error-500: #EF4444;
            --error-600: #DC2626;
            
            /* Info Colors */
            --info-50: #EFF6FF;
            --info-500: #3B82F6;
            --info-600: #2563EB;
            
            /* Shadows */
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
            
            /* Border Radius */
            --radius-sm: 0.375rem;
            --radius: 0.5rem;
            --radius-md: 0.75rem;
            --radius-lg: 1rem;
            --radius-xl: 1.5rem;
            
            /* Spacing */
            --sidebar-width: 280px;
            --header-height: 70px;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: var(--gray-50);
            color: var(--gray-900);
            line-height: 1.6;
            font-size: 14px;
        }
        
        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: linear-gradient(180deg, var(--primary-600) 0%, var(--primary-700) 100%);
            color: white;
            z-index: 1000;
            transition: all 0.3s ease;
            overflow-y: auto;
            box-shadow: var(--shadow-xl);
        }
        
        .sidebar.collapsed {
            width: 80px;
        }
        
        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .sidebar-logo {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: var(--radius-lg);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            color: white;
            flex-shrink: 0;
        }
        
        .sidebar-brand {
            font-size: 1.25rem;
            font-weight: 700;
            color: white;
            text-decoration: none;
            transition: opacity 0.3s ease;
        }
        
        .sidebar.collapsed .sidebar-brand {
            opacity: 0;
            width: 0;
            overflow: hidden;
        }
        
        .sidebar-nav {
            padding: 1rem 0;
        }
        
        .nav-section {
            margin-bottom: 2rem;
        }
        
        .nav-section-title {
            padding: 0 1.5rem 0.5rem;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: rgba(255, 255, 255, 0.6);
            transition: opacity 0.3s ease;
        }
        
        .sidebar.collapsed .nav-section-title {
            opacity: 0;
            height: 0;
            padding: 0;
            margin: 0;
        }
        
        .nav-item {
            margin: 0.25rem 0;
        }
        
        .nav-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1.5rem;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.3s ease;
            position: relative;
            font-weight: 500;
        }
        
        .nav-link:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
        }
        
        .nav-link.active {
            background: rgba(255, 255, 255, 0.15);
            color: white;
            font-weight: 600;
        }
        
        .nav-link.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: white;
            border-radius: 0 2px 2px 0;
        }
        
        .nav-icon {
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        
        .nav-text {
            transition: opacity 0.3s ease;
        }
        
        .sidebar.collapsed .nav-text {
            opacity: 0;
            width: 0;
            overflow: hidden;
        }
        
        .nav-badge {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
            border-radius: var(--radius);
            margin-left: auto;
            transition: opacity 0.3s ease;
        }
        
        .sidebar.collapsed .nav-badge {
            opacity: 0;
            width: 0;
            padding: 0;
            margin: 0;
        }
        
        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: margin-left 0.3s ease;
        }
        
        .sidebar.collapsed + .main-content {
            margin-left: 80px;
        }
        
        /* Header */
        .header {
            height: var(--header-height);
            background: white;
            border-bottom: 1px solid var(--gray-200);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: var(--shadow-sm);
        }
        
        .header-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .sidebar-toggle {
            background: none;
            border: none;
            color: var(--gray-600);
            font-size: 1.25rem;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: var(--radius);
            transition: all 0.3s ease;
        }
        
        .sidebar-toggle:hover {
            background: var(--gray-100);
            color: var(--gray-900);
        }
        
        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            color: var(--gray-600);
        }
        
        .breadcrumb-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .breadcrumb-item:not(:last-child)::after {
            content: '/';
            color: var(--gray-400);
        }
        
        .breadcrumb-item.active {
            color: var(--gray-900);
            font-weight: 500;
        }
        
        .header-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .search-box {
            position: relative;
            width: 300px;
        }
        
        .search-input {
            width: 100%;
            padding: 0.5rem 1rem 0.5rem 2.5rem;
            border: 1px solid var(--gray-300);
            border-radius: var(--radius-lg);
            font-size: 0.875rem;
            background: var(--gray-50);
            transition: all 0.3s ease;
        }
        
        .search-input:focus {
            outline: none;
            border-color: var(--primary-500);
            background: white;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }
        
        .search-icon {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray-400);
            font-size: 0.875rem;
        }
        
        .header-actions {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .header-btn {
            background: none;
            border: none;
            color: var(--gray-600);
            font-size: 1.125rem;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: var(--radius);
            transition: all 0.3s ease;
            position: relative;
        }
        
        .header-btn:hover {
            background: var(--gray-100);
            color: var(--gray-900);
        }
        
        .notification-badge {
            position: absolute;
            top: 0.25rem;
            right: 0.25rem;
            background: var(--error-500);
            color: white;
            font-size: 0.625rem;
            padding: 0.125rem 0.375rem;
            border-radius: 50%;
            min-width: 1.25rem;
            height: 1.25rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .user-menu {
            position: relative;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--primary-500);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .user-avatar:hover {
            background: var(--primary-600);
            transform: scale(1.05);
        }
        
        /* Content Area */
        .content {
            padding: 2rem;
        }
        
        /* Cards */
        .card {
            background: white;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow);
            border: 1px solid var(--gray-200);
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        .card:hover {
            box-shadow: var(--shadow-md);
        }
        
        .card-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--gray-200);
            background: var(--gray-50);
        }
        
        .card-body {
            padding: 1.5rem;
        }
        
        .card-footer {
            padding: 1rem 1.5rem;
            border-top: 1px solid var(--gray-200);
            background: var(--gray-50);
        }
        
        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border: none;
            border-radius: var(--radius);
            font-size: 0.875rem;
            font-weight: 500;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.3s ease;
            white-space: nowrap;
        }
        
        .btn-primary {
            background: var(--primary-500);
            color: white;
        }
        
        .btn-primary:hover {
            background: var(--primary-600);
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }
        
        .btn-secondary {
            background: var(--gray-100);
            color: var(--gray-700);
        }
        
        .btn-secondary:hover {
            background: var(--gray-200);
        }
        
        .btn-success {
            background: var(--success-500);
            color: white;
        }
        
        .btn-success:hover {
            background: var(--success-600);
        }
        
        .btn-warning {
            background: var(--warning-500);
            color: white;
        }
        
        .btn-warning:hover {
            background: var(--warning-600);
        }
        
        .btn-danger {
            background: var(--error-500);
            color: white;
        }
        
        .btn-danger:hover {
            background: var(--error-600);
        }
        
        .btn-outline-primary {
            background: transparent;
            color: var(--primary-500);
            border: 1px solid var(--primary-500);
        }
        
        .btn-outline-primary:hover {
            background: var(--primary-500);
            color: white;
        }
        
        .btn-sm {
            padding: 0.375rem 0.75rem;
            font-size: 0.75rem;
        }
        
        .btn-lg {
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
        }
        
        /* Tables */
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .table th,
        .table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid var(--gray-200);
        }
        
        .table th {
            background: var(--gray-50);
            font-weight: 600;
            color: var(--gray-700);
            font-size: 0.875rem;
        }
        
        .table tbody tr:hover {
            background: var(--gray-50);
        }
        
        /* Badges */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            padding: 0.25rem 0.75rem;
            border-radius: var(--radius);
            font-size: 0.75rem;
            font-weight: 500;
        }
        
        .badge-success {
            background: var(--success-50);
            color: var(--success-600);
        }
        
        .badge-warning {
            background: var(--warning-50);
            color: var(--warning-600);
        }
        
        .badge-danger {
            background: var(--error-50);
            color: var(--error-600);
        }
        
        .badge-info {
            background: var(--info-50);
            color: var(--info-600);
        }
        
        .badge-primary {
            background: var(--primary-50);
            color: var(--primary-600);
        }
        
        /* Forms */
        .form-group {
            margin-bottom: 1rem;
        }
        
        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--gray-700);
        }
        
        .form-control {
            width: 100%;
            padding: 0.5rem 0.75rem;
            border: 1px solid var(--gray-300);
            border-radius: var(--radius);
            font-size: 0.875rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--primary-500);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }
        
        .form-select {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 0.5rem center;
            background-repeat: no-repeat;
            background-size: 1.5em 1.5em;
            padding-right: 2.5rem;
        }
        
        /* Alerts */
        .alert {
            padding: 1rem;
            border-radius: var(--radius);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .alert-success {
            background: var(--success-50);
            color: var(--success-600);
            border: 1px solid var(--success-200);
        }
        
        .alert-warning {
            background: var(--warning-50);
            color: var(--warning-600);
            border: 1px solid var(--warning-200);
        }
        
        .alert-danger {
            background: var(--error-50);
            color: var(--error-600);
            border: 1px solid var(--error-200);
        }
        
        .alert-info {
            background: var(--info-50);
            color: var(--info-600);
            border: 1px solid var(--info-200);
        }
        
        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background: white;
            border-radius: var(--radius-lg);
            padding: 1.5rem;
            box-shadow: var(--shadow);
            border: 1px solid var(--gray-200);
            transition: all 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }
        
        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: var(--radius-lg);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }
        
        .stat-icon-primary {
            background: var(--primary-50);
            color: var(--primary-500);
        }
        
        .stat-icon-success {
            background: var(--success-50);
            color: var(--success-500);
        }
        
        .stat-icon-warning {
            background: var(--warning-50);
            color: var(--warning-500);
        }
        
        .stat-icon-info {
            background: var(--info-50);
            color: var(--info-500);
        }
        
        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--gray-900);
            margin-bottom: 0.25rem;
        }
        
        .stat-label {
            color: var(--gray-600);
            font-size: 0.875rem;
            font-weight: 500;
        }
        
        .stat-change {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            margin-top: 0.5rem;
            font-size: 0.75rem;
            font-weight: 500;
        }
        
        .stat-change.positive {
            color: var(--success-600);
        }
        
        .stat-change.negative {
            color: var(--error-600);
        }
        
        /* Responsive */
        @media (max-width: 1024px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.open {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .search-box {
                width: 200px;
            }
        }
        
        @media (max-width: 768px) {
            .header {
                padding: 0 1rem;
            }
            
            .content {
                padding: 1rem;
            }
            
            .search-box {
                display: none;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
        
        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .fade-in {
            animation: fadeIn 0.3s ease-out;
        }
        
        /* Custom Scrollbar */
        .sidebar::-webkit-scrollbar {
            width: 4px;
        }
        
        .sidebar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
        }
        
        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 2px;
        }
        
        .sidebar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.5);
        }
    </style>
    
    @yield('styles')
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo">
                <i class="fas fa-plane"></i>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="sidebar-brand">
                Tour365 Admin
            </a>
        </div>
        
        <nav class="sidebar-nav">
            <!-- Dashboard -->
            <div class="nav-section">
                <div class="nav-section-title">Tổng quan</div>
                <div class="nav-item">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <div class="nav-icon">
                            <i class="fas fa-tachometer-alt"></i>
                        </div>
                        <span class="nav-text">Dashboard</span>
                    </a>
                </div>
            </div>
            
            <!-- Tours Management -->
            <div class="nav-section">
                <div class="nav-section-title">Quản lý Tour</div>
                <div class="nav-item">
                    <a href="{{ route('admin.tours') }}" class="nav-link {{ request()->routeIs('admin.tours*') ? 'active' : '' }}">
                        <div class="nav-icon">
                            <i class="fas fa-map-marked-alt"></i>
                        </div>
                        <span class="nav-text">Tours</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route('admin.categories') }}" class="nav-link {{ request()->routeIs('admin.categories*') ? 'active' : '' }}">
                        <div class="nav-icon">
                            <i class="fas fa-tags"></i>
                        </div>
                        <span class="nav-text">Danh mục</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route('admin.promotions') }}" class="nav-link {{ request()->routeIs('admin.promotions*') ? 'active' : '' }}">
                        <div class="nav-icon">
                            <i class="fas fa-gift"></i>
                        </div>
                        <span class="nav-text">Mã giảm giá</span>
                    </a>
                </div>
            </div>
            
            <!-- Bookings Management -->
            <div class="nav-section">
                <div class="nav-section-title">Đặt tour</div>
                <div class="nav-item">
                    <a href="{{ route('admin.bookings') }}" class="nav-link {{ request()->routeIs('admin.bookings*') ? 'active' : '' }}">
                        <div class="nav-icon">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <span class="nav-text">Đặt tour</span>
                        <span class="nav-badge">{{ \App\Models\Booking::where('status', 'pending')->count() }}</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route('admin.customers') }}" class="nav-link {{ request()->routeIs('admin.customers*') ? 'active' : '' }}">
                        <div class="nav-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <span class="nav-text">Khách hàng</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route('admin.payments') }}" class="nav-link {{ request()->routeIs('admin.payments*') ? 'active' : '' }}">
                        <div class="nav-icon">
                            <i class="fas fa-credit-card"></i>
                        </div>
                        <span class="nav-text">Thanh toán</span>
                    </a>
                </div>
            </div>
            
            <!-- Reviews & Support -->
            <div class="nav-section">
                <div class="nav-section-title">Hỗ trợ</div>
                <div class="nav-item">
                    <a href="{{ route('admin.reviews') }}" class="nav-link {{ request()->routeIs('admin.reviews*') ? 'active' : '' }}">
                        <div class="nav-icon">
                            <i class="fas fa-star"></i>
                        </div>
                        <span class="nav-text">Đánh giá</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route('admin.support') }}" class="nav-link {{ request()->routeIs('admin.support*') ? 'active' : '' }}">
                        <div class="nav-icon">
                            <i class="fas fa-headset"></i>
                        </div>
                        <span class="nav-text">Hỗ trợ</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route('admin.notifications') }}" class="nav-link {{ request()->routeIs('admin.notifications*') ? 'active' : '' }}">
                        <div class="nav-icon">
                            <i class="fas fa-bell"></i>
                        </div>
                        <span class="nav-text">Thông báo</span>
                    </a>
                </div>
            </div>
            
            <!-- Reports & Settings -->
            <div class="nav-section">
                <div class="nav-section-title">Hệ thống</div>
                <div class="nav-item">
                    <a href="{{ route('admin.reports') }}" class="nav-link {{ request()->routeIs('admin.reports*') ? 'active' : '' }}">
                        <div class="nav-icon">
                            <i class="fas fa-chart-bar"></i>
                        </div>
                        <span class="nav-text">Báo cáo</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route('admin.settings') }}" class="nav-link {{ request()->routeIs('admin.settings*') ? 'active' : '' }}">
                        <div class="nav-icon">
                            <i class="fas fa-cog"></i>
                        </div>
                        <span class="nav-text">Cài đặt</span>
                    </a>
                </div>
            </div>
        </nav>
    </aside>
    
    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->
        <header class="header">
            <div class="header-left">
                <button class="sidebar-toggle" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>
                <nav class="breadcrumb">
                    @yield('breadcrumb')
                </nav>
            </div>
            
            <div class="header-right">
                <div class="search-box">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" class="search-input" placeholder="Tìm kiếm...">
                </div>
                
                <div class="header-actions">
                    <button class="header-btn" title="Thông báo">
                        <i class="fas fa-bell"></i>
                        <span class="notification-badge">3</span>
                    </button>
                    <button class="header-btn" title="Tin nhắn">
                        <i class="fas fa-envelope"></i>
                        <span class="notification-badge">5</span>
                    </button>
                    <button class="header-btn" title="Cài đặt">
                        <i class="fas fa-cog"></i>
                    </button>
                </div>
                
                <div class="user-menu">
                    <div class="user-avatar" onclick="toggleUserMenu()">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                </div>
            </div>
        </header>
        
        <!-- Content -->
        <main class="content">
            @yield('content')
        </main>
    </div>
    
    <!-- Scripts -->
    <script>
        // Sidebar Toggle
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');
        const mainContent = document.querySelector('.main-content');
        
        sidebarToggle.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
        });
        
        // Mobile sidebar toggle
        if (window.innerWidth <= 1024) {
            sidebarToggle.addEventListener('click', () => {
                sidebar.classList.toggle('open');
            });
            
            // Close sidebar when clicking outside
            document.addEventListener('click', (e) => {
                if (!sidebar.contains(e.target) && !sidebarToggle.contains(e.target)) {
                    sidebar.classList.remove('open');
                }
            });
        }
        
        // User menu toggle
        function toggleUserMenu() {
            // Implement user menu dropdown
            console.log('Toggle user menu');
        }
        
        // Search functionality
        const searchInput = document.querySelector('.search-input');
        searchInput.addEventListener('input', (e) => {
            // Implement search functionality
            console.log('Search:', e.target.value);
        });
        
        // Auto-hide alerts
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            setTimeout(() => {
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-10px)';
                setTimeout(() => {
                    alert.remove();
                }, 300);
            }, 5000);
        });
        
        // Initialize tooltips
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize any tooltips or other interactive elements
        });
    </script>
    
    @yield('scripts')
</body>
</html>
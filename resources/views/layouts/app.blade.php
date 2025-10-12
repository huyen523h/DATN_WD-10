<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Tour365 - Du lịch trọn gói')</title>
    <meta name="description" content="@yield('description', 'Tour365 - Dịch vụ du lịch trọn gói uy tín, chất lượng')">
    <meta name="theme-color" content="#0EA5E9">
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
    
    <!-- Bootstrap 5.3.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
    :root {
        /* Sky-blue theme */
        --brand-primary: #0EA5E9;        /* sky-500 */
        --brand-primary-dark: #0284C7;   /* sky-600 */
        --brand-accent: #06B6D4;        /* cyan-500 */
        --text-muted: #6B7280;
        --bg-soft: #F8FAFC;
        --shadow-sm: 0 4px 12px rgba(0, 0, 0, 0.06);
        --shadow-md: 0 10px 30px rgba(0,0,0,0.08);
        --radius: 14px;
    }

    /* User Dropdown Styles */
    .nav-item.dropdown .dropdown-toggle:hover {
        background-color: rgba(14, 165, 233, 0.1);
        border-radius: 8px;
    }

    .dropdown-menu .dropdown-item:hover {
        background-color: #f8fafc;
        transform: translateX(4px);
    }

    .dropdown-menu .dropdown-item:hover .icon-wrapper {
        transform: scale(1.1);
    }

    .user-avatar img {
        border: 2px solid #e2e8f0;
        transition: all 0.3s ease;
    }

    .nav-item.dropdown:hover .user-avatar img {
        border-color: var(--brand-primary);
        box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.1);
    }
        .navbar-brand {
            font-weight: bold;
            color: var(--brand-primary) !important;
            letter-spacing: .2px;
        }
        .navbar-light .navbar-nav .nav-link {
            font-weight: 600;
            color: #293241;
            transition: color .2s ease, transform .2s ease;
        }
        .navbar-light .navbar-nav .nav-link:hover {
            color: var(--brand-primary);
            transform: translateY(-1px);
        }
        .hero-section {
            background: linear-gradient(135deg, #0EA5E9 0%, #38BDF8 100%);
            color: white;
            padding: 80px 0;
        }
        .tour-card {
            transition: transform 0.3s ease;
            border: none;
            box-shadow: var(--shadow-sm);
            border-radius: var(--radius);
        }
        .tour-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-md);
        }
        .price-badge {
            background: linear-gradient(45deg, #FF6B6B, #4ECDC4);
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: bold;
        }
        .category-badge {
            background: #E3F2FD;
            color: #1976D2;
            padding: 4px 12px;
            border-radius: 15px;
            font-size: 0.85rem;
        }
    .footer {
        background: #2C3E50;
        color: white;
        padding: 40px 0 20px;
    }
    
    /* Search Bar Styles */
    .search-container {
        position: relative;
    }
    
    .navbar .input-group {
        max-width: 300px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        border-radius: 25px;
        overflow: hidden;
    }
    
    .navbar .form-control {
        border: none;
        border-radius: 25px 0 0 25px;
        padding-left: 20px;
        background: rgba(255,255,255,0.9);
        backdrop-filter: blur(10px);
    }
    
    .navbar .form-control:focus {
        border-color: transparent;
        box-shadow: none;
        background: white;
    }
    
    .navbar .btn-outline-primary {
        border: none;
        border-radius: 0 25px 25px 0;
        background: #4F46E5;
        color: white;
        padding: 8px 15px;
    }
    
    .navbar .btn-outline-primary:hover {
        background: #3730A3;
        color: white;
    }
    
    @media (max-width: 768px) {
        .search-container {
            width: 100%;
            margin-bottom: 15px;
        }
        
        .navbar .input-group {
            max-width: 100%;
        }
        
        .navbar .form-control {
            min-width: auto;
        }
    }

    /* Fancy buttons */
    .btn-primary {
        background: var(--brand-primary);
        border-color: var(--brand-primary);
        box-shadow: 0 6px 14px rgba(79,70,229,.25);
    }
    .btn-primary:hover { background: var(--brand-primary-dark); border-color: var(--brand-primary-dark); }
    .btn-outline-primary { color: var(--brand-primary); border-color: var(--brand-primary); }
    .btn-outline-primary:hover { color: #fff; background: var(--brand-primary); }

    /* Back to top */
    #backToTop {
        position: fixed;
        right: 20px;
        bottom: 20px;
        z-index: 999;
        display: none;
        border-radius: 50%;
        width: 48px; height: 48px;
        box-shadow: var(--shadow-sm);
    }

    /* Motion utilities */
    .fade-in { animation: fadeIn .5s ease both; }
    .slide-up { animation: slideUp .5s ease both; }
    @keyframes fadeIn { from { opacity: 0 } to { opacity: 1 } }
    @keyframes slideUp { from { transform: translateY(8px); opacity: 0 } to { transform: translateY(0); opacity: 1 } }

    @media (prefers-reduced-motion: reduce) {
        .fade-in, .slide-up { animation: none !important; }
        * { scroll-behavior: auto; }
    }

    /* Dark mode */
    body.dark {
        background: #0B1220;
        color: #E5E7EB;
    }
    body.dark .navbar.bg-white { background: #0F172A !important; }
    body.dark .card, body.dark .footer { background: #0F172A; color: #E5E7EB; }
    body.dark .list-group-item { background: #0F172A; color: #E5E7EB; }
    body.dark .btn-outline-primary { color: #38BDF8; border-color: #38BDF8; }
    body.dark .btn-outline-primary:hover { background:#38BDF8; color:#0B1220; }
    </style>
    @yield('styles')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top" style="backdrop-filter: blur(6px);">
        <div class="container">
            <a class="navbar-brand" href="{{ route('tours.index') }}">
                <i class="fas fa-plane"></i> Tour365
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('tours.index') }}">
                            <i class="fas fa-home"></i> Trang chủ
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('tours.index') }}">
                            <i class="fas fa-map-marked-alt"></i> Tours
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('about') }}">
                            <i class="fas fa-info-circle"></i> Về chúng tôi
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('blog.index') }}">
                            <i class="fas fa-newspaper"></i> Blog
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('promotions.index') }}">
                            <i class="fas fa-gift"></i> Ưu đãi
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('contact') }}">
                            <i class="fas fa-envelope"></i> Liên hệ
                        </a>
                    </li>
                    @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('bookings.index') }}">
                                <i class="fas fa-calendar-check"></i> Đặt tour
                            </a>
                        </li>
                    @endauth
                </ul>
                
                <!-- Search Bar -->
                <div class="search-container me-3">
                    <form class="d-flex" method="GET" action="{{ route('tours.index') }}">
                        <div class="input-group">
                            <input class="form-control" type="search" name="search" 
                                   placeholder="Tìm kiếm tours, điểm đến..." value="{{ request('search') }}"
                                   style="min-width: 250px;">
                            <button class="btn btn-outline-primary" type="submit" title="Tìm kiếm">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                </div>
                
                <ul class="navbar-nav align-items-center">
                    <li class="nav-item me-2">
                        <button id="themeToggle" class="btn btn-outline-primary btn-sm" title="Chuyển giao diện">
                            <i class="fas fa-moon"></i>
                        </button>
                    </li>
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="fas fa-sign-in-alt"></i> Đăng nhập
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">
                                <i class="fas fa-user-plus"></i> Đăng ký
                            </a>
                        </li>
                    @else
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" style="padding: 0.5rem 1rem;">
                                <div class="user-avatar me-2">
                                    <img src="{{ Auth::user()->employee ? Auth::user()->employee->avatar_url : asset('images/default-avatar.png') }}" 
                                         alt="Avatar" 
                                         class="rounded-circle" 
                                         style="width: 32px; height: 32px; object-fit: cover;">
                                </div>
                                <div class="user-info">
                                    <div class="user-name" style="font-weight: 500; color: #333; font-size: 0.9rem;">{{ Auth::user()->name }}</div>
                                    @if(Auth::user()->employee)
                                        <div class="user-role" style="font-size: 0.75rem; color: #666;">{{ Auth::user()->employee->position ?? 'Nhân viên' }}</div>
                                    @endif
                                </div>
                                <i class="fas fa-chevron-down ms-2" style="font-size: 0.8rem; color: #666;"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" style="min-width: 250px; border: none; box-shadow: 0 10px 25px rgba(0,0,0,0.1); border-radius: 12px; padding: 0.5rem 0;">
                                <!-- User Info Header -->
                                <li class="px-3 py-2" style="background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%); border-radius: 12px 12px 0 0;">
                                    <div class="d-flex align-items-center">
                                        <img src="{{ Auth::user()->employee ? Auth::user()->employee->avatar_url : asset('images/default-avatar.png') }}" 
                                             alt="Avatar" 
                                             class="rounded-circle me-3" 
                                             style="width: 40px; height: 40px; object-fit: cover;">
                                        <div>
                                            <div style="font-weight: 600; color: #1e293b; font-size: 0.9rem;">{{ Auth::user()->name }}</div>
                                            <div style="font-size: 0.75rem; color: #64748b;">
                                                @if(Auth::user()->employee)
                                                    {{ Auth::user()->employee->position ?? 'Nhân viên' }}
                                                @else
                                                    Khách hàng
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                
                                <li><hr class="dropdown-divider my-2"></li>
                                
                                @if(Auth::user()->isAdmin())
                                    <li><a class="dropdown-item d-flex align-items-center py-2" href="{{ route('admin.dashboard') }}" style="transition: all 0.3s ease;">
                                        <div class="icon-wrapper me-3" style="width: 32px; height: 32px; background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                            <i class="fas fa-tachometer-alt text-white" style="font-size: 0.8rem;"></i>
                                        </div>
                                        <div>
                                            <div style="font-weight: 500; color: #1e293b;">Admin Dashboard</div>
                                            <div style="font-size: 0.75rem; color: #64748b;">Quản trị hệ thống</div>
                                        </div>
                                    </a></li>
                                @endif
                                
                                @if(Auth::user()->employee)
                                    <li><a class="dropdown-item d-flex align-items-center py-2" href="{{ route('employee.dashboard') }}" style="transition: all 0.3s ease;">
                                        <div class="icon-wrapper me-3" style="width: 32px; height: 32px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                            <i class="fas fa-user-tie text-white" style="font-size: 0.8rem;"></i>
                                        </div>
                                        <div>
                                            <div style="font-weight: 500; color: #1e293b;">Staff Dashboard</div>
                                            <div style="font-size: 0.75rem; color: #64748b;">Bảng điều khiển nhân viên</div>
                                        </div>
                                    </a></li>
                                @endif
                                
                                <li><a class="dropdown-item d-flex align-items-center py-2" href="{{ route('profile.index') }}" style="transition: all 0.3s ease;">
                                    <div class="icon-wrapper me-3" style="width: 32px; height: 32px; background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-user text-white" style="font-size: 0.8rem;"></i>
                                    </div>
                                    <div>
                                        <div style="font-weight: 500; color: #1e293b;">Thông tin cá nhân</div>
                                        <div style="font-size: 0.75rem; color: #64748b;">Quản lý hồ sơ</div>
                                    </div>
                                </a></li>
                                
                                <li><a class="dropdown-item d-flex align-items-center py-2" href="{{ route('bookings.index') }}" style="transition: all 0.3s ease;">
                                    <div class="icon-wrapper me-3" style="width: 32px; height: 32px; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-calendar-check text-white" style="font-size: 0.8rem;"></i>
                                    </div>
                                    <div>
                                        <div style="font-weight: 500; color: #1e293b;">Đặt tour của tôi</div>
                                        <div style="font-size: 0.75rem; color: #64748b;">Lịch sử đặt tour</div>
                                    </div>
                                </a></li>
                                
                                <li><hr class="dropdown-divider my-2"></li>
                                
                                <li>
                                    <form method="POST" action="{{ route('logout') }}" class="d-inline w-100">
                                        @csrf
                                        <button type="submit" class="dropdown-item d-flex align-items-center py-2 w-100" style="border: none; background: none; transition: all 0.3s ease;">
                                            <div class="icon-wrapper me-3" style="width: 32px; height: 32px; background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                                <i class="fas fa-sign-out-alt text-white" style="font-size: 0.8rem;"></i>
                                            </div>
                                            <div>
                                                <div style="font-weight: 500; color: #1e293b;">Đăng xuất</div>
                                                <div style="font-size: 0.75rem; color: #64748b;">Thoát khỏi hệ thống</div>
                                            </div>
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5><i class="fas fa-plane"></i> Tour365</h5>
                    <p>Dịch vụ du lịch trọn gói uy tín, chất lượng với giá cả hợp lý.</p>
                </div>
                <div class="col-md-4">
                    <h5>Liên kết nhanh</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('tours.index') }}" class="text-light">Tours</a></li>
                        <li><a href="{{ route('about') }}" class="text-light">Về chúng tôi</a></li>
                        <li><a href="{{ route('contact') }}" class="text-light">Liên hệ</a></li>
                        <li><a href="{{ route('sitemap') }}" class="text-light">Sitemap</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Liên hệ</h5>
                    <p><i class="fas fa-phone"></i> 1900 1234</p>
                    <p><i class="fas fa-envelope"></i> info@tour365.vn</p>
                </div>
            </div>
            <hr>
            <div class="text-center">
                <p>&copy; 2025 Tour365. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5.3.3 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const backBtn = document.createElement('button');
        backBtn.id = 'backToTop';
        backBtn.className = 'btn btn-primary';
        backBtn.innerHTML = '<i class="fas fa-arrow-up"></i>';
        document.body.appendChild(backBtn);
        window.addEventListener('scroll', () => {
            backBtn.style.display = window.scrollY > 300 ? 'inline-flex' : 'none';
        });
        backBtn.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));

        // Theme toggle
        const themeToggle = document.getElementById('themeToggle');
        if (themeToggle) {
            if (localStorage.getItem('theme') === 'dark') {
                document.body.classList.add('dark');
            }
            themeToggle.addEventListener('click', () => {
                document.body.classList.toggle('dark');
                const isDark = document.body.classList.contains('dark');
                localStorage.setItem('theme', isDark ? 'dark' : 'light');
                themeToggle.innerHTML = isDark ? '<i class="fas fa-sun"></i>' : '<i class="fas fa-moon"></i>';
            });
        }

        // Enable Bootstrap tooltips globally
        if (window.bootstrap) {
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        }
    </script>
    @yield('scripts')
</body>
</html>

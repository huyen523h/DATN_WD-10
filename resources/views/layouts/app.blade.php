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
            --brand-primary: #0EA5E9;
            /* sky-500 */
            --brand-primary-dark: #0284C7;
            /* sky-600 */
            --brand-accent: #06B6D4;
            /* cyan-500 */
            --text-muted: #6B7280;
            --bg-soft: #F8FAFC;
            --shadow-sm: 0 4px 12px rgba(0, 0, 0, 0.06);
            --shadow-md: 0 10px 30px rgba(0, 0, 0, 0.08);
            --radius: 14px;
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
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border-radius: 25px;
            overflow: hidden;
        }

        .navbar .form-control {
            border: none;
            border-radius: 25px 0 0 25px;
            padding-left: 20px;
            background: rgba(255, 255, 255, 0.9);
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
            box-shadow: 0 6px 14px rgba(79, 70, 229, .25);
        }

        .btn-primary:hover {
            background: var(--brand-primary-dark);
            border-color: var(--brand-primary-dark);
        }

        .btn-outline-primary {
            color: var(--brand-primary);
            border-color: var(--brand-primary);
        }

        .btn-outline-primary:hover {
            color: #fff;
            background: var(--brand-primary);
        }

        /* Back to top */
        #backToTop {
            position: fixed;
            right: 20px;
            bottom: 20px;
            z-index: 999;
            display: none;
            border-radius: 50%;
            width: 48px;
            height: 48px;
            box-shadow: var(--shadow-sm);
        }

        /* Motion utilities */
        .fade-in {
            animation: fadeIn .5s ease both;
        }

        .slide-up {
            animation: slideUp .5s ease both;
        }

        @keyframes fadeIn {
            from {
                opacity: 0
            }

            to {
                opacity: 1
            }
        }

        @keyframes slideUp {
            from {
                transform: translateY(8px);
                opacity: 0
            }

            to {
                transform: translateY(0);
                opacity: 1
            }
        }

        @media (prefers-reduced-motion: reduce) {

            .fade-in,
            .slide-up {
                animation: none !important;
            }

            * {
                scroll-behavior: auto;
            }
        }

        /* Dark mode */
        body.dark {
            background: #0B1220;
            color: #E5E7EB;
        }

        body.dark .navbar.bg-white {
            background: #0F172A !important;
        }

        body.dark .card,
        body.dark .footer {
            background: #0F172A;
            color: #E5E7EB;
        }

        body.dark .list-group-item {
            background: #0F172A;
            color: #E5E7EB;
        }

        body.dark .btn-outline-primary {
            color: #38BDF8;
            border-color: #38BDF8;
        }

        body.dark .btn-outline-primary:hover {
            background: #38BDF8;
            color: #0B1220;
        }
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
                        <a class="nav-link" href="{{ route('wishlists.index') }}">
                            <i class="fas fa-heart text-danger"></i> Yêu thích
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
                        @if (Auth::user()->isStaff())
                            <li class="nav-item">
                                <a class="nav-link btn btn-outline-primary btn-sm ms-2" href="{{ route('staff.dashboard') }}">
                                    <i class="fas fa-user-tie"></i> Staff Dashboard
                                </a>
                            </li>
                        @elseif (Auth::user()->isAdmin())
                            <li class="nav-item">
                                <a class="nav-link btn btn-outline-danger btn-sm ms-2" href="{{ route('admin.dashboard') }}">
                                    <i class="fas fa-tachometer-alt"></i> Admin Dashboard
                                </a>
                            </li>
                        @endif
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
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                                data-bs-toggle="dropdown">
                                <i class="fas fa-user"></i> {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu">
                                @if (Auth::user()->isAdmin())
                                    <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                            <i class="fas fa-tachometer-alt"></i> Admin Dashboard
                                        </a></li>
                                @elseif (Auth::user()->isStaff())
                                    <li><a class="dropdown-item" href="{{ route('staff.dashboard') }}">
                                            <i class="fas fa-user-tie"></i> Staff Dashboard
                                        </a></li>
                                @endif
                                <li><a class="dropdown-item" href="{{ route('profile.index') }}">
                                        <i class="fas fa-user"></i> Thông tin cá nhân
                                    </a></li>
                                <li><a class="dropdown-item" href="{{ route('bookings.index') }}">
                                        <i class="fas fa-calendar-check"></i> Đặt tour của tôi
                                    </a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="fas fa-sign-out-alt"></i> Đăng xuất
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
        backBtn.addEventListener('click', () => window.scrollTo({
            top: 0,
            behavior: 'smooth'
        }));

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
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        }
    </script>
    @yield('scripts')
</body>

</html>

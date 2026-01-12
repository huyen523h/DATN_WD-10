<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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

        /* Modern Navigation Styles */
        .navbar {
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(14, 165, 233, 0.1);
            transition: all 0.3s ease;
        }

        .navbar.scrolled {
            background: rgba(255, 255, 255, 0.98) !important;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            font-weight: 800;
            color: var(--brand-primary) !important;
            letter-spacing: 0.5px;
            font-size: 1.5rem;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .navbar-brand:hover {
            color: var(--brand-primary-dark) !important;
            transform: scale(1.05);
        }

        .navbar-brand i {
            margin-right: 8px;
            font-size: 1.3rem;
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-3px);
            }
        }

        .navbar-nav {
            flex-wrap: nowrap !important;
        }

        .navbar-nav .nav-item {
            margin: 0 4px;
            flex-shrink: 0;
        }

        .navbar-light .navbar-nav .nav-link {
            font-weight: 600;
            color: #374151;
            transition: all 0.3s ease;
            padding: 10px 12px !important;
            border-radius: 12px;
            position: relative;
overflow: hidden;
            white-space: nowrap;
            font-size: 0.9rem;
        }

        .navbar-light .navbar-nav .nav-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(14, 165, 233, 0.1), transparent);
            transition: left 0.5s ease;
        }

        .navbar-light .navbar-nav .nav-link:hover::before {
            left: 100%;
        }

        .navbar-light .navbar-nav .nav-link:hover {
            color: var(--brand-primary);
            background: rgba(14, 165, 233, 0.08);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(14, 165, 233, 0.2);
        }

        .navbar-light .navbar-nav .nav-link i {
            margin-right: 6px;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .navbar-light .navbar-nav .nav-link:hover i {
            transform: scale(1.1);
        }

        /* Active state */
        .navbar-light .navbar-nav .nav-link.active {
            color: var(--brand-primary);
            background: rgba(14, 165, 233, 0.1);
            font-weight: 700;
        }

        /* Theme toggle button */
        #themeToggle {
            border-radius: 50px;
            padding: 10px 15px;
            transition: all 0.3s ease;
            border: 2px solid var(--brand-primary);
            background: transparent;
            color: var(--brand-primary);
        }

        #themeToggle:hover {
            background: var(--brand-primary);
            color: white;
            transform: scale(1.1);
            box-shadow: 0 4px 15px rgba(14, 165, 233, 0.3);
        }

        /* User dropdown */
        .navbar-nav .dropdown-toggle {
            border-radius: 25px;
            padding: 8px 16px;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .navbar-nav .dropdown-toggle:hover {
            background: rgba(14, 165, 233, 0.1);
            border-color: rgba(14, 165, 233, 0.2);
            transform: translateY(-1px);
        }

        /* Mobile menu */
        .navbar-toggler {
            border: none;
            padding: 8px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .navbar-toggler:focus {
            box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.2);
        }

        .navbar-toggler:hover {
            background: rgba(14, 165, 233, 0.1);
        }

        /* Responsive adjustments */
        @media (max-width: 1200px) {
            .navbar-light .navbar-nav .nav-link {
                padding: 8px 10px !important;
                font-size: 0.85rem;
            }

            .navbar-nav .nav-item {
                margin: 0 2px;
            }
        }

        @media (max-width: 1100px) {
            .navbar-light .navbar-nav .nav-link {
padding: 6px 8px !important;
                font-size: 0.8rem;
            }

            .navbar-nav .nav-item {
                margin: 0 1px;
            }
        }

        @media (max-width: 992px) {
            .navbar-light .navbar-nav .nav-link span {
                display: none !important;
            }
        }

        @media (max-width: 991px) {
            .navbar-nav {
                padding: 20px 0;
                background: rgba(255, 255, 255, 0.98);
                border-radius: 15px;
                margin-top: 15px;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
                flex-wrap: wrap !important;
            }

            .navbar-nav .nav-item {
                margin: 5px 0;
                flex-shrink: 1;
            }

            .navbar-light .navbar-nav .nav-link {
                padding: 15px 20px !important;
                margin: 2px 10px;
                border-radius: 10px;
                white-space: normal;
                font-size: 0.9rem;
            }
        }

        @media (max-width: 768px) {
            .navbar-light .navbar-nav .nav-link {
                padding: 12px 15px !important;
                font-size: 0.85rem;
            }
        }

        /* gỡ nền full */
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

        /* Footer Styles */
        .footer {
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
            color: white;
            padding: 60px 0 30px;
            position: relative;
            overflow: hidden;
        }

        .footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(14, 165, 233, 0.5), transparent);
        }

        /* Footer Brand */
        .footer-brand .brand-logo h2 {
            font-size: 2rem;
            margin-bottom: 0;
        }

        .footer-brand .brand-logo i {
            font-size: 1.8rem;
        }

        /* Certifications */
        .certifications {
margin: 20px 0;
        }

        .cert-badge {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            padding: 8px 12px;
            text-align: center;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .cert-badge:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }

        .cert-badge i {
            display: block;
            font-size: 1.2rem;
            margin-bottom: 4px;
        }

        .cert-badge span {
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Partnership Button */
        .btn-partnership {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .btn-partnership:hover {
            background: rgba(14, 165, 233, 0.2);
            border-color: rgba(14, 165, 233, 0.4);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(14, 165, 233, 0.3);
        }

        /* Footer Headings */
        .footer-heading {
            color: white;
            font-weight: 700;
            font-size: 1rem;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            position: relative;
        }

        .footer-heading::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 0;
            width: 30px;
            height: 2px;
            background: linear-gradient(90deg, #0EA5E9, #38BDF8);
        }

        /* Footer Links */
        .footer-links {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .footer-links li {
            margin-bottom: 8px;
        }

        .footer-links a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            position: relative;
        }

        .footer-links a:hover {
            color: #0EA5E9;
            padding-left: 8px;
        }

        .footer-links a::before {
            content: '';
            position: absolute;
            left: -8px;
            top: 50%;
            transform: translateY(-50%);
            width: 0;
            height: 1px;
            background: #0EA5E9;
            transition: width 0.3s ease;
        }

        .footer-links a:hover::before {
            width: 4px;
        }

        /* Payment Partners */
.payment-partners {
            margin-top: 15px;
        }

        .payment-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(60px, 1fr));
            gap: 8px;
            max-width: 300px;
        }

        .payment-item {
            background: white;
            border-radius: 6px;
            padding: 8px;
            text-align: center;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .payment-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .payment-logo {
            max-width: 100%;
            height: auto;
            border-radius: 4px;
        }

        /* Social Links */
        .social-links {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .social-link {
            display: flex;
            align-items: center;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.3s ease;
            padding: 8px 0;
        }

        .social-link:hover {
            color: #0EA5E9;
            transform: translateX(5px);
        }

        .social-link i {
            width: 20px;
            margin-right: 12px;
            font-size: 1.1rem;
        }

        .social-link span {
            font-size: 0.9rem;
        }

        /* App Download */
        .app-download {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-top: 15px;
        }

        .app-btn {
            display: inline-block;
            transition: all 0.3s ease;
        }

        .app-btn:hover {
            transform: scale(1.05);
        }

        .app-logo {
            max-width: 140px;
            height: auto;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }

        /* Footer Divider */
        .footer-divider {
            border: none;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            margin: 40px 0 20px;
        }

        /* Copyright */
        .copyright-text {
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.9rem;
            margin: 0;
        }

        /* Dark Mode Footer */
        body.dark .footer {
            background: linear-gradient(135deg, #0a0a0a 0%, #1a1a1a 100%);
        }

        body.dark .cert-badge {
            background: rgba(255, 255, 255, 0.05);
            border-color: rgba(255, 255, 255, 0.1);
        }

        body.dark .btn-partnership {
            background: rgba(255, 255, 255, 0.05);
            border-color: rgba(255, 255, 255, 0.1);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .footer {
                padding: 40px 0 20px;
            }
.footer-brand .brand-logo h2 {
                font-size: 1.5rem;
            }

            .payment-grid {
                grid-template-columns: repeat(4, 1fr);
                max-width: 100%;
            }

            .app-download {
                align-items: center;
            }

            .social-links {
                align-items: center;
            }
        }

        @media (max-width: 576px) {
            .cert-badge {
                padding: 6px 8px;
            }

            .cert-badge span {
                font-size: 0.6rem;
            }

            .payment-grid {
                grid-template-columns: repeat(3, 1fr);
            }

            .app-logo {
                max-width: 120px;
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

        /* Dark mode support for navigation */
        body.dark .navbar {
            background: rgba(15, 23, 42, 0.95) !important;
            border-bottom-color: rgba(56, 189, 248, 0.2);
        }

        body.dark .navbar.scrolled {
            background: rgba(15, 23, 42, 0.98) !important;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
}

        body.dark .navbar-light .navbar-nav .nav-link {
            color: #E2E8F0;
        }

        body.dark .navbar-light .navbar-nav .nav-link:hover {
            color: #38BDF8;
            background: rgba(56, 189, 248, 0.1);
        }

        body.dark .navbar-light .navbar-nav .nav-link.active {
            color: #38BDF8;
            background: rgba(56, 189, 248, 0.15);
        }

        body.dark .navbar-nav .dropdown-toggle:hover {
            background: rgba(56, 189, 248, 0.1);
            border-color: rgba(56, 189, 248, 0.2);
        }

        body.dark .navbar-nav {
            background: rgba(15, 23, 42, 0.98);
        }

        body.dark #themeToggle {
            border-color: #38BDF8;
            color: #38BDF8;
        }

        body.dark #themeToggle:hover {
            background: #38BDF8;
            color: #0F172A;
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


        /* ===== FIX   Lỗi tràn viền */
        html,
        body {
            max-width: 100%;
            overflow-x: hidden;
            /* chống nở ngang toàn trang */
        }

        /* Desktop */
        @media (min-width: 992px) {

            /* ĐỪNG ép navbar-nav nowrap toàn cục nữa */
            .navbar .navbar-nav {
                flex-wrap: wrap !important;
                /* override cái nowrap cũ */
            }

            /* Navbar collapse là flex 1 hàng */
            .navbar .navbar-collapse {
                display: flex !important;
                align-items: center !important;
                flex-wrap: nowrap !important;
                gap: 10px;
                min-width: 0;
                /* cực quan trọng để không nở */
            }

            /* Menu bên trái: nếu dài thì cuộn ngang trong chính nó, KHÔNG làm body nở */
            .navbar .navbar-collapse>.navbar-nav.me-auto {
                flex: 1 1 auto !important;
                min-width: 0 !important;
                flex-wrap: nowrap !important;
                overflow-x: auto !important;
                overflow-y: hidden !important;
                white-space: nowrap !important;
                -webkit-overflow-scrolling: touch;
                scrollbar-width: thin;
            }

            .navbar .navbar-collapse>.navbar-nav.me-auto .nav-item {
                flex: 0 0 auto;
            }

            /* Cụm bên phải (theme/chuông/user) không được đẩy nở */
            .navbar .navbar-collapse>.navbar-nav.align-items-center {
                flex: 0 0 auto !important;
white-space: nowrap !important;
            }

            /* Cắt tên user để không phá layout */
            .navbar #navbarDropdown {
                max-width: 160px;
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
            }
        }

        /* Mobile: để bootstrap tự collapse */
        @media (max-width: 991.98px) {
            .navbar .navbar-nav {
                flex-wrap: wrap !important;
                white-space: normal !important;
            }
        }


        /* ===== FORCE BACKGROUND (must be LAST) ===== */
        html,
        body {
            height: 100%;
            background:
                radial-gradient(900px 520px at 15% 10%, rgba(14, 165, 233, .16), transparent 60%),
                radial-gradient(900px 520px at 85% 22%, rgba(56, 189, 248, .12), transparent 62%),
                radial-gradient(900px 650px at 50% 95%, rgba(6, 182, 212, .10), transparent 60%),
                linear-gradient(180deg, #F8FAFC 0%, #F1F5F9 60%, #FFFFFF 100%) !important;
        }

        body {
            position: relative;
            overflow-x: hidden;
        }

        /* lớp hạt mịn sang hơn */
        body::before {
            content: "";
            position: fixed;
            inset: 0;
            pointer-events: none;
            z-index: 0;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='160' height='160'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='.8' numOctaves='2' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='160' height='160' filter='url(%23n)' opacity='.14'/%3E%3C/svg%3E");
            opacity: .22;
            mix-blend-mode: overlay;
        }

        /* đảm bảo nội dung nằm trên lớp nền */
        body>* {
            position: relative;
            z-index: 1;
        }

        /* dark mode */
        body.dark,
        body.dark html {
            background:
                radial-gradient(900px 520px at 15% 12%, rgba(56, 189, 248, .16), transparent 62%),
                radial-gradient(900px 520px at 85% 20%, rgba(14, 165, 233, .14), transparent 60%),
                linear-gradient(180deg, #0B1220 0%, #0F172A 60%, #0B1220 100%) !important;
        }

        body.dark::before {
            opacity: .18;
            mix-blend-mode: soft-light;
        }





    </style>


    @yield('styles')
</head>

<body>
    {{-- ===== GLOBAL WRAP (fix overflow + layout) ===== --}}
    <div class="app-shell">

        {{-- ================= NAVBAR ================= --}}
        <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
            <div class="container">
                <a class="navbar-brand" href="{{ route('welcome') }}">
                    <i class="fas fa-plane"></i> Tour365
                </a>
<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarNav">
                    {{-- Left --}}
                    <ul class="navbar-nav me-auto nav-wrap">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('welcome') ? 'active' : '' }}"
                                href="{{ route('welcome') }}">
                                <i class="fas fa-home"></i> Trang chủ
                            </a>
                        </li>

                        @if (!Auth::check() || !Auth::user()->isGuide())
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('tours.*') ? 'active' : '' }}"
                                    href="{{ route('tours.index') }}">
                                    <i class="fas fa-map-marked-alt"></i> Tours
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('wishlists.*') ? 'active' : '' }}"
                                    href="{{ route('wishlists.index') }}" title="Yêu thích">
                                    <i class="fas fa-heart text-danger"></i>
                                    <span class="d-none d-lg-inline">Yêu thích</span>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}"
                                    href="{{ route('about') }}">
                                    <i class="fas fa-info-circle"></i>
                                    <span class="d-none d-lg-inline">Về chúng tôi</span>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('blog.*') ? 'active' : '' }}"
                                    href="{{ route('blog.index') }}">
                                    <i class="fas fa-newspaper"></i> Blog
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('promotions.*') ? 'active' : '' }}"
                                    href="{{ route('promotions.index') }}">
                                    <i class="fas fa-gift"></i>
                                    <span class="d-none d-lg-inline">Ưu đãi</span>
                                </a>
</li>

                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}"
                                    href="{{ route('contact') }}">
                                    <i class="fas fa-envelope"></i>
                                    <span class="d-none d-lg-inline">Liên hệ</span>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('payment-policy') ? 'active' : '' }}"
                                    href="{{ route('payment-policy') }}">
                                    <i class="fas fa-file-contract"></i>
                                    <span class="d-none d-lg-inline">Điều khoản</span>
                                </a>
                            </li>
                        @endif

                        @auth
                            @if (Auth::user()->isGuide())
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('guide.*') ? 'active' : '' }}"
                                        href="{{ route('guide.dashboard') }}">
                                        <i class="fas fa-user-tie"></i>
                                        <span class="d-none d-lg-inline">Dashboard HDV</span>
                                    </a>
                                </li>
                            @else
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('group-tour.create') ? 'active' : '' }}"
                                        href="{{ route('group-tour.create') }}">
                                        <i class="fas fa-users"></i> Đặt tour đoàn
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('bookings.*') ? 'active' : '' }}"
                                        href="{{ route('bookings.index') }}">
                                        <i class="fas fa-calendar-check"></i> Đặt tour
                                    </a>
                                </li>
                            @endif
                        @endauth
                    </ul>

                    {{-- Right --}}
                    <ul class="navbar-nav align-items-center ms-auto">
                        <li class="nav-item me-2">
                            <button id="themeToggle" class="btn btn-outline-primary btn-sm rounded-pill px-3"
                                type="button" title="Chuyển giao diện">
                                <i class="fas fa-moon"></i>
                            </button>
                        </li>

                        @auth
<li class="nav-item me-2">
                                @include('components.notification-bell')
                            </li>
                        @endauth

                        @guest
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('login') ? 'active' : '' }}"
                                    href="{{ route('login') }}">
                                    <i class="fas fa-sign-in-alt"></i> Đăng nhập
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('register') ? 'active' : '' }}"
                                    href="{{ route('register') }}">
                                    <i class="fas fa-user-plus"></i> Đăng ký
                                </a>
                            </li>
                        @else
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle user-pill" href="#" id="navbarDropdown"
                                    role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-user"></i> {{ Auth::user()->name }}
                                </a>

                                <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                    @if (Auth::user()->isAdmin())
                                        <li>
                                            <a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                                <i class="fas fa-tachometer-alt me-2"></i> Admin Dashboard
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                    @elseif (Auth::user()->isStaff())
                                        <li>
                                            <a class="dropdown-item" href="{{ route('staff.dashboard') }}">
                                                <i class="fas fa-tachometer-alt me-2"></i> Staff Dashboard
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                    @elseif (Auth::user()->isGuide())
                                        <li>
                                            <a class="dropdown-item" href="{{ route('guide.dashboard') }}">
                                                <i class="fas fa-user-tie me-2"></i> Dashboard HDV
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
@endif

                                    <li>
                                        <a class="dropdown-item" href="{{ route('profile.index') }}">
                                            <i class="fas fa-user me-2"></i> Thông tin cá nhân
                                        </a>
                                    </li>

                                    @if (!Auth::user()->isGuide())
                                        <li>
                                            <a class="dropdown-item" href="{{ route('bookings.index') }}">
                                                <i class="fas fa-calendar-check me-2"></i> Đặt tour của tôi
                                            </a>
                                        </li>
                                    @endif

                                    <li><hr class="dropdown-divider"></li>

                                    <li>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="dropdown-item">
                                                <i class="fas fa-sign-out-alt me-2"></i> Đăng xuất
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

        {{-- ================= MAIN ================= --}}
        <main class="main-wrap">
            @yield('content')
        </main>

        {{-- ================= FOOTER (BOXED) ================= --}}
        <footer class="footer">
            <div class="container">
                <div class="footer-inner">
                    <div class="row mb-5">
                        <div class="col-lg-4">
                            <div class="footer-brand">
                                <div class="brand-logo mb-4">
                                    <h2 class="text-white fw-bold mb-0">
                                        <i class="fas fa-plane text-primary me-2"></i>Tour365
                                    </h2>
                                </div>

                                <div class="certifications mb-4">
                                    <div class="row g-2">
                                        <div class="col-6 col-md-4">
                                            <div class="cert-badge">
                                                <i class="fas fa-certificate text-warning"></i>
                                                <span>IATA</span>
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-4">
<div class="cert-badge">
                                                <i class="fas fa-shield-alt text-success"></i>
                                                <span>ISO 27001</span>
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-4">
                                            <div class="cert-badge">
                                                <i class="fas fa-check-circle text-danger"></i>
                                                <span>ĐĂNG KÝ BCT</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <button class="btn btn-partnership" type="button">
                                    <i class="fas fa-handshake me-2"></i>Hợp tác với Tour365
                                </button>
                            </div>
                        </div>

                        <div class="col-lg-8">
                            <div class="row">
                                <div class="col-md-4 mb-4">
                                    <h6 class="footer-heading">Về Tour365</h6>
                                    <ul class="footer-links">
                                        <li><a href="#">Cách đặt tour</a></li>
                                        <li><a href="{{ route('contact') }}">Liên hệ chúng tôi</a></li>
                                        <li><a href="#">Trợ giúp</a></li>
                                        <li><a href="#">Tuyển dụng</a></li>
                                        <li><a href="{{ route('about') }}">Về chúng tôi</a></li>
                                    </ul>
                                </div>

                                <div class="col-md-4 mb-4">
                                    <h6 class="footer-heading">Sản phẩm</h6>
                                    <ul class="footer-links">
                                        <li><a href="{{ route('tours.index') }}">Tours du lịch</a></li>
                                        <li><a href="#">Vé máy bay</a></li>
                                        <li><a href="#">Vé xe khách</a></li>
                                        <li><a href="#">Đưa đón sân bay</a></li>
                                        <li><a href="#">Cho thuê xe</a></li>
                                        <li><a href="#">Hoạt động & Vui chơi</a></li>
                                        <li><a href="#">Du thuyền</a></li>
                                        <li><a href="#">Khách sạn</a></li>
                                    </ul>
                                </div>

                                <div class="col-md-4 mb-4">
                                    <h6 class="footer-heading">Khác</h6>
<ul class="footer-links">
                                        <li><a href="#">Tour365 Affiliate</a></li>
                                        <li><a href="{{ route('blog.index') }}">Tour365 Blog</a></li>
                                        <li><a href="#">Chính sách bảo mật</a></li>
                                        <li><a href="{{ route('payment-policy') }}">Chính sách & Điều khoản Tour Đoàn</a></li>
                                        <li><a href="#">Đăng ký đối tác</a></li>
                                        <li><a href="#">Khu vực báo chí</a></li>
                                        <li><a href="#">Quy chế hoạt động</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-4 mb-4">
                            <h6 class="footer-heading">Đối tác thanh toán</h6>
                            <div class="payment-partners">
                                <div class="payment-grid">
                                    @php
                                        $payments = [
                                            ['MC', 'Mastercard', 'EB001B'],
                                            ['VISA', 'Visa', '1A1F71'],
                                            ['JCB', 'JCB', '003399'],
                                            ['AMEX', 'American Express', '006FCF'],
                                            ['MoMo', 'MoMo', '00A651'],
                                            ['VNPay', 'VNPay', 'FF6B35'],
                                            ['TCB', 'Techcombank', '1E88E5'],
                                            ['VPB', 'VPBank', '1A365D'],
                                            ['VIB', 'VIB', '00A651'],
                                            ['VCB', 'Vietcombank', '1E88E5'],
                                            ['OnePay', 'OnePay', 'FF6B35'],
                                            ['MB', 'MB Bank', '1A365D'],
                                        ];
                                    @endphp

                                    @foreach ($payments as [$text, $alt, $color])
                                        <div class="payment-item">
                                            <img src="{{ placeholder_url('60x40', $color, 'ffffff', $text) }}"
                                                alt="{{ $alt }}" class="payment-logo">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 mb-4">
                            <h6 class="footer-heading">Theo dõi chúng tôi</h6>
                            <div class="social-links">
<a href="#" class="social-link"><i class="fab fa-facebook-f"></i><span>Facebook</span></a>
                                <a href="#" class="social-link"><i class="fab fa-instagram"></i><span>Instagram</span></a>
                                <a href="#" class="social-link"><i class="fab fa-tiktok"></i><span>TikTok</span></a>
                                <a href="#" class="social-link"><i class="fab fa-youtube"></i><span>YouTube</span></a>
                                <a href="#" class="social-link"><i class="fab fa-telegram"></i><span>Telegram</span></a>
                            </div>
                        </div>

                        <div class="col-lg-5 mb-4">
                            <h6 class="footer-heading">Tải ứng dụng Tour365</h6>
                            <div class="app-download">
                                <a href="#" class="app-btn">
                                    <img src="{{ placeholder_url('160x48','000000','ffffff','Google Play') }}"
                                        alt="Google Play" class="app-logo">
                                </a>
                                <a href="#" class="app-btn">
                                    <img src="{{ placeholder_url('160x48','000000','ffffff','App Store') }}"
                                        alt="App Store" class="app-logo">
                                </a>
                            </div>
                        </div>
                    </div>

                    <hr class="footer-divider">

                    <div class="row">
                        <div class="col-12 text-center">
                            <p class="copyright-text mb-0">
                                &copy; {{ date('Y') }} Tour365. Tất cả quyền được bảo lưu.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </footer>

        {{-- Back to top --}}
        <button id="backToTop" class="btn btn-primary" type="button" aria-label="Back to top">
            <i class="fas fa-arrow-up"></i>
        </button>
    </div>

    {{-- ================== STYLES (PUT IN <head> ideally) ================== --}}
    <style>
        :root{
            --brand-primary: #0EA5E9;
            --brand-primary-dark: #0284C7;
            --brand-accent: #06B6D4;
            --text-muted: #6B7280;
            --shadow-sm: 0 4px 12px rgba(0,0,0,.06);
            --shadow-md: 0 18px 50px rgba(0,0,0,.12);
            --radius: 16px;
        }

        /* ==== anti overflow ==== */
        html, body{
            width: 100%;
            max-width: 100%;
            overflow-x: hidden;
        }
        img, video, svg, canvas{ max-width:100%; height:auto; }
        .app-shell{ min-height: 100vh; }

        /* ==== navbar ==== */
        .navbar{
            background: rgba(255,255,255,.92) !important;
            backdrop-filter: blur(14px);
border-bottom: 1px solid rgba(14,165,233,.12);
            transition: all .25s ease;
        }
        .navbar.scrolled{
            background: rgba(255,255,255,.98) !important;
            box-shadow: 0 10px 30px rgba(0,0,0,.08);
        }
        .navbar-brand{
            font-weight: 900;
            letter-spacing: .3px;
            color: var(--brand-primary) !important;
            text-decoration: none;
        }
        .navbar-brand:hover{ color: var(--brand-primary-dark) !important; }
        .navbar-brand i{ margin-right: 8px; }

        .navbar-nav .nav-link{
            font-weight: 650;
            color:#334155;
            border-radius: 12px;
            padding: 10px 12px !important;
            transition: all .22s ease;
            white-space: nowrap;
        }
        .navbar-nav .nav-link:hover{
            color: var(--brand-primary);
            background: rgba(14,165,233,.08);
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(14,165,233,.18);
        }
        .navbar-nav .nav-link.active{
            color: var(--brand-primary);
            background: rgba(14,165,233,.10);
            font-weight: 800;
        }

        .user-pill{
            border: 1px solid rgba(14,165,233,.18);
            border-radius: 999px;
            padding: 8px 14px !important;
        }

        /* Desktop: allow wrap to avoid overflow */
        @media (min-width: 992px){
            .nav-wrap{ flex-wrap: wrap; row-gap: 6px; }
        }

        /* Mobile collapse style */
        @media (max-width: 991.98px){
            .navbar-collapse{
                margin-top: 12px;
                padding: 12px;
                border-radius: 16px;
                background: rgba(255,255,255,.98);
                box-shadow: 0 14px 40px rgba(0,0,0,.10);
            }
            .navbar-nav .nav-link{ white-space: normal; }
        }

        /* ==== main ==== */
        .main-wrap{ padding-bottom: 28px; }

      

        .cert-badge{
            background: rgba(255,255,255,.08);
            border: 1px solid rgba(255,255,255,.14);
            border-radius: 10px;
            padding: 10px 10px;
            text-align:center;
            transition: all .2s ease;
        }
        .cert-badge:hover{
            transform: translateY(-2px);
            background: rgba(255,255,255,.12);
        }
        .cert-badge span{
            font-size: .68rem;
            font-weight: 800;
            letter-spacing: .4px;
            display:block;
            margin-top: 4px;
        }

        .btn-partnership{
            background: rgba(255,255,255,.10);
            border: 1px solid rgba(255,255,255,.18);
            color:#fff;
            padding: 12px 18px;
            border-radius: 12px;
            font-weight: 750;
            transition: all .2s ease;
        }
        .btn-partnership:hover{
            background: rgba(14,165,233,.18);
            border-color: rgba(14,165,233,.35);
            transform: translateY(-2px);
        }

        .payment-grid{
            display:grid;
            grid-template-columns: repeat(6, minmax(0, 1fr));
            gap: 10px;
        }
        .payment-item{
            background:#fff;
            border-radius: 10px;
            padding: 8px;
            text-align:center;
            box-shadow: 0 6px 18px rgba(0,0,0,.20);
            transition: transform .2s ease;
        }
        .payment-item:hover{ transform: translateY(-2px); }
        .payment-logo{ width:100%; height:auto; border-radius: 6px; }

        @media (max-width: 992px){
            .payment-grid{ grid-template-columns: repeat(4, minmax(0,1fr)); }
            .footer-inner{ padding: 44px 22px 24px; border-radius: 18px; }
        }
        @media (max-width: 576px){
            .payment-grid{ grid-template-columns: repeat(3, minmax(0,1fr)); }
        }

        .social-links{ display:flex; flex-direction:column; gap: 10px; }
        .social-link{
            display:flex; align-items:center;
            color: rgba(255,255,255,.82);
            text-decoration:none;
            transition: all .2s ease;
        }
        .social-link i{ width: 22px; margin-right: 10px; }
        .social-link:hover{ color:#0EA5E9; transform: translateX(4px); }

        .app-download{ display:flex; flex-direction:column; gap: 12px; }
        .app-logo{ border-radius: 10px; box-shadow: 0 8px 24px rgba(0,0,0,.25); }


        .copyright-text{
color: rgba(255,255,255,.65);
            font-size: .92rem;
        }

        /* ==== back to top ==== */
        #backToTop{
            position: fixed;
            right: 18px;
            bottom: 18px;
            width: 46px;
            height: 46px;
            border-radius: 999px;
            display: none;
            align-items: center;
            justify-content: center;
            box-shadow: 0 14px 30px rgba(0,0,0,.18);
            z-index: 999;
        }

        /* ==== dark mode (optional) ==== */
        body.dark{
            background: #0B1220;
            color:#E5E7EB;
        }
        body.dark .navbar{
            background: rgba(15,23,42,.92) !important;
            border-bottom-color: rgba(56,189,248,.18);
        }
        body.dark .navbar.scrolled{
            background: rgba(15,23,42,.98) !important;
            box-shadow: 0 12px 40px rgba(0,0,0,.35);
        }
        body.dark .navbar-nav .nav-link{ color:#E2E8F0; }
        body.dark .navbar-nav .nav-link:hover{
            color:#38BDF8;
            background: rgba(56,189,248,.10);
        }
        body.dark .navbar-collapse{ background: rgba(15,23,42,.98); }
        body.dark .footer-inner{
            background: linear-gradient(135deg, #0b0b0b 0%, #151515 100%);
        }
    </style>

    {{-- ================== SCRIPTS ================== --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Back to top
        const backBtn = document.getElementById('backToTop');
        window.addEventListener('scroll', () => {
            backBtn.style.display = window.scrollY > 320 ? 'inline-flex' : 'none';
        });
        backBtn.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));

        // Theme toggle
        const themeToggle = document.getElementById('themeToggle');
        if (themeToggle) {
            const saved = localStorage.getItem('theme');
            if (saved === 'dark') document.body.classList.add('dark');
            themeToggle.innerHTML = document.body.classList.contains('dark')
                ? '<i class="fas fa-sun"></i>'
                : '<i class="fas fa-moon"></i>';

            themeToggle.addEventListener('click', () => {
                document.body.classList.toggle('dark');
                const isDark = document.body.classList.contains('dark');
                localStorage.setItem('theme', isDark ? 'dark' : 'light');
                themeToggle.innerHTML = isDark ? '<i class="fas fa-sun"></i>' : '<i class="fas fa-moon"></i>';
            });
        }

        // Navbar scroll effect
        window.addEventListener('scroll', () => {
            const navbar = document.querySelector('.navbar');
            if (!navbar) return;
            navbar.classList.toggle('scrolled', window.scrollY > 40);
        });

        // Mobile: close menu when click link
document.querySelectorAll('.navbar-nav .nav-link').forEach(link => {
            link.addEventListener('click', () => {
                const nav = document.querySelector('#navbarNav');
                if (nav && nav.classList.contains('show')) {
                    bootstrap.Collapse.getOrCreateInstance(nav).hide();
                }
            });
        });
    </script>

    {{-- IMPORTANT: dùng stack để page push script không bị đè --}}
    @stack('scripts')

    @yield('scripts')
</body>

</html>
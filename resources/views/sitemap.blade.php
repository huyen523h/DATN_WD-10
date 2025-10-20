@extends('layouts.app')

@section('title', 'Sitemap - Tour365')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <h1 class="display-4 fw-bold mb-4">Sitemap</h1>
            <p class="lead text-muted mb-5">Tìm hiểu cấu trúc website Tour365</p>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-home"></i> Trang chính</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <a href="{{ route('tours.index') }}" class="text-decoration-none">
                                <i class="fas fa-arrow-right text-primary me-2"></i>Trang chủ
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="{{ route('tours.index') }}" class="text-decoration-none">
                                <i class="fas fa-arrow-right text-primary me-2"></i>Tours
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="{{ route('about') }}" class="text-decoration-none">
                                <i class="fas fa-arrow-right text-primary me-2"></i>Về chúng tôi
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="{{ route('contact') }}" class="text-decoration-none">
                                <i class="fas fa-arrow-right text-primary me-2"></i>Liên hệ
                            </a>
                        </li>
                        <li class="mb-2">
                            <span class="text-muted">
                                <i class="fas fa-search text-primary me-2"></i>Tìm kiếm (trên thanh navigation)
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-user"></i> Tài khoản</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <a href="{{ route('login') }}" class="text-decoration-none">
                                <i class="fas fa-arrow-right text-primary me-2"></i>Đăng nhập
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="{{ route('register') }}" class="text-decoration-none">
                                <i class="fas fa-arrow-right text-primary me-2"></i>Đăng ký
                            </a>
                        </li>
                        @auth
                        <li class="mb-2">
                            <a href="{{ route('profile.index') }}" class="text-decoration-none">
                                <i class="fas fa-arrow-right text-primary me-2"></i>Thông tin cá nhân
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="{{ route('bookings.index') }}" class="text-decoration-none">
                                <i class="fas fa-arrow-right text-primary me-2"></i>Đặt tour của tôi
                            </a>
                        </li>
                        @endauth
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-cog"></i> Quản trị</h5>
                </div>
                <div class="card-body">
                    @auth
                        @if(Auth::user()->isAdmin())
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <a href="{{ route('admin.dashboard') }}" class="text-decoration-none">
                                    <i class="fas fa-arrow-right text-primary me-2"></i>Dashboard
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="{{ route('admin.tours.index') }}" class="text-decoration-none">
                                    <i class="fas fa-arrow-right text-primary me-2"></i>Quản lý Tours
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="{{ route('admin.bookings') }}" class="text-decoration-none">
                                    <i class="fas fa-arrow-right text-primary me-2"></i>Quản lý Đặt tour
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="{{ route('admin.customers') }}" class="text-decoration-none">
                                    <i class="fas fa-arrow-right text-primary me-2"></i>Quản lý Khách hàng
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="{{ route('admin.settings') }}" class="text-decoration-none">
                                    <i class="fas fa-arrow-right text-primary me-2"></i>Cài đặt
                                </a>
                            </li>
                        </ul>
                        @else
                        <p class="text-muted">Chỉ dành cho quản trị viên</p>
                        @endif
                    @else
                        <p class="text-muted">Vui lòng đăng nhập để xem</p>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

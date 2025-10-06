@extends('layouts.app')

@section('title', 'Đăng nhập - Tour365')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-4">
            <div class="card shadow border-0">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <h2 class="fw-bold" style="color:#0EA5E9;">
                            <i class="fas fa-plane"></i> Tour365
                        </h2>
                        <p class="text-muted">Đăng nhập để tiếp tục</p>
                    </div>

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-envelope"></i>
                                </span>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email') }}" 
                                       placeholder="Nhập email của bạn" required autofocus>
                            </div>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Mật khẩu</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-lock"></i>
                                </span>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password" placeholder="Nhập mật khẩu" required>
                            </div>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label" for="remember">
                                Ghi nhớ đăng nhập
                            </label>
                        </div>

                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-sign-in-alt"></i> Đăng nhập
                            </button>
                        </div>
                    </form>

                    <div class="text-center">
                        <p class="text-muted">
                            Chưa có tài khoản? 
                            <a href="{{ route('register') }}" class="text-decoration-none">
                                Đăng ký ngay
                            </a>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Demo Account Info -->
            <div class="card mt-4 border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="card-title">
                        <i class="fas fa-info-circle text-info"></i> Tài khoản demo
                    </h6>
                    <div class="row">
                        <div class="col-6">
                            <small class="text-muted">Admin:</small><br>
                            <code>admin@tour365.vn</code><br>
                            <code>password</code>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">Customer:</small><br>
                            <code>customer@tour365.vn</code><br>
                            <code>password</code>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
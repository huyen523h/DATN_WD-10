<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập Nhân viên - Tour365</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #0EA5E9;
            --primary-dark: #0284C7;
            --secondary-color: #64748b;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --dark-color: #1e293b;
            --light-color: #f8fafc;
            --accent-color: #06B6D4;
        }

        body {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--accent-color) 50%, var(--primary-dark) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            position: relative;
            overflow: hidden;
        }

        /* Animated background elements */
        body::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 1px, transparent 1px);
            background-size: 50px 50px;
            animation: float 20s infinite linear;
            z-index: 0;
        }

        @keyframes float {
            0% { transform: translate(0, 0) rotate(0deg); }
            100% { transform: translate(-50px, -50px) rotate(360deg); }
        }

        .login-container {
            background: white;
            border-radius: 24px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.15), 0 0 0 1px rgba(255,255,255,0.1);
            overflow: hidden;
            max-width: 420px;
            width: 100%;
            margin: 1rem;
            position: relative;
            z-index: 1;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.2);
        }

        .login-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--accent-color) 100%);
            color: white;
            padding: 2.5rem 2rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .login-header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 1px, transparent 1px);
            background-size: 30px 30px;
            animation: float 15s infinite linear;
        }

        .login-header h3 {
            margin: 0;
            font-weight: 700;
            font-size: 1.5rem;
            position: relative;
            z-index: 1;
        }

        .login-header p {
            margin: 0.75rem 0 0 0;
            opacity: 0.9;
            font-size: 0.95rem;
            position: relative;
            z-index: 1;
        }

        .login-header .icon-wrapper {
            position: relative;
            z-index: 1;
            margin-bottom: 1rem;
        }

        .login-header .icon-wrapper i {
            font-size: 3.5rem;
            opacity: 0.9;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 0.9; }
            50% { transform: scale(1.05); opacity: 1; }
        }

        .login-body {
            padding: 2.5rem 2rem;
            position: relative;
        }

        .login-body form {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .form-floating {
            margin-bottom: 1.5rem;
        }

        .form-floating .form-control {
            border: 2px solid #e2e8f0;
            border-radius: 16px;
            padding: 1.25rem 0.75rem;
            transition: all 0.3s ease;
            background: #f8fafc;
            font-size: 0.95rem;
        }

        .form-floating .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(14, 165, 233, 0.25);
            background: white;
            transform: translateY(-2px);
        }

        .form-floating label {
            color: var(--secondary-color);
            font-weight: 500;
        }

        .input-group {
            margin-bottom: 1.5rem;
            display: flex;
            align-items: stretch;
        }

        .input-group-text {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            border: 2px solid #e2e8f0;
            border-right: none;
            border-radius: 16px 0 0 16px;
            color: var(--primary-color);
            font-size: 1rem;
            width: 50px;
            min-width: 50px;
            max-width: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            flex-shrink: 0;
        }

        .input-group .form-control {
            border-left: none;
            border-radius: 0 16px 16px 0;
            border: 2px solid #e2e8f0;
            border-left: none;
            padding: 1.25rem 0.75rem;
            background: #f8fafc;
            font-size: 0.95rem;
            flex: 1;
            min-height: 60px;
            transition: all 0.3s ease;
        }

        .input-group:focus-within .input-group-text {
            border-color: var(--primary-color);
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--accent-color) 100%);
            color: white;
            transform: scale(1.02);
        }

        .input-group:focus-within .form-control {
            border-color: var(--primary-color);
            background: white;
            transform: translateY(-2px);
            box-shadow: 0 0 0 0.2rem rgba(14, 165, 233, 0.25);
        }

        /* Ensure equal height for all input groups */
        .input-group {
            height: 60px;
            width: 100%;
        }

        .input-group .form-control,
        .input-group .input-group-text {
            height: 100%;
        }

        /* Make sure both input groups are exactly the same size */
        .input-group:first-of-type,
        .input-group:last-of-type {
            width: 100%;
            height: 60px;
        }

        /* Equal width for all form elements */
        .login-body form {
            width: 100%;
        }

        .login-body form .input-group {
            width: 100%;
            margin-bottom: 1.5rem;
        }

        /* Consistent spacing */
        .input-group + .input-group {
            margin-top: 0;
        }

        .btn-login {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--accent-color) 100%);
            border: none;
            color: white;
            padding: 1rem 2rem;
            border-radius: 16px;
            font-weight: 600;
            width: 100%;
            transition: all 0.3s ease;
            font-size: 1rem;
            position: relative;
            overflow: hidden;
        }

        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .btn-login:hover::before {
            left: 100%;
        }

        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 25px rgba(14, 165, 233, 0.4);
            color: white;
        }

        .btn-login:active {
            transform: translateY(-1px);
        }

        .login-footer {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            padding: 1.5rem 2rem;
            text-align: center;
            border-top: 1px solid #e2e8f0;
            position: relative;
        }

        .login-footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 4px;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--accent-color) 100%);
            border-radius: 2px;
        }

        .login-footer a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .login-footer a:hover {
            text-decoration: underline;
            color: var(--primary-dark);
        }

        .alert {
            border-radius: 16px;
            border: none;
            margin-bottom: 1.5rem;
            padding: 1rem 1.25rem;
            font-weight: 500;
        }

        .alert-danger {
            background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
            color: #dc2626;
            border-left: 4px solid #dc2626;
        }

        .alert-success {
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            color: #16a34a;
            border-left: 4px solid #16a34a;
        }

        /* Additional styling for perfect alignment */
        .input-group .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(14, 165, 233, 0.25);
        }

        .input-group .form-control::placeholder {
            color: #94a3b8;
            font-weight: 400;
        }

        /* Ensure perfect alignment */
        .input-group {
            box-sizing: border-box;
        }

        .input-group * {
            box-sizing: border-box;
        }

        /* Responsive design */
        @media (max-width: 480px) {
            .login-container {
                margin: 0.5rem;
                max-width: 100%;
            }
            
            .login-header {
                padding: 2rem 1.5rem;
            }
            
            .login-body {
                padding: 2rem 1.5rem;
            }

            .input-group {
                height: 55px;
            }

            .input-group-text {
                width: 45px;
                min-width: 45px;
                max-width: 45px;
            }
        }

        .input-group-text {
            background: #f8fafc;
            border: 2px solid #e2e8f0;
            border-right: none;
            border-radius: 12px 0 0 12px;
        }

        .input-group .form-control {
            border-left: none;
            border-radius: 0 12px 12px 0;
        }

        .input-group:focus-within .input-group-text {
            border-color: var(--primary-color);
        }

        .input-group:focus-within .form-control {
            border-color: var(--primary-color);
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <div class="icon-wrapper">
                <i class="fas fa-user-tie"></i>
            </div>
            <h3>Đăng nhập Nhân viên</h3>
            <p>Hệ thống quản lý Tour365</p>
        </div>

        <div class="login-body">
            @if($errors->any())
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    @foreach($errors->all() as $error)
                        {{ $error }}
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('employee.login') }}">
                @csrf
                
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fas fa-envelope"></i>
                    </span>
                    <input type="email" 
                           class="form-control @error('email') is-invalid @enderror" 
                           id="email" 
                           name="email" 
                           value="{{ old('email') }}" 
                           placeholder="Nhập email của bạn"
                           required>
                </div>

                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fas fa-lock"></i>
                    </span>
                    <input type="password" 
                           class="form-control @error('password') is-invalid @enderror" 
                           id="password" 
                           name="password" 
                           placeholder="Nhập mật khẩu"
                           required>
                </div>

                <button type="submit" class="btn btn-login">
                    <i class="fas fa-sign-in-alt me-2"></i>
                    Đăng nhập
                </button>
            </form>
        </div>

        <div class="login-footer">
            <p class="mb-0">
                <i class="fas fa-info-circle me-1"></i>
                Chỉ dành cho nhân viên Tour365
            </p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

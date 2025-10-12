<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng xuất thành công - Tour365</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 20px;
        }

        .logout-container {
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            text-align: center;
            max-width: 500px;
            width: 100%;
            position: relative;
            overflow: hidden;
        }

        .logout-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #667eea, #764ba2);
        }

        .success-icon {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, #10b981, #059669);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 30px;
            animation: pulse 2s infinite;
        }

        .success-icon i {
            font-size: 50px;
            color: white;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        .logout-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 20px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .logout-message {
            font-size: 1.1rem;
            color: #6b7280;
            margin-bottom: 10px;
            line-height: 1.6;
        }

        .redirect-notice {
            font-size: 1rem;
            color: #10b981;
            font-weight: 600;
            margin: 30px 0;
            padding: 15px;
            background: rgba(16, 185, 129, 0.1);
            border-radius: 10px;
            border-left: 4px solid #10b981;
        }

        .action-buttons {
            display: flex;
            flex-direction: column;
            gap: 15px;
            margin-top: 30px;
        }

        .btn-login-again {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border: none;
            color: white;
            padding: 15px 30px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1.1rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .btn-login-again:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
            color: white;
        }

        .btn-homepage {
            background: white;
            border: 2px solid #e5e7eb;
            color: #374151;
            padding: 15px 30px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1.1rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: all 0.3s ease;
        }

        .btn-homepage:hover {
            background: #f9fafb;
            border-color: #d1d5db;
            color: #374151;
            transform: translateY(-1px);
        }

        .countdown {
            font-size: 1.2rem;
            font-weight: 700;
            color: #10b981;
        }

        @media (max-width: 576px) {
            .logout-container {
                padding: 30px 20px;
                margin: 10px;
            }
            
            .logout-title {
                font-size: 2rem;
            }
            
            .success-icon {
                width: 80px;
                height: 80px;
            }
            
            .success-icon i {
                font-size: 40px;
            }
        }
    </style>
</head>
<body>
    <div class="logout-container">
        <div class="success-icon">
            <i class="fas fa-check"></i>
        </div>
        
        <h1 class="logout-title">Đăng xuất thành công!</h1>
        
        <p class="logout-message">
            Bạn đã đăng xuất khỏi hệ thống thành công.
        </p>
        <p class="logout-message">
            Cảm ơn bạn đã sử dụng dịch vụ của chúng tôi!
        </p>
        
        <div class="redirect-notice">
            <i class="fas fa-clock me-2"></i>
            Tự động chuyển hướng trong <span class="countdown" id="countdown">3</span> giây...
        </div>
        
        <div class="action-buttons">
            <a href="{{ route('employee.login') }}" class="btn-login-again">
                <i class="fas fa-sign-in-alt"></i>
                Đăng nhập lại
            </a>
            
            <a href="{{ route('tours.index') }}" class="btn-homepage">
                <i class="fas fa-home"></i>
                Về trang chủ
            </a>
        </div>
    </div>

    <script>
        // Countdown timer
        let timeLeft = 3;
        const countdownElement = document.getElementById('countdown');
        
        const timer = setInterval(() => {
            timeLeft--;
            countdownElement.textContent = timeLeft;
            
            if (timeLeft <= 0) {
                clearInterval(timer);
                // Redirect to employee login
                window.location.href = "{{ route('employee.login') }}";
            }
        }, 1000);

        // Add some interactive effects
        document.addEventListener('DOMContentLoaded', function() {
            // Animate success icon
            const successIcon = document.querySelector('.success-icon');
            successIcon.style.animation = 'pulse 2s infinite';
            
            // Add hover effects to buttons
            const buttons = document.querySelectorAll('.btn-login-again, .btn-homepage');
            buttons.forEach(button => {
                button.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-2px)';
                });
                
                button.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });
        });
    </script>
</body>
</html>

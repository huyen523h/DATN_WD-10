<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng xuất - Tour365</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', sans-serif;
        }
        
        .logout-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            padding: 3rem;
            text-align: center;
            max-width: 500px;
            width: 100%;
            margin: 2rem;
        }
        
        .logout-icon {
            font-size: 4rem;
            color: #28a745;
            margin-bottom: 1.5rem;
            animation: bounce 2s infinite;
        }
        
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translateY(0);
            }
            40% {
                transform: translateY(-10px);
            }
            60% {
                transform: translateY(-5px);
            }
        }
        
        .logout-title {
            color: #333;
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }
        
        .logout-message {
            color: #666;
            font-size: 1.1rem;
            margin-bottom: 2rem;
            line-height: 1.6;
        }
        
        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            padding: 12px 30px;
            border-radius: 25px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
            margin: 0.5rem;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
            color: white;
        }
        
        .btn-home {
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            color: #495057;
            padding: 12px 30px;
            border-radius: 25px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
            margin: 0.5rem;
        }
        
        .btn-home:hover {
            background: #e9ecef;
            color: #495057;
            transform: translateY(-2px);
        }
        
        .countdown {
            font-size: 1.2rem;
            font-weight: 600;
            color: #28a745;
            margin-top: 1rem;
        }
    </style>
</head>
<body>
    <div class="logout-container">
        <div class="logout-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        
        <h1 class="logout-title">Đăng xuất thành công!</h1>
        
        <p class="logout-message">
            Bạn đã đăng xuất khỏi hệ thống thành công.<br>
            Cảm ơn bạn đã sử dụng dịch vụ của chúng tôi!
        </p>
        
        <div class="countdown" id="countdown">
            Tự động chuyển hướng trong <span id="timer">5</span> giây...
        </div>
        
        <div class="mt-4">
            <a href="{{ route('login') }}" class="btn-login">
                <i class="fas fa-sign-in-alt me-2"></i>
                Đăng nhập lại
            </a>
            
            <a href="{{ route('tours.index') }}" class="btn-home">
                <i class="fas fa-home me-2"></i>
                Về trang chủ
            </a>
        </div>
    </div>

    <script>
        // Countdown timer
        let timeLeft = 5;
        const timerElement = document.getElementById('timer');
        
        const countdown = setInterval(() => {
            timeLeft--;
            timerElement.textContent = timeLeft;
            
            if (timeLeft <= 0) {
                clearInterval(countdown);
                window.location.href = '{{ route("login") }}';
            }
        }, 1000);
    </script>
</body>
</html>

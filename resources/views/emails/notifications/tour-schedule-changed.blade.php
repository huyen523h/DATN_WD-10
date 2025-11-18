<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lịch khởi hành đã thay đổi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }

        .content {
            background: #f9f9f9;
            padding: 30px;
            border-radius: 0 0 10px 10px;
        }

        .message {
            background: white;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
        }

        .warning-box {
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 15px;
            margin: 20px 0;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            color: #666;
            font-size: 12px;
        }

        .button {
            display: inline-block;
            padding: 12px 30px;
            background: #8b5cf6;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>🔄 Lịch khởi hành đã thay đổi</h1>
    </div>
    <div class="content">
        <p>Xin chào <strong>{{ $user->name }}</strong>,</p>

        <div class="message">
            {!! nl2br(e($notificationMessage ?? $message ?? '')) !!}
        </div>

        <div class="warning-box">
            <p><strong>Quan trọng:</strong> Vui lòng kiểm tra và xác nhận lịch khởi hành mới. Nếu bạn không thể tham gia
                vào ngày mới, vui lòng liên hệ với chúng tôi để được hỗ trợ.</p>
        </div>

        @if($relatedId)
            <div style="text-align: center;">
                <a href="{{ url('/bookings/' . $relatedId) }}" class="button">Xem chi tiết đặt tour</a>
            </div>
        @endif

        <div class="footer">
            <p>Trân trọng,<br>Đội ngũ Tour365</p>
            <p>Email này được gửi tự động, vui lòng không trả lời.</p>
        </div>
    </div>
</body>

</html>

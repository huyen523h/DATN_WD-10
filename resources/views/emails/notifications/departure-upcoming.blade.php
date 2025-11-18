<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lịch khởi hành sắp tới</title>
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
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
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
        .info-box {
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
            background: #f59e0b;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📅 Lịch khởi hành sắp tới</h1>
    </div>
    <div class="content">
        <p>Xin chào <strong>{{ $user->name }}</strong>,</p>
        
        <div class="message">
            {!! nl2br(e($notificationMessage ?? $message ?? '')) !!}
        </div>

        <div class="info-box">
            <p><strong>Những việc cần chuẩn bị:</strong></p>
            <ul>
                <li>Kiểm tra lại giấy tờ tùy thân (CMND/CCCD/Hộ chiếu)</li>
                <li>Chuẩn bị hành lý theo hướng dẫn</li>
                <li>Xác nhận lại địa điểm và thời gian tập trung</li>
                <li>Liên hệ với hướng dẫn viên nếu có thắc mắc</li>
            </ul>
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


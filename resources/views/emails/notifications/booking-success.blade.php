<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt tour thành công</title>
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
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
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
            background: #e0f2fe;
            border-left: 4px solid #0EA5E9;
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
            background: #0EA5E9;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🎉 Đặt tour thành công!</h1>
    </div>
    <div class="content">
        <p>Xin chào <strong>{{ $user->name }}</strong>,</p>
        
        <div class="message">
            {!! nl2br(e($notificationMessage ?? $message ?? '')) !!}
        </div>

        <div class="info-box">
            <p><strong>Lưu ý:</strong> Vui lòng thanh toán trong vòng 24 giờ để giữ chỗ. Sau thời gian này, đặt tour có thể bị hủy tự động.</p>
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


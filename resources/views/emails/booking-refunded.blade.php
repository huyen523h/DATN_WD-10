<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, sans-serif;
            background: #f4f6f8;
            padding: 20px;
        }

        .box {
            max-width: 600px;
            margin: auto;
            background: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.08);
        }

        .header {
            background: linear-gradient(135deg, #dc3545, #b91c1c);
            color: #fff;
            padding: 24px;
            text-align: center;
        }

        .body {
            padding: 28px;
            color: #1f2937;
        }

        .info {
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 8px;
            padding: 16px;
            margin: 20px 0;
        }

        .footer {
            background: #f9fafb;
            padding: 18px;
            text-align: center;
            font-size: 13px;
            color: #6b7280;
        }
    </style>
</head>

<body>
    <div class="box">

        <div class="header">
            <h2>BOOKING ĐÃ ĐƯỢC HUỶ</h2>
            <p>Tour365</p>
        </div>

        <div class="body">
            <p>Xin chào <strong>{{ $booking->user->name }}</strong>,</p>

            <p>
                Booking <strong>#{{ $booking->id }}</strong> cho tour
                <strong>{{ $booking->tour->title }}</strong>
                đã được <strong>huỷ thành công</strong>.
            </p>

            <div class="info">
                <p><strong>Hướng dẫn hoàn tiền:</strong></p>
                <p>
                    Quý khách vui lòng mang theo <strong>CMND/CCCD</strong> và
                    <strong>mã booking #{{ $booking->id }}</strong>
                    đến trực tiếp cơ sở của chúng tôi để nhận tiền hoàn.
                </p>
            </div>

            <p>
                Nếu cần hỗ trợ thêm, vui lòng liên hệ hotline hoặc phản hồi email này.
            </p>

            <p>
                Trân trọng,<br>
                <strong>Tour365</strong>
            </p>
        </div>

        <div class="footer">
            Hotline: 1900-xxxx • Email: support@tour365.vn
        </div>

    </div>
</body>

</html>
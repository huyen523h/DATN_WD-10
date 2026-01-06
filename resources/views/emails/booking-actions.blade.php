<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xử lý booking #{{ $booking->id }}</title>

    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f4f6f8;
            padding: 20px;
            color: #1f2937;
        }

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.08);
        }

        .email-header {
            background: linear-gradient(135deg, #0d6efd 0%, #1e40af 100%);
            color: #ffffff;
            padding: 28px;
            text-align: center;
        }

        .email-header h1 {
            margin: 0;
            font-size: 26px;
            font-weight: 700;
        }

        .email-header p {
            margin-top: 8px;
            opacity: 0.9;
        }

        .email-body {
            padding: 30px;
        }

        .greeting {
            font-size: 18px;
            margin-bottom: 16px;
        }

        .booking-box {
            background: #f8fafc;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 18px;
            margin: 20px 0;
        }

        .booking-box h3 {
            margin: 0 0 12px 0;
            font-size: 17px;
            color: #111827;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 6px;
            font-size: 14px;
        }

        .info-label {
            color: #6b7280;
            font-weight: 500;
        }

        .info-value {
            font-weight: 600;
        }

        .action-box {
            margin: 30px 0;
            text-align: center;
        }

        .action-box p {
            margin-bottom: 16px;
            font-weight: 500;
        }

        .btn {
            display: block;
            width: 100%;
            padding: 14px;
            margin: 10px 0;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 15px;
        }

        .btn-refund {
            background: #dc3545;
            color: #fff;
        }

        .btn-change {
            background: #fd7e14;
            color: #fff;
        }

        .btn-reschedule {
            background: #0dcaf0;
            color: #000;
        }

        .note-box {
            background: #fff7ed;
            border: 1px solid #fed7aa;
            border-radius: 8px;
            padding: 16px;
            font-size: 14px;
            color: #92400e;
            margin-top: 20px;
        }

        .footer {
            background: #f9fafb;
            padding: 20px;
            text-align: center;
            font-size: 13px;
            color: #6b7280;
        }

        @media (max-width: 600px) {
            body {
                padding: 10px;
            }

            .email-body {
                padding: 20px;
            }
        }
    </style>
</head>

<body>
    <div class="email-container">

        <!-- HEADER -->
        <div class="email-header">
            <h1>XỬ LÝ BOOKING TOUR</h1>
            <p>Tour365 – Dịch vụ du lịch uy tín</p>
        </div>

        <!-- BODY -->
        <div class="email-body">

            <div class="greeting">
                Xin chào <strong>{{ $booking->user->name }}</strong>,
            </div>

            <p>
                Chúng tôi liên hệ với bạn liên quan đến booking tour của bạn.
                Vì lý do hiện số chỗ tối thiểu đang chưa đạt chỉ tiêu.
                Vui lòng xem thông tin bên dưới và chọn <strong>một phương án xử lý</strong>.
            </p>

            <!-- BOOKING INFO -->
            <div class="booking-box">
                <h3>📋 Thông tin booking</h3>

                <div class="info-row">
                    <span class="info-label">Mã booking:</span>
                    <span class="info-value">#{{ $booking->id }}</span>
                </div>

                <div class="info-row">
                    <span class="info-label">Tour:</span>
                    <span class="info-value">{{ $booking->tour->title }}</span>
                </div>

                <div class="info-row">
                    <span class="info-label">Ngày đặt:</span>
                    <span class="info-value">{{ $booking->created_at->format('d/m/Y H:i') }}</span>
                </div>
            </div>

            <div class="action-box">
                <p>Vui lòng chọn <strong>1 trong các phương án</strong> sau:</p>

                @if($booking->status === 'confirmed')
                <a href="{{ route('booking.action', [$booking, 'refund']) }}" class="btn btn-refund"
                    style="display:block;margin:8px 0;padding:10px;background:#dc3545;color:#fff;text-align:center;text-decoration:none;border-radius:6px">
                    1. Hoàn lại tiền
                </a>
                @endif

                <a href="{{ route('booking.action', [$booking, 'change_tour']) }}" class="btn btn-change">
                    2. Đổi sang tour khác
                </a>

                @if($futureDepartures->count() > 0)
                <p>3. Dời ngày khởi hành:</p>
                @foreach($futureDepartures as $departure)
                <a href="{{ route('booking.reschedule', $booking) }}?departure_id={{ $departure->id }}"
                    class="btn btn-reschedule"
                    style="display:block;margin:8px 0;padding:10px;background:#0dcaf0;color:#000;text-align:center;text-decoration:none;border-radius:6px">
                    {{ \Carbon\Carbon::parse($departure->departure_date)->format('d/m/Y') }}
                </a>
                @endforeach
                @else
                @endif
            </div>

            <div class="note-box">
                Nếu bạn không phản hồi email này, đơn đặt sẽ được giữ nguyên trạng thái hiện tại.
            </div>

        </div>

        <div class="footer">
            <p><strong>Tour365</strong></p>
            <p>Hotline: 1900-xxxx | Email: support@tour365.vn</p>
            <p style="margin-top:10px;font-size:12px;color:#9ca3af">
                Email này được gửi tự động từ hệ thống Tour365
            </p>
        </div>

    </div>
</body>

</html>
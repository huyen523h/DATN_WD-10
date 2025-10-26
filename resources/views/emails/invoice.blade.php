<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hóa đơn #{{ $invoice->invoice_number }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        
        .email-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        
        .email-header {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .email-header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
        }
        
        .email-header p {
            margin: 10px 0 0 0;
            opacity: 0.9;
        }
        
        .email-body {
            padding: 30px;
        }
        
        .greeting {
            font-size: 18px;
            margin-bottom: 20px;
            color: #1f2937;
        }
        
        .invoice-info {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        
        .invoice-info h3 {
            margin: 0 0 15px 0;
            color: #1f2937;
            font-size: 18px;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            padding: 5px 0;
        }
        
        .info-label {
            font-weight: 600;
            color: #4b5563;
        }
        
        .info-value {
            color: #1f2937;
        }
        
        .tour-details {
            background: #f0f9ff;
            border: 1px solid #bae6fd;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        
        .tour-details h3 {
            margin: 0 0 15px 0;
            color: #0c4a6e;
            font-size: 18px;
        }
        
        .amount-highlight {
            background: #ecfdf5;
            border: 2px solid #10b981;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            margin: 20px 0;
        }
        
        .amount-highlight h3 {
            margin: 0 0 10px 0;
            color: #065f46;
            font-size: 20px;
        }
        
        .amount-highlight .total-amount {
            font-size: 32px;
            font-weight: 700;
            color: #059669;
            margin: 0;
        }
        
        .payment-info {
            background: #fef3c7;
            border: 1px solid #f59e0b;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        
        .payment-info h3 {
            margin: 0 0 15px 0;
            color: #92400e;
            font-size: 18px;
        }
        
        .payment-method {
            margin-bottom: 15px;
        }
        
        .payment-method h4 {
            margin: 0 0 8px 0;
            color: #92400e;
            font-size: 16px;
        }
        
        .payment-method p {
            margin: 0;
            color: #92400e;
            font-size: 14px;
        }
        
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            text-decoration: none;
            padding: 15px 30px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            margin: 20px 0;
            text-align: center;
            box-shadow: 0 4px 6px rgba(16, 185, 129, 0.3);
        }
        
        .cta-button:hover {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            color: white;
            text-decoration: none;
        }
        
        .footer {
            background: #f8fafc;
            padding: 20px;
            text-align: center;
            color: #6b7280;
            font-size: 14px;
        }
        
        .footer p {
            margin: 5px 0;
        }
        
        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }
        
        .status-paid {
            background: #d1fae5;
            color: #065f46;
        }
        
        .divider {
            height: 1px;
            background: #e5e7eb;
            margin: 20px 0;
        }
        
        @media (max-width: 600px) {
            body {
                padding: 10px;
            }
            
            .email-header {
                padding: 20px;
            }
            
            .email-header h1 {
                font-size: 24px;
            }
            
            .email-body {
                padding: 20px;
            }
            
            .info-row {
                flex-direction: column;
            }
            
            .info-label {
                margin-bottom: 5px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <h1>HÓA ĐƠN TOUR</h1>
            <p>Tour365 - Dịch vụ du lịch uy tín</p>
        </div>
        
        <!-- Body -->
        <div class="email-body">
            <div class="greeting">
                Xin chào <strong>{{ $invoice->customer_name }}</strong>,
            </div>
            
            <p>Cảm ơn bạn đã đặt tour với chúng tôi! Dưới đây là thông tin hóa đơn chi tiết:</p>
            
            <!-- Invoice Information -->
            <div class="invoice-info">
                <h3>📋 Thông tin hóa đơn</h3>
                <div class="info-row">
                    <span class="info-label">Số hóa đơn:</span>
                    <span class="info-value"><strong>#{{ $invoice->invoice_number }}</strong></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Ngày tạo:</span>
                    <span class="info-value">{{ $invoice->invoice_date->format('d/m/Y H:i') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Hạn thanh toán:</span>
                    <span class="info-value">{{ $invoice->due_date->format('d/m/Y') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Trạng thái:</span>
                    <span class="info-value">
                        <span class="status-badge status-{{ $invoice->payment_status }}">
                            @switch($invoice->payment_status)
                                @case('pending') Chờ thanh toán @break
                                @case('paid') Đã thanh toán @break
                                @case('cancelled') Đã hủy @break
                                @case('refunded') Đã hoàn tiền @break
                            @endswitch
                        </span>
                    </span>
                </div>
            </div>
            
            <!-- Tour Details -->
            <div class="tour-details">
                <h3>🗺️ Thông tin tour</h3>
                <div class="info-row">
                    <span class="info-label">Tour:</span>
                    <span class="info-value"><strong>{{ $invoice->tour_title }}</strong></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Ngày khởi hành:</span>
                    <span class="info-value">{{ $invoice->departure_date->format('d/m/Y') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Số khách:</span>
                    <span class="info-value">
                        {{ $invoice->adults }} người lớn
                        @if($invoice->children > 0), {{ $invoice->children }} trẻ em @endif
                        @if($invoice->infants > 0), {{ $invoice->infants }} em bé @endif
                    </span>
                </div>
            </div>
            
            <!-- Amount -->
            <div class="amount-highlight">
                <h3>Tổng số tiền cần thanh toán</h3>
                <p class="total-amount">{{ number_format($invoice->total_amount, 0, ',', '.') }} VNĐ</p>
            </div>
            
            <!-- Payment Information -->
            <div class="payment-info">
                <h3>💳 Thông tin thanh toán</h3>
                
                <div class="payment-method">
                    <h4>Chuyển khoản ngân hàng</h4>
                    <p><strong>Ngân hàng:</strong> Vietcombank</p>
                    <p><strong>Số tài khoản:</strong> 1234567890</p>
                    <p><strong>Chủ tài khoản:</strong> {{ $invoice->company_name }}</p>
                    <p><strong>Nội dung chuyển khoản:</strong> {{ $invoice->invoice_number }}</p>
                </div>
                
                <div class="payment-method">
                    <h4>Thanh toán trực tuyến</h4>
                    <p>Visa, Mastercard, Ví điện tử, QR Code</p>
                </div>
            </div>
            
            <!-- CTA Button -->
            <div style="text-align: center;">
                <a href="{{ route('admin.invoices.download', $invoice) }}" class="cta-button">
                    📄 Tải hóa đơn PDF
                </a>
            </div>
            
            <div class="divider"></div>
            
            <!-- Notes -->
            @if($invoice->notes)
            <div style="background: #f3f4f6; padding: 15px; border-radius: 6px; margin: 20px 0;">
                <h4 style="margin: 0 0 10px 0; color: #374151;">📝 Ghi chú:</h4>
                <p style="margin: 0; color: #6b7280;">{{ $invoice->notes }}</p>
            </div>
            @endif
            
            <p><strong>Lưu ý quan trọng:</strong></p>
            <ul style="color: #6b7280; margin: 10px 0;">
                <li>Vui lòng thanh toán trước ngày hết hạn để đảm bảo chỗ đặt tour</li>
                <li>Sau khi thanh toán, vui lòng gửi biên lai để chúng tôi xác nhận</li>
                <li>Mọi thắc mắc vui lòng liên hệ hotline: 1900-xxxx</li>
            </ul>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <p><strong>{{ $invoice->company_name }}</strong></p>
            @if($invoice->company_address)
                <p>{{ $invoice->company_address }}</p>
            @endif
            @if($invoice->company_phone)
                <p>📞 {{ $invoice->company_phone }}</p>
            @endif
            @if($invoice->company_email)
                <p>📧 {{ $invoice->company_email }}</p>
            @endif
            <p style="margin-top: 15px; font-size: 12px; color: #9ca3af;">
                Email này được gửi tự động từ hệ thống Tour365
            </p>
        </div>
    </div>
</body>
</html>

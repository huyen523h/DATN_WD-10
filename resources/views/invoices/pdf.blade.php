<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hóa đơn #{{ $invoice->invoice_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            background: white;
        }

        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Header */
        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #e5e7eb;
        }

        .company-info {
            flex: 1;
        }

        .company-logo {
            font-size: 24px;
            font-weight: bold;
            color: #3b82f6;
            margin-bottom: 10px;
        }

        .company-details {
            color: #6b7280;
        }

        .invoice-title {
            text-align: right;
            flex: 1;
        }

        .invoice-title h1 {
            font-size: 28px;
            color: #1f2937;
            margin-bottom: 10px;
        }

        .invoice-number {
            font-size: 16px;
            color: #6b7280;
            font-weight: 600;
        }

        /* Invoice Details */
        .invoice-details {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }

        .bill-to, .invoice-info {
            flex: 1;
            padding: 0 10px;
        }

        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 1px solid #e5e7eb;
        }

        .customer-info, .invoice-meta {
            color: #6b7280;
        }

        .invoice-meta .meta-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }

        .meta-label {
            font-weight: 600;
        }

        /* Tour Information */
        .tour-section {
            margin-bottom: 30px;
            padding: 20px;
            background: #f9fafb;
            border-radius: 8px;
        }

        .tour-title {
            font-size: 18px;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 15px;
        }

        .tour-details {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
        }

        .tour-detail {
            flex: 1;
            min-width: 200px;
            margin-bottom: 10px;
        }

        .tour-detail-label {
            font-weight: 600;
            color: #374151;
        }

        .tour-detail-value {
            color: #6b7280;
        }

        /* Items Table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .items-table th,
        .items-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }

        .items-table th {
            background: #f9fafb;
            font-weight: bold;
            color: #374151;
            font-size: 13px;
        }

        .items-table td {
            color: #6b7280;
        }

        .items-table .text-right {
            text-align: right;
        }

        .items-table .text-center {
            text-align: center;
        }

        .guest-type {
            font-weight: 600;
            color: #374151;
        }

        .price {
            font-weight: 600;
            color: #1f2937;
        }

        /* Totals */
        .totals {
            width: 100%;
            max-width: 300px;
            margin-left: auto;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .total-row.final {
            border-top: 2px solid #1f2937;
            border-bottom: 2px solid #1f2937;
            font-weight: bold;
            font-size: 14px;
            color: #1f2937;
            margin-top: 10px;
            padding-top: 15px;
        }

        .total-label {
            color: #6b7280;
        }

        .total-value {
            font-weight: 600;
            color: #1f2937;
        }

        /* Payment Information */
        .payment-info {
            margin-top: 30px;
            padding: 20px;
            background: #f0f9ff;
            border-radius: 8px;
            border-left: 4px solid #3b82f6;
        }

        .payment-title {
            font-size: 16px;
            font-weight: bold;
            color: #1e40af;
            margin-bottom: 15px;
        }

        .payment-methods {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
        }

        .payment-method {
            flex: 1;
            min-width: 200px;
            margin-bottom: 15px;
        }

        .payment-method-title {
            font-weight: 600;
            color: #374151;
            margin-bottom: 5px;
        }

        .payment-method-details {
            color: #6b7280;
            font-size: 11px;
        }

        /* Status Badge */
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
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

        .status-cancelled {
            background: #fee2e2;
            color: #991b1b;
        }

        /* Footer */
        .invoice-footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            color: #6b7280;
            font-size: 11px;
        }

        .notes {
            margin-top: 20px;
            padding: 15px;
            background: #f9fafb;
            border-radius: 6px;
            border-left: 3px solid #6b7280;
        }

        .notes-title {
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
        }

        .notes-content {
            color: #6b7280;
            line-height: 1.5;
        }

        /* Print Styles */
        @media print {
            body {
                font-size: 11px;
            }
            
            .invoice-container {
                padding: 0;
            }
            
            .payment-info {
                break-inside: avoid;
            }
        
        .invoice-details, .customer-details {
            width: 48%;
        }
        
        .section-title {
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 10px;
            color: #0EA5E9;
            border-bottom: 1px solid #0EA5E9;
            padding-bottom: 5px;
        }
        
        .detail-row {
            display: flex;
            margin-bottom: 5px;
        }
        
        .detail-label {
            font-weight: bold;
            width: 120px;
        }
        
        .detail-value {
            flex: 1;
        }
        
        .tour-details {
            margin: 30px 0;
        }
        
        .tour-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        
        .tour-table th,
        .tour-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        
        .tour-table th {
            background-color: #0EA5E9;
            color: white;
            font-weight: bold;
        }
        
        .tour-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .amount-section {
            margin-top: 30px;
            text-align: right;
        }
        
        .amount-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            padding: 5px 0;
        }
        
        .amount-label {
            font-weight: bold;
        }
        
        .total-row {
            border-top: 2px solid #0EA5E9;
            padding-top: 10px;
            margin-top: 10px;
            font-size: 16px;
            font-weight: bold;
            color: #0EA5E9;
        }
        
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }
        
        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .status-issued {
            background-color: #fef3c7;
            color: #92400e;
        }
        
        .status-paid {
            background-color: #d1fae5;
            color: #065f46;
        }
        
        .status-cancelled {
            background-color: #fee2e2;
            color: #991b1b;
        }
        
        .departure-info {
            background-color: #f8fafc;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
        }
        
        .promotion-info {
            background-color: #f0f9ff;
            padding: 10px;
            border-left: 4px solid #0EA5E9;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="company-name">{{ $company['name'] }}</div>
            <div class="company-info">
                {{ $company['address'] }}<br>
                Điện thoại: {{ $company['phone'] }} | Email: {{ $company['email'] }}<br>
                Website: {{ $company['website'] }} | MST: {{ $company['tax_code'] }}
            </div>
        </div>

        <!-- Invoice Title -->
        <div class="invoice-title">HÓA ĐƠN DỊCH VỤ DU LỊCH</div>

        <!-- Invoice Info -->
        <div class="invoice-info">
            <div class="invoice-details">
                <div class="section-title">Thông tin hóa đơn</div>
                <div class="detail-row">
                    <div class="detail-label">Số hóa đơn:</div>
                    <div class="detail-value">{{ $invoice->invoice_number }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Ngày phát hành:</div>
                    <div class="detail-value">{{ $invoice->issue_date->format('d/m/Y') }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Trạng thái:</div>
                    <div class="detail-value">
                        <span class="status-badge status-{{ $invoice->status }}">
                            {{ ucfirst($invoice->status) }}
                        </span>
                    </div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Mã đặt tour:</div>
                    <div class="detail-value">#{{ $booking->id }}</div>
                </div>
            </div>

            <div class="customer-details">
                <div class="section-title">Thông tin khách hàng</div>
                <div class="detail-row">
                    <div class="detail-label">Họ tên:</div>
                    <div class="detail-value">{{ $user->name }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Email:</div>
                    <div class="detail-value">{{ $user->email }}</div>
                </div>
                @if($user->phone)
                <div class="detail-row">
                    <div class="detail-label">Điện thoại:</div>
                    <div class="detail-value">{{ $user->phone }}</div>
                </div>
                @endif
                @if($user->address)
                <div class="detail-row">
                    <div class="detail-label">Địa chỉ:</div>
                    <div class="detail-value">{{ $user->address }}</div>
                </div>
                @endif
            </div>
        </div>

        <!-- Tour Details -->
        <div class="tour-details">
            <div class="section-title">Chi tiết tour</div>
            
            <table class="tour-table">
                <thead>
                    <tr>
                        <th>Tên tour</th>
                        <th>Địa điểm</th>
                        <th>Thời gian</th>
                        <th>Ngày khởi hành</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $tour->title }}</td>
                        <td>{{ $tour->location }}</td>
                        <td>{{ $tour->duration }}</td>
                        <td>{{ $departure ? $departure->departure_date->format('d/m/Y') : 'N/A' }}</td>
                    </tr>
                </tbody>
            </table>

            @if($departure)
            <div class="departure-info">
                <strong>Thông tin khởi hành:</strong><br>
                Ngày: {{ $departure->departure_date->format('d/m/Y') }}<br>
                @if($departure->return_date)
                Ngày về: {{ $departure->return_date->format('d/m/Y') }}<br>
                @endif
                @if($departure->meeting_point)
                Điểm tập trung: {{ $departure->meeting_point }}<br>
                @endif
                @if($departure->meeting_time)
                Giờ tập trung: {{ $departure->meeting_time }}<br>
                @endif
            </div>
            @endif
        </div>

        <!-- Passenger Details -->
        <div class="tour-details">
            <div class="section-title">Thông tin hành khách</div>
            
            <table class="tour-table">
                <thead>
                    <tr>
                        <th>Loại</th>
                        <th>Số lượng</th>
                        <th>Giá</th>
                        <th>Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    @if($booking->adults > 0)
                    <tr>
                        <td>Người lớn</td>
                        <td>{{ $booking->adults }}</td>
                        <td>{{ number_format($tour->price_adult ?? $tour->price, 0, ',', '.') }} VNĐ</td>
                        <td>{{ number_format(($tour->price_adult ?? $tour->price) * $booking->adults, 0, ',', '.') }} VNĐ</td>
                    </tr>
                    @endif
                    
                    @if($booking->children > 0)
                    <tr>
                        <td>Trẻ em</td>
                        <td>{{ $booking->children }}</td>
                        <td>{{ number_format($tour->price_child ?? ($tour->price * 0.7), 0, ',', '.') }} VNĐ</td>
                        <td>{{ number_format(($tour->price_child ?? ($tour->price * 0.7)) * $booking->children, 0, ',', '.') }} VNĐ</td>
                    </tr>
                    @endif
                    
                    @if($booking->infants > 0)
                    <tr>
                        <td>Em bé</td>
                        <td>{{ $booking->infants }}</td>
                        <td>{{ number_format($tour->price_infant ?? ($tour->price * 0.3), 0, ',', '.') }} VNĐ</td>
                        <td>{{ number_format(($tour->price_infant ?? ($tour->price * 0.3)) * $booking->infants, 0, ',', '.') }} VNĐ</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <!-- Promotion Info -->
        @if($promotion)
        <div class="promotion-info">
            <strong>Mã giảm giá đã áp dụng:</strong> {{ $promotion->code }}<br>
            <strong>Mô tả:</strong> {{ $promotion->description }}<br>
            <strong>Giảm giá:</strong> {{ $promotion->discount_type === 'percentage' ? $promotion->discount_value . '%' : number_format($promotion->discount_value, 0, ',', '.') . ' VNĐ' }}
        </div>
        @endif

        <!-- Amount Section -->
        <div class="amount-section">
            <div class="amount-row">
                <div class="amount-label">Tổng tiền tour:</div>
                <div>{{ number_format($booking->total_amount, 0, ',', '.') }} VNĐ</div>
            </div>
            
            @if($promotion)
            @php
                $discountAmount = $promotion->discount_type === 'percentage' 
                    ? ($booking->total_amount * $promotion->discount_value / 100)
                    : $promotion->discount_value;
            @endphp
            <div class="amount-row">
                <div class="amount-label">Giảm giá:</div>
                <div>-{{ number_format($discountAmount, 0, ',', '.') }} VNĐ</div>
            </div>
            @endif
            
            <div class="amount-row total-row">
                <div class="amount-label">TỔNG CỘNG:</div>
                <div>{{ number_format($invoice->amount, 0, ',', '.') }} VNĐ</div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>Cảm ơn quý khách đã sử dụng dịch vụ của {{ $company['name'] }}!</strong></p>
            <p>Hóa đơn này được tạo tự động bởi hệ thống quản lý du lịch Tour365</p>
            <p>Mọi thắc mắc vui lòng liên hệ: {{ $company['phone'] }} hoặc {{ $company['email'] }}</p>
        </div>
    </div>
</body>
</html>

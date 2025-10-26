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
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <!-- Header -->
        <div class="invoice-header">
            <div class="company-info">
                <div class="company-logo">{{ $invoice->company_name }}</div>
                <div class="company-details">
                    @if($invoice->company_address)
                        <div>{{ $invoice->company_address }}</div>
                    @endif
                    @if($invoice->company_phone)
                        <div>Điện thoại: {{ $invoice->company_phone }}</div>
                    @endif
                    @if($invoice->company_email)
                        <div>Email: {{ $invoice->company_email }}</div>
                    @endif
                    @if($invoice->company_tax_code)
                        <div>Mã số thuế: {{ $invoice->company_tax_code }}</div>
                    @endif
                </div>
            </div>
            <div class="invoice-title">
                <h1>HÓA ĐƠN</h1>
                <div class="invoice-number">#{{ $invoice->invoice_number }}</div>
            </div>
        </div>

        <!-- Invoice Details -->
        <div class="invoice-details">
            <div class="bill-to">
                <div class="section-title">Thông tin khách hàng</div>
                <div class="customer-info">
                    <div><strong>{{ $invoice->customer_name }}</strong></div>
                    <div>{{ $invoice->customer_email }}</div>
                    @if($invoice->customer_phone)
                        <div>{{ $invoice->customer_phone }}</div>
                    @endif
                    @if($invoice->customer_address)
                        <div>{{ $invoice->customer_address }}</div>
                    @endif
                </div>
            </div>
            <div class="invoice-info">
                <div class="section-title">Thông tin hóa đơn</div>
                <div class="invoice-meta">
                    <div class="meta-row">
                        <span class="meta-label">Ngày tạo:</span>
                        <span>{{ $invoice->invoice_date->format('d/m/Y') }}</span>
                    </div>
                    <div class="meta-row">
                        <span class="meta-label">Hạn thanh toán:</span>
                        <span>{{ $invoice->due_date->format('d/m/Y') }}</span>
                    </div>
                    <div class="meta-row">
                        <span class="meta-label">Trạng thái:</span>
                        <span class="status-badge status-{{ $invoice->payment_status }}">
                            @switch($invoice->payment_status)
                                @case('pending') Chờ thanh toán @break
                                @case('paid') Đã thanh toán @break
                                @case('cancelled') Đã hủy @break
                                @case('refunded') Đã hoàn tiền @break
                            @endswitch
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tour Information -->
        <div class="tour-section">
            <div class="tour-title">{{ $invoice->tour_title }}</div>
            <div class="tour-details">
                <div class="tour-detail">
                    <div class="tour-detail-label">Ngày khởi hành:</div>
                    <div class="tour-detail-value">{{ $invoice->departure_date->format('d/m/Y') }}</div>
                </div>
                <div class="tour-detail">
                    <div class="tour-detail-label">Số khách:</div>
                    <div class="tour-detail-value">
                        {{ $invoice->adults }} người lớn
                        @if($invoice->children > 0), {{ $invoice->children }} trẻ em @endif
                        @if($invoice->infants > 0), {{ $invoice->infants }} em bé @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Items Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th>Loại khách</th>
                    <th class="text-center">Số lượng</th>
                    <th class="text-right">Đơn giá</th>
                    <th class="text-right">Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                @if($invoice->adults > 0)
                <tr>
                    <td class="guest-type">Người lớn</td>
                    <td class="text-center">{{ $invoice->adults }}</td>
                    <td class="text-right price">{{ number_format($invoice->adult_price, 0, ',', '.') }} VNĐ</td>
                    <td class="text-right price">{{ number_format($invoice->adults * $invoice->adult_price, 0, ',', '.') }} VNĐ</td>
                </tr>
                @endif
                @if($invoice->children > 0)
                <tr>
                    <td class="guest-type">Trẻ em</td>
                    <td class="text-center">{{ $invoice->children }}</td>
                    <td class="text-right price">{{ number_format($invoice->child_price, 0, ',', '.') }} VNĐ</td>
                    <td class="text-right price">{{ number_format($invoice->children * $invoice->child_price, 0, ',', '.') }} VNĐ</td>
                </tr>
                @endif
                @if($invoice->infants > 0)
                <tr>
                    <td class="guest-type">Em bé</td>
                    <td class="text-center">{{ $invoice->infants }}</td>
                    <td class="text-right price">{{ number_format($invoice->infant_price, 0, ',', '.') }} VNĐ</td>
                    <td class="text-right price">{{ number_format($invoice->infants * $invoice->infant_price, 0, ',', '.') }} VNĐ</td>
                </tr>
                @endif
            </tbody>
        </table>

        <!-- Totals -->
        <div class="totals">
            <div class="total-row">
                <span class="total-label">Tạm tính:</span>
                <span class="total-value">{{ number_format($invoice->subtotal, 0, ',', '.') }} VNĐ</span>
            </div>
            @if($invoice->discount_amount > 0)
            <div class="total-row">
                <span class="total-label">Giảm giá:</span>
                <span class="total-value">-{{ number_format($invoice->discount_amount, 0, ',', '.') }} VNĐ</span>
            </div>
            @endif
            <div class="total-row">
                <span class="total-label">VAT ({{ $invoice->tax_rate }}%):</span>
                <span class="total-value">{{ number_format($invoice->tax_amount, 0, ',', '.') }} VNĐ</span>
            </div>
            <div class="total-row final">
                <span class="total-label">TỔNG CỘNG:</span>
                <span class="total-value">{{ number_format($invoice->total_amount, 0, ',', '.') }} VNĐ</span>
            </div>
        </div>

        <!-- Payment Information -->
        <div class="payment-info">
            <div class="payment-title">Thông tin thanh toán</div>
            <div class="payment-methods">
                <div class="payment-method">
                    <div class="payment-method-title">Chuyển khoản ngân hàng</div>
                    <div class="payment-method-details">
                        <div>Ngân hàng: Vietcombank</div>
                        <div>Số tài khoản: 1234567890</div>
                        <div>Chủ tài khoản: {{ $invoice->company_name }}</div>
                        <div>Nội dung: {{ $invoice->invoice_number }}</div>
                    </div>
                </div>
                <div class="payment-method">
                    <div class="payment-method-title">Thanh toán trực tuyến</div>
                    <div class="payment-method-details">
                        <div>Visa, Mastercard</div>
                        <div>Ví điện tử</div>
                        <div>QR Code</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notes -->
        @if($invoice->notes)
        <div class="notes">
            <div class="notes-title">Ghi chú:</div>
            <div class="notes-content">{{ $invoice->notes }}</div>
        </div>
        @endif

        <!-- Footer -->
        <div class="invoice-footer">
            <div>Cảm ơn quý khách đã sử dụng dịch vụ của chúng tôi!</div>
            <div>Hóa đơn được tạo tự động bởi hệ thống Tour365</div>
            <div>Ngày tạo: {{ now()->format('d/m/Y H:i:s') }}</div>
        </div>
    </div>
</body>
</html>

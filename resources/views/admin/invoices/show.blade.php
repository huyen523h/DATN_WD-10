@extends('layouts.admin')

@section('title', 'Chi tiết Hóa đơn - Admin')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.invoices.index') }}">Hóa đơn</a></li>
<li class="breadcrumb-item active">#{{ $invoice->invoice_number }}</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2><i class="fas fa-file-invoice text-primary"></i> Hóa đơn #{{ $invoice->invoice_number }}</h2>
            <p class="text-muted mb-0">Chi tiết hóa đơn và thông tin thanh toán</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.invoices.pdf', $invoice) }}" target="_blank" class="btn btn-info">
                <i class="fas fa-file-pdf"></i> Xem PDF
            </a>
            <a href="{{ route('admin.invoices.download', $invoice) }}" class="btn btn-success">
                <i class="fas fa-download"></i> Tải PDF
            </a>
            <a href="{{ route('admin.invoices.edit', $invoice) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Chỉnh sửa
            </a>
            <a href="{{ route('admin.invoices.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Left Column -->
        <div class="col-lg-8">
            <!-- Invoice Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> Thông tin hóa đơn</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-item">
                                <label class="info-label">Số hóa đơn:</label>
                                <span class="info-value">{{ $invoice->invoice_number }}</span>
                            </div>
                            <div class="info-item">
                                <label class="info-label">Ngày tạo:</label>
                                <span class="info-value">{{ $invoice->invoice_date->format('d/m/Y H:i') }}</span>
                            </div>
                            <div class="info-item">
                                <label class="info-label">Hạn thanh toán:</label>
                                <span class="info-value">{{ $invoice->due_date->format('d/m/Y') }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <label class="info-label">Trạng thái:</label>
                                <span class="status-badge status-{{ $invoice->payment_status }}">
                                    @switch($invoice->payment_status)
                                        @case('pending') Chờ thanh toán @break
                                        @case('paid') Đã thanh toán @break
                                        @case('cancelled') Đã hủy @break
                                        @case('refunded') Đã hoàn tiền @break
                                    @endswitch
                                </span>
                            </div>
                            <div class="info-item">
                                <label class="info-label">Phương thức thanh toán:</label>
                                <span class="info-value">{{ ucfirst($invoice->payment_method) }}</span>
                            </div>
                            <div class="info-item">
                                <label class="info-label">Tổng tiền:</label>
                                <span class="info-value total-amount">{{ number_format($invoice->total_amount, 0, ',', '.') }} VNĐ</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customer Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-user"></i> Thông tin khách hàng</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-item">
                                <label class="info-label">Tên khách hàng:</label>
                                <span class="info-value">{{ $invoice->customer_name }}</span>
                            </div>
                            <div class="info-item">
                                <label class="info-label">Email:</label>
                                <span class="info-value">{{ $invoice->customer_email }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <label class="info-label">Điện thoại:</label>
                                <span class="info-value">{{ $invoice->customer_phone ?: 'N/A' }}</span>
                            </div>
                            <div class="info-item">
                                <label class="info-label">Địa chỉ:</label>
                                <span class="info-value">{{ $invoice->customer_address ?: 'N/A' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tour Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-map-marked-alt"></i> Thông tin tour</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-item">
                                <label class="info-label">Tour:</label>
                                <span class="info-value">{{ $invoice->tour_title }}</span>
                            </div>
                            <div class="info-item">
                                <label class="info-label">Ngày khởi hành:</label>
                                <span class="info-value">{{ $invoice->departure_date->format('d/m/Y') }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <label class="info-label">Số khách:</label>
                                <span class="info-value">
                                    {{ $invoice->adults }} người lớn
                                    @if($invoice->children > 0), {{ $invoice->children }} trẻ em @endif
                                    @if($invoice->infants > 0), {{ $invoice->infants }} em bé @endif
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pricing Details -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-calculator"></i> Chi tiết giá</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
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
                                    <td>Người lớn</td>
                                    <td class="text-center">{{ $invoice->adults }}</td>
                                    <td class="text-right">{{ number_format($invoice->adult_price, 0, ',', '.') }} VNĐ</td>
                                    <td class="text-right">{{ number_format($invoice->adults * $invoice->adult_price, 0, ',', '.') }} VNĐ</td>
                                </tr>
                                @endif
                                @if($invoice->children > 0)
                                <tr>
                                    <td>Trẻ em</td>
                                    <td class="text-center">{{ $invoice->children }}</td>
                                    <td class="text-right">{{ number_format($invoice->child_price, 0, ',', '.') }} VNĐ</td>
                                    <td class="text-right">{{ number_format($invoice->children * $invoice->child_price, 0, ',', '.') }} VNĐ</td>
                                </tr>
                                @endif
                                @if($invoice->infants > 0)
                                <tr>
                                    <td>Em bé</td>
                                    <td class="text-center">{{ $invoice->infants }}</td>
                                    <td class="text-right">{{ number_format($invoice->infant_price, 0, ',', '.') }} VNĐ</td>
                                    <td class="text-right">{{ number_format($invoice->infants * $invoice->infant_price, 0, ',', '.') }} VNĐ</td>
                                </tr>
                                @endif
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-right"><strong>Tạm tính:</strong></td>
                                    <td class="text-right"><strong>{{ number_format($invoice->subtotal, 0, ',', '.') }} VNĐ</strong></td>
                                </tr>
                                @if($invoice->discount_amount > 0)
                                <tr>
                                    <td colspan="3" class="text-right"><strong>Giảm giá:</strong></td>
                                    <td class="text-right"><strong>-{{ number_format($invoice->discount_amount, 0, ',', '.') }} VNĐ</strong></td>
                                </tr>
                                @endif
                                <tr>
                                    <td colspan="3" class="text-right"><strong>VAT ({{ $invoice->tax_rate }}%):</strong></td>
                                    <td class="text-right"><strong>{{ number_format($invoice->tax_amount, 0, ',', '.') }} VNĐ</strong></td>
                                </tr>
                                <tr class="table-primary">
                                    <td colspan="3" class="text-right"><strong>TỔNG CỘNG:</strong></td>
                                    <td class="text-right"><strong>{{ number_format($invoice->total_amount, 0, ',', '.') }} VNĐ</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Notes -->
            @if($invoice->notes)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-sticky-note"></i> Ghi chú</h5>
                </div>
                <div class="card-body">
                    <p>{{ $invoice->notes }}</p>
                </div>
            </div>
            @endif
        </div>

        <!-- Right Column -->
        <div class="col-lg-4">
            <!-- Actions -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-cogs"></i> Thao tác</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @if($invoice->payment_status !== 'paid')
                        <button class="btn btn-success" onclick="markAsPaid({{ $invoice->id }})">
                            <i class="fas fa-check"></i> Đánh dấu đã thanh toán
                        </button>
                        @endif
                        
                        @if($invoice->booking)
                        <button class="btn btn-success" onclick="generateInvoice({{ $invoice->booking->id }})">
                            <i class="fas fa-file-invoice"></i> In hóa đơn
                        </button>
                        
                        <button class="btn btn-primary" onclick="downloadInvoice({{ $invoice->booking->id }})">
                            <i class="fas fa-download"></i> Tải PDF
                        </button>
                        @else
                        <a href="{{ route('admin.invoices.pdf', $invoice) }}" target="_blank" class="btn btn-info">
                            <i class="fas fa-file-pdf"></i> Xem PDF
                        </a>
                        
                        <a href="{{ route('admin.invoices.download', $invoice) }}" class="btn btn-primary">
                            <i class="fas fa-download"></i> Tải PDF
                        </a>
                        @endif
                        
                        <a href="{{ route('admin.invoices.edit', $invoice) }}" class="btn btn-secondary">
                            <i class="fas fa-edit"></i> Chỉnh sửa
                        </a>
                        
                        <button class="btn btn-danger" onclick="deleteInvoice({{ $invoice->id }})">
                            <i class="fas fa-trash"></i> Xóa hóa đơn
                        </button>
                    </div>
                </div>
            </div>

            <!-- Company Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-building"></i> Thông tin công ty</h5>
                </div>
                <div class="card-body">
                    <div class="info-item">
                        <label class="info-label">Tên công ty:</label>
                        <span class="info-value">{{ $invoice->company_name }}</span>
                    </div>
                    @if($invoice->company_address)
                    <div class="info-item">
                        <label class="info-label">Địa chỉ:</label>
                        <span class="info-value">{{ $invoice->company_address }}</span>
                    </div>
                    @endif
                    @if($invoice->company_phone)
                    <div class="info-item">
                        <label class="info-label">Điện thoại:</label>
                        <span class="info-value">{{ $invoice->company_phone }}</span>
                    </div>
                    @endif
                    @if($invoice->company_email)
                    <div class="info-item">
                        <label class="info-label">Email:</label>
                        <span class="info-value">{{ $invoice->company_email }}</span>
                    </div>
                    @endif
                    @if($invoice->company_tax_code)
                    <div class="info-item">
                        <label class="info-label">Mã số thuế:</label>
                        <span class="info-value">{{ $invoice->company_tax_code }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.info-item {
    margin-bottom: 15px;
}

.info-label {
    font-weight: 600;
    color: #6b7280;
    display: block;
    margin-bottom: 5px;
}

.info-value {
    color: #1f2937;
    font-size: 14px;
}

.total-amount {
    font-size: 18px;
    font-weight: 700;
    color: #059669;
}

.status-badge {
    display: inline-block;
    padding: 4px 12px;
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

.status-cancelled {
    background: #fee2e2;
    color: #991b1b;
}

.status-refunded {
    background: #e0e7ff;
    color: #3730a3;
}
</style>
@endpush

@push('scripts')
<script>
function markAsPaid(invoiceId) {
    if (confirm('Bạn có chắc chắn muốn đánh dấu hóa đơn này là đã thanh toán?')) {
        fetch(`/admin/invoices/${invoiceId}/mark-paid`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification(data.message, 'success');
                location.reload();
            } else {
                showNotification(data.message, 'error');
            }
        })
        .catch(error => {
            showNotification('Có lỗi xảy ra!', 'error');
        });
    }
}

// Generate Invoice PDF
async function generateInvoice(bookingId, buttonElement = null) {
    let button = null;
    let originalContent = null;
    
    try {
        button = buttonElement || event.target.closest('button');
        originalContent = button.innerHTML;
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        button.disabled = true;

        const response = await fetch(`/web/invoices/booking/${bookingId}/pdf`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();

        if (data.success && data.data && data.data.download_url) {
            const newWindow = window.open(data.data.download_url, '_blank');
            
            if (newWindow) {
                showNotification('PDF hóa đơn đã được tạo thành công!', 'success');
            } else {
                showNotification('Popup bị chặn. Vui lòng cho phép popup và thử lại.', 'error');
            }
        } else {
            showNotification('Lỗi: ' + (data.message || 'Không thể tạo PDF'), 'error');
        }
    } catch (error) {
        console.error('Error generating invoice:', error);
        showNotification('Lỗi: ' + error.message, 'error');
    } finally {
        if (button && originalContent) {
            button.innerHTML = originalContent;
            button.disabled = false;
        }
    }
}

// Download Invoice PDF
async function downloadInvoice(bookingId) {
    let button = null;
    let originalContent = null;
    
    try {
        button = event.target.closest('button');
        originalContent = button.innerHTML;
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        button.disabled = true;

        const response = await fetch(`/web/invoices/booking/${bookingId}/pdf`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });

        if (response.ok) {
            const data = await response.json();
            if (data.success) {
                const link = document.createElement('a');
                link.href = data.data.download_url;
                link.download = data.data.file_name;
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                
                showNotification('PDF hóa đơn đã được tải xuống!', 'success');
            } else {
                showNotification('Lỗi: ' + data.message, 'error');
            }
        } else {
            const errorText = await response.text();
            showNotification('Lỗi: ' + errorText, 'error');
        }
    } catch (error) {
        showNotification('Lỗi: ' + error.message, 'error');
    } finally {
        if (button && originalContent) {
            button.innerHTML = originalContent;
            button.disabled = false;
        }
    }
}

function deleteInvoice(invoiceId) {
    if (confirm('Bạn có chắc chắn muốn xóa hóa đơn này? Hành động này không thể hoàn tác!')) {
        fetch(`/admin/invoices/${invoiceId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification(data.message, 'success');
                window.location.href = '/admin/invoices';
            } else {
                showNotification(data.message, 'error');
            }
        })
        .catch(error => {
            showNotification('Có lỗi xảy ra khi xóa hóa đơn!', 'error');
        });
    }
}

function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
        <span>${message}</span>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => notification.classList.add('show'), 100);
    
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}
</script>
@endpush

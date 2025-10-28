@extends('layouts.admin')

@section('title', 'Quản lý Đặt Tour - Admin')

@section('breadcrumb')
<li class="breadcrumb-item active">Quản lý Đặt Tour</li>
@endsection

@section('content')
<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
            <div>
        <h2><i class="fas fa-calendar-check text-primary"></i> Quản lý Đặt Tour</h2>
                <p class="text-muted mb-0">Quản lý tất cả các đặt tour trong hệ thống</p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-primary">
                    <i class="fas fa-download"></i> Xuất Excel
                </button>
                <button class="btn btn-outline-secondary">
                    <i class="fas fa-filter"></i> Lọc nâng cao
                </button>
                <button class="btn btn-outline-info" onclick="testInvoiceAPI()">
                    <i class="fas fa-bug"></i> Test API
                </button>
                <button class="btn btn-outline-success" onclick="simpleTest()">
                    <i class="fas fa-play"></i> Quick Test
                </button>
                <button class="btn btn-outline-warning" onclick="testCreateInvoice()">
                    <i class="fas fa-plus"></i> Test Create Invoice
                </button>
            </div>
</div>

<!-- Success Alert -->
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                            <i class="fas fa-calendar-check text-primary fa-lg"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-1">Tổng đặt tour</h6>
                        <h4 class="mb-0 fw-bold">{{ $bookings->total() }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-warning bg-opacity-10 rounded-circle p-3">
                            <i class="fas fa-clock text-warning fa-lg"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-1">Chờ xác nhận</h6>
                        <h4 class="mb-0 fw-bold">{{ $bookings->where('status', 'pending')->count() }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-success bg-opacity-10 rounded-circle p-3">
                            <i class="fas fa-check-circle text-success fa-lg"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-1">Đã xác nhận</h6>
                        <h4 class="mb-0 fw-bold">{{ $bookings->where('status', 'confirmed')->count() }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-info bg-opacity-10 rounded-circle p-3">
                            <i class="fas fa-money-bill-wave text-info fa-lg"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-1">Tổng doanh thu</h6>
                        <h4 class="mb-0 fw-bold">{{ number_format($bookings->sum('total_amount')) }}đ</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.bookings') }}" class="row g-3">
                    <div class="col-md-3">
                <label for="search" class="form-label">Tìm kiếm</label>
                <input type="text" class="form-control" id="search" name="search" 
                                   value="{{ request('search') }}" placeholder="Mã đặt tour, tên khách hàng...">
                    </div>
                    <div class="col-md-3">
                <label for="status" class="form-label">Trạng thái</label>
                        <select class="form-select" id="status" name="status">
                    <option value="">Tất cả trạng thái</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ xác nhận</option>
                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                </select>
            </div>
            <div class="col-md-2">
                <label for="date_from" class="form-label">Từ ngày</label>
                <input type="date" class="form-control" id="date_from" name="date_from" value="{{ request('date_from') }}">
            </div>
            <div class="col-md-2">
                <label for="date_to" class="form-label">Đến ngày</label>
                <input type="date" class="form-control" id="date_to" name="date_to" value="{{ request('date_to') }}">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search"></i> Tìm kiếm
                </button>
            </div>
        </form>
            </div>
        </div>
        
<!-- Bookings Table -->
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="5%">Mã</th>
                        <th width="15%">Khách hàng</th>
                        <th width="20%">Tour</th>
                        <th width="10%">Ngày khởi hành</th>
                        <th width="10%">Số khách</th>
                        <th width="12%">Tổng tiền</th>
                        <th width="10%">Trạng thái</th>
                        <th width="18%" class="text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $booking)
                        <tr>
                            <td><strong>#{{ $booking->id }}</strong></td>
                            <td>
                                <div>
                                    <div class="fw-bold">{{ $booking->user->name }}</div>
                                    <small class="text-muted">{{ $booking->user->email }}</small>
                                </div>
                            </td>
                            <td>
                                <div class="fw-bold">{{ Str::limit($booking->tour->title, 40) }}</div>
                                <small class="text-muted">{{ $booking->tour->category->name }}</small>
                            </td>
                            <td>
                                @if($booking->departure)
                                    {{ \Carbon\Carbon::parse($booking->departure->departure_date)->format('d/m/Y') }}
                                @else
                                    <span class="text-muted">Chưa có</span>
                                @endif
                            </td>
                            <td>
                                <small>
                                    <i class="fas fa-user"></i> {{ $booking->adults }}
                                    @if($booking->children > 0)
                                        | <i class="fas fa-child"></i> {{ $booking->children }}
                                    @endif
                                    @if($booking->infants > 0)
                                        | <i class="fas fa-baby"></i> {{ $booking->infants }}
                                    @endif
                                </small>
                            </td>
                            <td>
                                <strong class="text-primary">{{ number_format($booking->total_amount) }}đ</strong>
                            </td>
                            <td>
                                @if($booking->status == 'pending')
                                    <span class="badge bg-warning">Chờ xác nhận</span>
                                @elseif($booking->status == 'confirmed')
                                    <span class="badge bg-success">Đã xác nhận</span>
                                @elseif($booking->status == 'cancelled')
                                    <span class="badge bg-danger">Đã hủy</span>
                                @else
                                    <span class="badge bg-info">Hoàn thành</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.bookings.show', $booking) }}" 
                                       class="btn btn-sm btn-info" 
                                       title="Xem chi tiết">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <button type="button" 
                                            class="btn btn-sm btn-primary" 
                                            onclick="generateInvoice(this.dataset.bookingId)"
                                            data-booking-id="{{ $booking->id }}"
                                            title="In hóa đơn">
                                        <i class="fas fa-print"></i>
                                    </button>
                                    <button type="button" 
                                            class="btn btn-sm btn-success" 
                                            onclick="downloadInvoice(this.dataset.bookingId)"
                                            data-booking-id="{{ $booking->id }}"
                                            title="Tải PDF">
                                        <i class="fas fa-download"></i>
                                    </button>
                                    <form action="{{ route('admin.bookings.destroy', $booking) }}" 
                                          method="POST" 
                                          class="d-inline"
                                          onsubmit="return confirm('Bạn có chắc muốn xóa đặt tour này?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Xóa">
                                            <i class="fas fa-trash"></i>
            </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Chưa có đặt tour nào trong hệ thống</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($bookings->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $bookings->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
// Generate Invoice PDF
async function generateInvoice(bookingId, buttonElement = null) {
    let button = null;
    let originalContent = null;
    
    try {
        button = buttonElement || event.target.closest('button');
        originalContent = button.innerHTML;
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        button.disabled = true;

        // Debug route first
        const debugResponse = await fetch(`/debug-invoice-simple/${bookingId}`);
        const debugData = await debugResponse.json();

        if (!debugData.success) {
            throw new Error('Debug failed: ' + debugData.message);
        }

        // Generate invoice
        const response = await fetch(`/web/invoices/booking/${bookingId}/pdf`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });

        if (response.ok) {
            const data = await response.json();
            if (data.success && data.data && data.data.download_url) {
                const newWindow = window.open(data.data.download_url, '_blank');
                if (newWindow) {
                    showAlert('success', 'PDF hóa đơn đã được tạo thành công!');
                } else {
                    showAlert('warning', 'Popup bị chặn. Vui lòng cho phép popup và thử lại.');
                }
            }
        }
        } catch (error) {
            console.error('Error generating invoice:', error);
            showAlert('danger', 'Lỗi: ' + error.message);
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
                    showAlert('success', 'PDF hóa đơn đã được tải xuống!');
                }
            }
        } catch (error) {
            showAlert('danger', 'Lỗi: ' + error.message);
        } finally {
            if (button && originalContent) {
                button.innerHTML = originalContent;
                button.disabled = false;
            }
        }
    }

// Test Invoice API
async function testInvoiceAPI() {
    try {
        showAlert('info', 'Đang test API...');
        
        // Check bookings
        const bookingsResponse = await fetch('/test-bookings');
        const bookingsData = await bookingsResponse.json();
        
        if (!bookingsData.success || bookingsData.count === 0) {
            showAlert('warning', 'Không có booking nào trong database để test');
            return;
        }
        
        const firstBooking = bookingsData.data[0];
        const bookingId = firstBooking.id;
        
        // Test debug endpoint
        const debugResponse = await fetch(`/debug-invoice-simple/${bookingId}`);
        const debugData = await debugResponse.json();
        
        if (debugData.success) {
            showAlert('success', `Booking ${bookingId} found: ${debugData.data.user_name} - ${debugData.data.tour_title}`);
            
            // Test invoice API
            const invoiceResponse = await fetch(`/web/invoices/booking/${bookingId}/pdf`, {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
            
            if (invoiceResponse.ok) {
                showAlert('success', 'Invoice API hoạt động bình thường!');
            } else {
                const errorText = await invoiceResponse.text();
                showAlert('danger', `Invoice API lỗi: ${invoiceResponse.status} - ${errorText}`);
            }
        }
    } catch (error) {
        console.error('Test API error:', error);
        showAlert('danger', 'Lỗi test API: ' + error.message);
    }
}

// Simple Test Function
async function simpleTest() {
    try {
        console.log('Simple test function called!');
        showAlert('info', 'JavaScript hoạt động! Đang test API...');
        
        const response = await fetch('/simple-test');
        const data = await response.json();
        
        showAlert('success', 'API test thành công: ' + data.message);
    } catch (error) {
        console.error('Simple test error:', error);
        showAlert('danger', 'Lỗi: ' + error.message);
    }
}

// Test Create Invoice Function
async function testCreateInvoice() {
    try {
        showAlert('info', 'Đang test tạo invoice...');
        
        // Get first booking
        const bookingsResponse = await fetch('/test-bookings');
        const bookingsData = await bookingsResponse.json();
        
        if (!bookingsData.success || bookingsData.count === 0) {
            showAlert('warning', 'Không có booking nào trong database để test');
            return;
        }
        
        const firstBooking = bookingsData.data[0];
        const bookingId = firstBooking.id;
        
        // Test creating invoice
        const response = await fetch(`/test-create-invoice/${bookingId}`, {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        if (response.ok) {
            const data = await response.json();
            if (data.success) {
                showAlert('success', `Invoice tạo thành công! Số: ${data.data.invoice_number}`);
            } else {
                showAlert('danger', 'Lỗi tạo invoice: ' + data.message);
            }
        } else {
            const errorText = await response.text();
            showAlert('danger', `Lỗi HTTP: ${response.status} - ${errorText}`);
        }
    } catch (error) {
        console.error('Test create invoice error:', error);
        showAlert('danger', 'Lỗi test tạo invoice: ' + error.message);
    }
}

    // Show alert message
    function showAlert(type, message) {
        const alertContainer = document.createElement('div');
        alertContainer.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
        alertContainer.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        alertContainer.innerHTML = `
            <div class="d-flex align-items-center">
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>
                <span>${message}</span>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        document.body.appendChild(alertContainer);
        
        setTimeout(() => {
            if (alertContainer.parentNode) {
                alertContainer.remove();
            }
        }, 5000);
    }
</script>
@endpush

@push('styles')
<style>
.bg-opacity-10 {
    opacity: 0.1;
}
.btn-group .btn {
    padding: 0.25rem 0.5rem;
}
.badge {
    padding: 0.35em 0.65em;
    font-weight: 500;
}
</style>
@endpush

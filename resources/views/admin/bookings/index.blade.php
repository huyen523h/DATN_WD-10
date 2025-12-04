@extends('layouts.admin')

@section('title', 'Quản lý Đặt Tour - Admin')

@section('breadcrumb')
<li class="breadcrumb-item active">Quản lý Đặt Tour</li>
@endsection

@section('content')
<!-- Header Section -->
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="fw-bold text-dark mb-2">
                    <i class="fas fa-calendar-check text-primary me-2"></i>
                    Quản lý Đặt Tour
                </h2>
                <p class="text-muted mb-0">Quản lý tất cả các đặt tour trong hệ thống</p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-primary">
                    <i class="fas fa-download me-1"></i>
                    Xuất Excel
                </button>
                <button class="btn btn-outline-secondary">
                    <i class="fas fa-filter me-1"></i>
                    Lọc nâng cao
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Success Alert -->
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert">
        <div class="d-flex align-items-center">
            <i class="fas fa-check-circle me-2"></i>
            <span>{{ session('success') }}</span>
        </div>
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
                        <h4 class="mb-0 fw-bold">{{ number_format($bookings->sum('total_amount'), 0, ',', '.') }}đ</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    <!-- Modern Bookings Table -->
    <div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th></th>
                        <th class="border-0 py-3 ps-4">Mã đơn</th>
                        <th class="border-0 py-3">Khách hàng</th>
                        <th class="border-0 py-3">Tour</th>
                        <th class="border-0 py-3">Ngày đi</th>
                        <th class="border-0 py-3">Tổng tiền</th>
                        <th class="border-0 py-3">Trạng thái</th>
                        <th class="border-0 py-3 text-end pe-4">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $booking)
                        <tr>
                            <td class="ps-4 fw-bold">#{{ $booking->id }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle bg-primary text-white me-2">
                                        {{ substr($booking->user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold">{{ $booking->user->name }}</div>
                                        <small class="text-muted">{{ $booking->user->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ Str::limit($booking->tour->title, 30) }}</td>
                            <td>
                                @if($booking->departure)
                                    {{ \Carbon\Carbon::parse($booking->departure->departure_date)->format('d/m/Y') }}
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td class="fw-bold text-primary">
                                {{ number_format($booking->total_amount, 0, ',', '.') }}đ
                            </td>
                            <td>
                                @if($booking->status == 'pending')
                                    <span class="badge bg-warning text-dark">Chờ xác nhận</span>
                                @elseif($booking->status == 'confirmed')
                                    <span class="badge bg-info text-dark">Đã xác nhận</span>
                                @elseif($booking->status == 'paid')
                                    <span class="badge bg-success">Đã thanh toán</span>
                                @elseif($booking->status == 'completed')
                                    <span class="badge bg-secondary">Hoàn thành</span>
                                @else
                                    <span class="badge bg-danger">Đã hủy</span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <div class="btn-group">
                                    <a href="{{ route('admin.bookings.show', $booking->id) }}" 
                                       class="btn btn-sm btn-outline-primary" 
                                       title="Xem chi tiết & Điều hành">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    <form action="{{ route('admin.bookings.destroy', $booking->id) }}" method="POST" 
                                          onsubmit="return confirm('Bạn có chắc muốn xóa đơn này?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                Không tìm thấy đơn hàng nào.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white border-0 py-3">
        {{ $bookings->links() }}
    </div>
</div>

<!-- Search and Filter -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-4">
                <h5 class="mb-0 fw-bold text-dark">
                    <i class="fas fa-search text-primary me-2"></i>
                    Tìm kiếm và lọc
                </h5>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('admin.bookings') }}" class="row g-3">
                    <div class="col-md-3">
                        <label for="search" class="form-label fw-semibold">Tìm kiếm</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="fas fa-search text-muted"></i>
                            </span>
                            <input type="text" class="form-control border-start-0" id="search" name="search" 
                                   value="{{ request('search') }}" placeholder="Mã đặt tour, tên khách hàng...">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label for="status" class="form-label fw-semibold">Trạng thái</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">Tất cả trạng thái</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ xác nhận</option>
                            <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="date_from" class="form-label fw-semibold">Từ ngày</label>
                        <input type="date" class="form-control" id="date_from" name="date_from" 
                               value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="date_to" class="form-label fw-semibold">Đến ngày</label>
                        <input type="date" class="form-control" id="date_to" name="date_to" 
                               value="{{ request('date_to') }}">
                    </div>
                    <div class="col-12">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search me-1"></i>
                                Tìm kiếm
                            </button>
                            <a href="{{ route('admin.bookings') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-1"></i>
                                Xóa bộ lọc
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modern Bookings Table -->
<x-admin.table 
    :headers="[
        ['key' => 'id', 'label' => 'Mã đặt tour', 'sortable' => true, 'component' => 'admin.table.booking-id'],
        ['key' => 'customer', 'label' => 'Khách hàng', 'sortable' => true, 'component' => 'admin.table.avatar'],
        ['key' => 'tour', 'label' => 'Tour', 'sortable' => true],
        ['key' => 'departure_date', 'label' => 'Ngày khởi hành', 'sortable' => true, 'component' => 'admin.table.date'],
        ['key' => 'guests', 'label' => 'Số khách', 'sortable' => true, 'component' => 'admin.table.guests'],
        ['key' => 'total_amount', 'label' => 'Tổng tiền', 'sortable' => true, 'component' => 'admin.table.price'],
        ['key' => 'status', 'label' => 'Trạng thái', 'sortable' => true, 'component' => 'admin.table.status-badge'],
        ['key' => 'created_at', 'label' => 'Ngày đặt', 'sortable' => true, 'component' => 'admin.table.date']
    ]"
    :data="$bookings->map(function($booking) {
        return [
            'id' => $booking->id,
            'customer' => [
                'name' => $booking->user->name,
                'email' => $booking->user->email
            ],
            'tour' => $booking->tour->title,
            'departure_date' => $booking->departure->departure_date ?? 'N/A',
            'guests' => [
                'adults' => $booking->adults,
                'children' => $booking->children,
                'infants' => $booking->infants
            ],
            'total_amount' => $booking->total_amount,
            'status' => $booking->status,
            'created_at' => $booking->created_at
        ];
    })"
    :actions="[
        ['action' => 'view', 'icon' => 'fas fa-eye', 'class' => 'btn-primary', 'title' => 'Xem chi tiết'],
        ['action' => 'status', 'icon' => 'fas fa-edit', 'class' => 'btn-info', 'title' => 'Cập nhật trạng thái'],
        ['action' => 'invoice', 'icon' => 'fas fa-file-invoice', 'class' => 'btn-success', 'title' => 'In hóa đơn'],
        ['action' => 'download_pdf', 'icon' => 'fas fa-download', 'class' => 'btn-primary', 'title' => 'Tải PDF'],
        ['action' => 'delete', 'icon' => 'fas fa-trash', 'class' => 'btn-danger', 'title' => 'Xóa']
    ]"
    :searchable="true"
    :sortable="true"
    :filterable="true"
    :pagination="$bookings"
    empty-message="Chưa có đặt tour nào"
    id="bookings-table"
>
    <!-- Custom Filters -->
    <x-slot name="filters">
        <div class="filter-grid">
            <div class="filter-group">
                <label class="filter-label">Trạng thái</label>
                <select name="status" class="filter-select">
                    <option value="">Tất cả trạng thái</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ xác nhận</option>
                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                </select>
            </div>
            
            <div class="filter-group">
                <label class="filter-label">Từ ngày</label>
                <input type="date" name="date_from" class="filter-input" value="{{ request('date_from') }}">
            </div>
            
            <div class="filter-group">
                <label class="filter-label">Đến ngày</label>
                <input type="date" name="date_to" class="filter-input" value="{{ request('date_to') }}">
            </div>
            
            <div class="filter-group">
                <label class="filter-label">Khoảng tiền</label>
                <div class="price-range">
                    <input type="number" name="min_amount" class="filter-input" placeholder="Từ" value="{{ request('min_amount') }}">
                    <span class="range-separator">-</span>
                    <input type="number" name="max_amount" class="filter-input" placeholder="Đến" value="{{ request('max_amount') }}">
                </div>
            </div>
        </div>
        
        <div class="filter-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search"></i> Áp dụng bộ lọc
            </button>
            <a href="{{ route('admin.bookings') }}" class="btn btn-secondary">
                <i class="fas fa-times"></i> Xóa bộ lọc
            </a>
        </div>
    </x-slot>
</x-admin.table>

@endsection

@section('styles')
<style>
    .card {
        border-radius: 12px;
        transition: all 0.3s ease;
    }
    
    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important;
    }
    
    .table tbody tr:hover {
        background-color: #f8f9fa;
    }
    
    .badge {
        border-radius: 8px;
        font-weight: 500;
    }
    
    .bg-opacity-10 {
        background-color: rgba(var(--bs-primary-rgb), 0.1) !important;
    }
    
    .text-primary {
        color: #0EA5E9 !important;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #0EA5E9 0%, #38BDF8 100%);
        border: none;
        border-radius: 8px;
    }
    
    .btn-primary:hover {
        background: linear-gradient(135deg, #0284C7 0%, #0EA5E9 100%);
        transform: translateY(-1px);
    }
    
    .shadow-sm {
        box-shadow: 0 2px 4px rgba(0,0,0,0.08) !important;
    }
    
    .border-0 {
        border: none !important;
    }
    
    .fw-bold {
        font-weight: 700 !important;
    }
    
    .fw-semibold {
        font-weight: 600 !important;
    }
    
    .btn-group .btn {
        border-radius: 8px;
        margin: 0 2px;
    }
    
    .form-select {
        border-radius: 8px;
        font-size: 0.875rem;
    }
    
    .form-select:focus {
        border-color: #0EA5E9;
        box-shadow: 0 0 0 0.2rem rgba(14, 165, 233, 0.25);
    }
    
    /* Notification styles */
    .notification {
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 15px 20px;
        border-radius: 8px;
        color: white;
        font-weight: 500;
        z-index: 9999;
        transform: translateX(100%);
        transition: transform 0.3s ease;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
    
    .notification.show {
        transform: translateX(0);
    }
    
    .notification-success {
        background: linear-gradient(135deg, #10b981, #059669);
    }
    
    .notification-error {
        background: linear-gradient(135deg, #ef4444, #dc2626);
    }
    
    .notification i {
        margin-right: 8px;
    }
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle booking actions - Only for bookings table
    const bookingsTable = document.getElementById('bookings-table');
    if (!bookingsTable) return;
    
    // Find action buttons only within bookings table
    const actionButtons = bookingsTable.querySelectorAll('[data-action]');
    
    actionButtons.forEach(btn => {
        // Remove any existing listeners first
        const newBtn = btn.cloneNode(true);
        btn.parentNode.replaceChild(newBtn, btn);
        
        newBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const action = this.dataset.action;
            const bookingId = this.dataset.id;
            
            console.log('Action button clicked:', { action, bookingId, button: this, url: `/admin/bookings/${bookingId}` });
            
            if (!bookingId || bookingId === 'undefined' || bookingId === 'null') {
                console.error('Booking ID not found in button:', this, 'data-id:', this.dataset.id);
                showAlert('danger', 'Không tìm thấy ID đặt tour. Vui lòng thử lại.');
                return;
            }
            
            switch(action) {
                case 'view':
                    window.location.href = `/admin/bookings/${bookingId}`;
                    break;
                    
                case 'status':
                    if (typeof updateBookingStatus === 'function') {
                        updateBookingStatus(parseInt(bookingId));
                    } else {
                        console.error('updateBookingStatus function not found');
                        showAlert('danger', 'Hàm cập nhật trạng thái không tồn tại.');
                    }
                    break;
                    
                case 'invoice':
                    if (typeof generateInvoice === 'function') {
                        generateInvoice(parseInt(bookingId), this);
                    } else {
                        console.error('generateInvoice function not found');
                        showAlert('danger', 'Hàm tạo hóa đơn không tồn tại.');
                    }
                    break;
                    
                case 'download_pdf':
                    if (typeof downloadInvoice === 'function') {
                        downloadInvoice(parseInt(bookingId), this);
                    } else {
                        console.error('downloadInvoice function not found');
                        showAlert('danger', 'Hàm tải PDF không tồn tại.');
                    }
                    break;
                    
                case 'delete':
                    if (confirm('Bạn có chắc chắn muốn xóa đặt tour này?')) {
                        if (typeof deleteBooking === 'function') {
                            deleteBooking(parseInt(bookingId));
                        } else {
                            console.error('deleteBooking function not found');
                            showAlert('danger', 'Hàm xóa đặt tour không tồn tại.');
                        }
                    }
                    break;
                    
                default:
                    console.warn('Unknown action:', action);
                    showAlert('warning', `Hành động "${action}" chưa được hỗ trợ.`);
            }
        });
    });
});

// Show alert message - Must be defined first
function showAlert(type, message) {
    const alertContainer = document.createElement('div');
    alertContainer.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    alertContainer.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    alertContainer.innerHTML = `
        <div class="d-flex align-items-center">
            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'warning' ? 'exclamation-triangle' : type === 'info' ? 'info-circle' : 'exclamation-circle'} me-2"></i>
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

// Move functions outside DOMContentLoaded so they're globally accessible
function updateBookingStatus(bookingId) {
    // Tạo modal để chọn trạng thái mới
    const statusOptions = [
        { value: 'pending', label: 'CHỜ XỬ LÝ', class: 'warning' },
        { value: 'confirmed', label: 'ĐÃ XÁC NHẬN', class: 'success' },
        { value: 'cancelled', label: 'ĐÃ HỦY', class: 'danger' },
        { value: 'completed', label: 'HOÀN THÀNH', class: 'info' }
    ];
    
    let modalHtml = `
        <div class="modal fade" id="statusModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Cập nhật trạng thái booking</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Chọn trạng thái mới cho booking #${bookingId}:</p>
                        <div class="d-grid gap-2">
    `;
    
    statusOptions.forEach(option => {
        modalHtml += `
            <button type="button" class="btn btn-${option.class}" 
                    onclick="confirmStatusUpdate(${bookingId}, '${option.value}')">
                ${option.label}
            </button>
        `;
    });
    
    modalHtml += `
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Thêm modal vào body
    document.body.insertAdjacentHTML('beforeend', modalHtml);
    
    // Hiển thị modal
    const modal = new bootstrap.Modal(document.getElementById('statusModal'));
    modal.show();
    
    // Xóa modal khi đóng
    document.getElementById('statusModal').addEventListener('hidden.bs.modal', function() {
        this.remove();
    });
}

function confirmStatusUpdate(bookingId, newStatus) {
    const statusLabels = {
        'pending': 'Chờ xử lý',
        'confirmed': 'Đã xác nhận', 
        'cancelled': 'Đã hủy',
        'completed': 'Hoàn thành'
    };
    
    if (confirm(`Bạn có chắc chắn muốn cập nhật trạng thái thành "${statusLabels[newStatus]}"?`)) {
        // Đóng modal trước
        const modal = bootstrap.Modal.getInstance(document.getElementById('statusModal'));
        if (modal) modal.hide();
        
        // Disable buttons while processing
        const buttons = document.querySelectorAll(`[onclick*="confirmStatusUpdate(${bookingId}"]`);
        buttons.forEach(btn => {
            btn.disabled = true;
            const originalHTML = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang xử lý...';
            btn.dataset.originalHTML = originalHTML;
        });
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (!csrfToken) {
            showAlert('danger', 'CSRF token not found. Vui lòng refresh trang và thử lại.');
            // Re-enable buttons on error
            buttons.forEach(btn => {
                btn.disabled = false;
                if (btn.dataset.originalHTML) {
                    btn.innerHTML = btn.dataset.originalHTML;
                }
            });
            return;
        }
        
        fetch(`/admin/bookings/${bookingId}`, {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': csrfToken.content,
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                status: newStatus
            })
        })
        .then(response => {
            console.log('Response status:', response.status);
            
            // Get response content type
            const contentType = response.headers.get('content-type');
            console.log('Content-Type:', contentType);
            
            if (!response.ok) {
                // Try to get error message from response
                return response.text().then(text => {
                    console.error('Error response:', text);
                    try {
                        const err = JSON.parse(text);
                        return Promise.reject(new Error(err.message || 'Lỗi cập nhật trạng thái'));
                    } catch (e) {
                        return Promise.reject(new Error(text || 'Lỗi cập nhật trạng thái'));
                    }
                });
            }
            
            // Parse JSON response
            if (contentType && contentType.includes('application/json')) {
                return response.json();
            } else {
                // If not JSON, it might be a redirect HTML
                return response.text().then(text => {
                    console.warn('Received non-JSON response:', text);
                    return { success: false, message: 'Nhận được phản hồi không hợp lệ từ server' };
                });
            }
        })
        .then(data => {
            if (data.success) {
                showAlert('success', data.message || 'Đặt tour đã được cập nhật thành công!');
                setTimeout(() => {
                    location.reload();
                }, 1000);
            } else {
                showAlert('danger', data.message || 'Có lỗi xảy ra khi cập nhật trạng thái!');
                // Re-enable buttons on error
                buttons.forEach(btn => {
                    btn.disabled = false;
                    if (btn.dataset.originalHTML) {
                        btn.innerHTML = btn.dataset.originalHTML;
                    }
                });
            }
        })
        .catch(error => {
            console.error('Error updating status:', error);
            const errorMessage = error.message || 'Có lỗi xảy ra khi cập nhật trạng thái!';
            showAlert('danger', errorMessage);
            // Re-enable buttons on error
            buttons.forEach(btn => {
                btn.disabled = false;
                if (btn.dataset.originalHTML) {
                    btn.innerHTML = btn.dataset.originalHTML;
                }
            });
        });
    }
}

function deleteBooking(bookingId) {
    console.log('deleteBooking called with bookingId:', bookingId);
    
    if (!bookingId) {
        showAlert('danger', 'Không tìm thấy ID đặt tour để xóa.');
        return;
    }
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) {
        showAlert('danger', 'CSRF token not found. Vui lòng refresh trang và thử lại.');
        return;
    }
    
    const url = `/admin/bookings/${bookingId}`;
    console.log('Deleting booking at URL:', url);
    
    fetch(url, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': csrfToken.content,
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => Promise.reject(err));
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            showAlert('success', data.message || 'Đặt tour đã được xóa thành công!');
            setTimeout(() => {
                location.reload();
            }, 1000);
        } else {
            showAlert('danger', data.message || 'Có lỗi xảy ra khi xóa booking!');
        }
    })
    .catch(error => {
        console.error('Error deleting booking:', error);
        showAlert('danger', error.message || 'Có lỗi xảy ra khi xóa booking!');
    });
}

// Initialize tooltips after DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    if (typeof bootstrap !== 'undefined') {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }
});

// Generate Invoice PDF - Must be defined outside DOMContentLoaded
async function generateInvoice(bookingId, buttonElement = null) {
    let button = null;
    let originalContent = null;
    
    try {
        // Get button element safely
        if (buttonElement) {
            button = buttonElement;
        } else if (typeof event !== 'undefined' && event && event.target) {
            button = event.target.closest('button');
        } else {
            // Find button by data-action and data-id
            button = document.querySelector(`button[data-action="invoice"][data-id="${bookingId}"]`);
        }
        
        if (!button) {
            console.error('Button not found for booking:', bookingId);
            showAlert('danger', 'Không tìm thấy nút. Vui lòng thử lại.');
            return;
        }

        originalContent = button.innerHTML;
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        button.disabled = true;

        // Get CSRF token safely
        const csrfTokenElement = document.querySelector('meta[name="csrf-token"]');
        if (!csrfTokenElement) {
            throw new Error('CSRF token not found');
        }
        const csrfToken = csrfTokenElement.getAttribute('content');

        const response = await fetch(`/web/invoices/booking/${bookingId}/pdf`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        });

        if (!response.ok) {
            const errorText = await response.text();
            throw new Error(`HTTP error! status: ${response.status} - ${errorText}`);
        }

        const data = await response.json();

        if (data.success && data.data && data.data.download_url) {
            // Open PDF in new tab
            const newWindow = window.open(data.data.download_url, '_blank');
            
            if (newWindow) {
                showAlert('success', 'PDF hóa đơn đã được tạo thành công!');
            } else {
                showAlert('warning', 'Popup bị chặn. Vui lòng cho phép popup và thử lại.');
            }
        } else {
            showAlert('danger', 'Lỗi: ' + (data.message || 'Không thể tạo PDF'));
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
async function downloadInvoice(bookingId, buttonElement = null) {
    let button = null;
    let originalContent = null;
    
    try {
        // Get button element safely
        if (buttonElement) {
            button = buttonElement;
        } else if (typeof event !== 'undefined' && event && event.target) {
            button = event.target.closest('button');
        } else {
            // Find button by data-action and data-id
            button = document.querySelector(`button[data-action="download_pdf"][data-id="${bookingId}"]`);
        }
        
        if (!button) {
            console.error('Button not found for booking:', bookingId);
            showAlert('danger', 'Không tìm thấy nút. Vui lòng thử lại.');
            return;
        }

        originalContent = button.innerHTML;
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        button.disabled = true;

        // Get CSRF token safely
        const csrfTokenElement = document.querySelector('meta[name="csrf-token"]');
        if (!csrfTokenElement) {
            throw new Error('CSRF token not found');
        }
        const csrfToken = csrfTokenElement.getAttribute('content');

        const response = await fetch(`/web/invoices/booking/${bookingId}/pdf`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        });

        if (response.ok) {
            const data = await response.json();
            if (data.success && data.data && data.data.download_url) {
                // Download the PDF file
                const link = document.createElement('a');
                link.href = data.data.download_url;
                link.download = data.data.file_name || `invoice_${bookingId}.html`;
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                
                showAlert('success', 'PDF hóa đơn đã được tải xuống!');
            } else {
                showAlert('danger', 'Lỗi: ' + (data.message || 'Không thể tải PDF'));
            }
        } else {
            const errorText = await response.text();
            showAlert('danger', 'Lỗi: ' + errorText);
        }
    } catch (error) {
        console.error('Error downloading invoice:', error);
        showAlert('danger', 'Lỗi: ' + error.message);
    } finally {
        if (button && originalContent) {
            button.innerHTML = originalContent;
            button.disabled = false;
        }
    }
}

</script>
@endsection

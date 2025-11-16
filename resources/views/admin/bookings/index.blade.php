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
    @if (session('success'))
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
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ xác nhận
                                </option>
                                <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Đã xác
                                    nhận</option>

                                <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Đã thanh toán
                                </option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Đã hủy
                                </option>
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
    <x-admin.table :headers="[
        ['key' => 'id', 'label' => 'Mã đặt tour', 'sortable' => true, 'component' => 'admin.table.booking-id'],
        ['key' => 'customer', 'label' => 'Khách hàng', 'sortable' => true, 'component' => 'admin.table.avatar'],
        ['key' => 'tour', 'label' => 'Tour', 'sortable' => true],
        ['key' => 'departure_date', 'label' => 'Ngày khởi hành', 'sortable' => true, 'component' => 'admin.table.date'],
        ['key' => 'guests', 'label' => 'Số khách', 'sortable' => true, 'component' => 'admin.table.guests'],
        ['key' => 'total_amount', 'label' => 'Tổng tiền', 'sortable' => true, 'component' => 'admin.table.price'],
        ['key' => 'status', 'label' => 'Trạng thái', 'sortable' => true, 'component' => 'admin.table.status-badge'],
        ['key' => 'created_at', 'label' => 'Ngày đặt', 'sortable' => true, 'component' => 'admin.table.date'],
    ]" :data="$bookings->map(function ($booking) {
        return [
            'id' => $booking->id,
            'customer' => [
                'name' => $booking->user->name,
                'email' => $booking->user->email,
            ],
            'tour' => $booking->tour->title,
            'departure_date' => $booking->departure->departure_date ?? 'N/A',
            'guests' => [
                'adults' => $booking->adults,
                'children' => $booking->children,
                'infants' => $booking->infants,
            ],
            'total_amount' => $booking->total_amount,
            'status' => $booking->status,
            'created_at' => $booking->created_at,
        ];
    })" :actions="[
        ['action' => 'view', 'icon' => 'fas fa-eye', 'class' => 'btn-primary', 'title' => 'Xem chi tiết'],
        ['action' => 'status', 'icon' => 'fas fa-edit', 'class' => 'btn-info', 'title' => 'Cập nhật trạng thái'],
        ['action' => 'email', 'icon' => 'fas fa-envelope', 'class' => 'btn-warning', 'title' => 'Gửi email'],
        ['action' => 'delete', 'icon' => 'fas fa-trash', 'class' => 'btn-danger', 'title' => 'Xóa'],
    ]" :searchable="true" :sortable="true"
        :filterable="true" :pagination="$bookings" empty-message="Chưa có đặt tour nào" id="bookings-table">
        <!-- Custom Filters -->
        <x-slot name="filters">
            <div class="filter-grid">
                <div class="filter-group">
                    <label class="filter-label">Trạng thái</label>
                    <select name="status" class="filter-select">
                        <option value="">Tất cả trạng thái</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ xác nhận
                        </option>
                        <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Đã xác nhận
                        </option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Hoàn thành
                        </option>
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
                        <input type="number" name="min_amount" class="filter-input" placeholder="Từ"
                            value="{{ request('min_amount') }}">
                        <span class="range-separator">-</span>
                        <input type="number" name="max_amount" class="filter-input" placeholder="Đến"
                            value="{{ request('max_amount') }}">
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
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1) !important;
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
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08) !important;
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

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // Đảm bảo meta[name="csrf-token"] tồn tại trong layout của bạn
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
            if (!csrfToken) {
                console.error(
                    'Lỗi: Không tìm thấy CSRF Token. Hãy thêm <meta name="csrf-token" content="{{ csrf_token() }}"> vào layout chính.'
                    );
            }

            async function sendRequest(url, method, data = null) {
                try {
                    const options = {
                        method: method,
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        }
                    };
                    if (data) {
                        options.body = JSON.stringify(data);
                    }

                    const response = await fetch(url, options);
                    const responseData = await response.json();

                    if (!response.ok) {
                        throw new Error(responseData.message || `Lỗi ${response.status}`);
                    }

                    return responseData;

                } catch (error) {
                    console.error('Fetch error:', error);
                    showNotification(error.message || 'Có lỗi xảy ra!', 'error');
                    return null;
                }
            }

            // Xử lý các nút bấm [data-action]
            const actionButtons = document.querySelectorAll('[data-action]');
            actionButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    let bookingId = this.dataset.id;
                    if (!bookingId) {
                        const row = this.closest('tr');
                        if (row) {
                            const idCell = row.querySelector('[data-key="id"]');
                            if (idCell) {
                                bookingId = idCell.textContent.trim().replace('#', '');
                            }
                        }
                    }

                    if (!bookingId) {
                        console.error('Không tìm thấy booking ID cho nút này.');
                        return;
                    }

                    const action = this.dataset.action;
                    switch (action) {
                        case 'view':
                            window.location.href = `/admin/bookings/${bookingId}`;
                            break;

                        case 'status':
                            // Mở Modal chọn trạng thái
                            showStatusModal(bookingId);
                            break;

                        case 'email':
                            // (Giữ nguyên logic của bạn, cần route /send-email)
                            sendBookingEmail(bookingId);
                            break;

                        case 'delete':
                            if (confirm('Bạn có chắc chắn muốn XÓA VĨNH VIỄN đặt tour này?')) {
                                deleteBooking(bookingId);
                            }
                            break;
                    }
                });
            });

            // HÀM MỚI: Hiển thị Modal
            function showStatusModal(bookingId) {
                const statusOptions = [{
                        action: 'confirm',
                        label: 'Xác nhận đơn',
                        class: 'success',
                        route: `/admin/bookings/${bookingId}/confirm`
                    },
                    {
                        action: 'markAsPaid',
                        label: 'Đánh dấu Đã thanh toán',
                        class: 'primary',
                        route: `/admin/bookings/${bookingId}/mark-as-paid`
                    },
                    {
                        action: 'cancel',
                        label: 'Hủy đơn',
                        class: 'warning',
                        route: `/admin/bookings/${bookingId}/cancel`
                    }
                ];

                let modalHtml = `
            <div class="modal fade" id="statusModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Cập nhật trạng thái booking #${bookingId}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <p>Chọn hành động bạn muốn thực hiện:</p>
                            <div class="list-group">
        `;

                statusOptions.forEach(option => {
                    modalHtml += `
                <button type="button" class="list-group-item list-group-item-action" 
                        onclick="confirmAction('${option.label}', '${option.route}')">
                    <span class="badge bg-${option.class} me-2">${option.label}</span>
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

                // Xóa modal cũ nếu có
                document.getElementById('statusModal')?.remove();
                document.body.insertAdjacentHTML('beforeend', modalHtml);
                const modal = new bootstrap.Modal(document.getElementById('statusModal'));
                modal.show();

                // Xóa modal khỏi DOM khi đóng
                document.getElementById('statusModal').addEventListener('hidden.bs.modal', function() {
                    this.remove();
                });
            }

            window.confirmAction = async function(label, route) {
                if (confirm(`Bạn có chắc chắn muốn: "${label}"?`)) {
                    const data = await sendRequest(route, 'POST');

                    if (data && data.success) {
                        showNotification(data.message, 'success');
                        location.reload(); // Tải lại trang để cập nhật
                    }
                }

                const modal = bootstrap.Modal.getInstance(document.getElementById('statusModal'));
                if (modal) modal.hide();
            }

            async function sendBookingEmail(bookingId) {
                if (confirm('Bạn có chắc chắn muốn gửi email cho khách hàng?')) {
                    // (Lưu ý: Bạn cần tạo route POST /admin/bookings/{id}/send-email trong web.php)
                    const data = await sendRequest(`/admin/bookings/${bookingId}/send-email`, 'POST');
                    if (data && data.success) {
                        showNotification(data.message, 'success');
                    }
                }
            }

            // HÀM XÓA BOOKING (Đã sửa)
            async function deleteBooking(bookingId) {
                // Gọi route DELETE
                const data = await sendRequest(`/admin/bookings/${bookingId}`, 'DELETE');
                if (data && data.success) {
                    showNotification(data.message, 'success');
                    location.reload(); // Tải lại trang
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
        });
    </script>
@endpush

@section('scripts')
    <script>
        // Initialize tooltips
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof bootstrap !== 'undefined') {
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                });
            }
        });

        // Generate Invoice PDF
        async function generateInvoice(bookingId, buttonElement = null) {
            let button = null;
            let originalContent = null;

            try {
                button = buttonElement || event.target.closest('button');
                originalContent = button.innerHTML;
                button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                button.disabled = true;

                console.log('Generating invoice for booking:', bookingId);

                // First test simple debug route
                const debugResponse = await fetch(`/debug-invoice-simple/${bookingId}`);
                const debugData = await debugResponse.json();
                console.log('Debug response:', debugData);

                if (!debugData.success) {
                    throw new Error('Debug failed: ' + debugData.message);
                }

                const response = await fetch(`/web/invoices/booking/${bookingId}/pdf`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    }
                });

                console.log('Response status:', response.status);

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();
                console.log('Response data:', data);

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
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    }
                });

                if (response.ok) {
                    const data = await response.json();
                    if (data.success) {
                        // Download the PDF file
                        const link = document.createElement('a');
                        link.href = data.data.download_url;
                        link.download = data.data.file_name;
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);

                        showAlert('success', 'PDF hóa đơn đã được tải xuống!');
                    } else {
                        showAlert('danger', 'Lỗi: ' + data.message);
                    }
                } else {
                    const errorText = await response.text();
                    showAlert('danger', 'Lỗi: ' + errorText);
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

        // Show alert message
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
    </script>
@endsection

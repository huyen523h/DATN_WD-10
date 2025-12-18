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
                            <h4 class="mb-0 fw-bold">{{ $bookings->count() }}</h4>
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

    <!-- Grouped Bookings by Departure Date -->
    @if(isset($groupedBookings) && $groupedBookings->count() > 0)
        @foreach($groupedBookings as $group)
            <div class="card border-0 shadow-sm mb-4">
                <!-- Group Header -->
                <div class="card-header bg-primary text-white py-3">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h5 class="mb-0">
                                <i class="fas fa-calendar-alt me-2"></i>
                                Ngày khởi hành: 
                                @if($group['date'])
                                    <strong>{{ $group['date']->format('d/m/Y') }}</strong>
                                @else
                                    <strong>Chưa có ngày</strong>
                                @endif
                            </h5>
                            @if(!empty($group['tour']))
                                <div class="mt-1">
                                    <small class="text-white-50">Tour:</small>
                                    <strong>{{ $group['tour']->title }}</strong>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-6 text-end">
                            <div class="d-flex justify-content-end gap-3">
                                <div class="text-white-50">
                                    <small><i class="fas fa-users me-1"></i> {{ $group['count'] }} đơn</small>
                                </div>
                                <div class="text-white-50">
                                    <small><i class="fas fa-user-friends me-1"></i> {{ $group['total_guests'] }} khách</small>
                                </div>
                                <div class="text-white">
                                    <strong>{{ number_format($group['total_amount'], 0, ',', '.') }}đ</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Group Summary -->
                <div class="card-body bg-light py-3">
                    <div class="row align-items-center">
                        <div class="col-md-2 text-center">
                            <small class="text-muted d-block">Người lớn</small>
                            <strong>{{ $group['total_adults'] }}</strong>
                        </div>
                        <div class="col-md-2 text-center">
                            <small class="text-muted d-block">Trẻ em</small>
                            <strong>{{ $group['total_children'] }}</strong>
                        </div>
                        <div class="col-md-2 text-center">
                            <small class="text-muted d-block">Em bé</small>
                            <strong>{{ $group['total_infants'] }}</strong>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex gap-2 flex-wrap">
                                <span class="badge bg-success">
                                    <i class="fas fa-check-circle me-1"></i>
                                    {{ $group['group_confirmed'] ? 'Đã chốt' : 'Chưa chốt' }}: {{ $group['group_confirmed'] ? $group['confirmed_guests_count'] : $group['total_guests'] }} khách
                                </span>
                                <button class="btn btn-sm btn-outline-success"
                                    onclick="openConfirmGroupModal('{{ $group['date'] ? $group['date']->format('Y-m-d') : 'no-date' }}', {{ $group['group_confirmed'] ? $group['confirmed_guests_count'] : $group['total_guests'] }})">
                                    <i class="fas fa-sync-alt me-1"></i> Cập nhật chốt đoàn
                                </button>
                                
                                @if($group['guide'])
                                    <span class="badge bg-info">
                                        <i class="fas fa-user-tie me-1"></i>HDV: {{ $group['guide']->name }}
                                    </span>
                                @else
                                    <button class="btn btn-sm btn-info" onclick="openAssignGuideModal('{{ $group['date'] ? $group['date']->format('Y-m-d') : 'no-date' }}', {{ $group['tour_id'] ?? 'null' }})">
                                        <i class="fas fa-user-tie me-1"></i> Gán HDV
                                    </button>
                                @endif
                                
                                @if($group['vehicle_type'])
                                    <span class="badge bg-warning text-dark">
                                        <i class="fas fa-bus me-1"></i>Xe {{ $group['vehicle_type'] }} chỗ
                                        @if($group['vehicle'] && $group['vehicle']->license_plate)
                                            - {{ $group['vehicle']->license_plate }}
                                        @elseif($group['vehicle_details'])
                                            - {{ $group['vehicle_details'] }}
                                        @endif
                                    </span>
                                @else
                                    <button class="btn btn-sm btn-warning" onclick="openAssignVehicleModal('{{ $group['date'] ? $group['date']->format('Y-m-d') : 'no-date' }}', {{ $group['tour_id'] ?? 'null' }})">
                                        <i class="fas fa-bus me-1"></i> Gán xe
                                    </button>
                                @endif
                                
                                <button class="btn btn-sm btn-primary" onclick="openSendPreTourInfoModal('{{ $group['date'] ? $group['date']->format('Y-m-d') : 'no-date' }}')">
                                    <i class="fas fa-paper-plane me-1"></i> Gửi thông tin
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Bookings Table -->
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0 py-3 px-3">MÃ ĐẶT TOUR</th>
                                    <th class="border-0 py-3 px-3">KHÁCH HÀNG</th>
                                    <th class="border-0 py-3 px-3">TOUR</th>
                                    <th class="border-0 py-3 px-3">SỐ KHÁCH</th>
                                    <th class="border-0 py-3 px-3">TỔNG TIỀN</th>
                                    <th class="border-0 py-3 px-3">TRẠNG THÁI</th>
                                    <th class="border-0 py-3 px-3">NGÀY ĐẶT</th>
                                    <th class="border-0 py-3 px-3 text-end">THAO TÁC</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($group['bookings'] as $booking)
                                    <tr>
                                        <td class="px-3">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-building text-primary me-2"></i>
                                                <strong>#{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}</strong>
                                                <span class="badge bg-secondary ms-2">BOOKING</span>
                                            </div>
                                        </td>
                                        <td class="px-3">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-circle bg-purple text-white me-2" style="width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; background-color: #6f42c1;">
                                                    {{ strtoupper(substr($booking->user->name, 0, 2)) }}
                                                </div>
                                                <div>
                                                    <div class="fw-bold">{{ $booking->user->name }}</div>
                                                    <small class="text-muted">{{ $booking->user->email }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-3">
                                            <div class="text-truncate" style="max-width: 300px;" title="{{ $booking->tour->title }}">
                                                {{ $booking->tour->title }}
                                            </div>
                                        </td>
                                        <td class="px-3">
                                            <div>
                                                <span class="badge bg-info">
                                                    <i class="fas fa-user me-1"></i>{{ $booking->adults }} người lớn
                                                </span>
                                                @if($booking->children > 0)
                                                    <br><span class="badge bg-info mt-1">
                                                        <i class="fas fa-child me-1"></i>{{ $booking->children }} trẻ em
                                                    </span>
                                                @endif
                                                @if($booking->infants > 0)
                                                    <br><span class="badge bg-info mt-1">
                                                        <i class="fas fa-baby me-1"></i>{{ $booking->infants }} em bé
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-3 fw-bold text-success">
                                            {{ number_format($booking->total_amount, 0, ',', '.') }} VNĐ
                                        </td>
                                        <td class="px-3">
                                            @if($booking->status == 'pending')
                                                <span class="badge bg-warning text-dark">
                                                    <i class="fas fa-clock me-1"></i>Chờ xác nhận
                                                </span>
                                            @elseif($booking->status == 'confirmed')
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check-circle me-1"></i>Đã xác nhận
                                                </span>
                                            @elseif($booking->status == 'paid')
                                                <span class="badge bg-success">
                                                    <i class="fas fa-money-bill-wave me-1"></i>Đã thanh toán
                                                </span>
                                            @elseif($booking->status == 'completed')
                                                <span class="badge bg-secondary">Hoàn thành</span>
                                            @else
                                                <span class="badge bg-danger">Đã hủy</span>
                                            @endif
                                        </td>
                                        <td class="px-3">
                                            <i class="fas fa-calendar text-muted me-1"></i>
                                            {{ $booking->created_at->format('d/m/Y') }}
                                        </td>
                                        <td class="px-3 text-end">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.bookings.show', $booking->id) }}" 
                                                   class="btn btn-sm btn-outline-primary" 
                                                   title="Xem chi tiết">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <form action="{{ route('admin.bookings.destroy', $booking->id) }}" 
                                                      method="POST" 
                                                      class="d-inline"
                                                      onsubmit="return confirm('Bạn có chắc muốn xóa đơn này?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Xóa">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <i class="fas fa-calendar-times fa-4x text-muted mb-4"></i>
                <h4>Chưa có đặt tour nào</h4>
                <p class="text-muted">Khách hàng chưa đặt tour nào</p>
            </div>
        </div>
    @endif
@endsection

@section('styles')
    <style>
        /* Fix lỗi lệch cột trong bảng - tham khảo từ quản lý khởi hành */
        .table {
            table-layout: fixed;
            width: 100%;
            border-collapse: collapse;
        }

        /* Đảm bảo th và td có cùng padding và border - override Bootstrap classes */
        .table th,
        .table td {
            text-align: left;
            vertical-align: middle;
            white-space: normal;
            word-wrap: break-word;
            padding: 12px 15px !important;
            border: none !important;
            box-sizing: border-box;
        }

        /* Override các class Bootstrap padding */
        .table th.py-3,
        .table th.px-3,
        .table td.py-3,
        .table td.px-3 {
            padding: 12px 15px !important;
        }

        /* Xóa hiệu ứng ảo gây lệch */
        .table tr::before,
        .table tr::after {
            content: none !important;
        }

        /* Đảm bảo thead và tbody có cùng cấu trúc */
        .table thead th {
            padding: 12px 15px !important;
            border: none !important;
        }

        .table tbody td {
            padding: 12px 15px !important;
            border: none !important;
        }

        /* Đảm bảo không có margin hoặc spacing khác biệt */
        .table thead,
        .table tbody {
            margin: 0;
            padding: 0;
        }

        /* Cột MÃ ĐẶT TOUR */
        .table th:nth-child(1),
        .table td:nth-child(1) {
            width: 12%;
        }

        /* Cột KHÁCH HÀNG */
        .table th:nth-child(2),
        .table td:nth-child(2) {
            width: 18%;
        }

        /* Cột TOUR */
        .table th:nth-child(3),
        .table td:nth-child(3) {
            width: 20%;
        }

        /* Cột SỐ KHÁCH */
        .table th:nth-child(4),
        .table td:nth-child(4) {
            width: 12%;
        }

        /* Cột TỔNG TIỀN */
        .table th:nth-child(5),
        .table td:nth-child(5) {
            width: 12%;
            text-align: right;
        }

        /* Cột TRẠNG THÁI */
        .table th:nth-child(6),
        .table td:nth-child(6) {
            width: 12%;
            text-align: center;
        }

        /* Cột NGÀY ĐẶT */
        .table th:nth-child(7),
        .table td:nth-child(7) {
            width: 10%;
            text-align: center;
        }

        /* Cột THAO TÁC */
        .table th:nth-child(8),
        .table td:nth-child(8) {
            width: 12%;
            text-align: right;
        }

        /* Chống lệch do badge hoặc nút */
        .table td span.badge,
        .table td .btn {
            vertical-align: middle !important;
            display: inline-block;
        }

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

        // B2: Chốt đoàn
        function openConfirmGroupModal(departureDate, totalGuests) {
            const modalHtml = `
                <div class="modal fade" id="confirmGroupModal" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Chốt đoàn</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <form id="confirmGroupForm">
                                <div class="modal-body">
                                    <input type="hidden" name="departure_date" value="${departureDate}">
                                    <div class="mb-3">
                                        <label class="form-label">Tổng số khách hiện tại: <strong>${totalGuests}</strong></label>
                                    </div>
                                    <div class="mb-3">
                                        <label for="confirmed_guests_count" class="form-label">Số khách chốt đoàn *</label>
                                        <input type="number" class="form-control" id="confirmed_guests_count" name="confirmed_guests_count" min="1" value="${totalGuests}" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                    <button type="submit" class="btn btn-success">Chốt đoàn</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            `;
            
            document.body.insertAdjacentHTML('beforeend', modalHtml);
            const modal = new bootstrap.Modal(document.getElementById('confirmGroupModal'));
            modal.show();
            
            document.getElementById('confirmGroupForm').addEventListener('submit', async function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                
                try {
                    const response = await fetch('{{ route("admin.bookings.confirm-group") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: formData
                    });
                    
                    const data = await response.json();
                    if (data.success) {
                        showAlert('success', data.message);
                        modal.hide();
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        showAlert('danger', data.message || 'Có lỗi xảy ra');
                    }
                } catch (error) {
                    showAlert('danger', 'Lỗi: ' + error.message);
                }
            });
            
            document.getElementById('confirmGroupModal').addEventListener('hidden.bs.modal', function() {
                this.remove();
            });
        }

        // B3: Gán HDV
        async function openAssignGuideModal(departureDate, tourId) {
            // Hiển thị modal với loading state
            const modalHtml = `
                <div class="modal fade" id="assignGuideModal" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Gán hướng dẫn viên</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <form id="assignGuideForm">
                                <div class="modal-body">
                                    <input type="hidden" name="departure_date" value="${departureDate}">
                                    <input type="hidden" name="tour_id" value="${tourId}">
                                    <div class="mb-3">
                                        <label for="guide_id" class="form-label">Chọn hướng dẫn viên *</label>
                                        <select class="form-select" id="guide_id" name="guide_id" required>
                                            <option value="">Đang tải danh sách...</option>
                                        </select>
                                        <small class="text-muted">Chỉ hiển thị các HDV chưa được gán cho tour khác cùng ngày.</small>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                    <button type="submit" class="btn btn-info">Gán HDV</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            `;
            
            document.body.insertAdjacentHTML('beforeend', modalHtml);
            const modal = new bootstrap.Modal(document.getElementById('assignGuideModal'));
            modal.show();

            // Gọi API để lấy danh sách HDV có sẵn
            try {
                const availableGuidesUrl = '{{ route("admin.bookings.available-guides") }}';
                const url = availableGuidesUrl + '?departure_date=' + encodeURIComponent(departureDate) + '&tour_id=' + encodeURIComponent(tourId);
                const response = await fetch(url, {
                    headers: {
                        'Accept': 'application/json'
                    }
                });
                
                const data = await response.json();
                const selectElement = document.getElementById('guide_id');
                
                if (data.success && data.data.length > 0) {
                    let optionsHtml = '<option value="">-- Chọn hướng dẫn viên --</option>';
                    data.data.forEach(guide => {
                        optionsHtml += `<option value="${guide.id}">${guide.name} (${guide.email})</option>`;
                    });
                    selectElement.innerHTML = optionsHtml;
                } else {
                    selectElement.innerHTML = '<option value="">Không có HDV nào có sẵn</option>';
                    selectElement.disabled = true;
                }
            } catch (error) {
                console.error('Error loading guides:', error);
                document.getElementById('guide_id').innerHTML = '<option value="">Lỗi khi tải danh sách HDV</option>';
            }
            
            document.getElementById('assignGuideForm').addEventListener('submit', async function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                
                try {
                    const response = await fetch('{{ route("admin.bookings.assign-guide") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: formData
                    });
                    
                    const data = await response.json();
                    if (data.success) {
                        showAlert('success', data.message);
                        modal.hide();
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        showAlert('danger', data.message || 'Có lỗi xảy ra');
                    }
                } catch (error) {
                    showAlert('danger', 'Lỗi: ' + error.message);
                }
            });
            
            document.getElementById('assignGuideModal').addEventListener('hidden.bs.modal', function() {
                this.remove();
            });
        }

        // B4: Gán xe (chọn từ danh sách Quản lý xe)
        async function openAssignVehicleModal(departureDate, tourId) {
            // Hiển thị modal với loading state
            const modalHtml = `
                <div class="modal fade" id="assignVehicleModal" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Gán xe</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <form id="assignVehicleForm">
                                <div class="modal-body">
                                    <input type="hidden" name="departure_date" value="${departureDate}">
                                    <input type="hidden" name="tour_id" value="${tourId}">
                                    <div class="mb-3">
                                        <label for="vehicle_id" class="form-label">Chọn xe *</label>
                                        <select class="form-select" id="vehicle_id" name="vehicle_id" required>
                                            <option value="">Đang tải danh sách...</option>
                                        </select>
                                    </div>
                                    <p class="text-muted mb-0"><small>Chỉ hiển thị các xe chưa được gán cho tour khác cùng ngày.</small></p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                    <button type="submit" class="btn btn-warning">Gán xe</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            `;
            
            document.body.insertAdjacentHTML('beforeend', modalHtml);
            const modal = new bootstrap.Modal(document.getElementById('assignVehicleModal'));
            modal.show();

            // Gọi API để lấy danh sách xe có sẵn
            try {
                const availableVehiclesUrl = '{{ route("admin.bookings.available-vehicles") }}';
                const url = availableVehiclesUrl + '?departure_date=' + encodeURIComponent(departureDate) + '&tour_id=' + encodeURIComponent(tourId);
                const response = await fetch(url, {
                    headers: {
                        'Accept': 'application/json'
                    }
                });
                
                const data = await response.json();
                const selectElement = document.getElementById('vehicle_id');
                
                if (data.success && data.data.length > 0) {
                    let optionsHtml = '<option value="">-- Chọn xe --</option>';
                    data.data.forEach(vehicle => {
                        optionsHtml += `<option value="${vehicle.id}">${vehicle.label}</option>`;
                    });
                    selectElement.innerHTML = optionsHtml;
                } else {
                    selectElement.innerHTML = '<option value="">Không có xe nào có sẵn</option>';
                    selectElement.disabled = true;
                }
            } catch (error) {
                console.error('Error loading vehicles:', error);
                document.getElementById('vehicle_id').innerHTML = '<option value="">Lỗi khi tải danh sách xe</option>';
            }
            
            document.getElementById('assignVehicleForm').addEventListener('submit', async function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                
                try {
                    const response = await fetch('{{ route("admin.bookings.assign-vehicle") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: formData
                    });
                    
                    const data = await response.json();
                    if (data.success) {
                        showAlert('success', data.message);
                        modal.hide();
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        showAlert('danger', data.message || 'Có lỗi xảy ra');
                    }
                } catch (error) {
                    showAlert('danger', 'Lỗi: ' + error.message);
                }
            });
            
            document.getElementById('assignVehicleModal').addEventListener('hidden.bs.modal', function() {
                this.remove();
            });
        }

        // B5: Gửi thông tin trước tour
        function openSendPreTourInfoModal(departureDate) {
            const modalHtml = `
                <div class="modal fade" id="sendPreTourInfoModal" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Gửi thông tin trước tour</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <form id="sendPreTourInfoForm">
                                <div class="modal-body">
                                    <input type="hidden" name="departure_date" value="${departureDate}">
                                    <div class="mb-3">
                                        <label for="message" class="form-label">Nội dung thông báo</label>
                                        <textarea class="form-control" id="message" name="message" rows="4" placeholder="Nhập thông tin cần gửi cho khách hàng..."></textarea>
                                    </div>
                                    <div class="mb-3 form-check">
                                        <input type="checkbox" class="form-check-input" id="send_email" name="send_email" value="1" checked>
                                        <label class="form-check-label" for="send_email">Gửi qua email</label>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                    <button type="submit" class="btn btn-primary">Gửi thông tin</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            `;
            
            document.body.insertAdjacentHTML('beforeend', modalHtml);
            const modal = new bootstrap.Modal(document.getElementById('sendPreTourInfoModal'));
            modal.show();
            
            document.getElementById('sendPreTourInfoForm').addEventListener('submit', async function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                
                try {
                    const response = await fetch('{{ route("admin.bookings.send-pre-tour-info") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: formData
                    });
                    
                    const data = await response.json();
                    if (data.success) {
                        showAlert('success', data.message);
                        modal.hide();
                    } else {
                        showAlert('danger', data.message || 'Có lỗi xảy ra');
                    }
                } catch (error) {
                    showAlert('danger', 'Lỗi: ' + error.message);
                }
            });
            
            document.getElementById('sendPreTourInfoModal').addEventListener('hidden.bs.modal', function() {
                this.remove();
            });
        }
    </script>


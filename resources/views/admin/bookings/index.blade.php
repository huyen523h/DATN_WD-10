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
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('admin.bookings.manual.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus-circle me-1"></i> Thêm booking thủ công
                    </a>
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

    @if(isset($groupedBookings) && $groupedBookings->count() > 0)
        <div class="accordion" id="departureAccordion">
            @foreach($groupedBookings as $group)
                @php
                    $departureKey = $group['departure_id'] ?? ($group['date'] ? $group['date']->format('Ymd') : 'no-date');
                    $totalBookings = $group['count'] ?? collect($group['bookings'] ?? [])->count();
                    $totalGuests = $group['total_guests'] ?? collect($group['bookings'] ?? [])->sum(function($item){ return $item->adults + $item->children + $item->infants; });
                    $totalAdults = $group['total_adults'] ?? collect($group['bookings'] ?? [])->sum('adults');
                    $totalChildren = $group['total_children'] ?? collect($group['bookings'] ?? [])->sum('children');
                    $totalInfants = $group['total_infants'] ?? collect($group['bookings'] ?? [])->sum('infants');
                    $totalRevenue = $group['total_amount'] ?? collect($group['bookings'] ?? [])->where('status', '!=', 'cancelled')->sum('total_amount');
                    $isConfirmed = $group['group_confirmed'] ?? false;
                    $isFinished = ($group['date'] && $group['date']->isPast()) ? true : false;
                    $badgeLabel = $isFinished ? 'Đã kết thúc' : ($isConfirmed ? 'Đã chốt' : 'Chưa chốt');
                    $badgeClass = $isFinished ? 'bg-dark' : ($isConfirmed ? 'bg-success' : 'bg-secondary');
                @endphp
                <div class="card border-0 shadow-sm mb-4 departure-card">
                    <!-- Group Header -->
                    <div class="card-header bg-primary text-white py-3">
                        <div class="d-flex flex-wrap justify-content-between align-items-center">
                            <div class="d-flex flex-column">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="fas fa-calendar-alt"></i>
                                    <span class="fw-bold">{{ $group['date'] ? $group['date']->format('d/m/Y') : 'Chưa có ngày' }}</span>
                                </div>
                                @if(!empty($group['tour']))
                                    <div class="mt-1">
                                        <small class="text-white-50">Tour</small>
                                        <div class="fw-semibold">{{ $group['tour']->title }}</div>
                                    </div>
                                @endif
                            </div>
                            <div class="d-flex align-items-center flex-wrap gap-3">
                                <div class="text-white">
                                    <small class="d-block text-white-50">Tổng booking</small>
                                    <span class="fw-bold">{{ $totalBookings }}</span>
                                </div>
                                <div class="text-white">
                                    <small class="d-block text-white-50">Tổng khách</small>
                                    <span class="fw-bold">{{ $totalGuests }}</span>
                                </div>
                                <div class="text-white">
                                    <small class="d-block text-white-50">Doanh thu (không hủy)</small>
                                    <span class="fw-bold">{{ number_format($totalRevenue, 0, ',', '.') }}đ</span>
                                </div>
                                <span class="badge {{ $badgeClass }} rounded-pill px-3 py-2">{{ $badgeLabel }}</span>
                                <button class="btn btn-sm btn-light text-primary fw-semibold d-flex align-items-center gap-2"
                                        type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#booking-panel-{{ $departureKey }}"
                                        aria-expanded="false"
                                        aria-controls="booking-panel-{{ $departureKey }}"
                                        data-toggle-bookings
                                        data-target="booking-panel-{{ $departureKey }}"
                                        data-departure-id="{{ $group['departure_id'] ?? $departureKey }}">
                                    <i class="fas fa-list"></i> Xem booking
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Group Summary (operations layer) -->
                    <div class="card-body bg-light py-3">
                        <div class="row align-items-center">
                            <div class="col-md-2 text-center">
                                <small class="text-muted d-block">Người lớn</small>
                                <strong>{{ $totalAdults }}</strong>
                            </div>
                            <div class="col-md-2 text-center">
                                <small class="text-muted d-block">Trẻ em</small>
                                <strong>{{ $totalChildren }}</strong>
                            </div>
                            <div class="col-md-2 text-center">
                                <small class="text-muted d-block">Em bé</small>
                                <strong>{{ $totalInfants }}</strong>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex gap-2 flex-wrap">
                                    <span class="badge bg-success">
                                        <i class="fas fa-check-circle me-1"></i>
                                        {{ $isConfirmed ? 'Đã chốt' : 'Chưa chốt' }}: {{ $isConfirmed ? ($group['confirmed_guests_count'] ?? $totalGuests) : $totalGuests }} khách
                                    </span>
                                    <button class="btn btn-sm btn-outline-success"
                                        @if(!($group['can_confirm_group'] ?? true)) 
                                            disabled 
                                            title="Không thể chốt đoàn! Có {{ count($group['unconfirmed_bookings'] ?? []) }} booking chưa xác nhận và {{ count($group['unpaid_bookings'] ?? []) }} booking chưa thanh toán."
                                        @endif
                                        onclick="openConfirmGroupModal('{{ $group['date'] ? $group['date']->format('Y-m-d') : 'no-date' }}', {{ $isConfirmed ? ($group['confirmed_guests_count'] ?? $totalGuests) : $totalGuests }})">
                                        <i class="fas fa-sync-alt me-1"></i> Cập nhật chốt đoàn
                                    </button>
                                    @if(!($group['can_confirm_group'] ?? true))
                                        <small class="text-danger d-block mt-1">
                                            <i class="fas fa-exclamation-triangle"></i> 
                                            Chưa thể chốt: {{ count($group['unconfirmed_bookings'] ?? []) }} booking chưa xác nhận, {{ count($group['unpaid_bookings'] ?? []) }} booking chưa thanh toán
                                        </small>
                                    @endif
                                    
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

                    <!-- Bookings Table (lazy render) -->
                    <div id="booking-panel-{{ $departureKey }}" class="collapse" data-bs-parent="#departureAccordion" data-departure-key="{{ $departureKey }}" data-loaded="false">
                        <div class="p-3">
                            <div class="booking-placeholder d-flex align-items-center justify-content-center gap-2 text-muted" aria-live="polite">
                                <i class="fas fa-info-circle"></i>
                                <span>Nhấn "Xem booking" để tải danh sách. Dữ liệu lớn sẽ được tải khi cần.</span>
                            </div>
                            <div class="booking-table-wrapper d-none"></div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
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

        .departure-card .card-header {
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
        }

        .booking-placeholder {
            border: 1px dashed #d0d7de;
            border-radius: 10px;
            padding: 16px;
            background: #f8fafc;
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

@section('scripts')
    @parent
    <script>
        // Payload dữ liệu booking theo đoàn (được stringify để tránh lỗi khi có ký tự đặc biệt)
        const departureBookings = JSON.parse(@json(json_encode($departureBookingPayload ?? [])));

        document.addEventListener('DOMContentLoaded', function() {

            // Đảm bảo meta[name="csrf-token"] tồn tại trong layout của bạn
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
            if (!csrfToken) {
                console.error(
                    'Lỗi: Không tìm thấy CSRF Token. Hãy thêm <meta name="csrf-token" content="{{ csrf_token() }}"> vào layout chính.'
                    );
            }

            // Lazy load bảng booking theo đoàn
            function escapeHtml(string) {
                const div = document.createElement('div');
                div.appendChild(document.createTextNode(string ?? ''));
                return div.innerHTML;
            }

            function formatCurrency(value) {
                const number = Number(value || 0);
                return number.toLocaleString('vi-VN') + ' VNĐ';
            }

            function buildStatusBadge(status) {
                const map = {
                    pending: { class: 'bg-warning text-dark', icon: 'fa-clock', label: 'Chờ xác nhận' },
                    deposit: { class: 'bg-info text-dark', icon: 'fa-hand-holding-usd', label: 'Đặt cọc' },
                    confirmed: { class: 'bg-success', icon: 'fa-check-circle', label: 'Đã xác nhận' },
                    paid: { class: 'bg-success', icon: 'fa-money-bill-wave', label: 'Đã thanh toán' },
                    completed: { class: 'bg-secondary', icon: 'fa-check', label: 'Hoàn thành' },
                    cancelled: { class: 'bg-danger', icon: 'fa-times-circle', label: 'Đã hủy' },
                };
                return map[status] || { class: 'bg-secondary', icon: 'fa-question-circle', label: 'Khác' };
            }

            function renderBookingTable(panel, bookings = []) {
                const placeholder = panel.querySelector('.booking-placeholder');
                const wrapper = panel.querySelector('.booking-table-wrapper');

                if (placeholder) placeholder.classList.add('d-none');
                if (!wrapper) return;

                if (!bookings.length) {
                    wrapper.classList.remove('d-none');
                    wrapper.innerHTML = `<div class="text-center text-muted py-4">
                        <i class="fas fa-info-circle me-2"></i>Chưa có booking nào cho lịch khởi hành này
                    </div>`;
                    return;
                }

                const rows = bookings.map((booking) => {
                    const statusMeta = buildStatusBadge(booking.status);
                    return `
                        <tr>
                            <td class="px-3">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-building text-primary me-2"></i>
                                    <strong>#${escapeHtml(booking.code)}</strong>
                                    <span class="badge bg-secondary ms-2">BOOKING</span>
                                </div>
                            </td>
                            <td class="px-3">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle bg-purple text-white me-2" style="width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; background-color: #6f42c1;">
                                        ${escapeHtml(booking.profile_initial)}
                                    </div>
                                    <div>
                                        <div class="fw-bold">${escapeHtml(booking.customer_name)}</div>
                                        <small class="text-muted">${escapeHtml(booking.customer_email)}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="px-3">
                                <div class="text-truncate" style="max-width: 300px;" title="${escapeHtml(booking.tour_title)}">
                                    ${escapeHtml(booking.tour_title)}
                                </div>
                            </td>
                            <td class="px-3">
                                <div>
                                    <span class="badge bg-info">
                                        <i class="fas fa-user me-1"></i>${booking.adults} người lớn
                                    </span>
                                    ${booking.children > 0 ? `<br><span class="badge bg-info mt-1"><i class="fas fa-child me-1"></i>${booking.children} trẻ em</span>` : ''}
                                    ${booking.infants > 0 ? `<br><span class="badge bg-info mt-1"><i class="fas fa-baby me-1"></i>${booking.infants} em bé</span>` : ''}
                                </div>
                            </td>
                            <td class="px-3 fw-bold text-success">${formatCurrency(booking.total_amount)}</td>
                            <td class="px-3">
                                <span class="badge ${statusMeta.class}">
                                    <i class="fas ${statusMeta.icon} me-1"></i>${statusMeta.label}
                                </span>
                            </td>
                            <td class="px-3">
                                <i class="fas fa-calendar text-muted me-1"></i>
                                ${escapeHtml(booking.created_at)}
                            </td>
                            <td class="px-3 text-end">
                                <a href="${booking.url}" class="btn btn-sm btn-outline-primary" title="Xem chi tiết">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    `;
                }).join('');

                wrapper.classList.remove('d-none');
                wrapper.innerHTML = `
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
                            <tbody>${rows}</tbody>
                        </table>
                    </div>
                `;
            }

            function loadPanelOnce(panel) {
                if (!panel || panel.dataset.loaded === 'true') return;
                const departureKey = panel.dataset.departureKey;
                const bookings = departureBookings[departureKey] || [];
                renderBookingTable(panel, bookings);
                panel.dataset.loaded = 'true';
            }

            function setupLazyBookingTables() {
                const toggleButtons = document.querySelectorAll('[data-toggle-bookings]');
                toggleButtons.forEach(btn => {
                    btn.addEventListener('click', function () {
                        const targetId = this.dataset.target;
                        const departureId = this.dataset.departureId;
                        const panel = document.getElementById(targetId) || this.closest('.departure-card')?.querySelector(`#${targetId}`);
                        console.log('[Xem booking] Click', { departureId, targetId, hasPanel: !!panel });
                        if (!panel) return;

                        // Luôn lấy dữ liệu từ departureBookings theo departureId
                        const bookings = departureBookings?.[departureId] || [];
                        console.log('[Xem booking] Load bookings', { departureId, count: bookings.length });

                        renderBookingTable(panel, bookings);
                        panel.dataset.loaded = 'true';
                    });
                });

                // Phòng trường hợp người dùng mở collapse bằng các cách khác
                const collapses = document.querySelectorAll('.collapse[data-departure-key]');
                collapses.forEach(panel => {
                    panel.addEventListener('show.bs.collapse', function () {
                        loadPanelOnce(panel);
                    });
                });
            }

            setupLazyBookingTables();

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
@endsection

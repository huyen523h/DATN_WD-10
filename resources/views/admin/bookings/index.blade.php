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

    <!-- Stats Cards - Thống kê theo nguồn booking -->
    @php
        // Tính toán thống kê theo nguồn (case-insensitive)
        $totalBookings = $bookings->count();
        $websiteBookings = $bookings->filter(function($b) {
            return strtolower($b->booking_source ?? 'website') === 'website';
        })->count();
        $saleBookings = $bookings->filter(function($b) {
            $source = strtolower($b->booking_source ?? '');
            return in_array($source, ['zalo', 'facebook', 'phone']);
        })->count();
        // Doanh thu: không tính booking đã hủy, tính theo ngày khởi hành trong filter
        $totalRevenue = $bookings->where('status', '!=', 'cancelled')->sum('total_amount');
    @endphp
    <div class="row mb-4">
        <!-- Tổng số booking -->
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100 border-start border-primary border-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-calendar-check text-primary fa-lg"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Tổng số booking</h6>
                            <h4 class="mb-0 fw-bold text-primary">{{ $totalBookings }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Booking từ Website -->
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100 border-start border-info border-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-info bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-globe text-info fa-lg"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Từ Website</h6>
                            <h4 class="mb-0 fw-bold text-info">{{ $websiteBookings }}</h4>
                            <small class="text-muted">{{ $totalBookings > 0 ? round($websiteBookings / $totalBookings * 100, 1) : 0 }}% tổng</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Booking từ Sale (Zalo + Facebook + Phone) -->
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100 border-start border-success border-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-success bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-headset text-success fa-lg"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Từ Sale</h6>
                            <h4 class="mb-0 fw-bold text-success">{{ $saleBookings }}</h4>
                            <small class="text-muted">Zalo, Facebook, ĐT</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Doanh thu (không tính cancelled) -->
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100 border-start border-warning border-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-warning bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-money-bill-wave text-warning fa-lg"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Doanh thu</h6>
                            <h4 class="mb-0 fw-bold text-warning">{{ number_format($totalRevenue, 0, ',', '.') }}đ</h4>
                            <small class="text-muted">Không tính đã hủy</small>
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
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <h5 class="mb-0 fw-bold text-dark">
                            <i class="fas fa-search text-primary me-2"></i>
                            Tìm kiếm và lọc
                        </h5>
                        <!-- Quick Filters -->
                        <div class="d-flex gap-2 flex-wrap">
                            <a href="{{ route('admin.bookings', ['group_status' => 'pending']) }}" 
                               class="btn btn-sm {{ request('group_status') == 'pending' ? 'btn-secondary' : 'btn-outline-secondary' }}">
                                <i class="fas fa-hourglass-half me-1"></i> Chưa chốt
                            </a>
                            <a href="{{ route('admin.bookings', ['cutoff_status' => 'near']) }}" 
                               class="btn btn-sm {{ request('cutoff_status') == 'near' ? 'btn-warning' : 'btn-outline-warning' }}">
                                <i class="fas fa-clock me-1"></i> Sắp cutoff
                            </a>
                            <a href="{{ route('admin.bookings', ['cutoff_status' => 'passed']) }}" 
                               class="btn btn-sm {{ request('cutoff_status') == 'passed' ? 'btn-danger' : 'btn-outline-danger' }}">
                                <i class="fas fa-exclamation-circle me-1"></i> Quá cutoff
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.bookings') }}" class="row g-3">
                        <!-- Row 1: Search, Status, Booking Source -->
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
                            <label for="status" class="form-label fw-semibold">Trạng thái booking</label>
                            <select class="form-select" id="status" name="status">
                                <option value="">Tất cả trạng thái</option>
                                <option value="hold" {{ request('status') == 'hold' ? 'selected' : '' }}>Giữ chỗ (HOLD)</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ xác nhận (PENDING)</option>
                                <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Đã xác nhận (CONFIRMED)</option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Đã hủy (CANCELLED)</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="booking_source" class="form-label fw-semibold">Nguồn booking</label>
                            <select class="form-select" id="booking_source" name="booking_source">
                                <option value="">Tất cả nguồn</option>
                                <option value="website" {{ request('booking_source') == 'website' ? 'selected' : '' }}>🌐 Website</option>
                                <option value="zalo" {{ request('booking_source') == 'zalo' ? 'selected' : '' }}>💬 Zalo</option>
                                <option value="facebook" {{ request('booking_source') == 'facebook' ? 'selected' : '' }}>📘 Facebook</option>
                                <option value="phone" {{ request('booking_source') == 'phone' ? 'selected' : '' }}>📞 Điện thoại</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="sale_staff" class="form-label fw-semibold">Sale phụ trách</label>
                            <select class="form-select" id="sale_staff" name="sale_staff">
                                <option value="">Tất cả Sale</option>
                                @php
                                    $saleStaffs = \App\Models\User::whereHas('roles', function($q) {
                                        $q->whereIn('name', ['admin', 'staff', 'sale']);
                                    })->orderBy('name')->get();
                                @endphp
                                @foreach($saleStaffs as $staff)
                                    <option value="{{ $staff->id }}" {{ request('sale_staff') == $staff->id ? 'selected' : '' }}>
                                        {{ $staff->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Row 2: Date filters -->
                        <div class="col-md-3">
                            <label for="departure_from" class="form-label fw-semibold">Ngày khởi hành từ</label>
                            <input type="date" class="form-control" id="departure_from" name="departure_from"
                                value="{{ request('departure_from') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="departure_to" class="form-label fw-semibold">Ngày khởi hành đến</label>
                            <input type="date" class="form-control" id="departure_to" name="departure_to"
                                value="{{ request('departure_to') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="date_from" class="form-label fw-semibold">Ngày đặt từ</label>
                            <input type="date" class="form-control" id="date_from" name="date_from"
                                value="{{ request('date_from') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="date_to" class="form-label fw-semibold">Ngày đặt đến</label>
                            <input type="date" class="form-control" id="date_to" name="date_to"
                                value="{{ request('date_to') }}">
                        </div>
                        
                        <!-- Action buttons -->
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
                    
                    // TRẠNG THÁI ĐOÀN (Header) - Độc lập với trạng thái booking
                    $isConfirmed = $group['group_confirmed'] ?? false;
                    $departureDate = $group['date'] ?? null;
                    $today = \Carbon\Carbon::today();
                    $now = \Carbon\Carbon::now();
                    
                    // CUTOFF LOGIC: Mặc định cutoff trước 3 ngày trước ngày khởi hành
                    $cutoffDays = $group['departure']->cutoff_days ?? 3;
                    $cutoffDate = $departureDate ? $departureDate->copy()->subDays($cutoffDays) : null;
                    $isBeforeCutoff = $cutoffDate ? $now->lt($cutoffDate) : true;
                    $isAtCutoff = $cutoffDate ? $now->isSameDay($cutoffDate) : false;
                    $isAfterCutoff = $cutoffDate ? $now->gt($cutoffDate) : false;
                    $daysUntilCutoff = $cutoffDate ? $now->diffInDays($cutoffDate, false) : null;
                    
                    // Cutoff status
                    if ($isAfterCutoff) {
                        $cutoffStatus = 'passed';
                        $cutoffBadge = 'bg-danger';
                        $cutoffLabel = 'Quá cutoff';
                    } elseif ($isAtCutoff) {
                        $cutoffStatus = 'today';
                        $cutoffBadge = 'bg-warning text-dark';
                        $cutoffLabel = 'Cutoff hôm nay!';
                    } elseif ($daysUntilCutoff !== null && $daysUntilCutoff <= 2) {
                        $cutoffStatus = 'near';
                        $cutoffBadge = 'bg-warning text-dark';
                        $cutoffLabel = 'Còn ' . $daysUntilCutoff . ' ngày';
                    } else {
                        $cutoffStatus = 'ok';
                        $cutoffBadge = 'bg-light text-muted';
                        $cutoffLabel = $cutoffDate ? 'Cutoff: ' . $cutoffDate->format('d/m') : '';
                    }
                    
                    // TRẠNG THÁI SẴN SÀNG VẬN HÀNH
                    $hasGuide = !empty($group['guide']);
                    $hasVehicle = !empty($group['vehicle_type']);
                    $hasAllBookingsConfirmed = ($group['can_confirm_group'] ?? true);
                    $isOperationReady = $hasGuide && $hasVehicle && $isConfirmed;
                    
                    // Logic trạng thái đoàn:
                    $tourDuration = $group['tour']->duration ?? 1;
                    $endDate = $departureDate ? $departureDate->copy()->addDays($tourDuration) : null;
                    
                    if ($endDate && $today->gt($endDate)) {
                        $groupStatus = 'finished';
                        $badgeLabel = 'Đã kết thúc';
                        $badgeClass = 'bg-dark';
                        $badgeIcon = 'fa-flag-checkered';
                    } elseif ($departureDate && $today->gte($departureDate) && $endDate && $today->lte($endDate)) {
                        $groupStatus = 'departed';
                        $badgeLabel = 'Đang khởi hành';
                        $badgeClass = 'bg-primary';
                        $badgeIcon = 'fa-plane-departure';
                    } elseif ($isConfirmed) {
                        $groupStatus = 'confirmed';
                        $badgeLabel = 'Đã chốt';
                        $badgeClass = 'bg-success';
                        $badgeIcon = 'fa-check-double';
                    } else {
                        $groupStatus = 'pending';
                        $badgeLabel = 'Chưa chốt';
                        $badgeClass = 'bg-secondary';
                        $badgeIcon = 'fa-hourglass-half';
                    }
                @endphp
                <div class="card border-0 shadow-sm mb-4 departure-card {{ $isAfterCutoff ? 'border-start border-danger border-4' : '' }}">
                    <!-- Group Header -->
                    <div class="card-header bg-primary text-white py-3">
                        <div class="d-flex flex-wrap justify-content-between align-items-center">
                            <div class="d-flex flex-column">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="fas fa-calendar-alt"></i>
                                    <span class="fw-bold">{{ $group['date'] ? $group['date']->format('d/m/Y') : 'Chưa có ngày' }}</span>
                                    <!-- Cutoff Badge with Business Logic Tooltip -->
                                    @if($cutoffDate)
                                        @php
                                            $cutoffTooltip = "Ngày cutoff: " . $cutoffDate->format('d/m/Y');
                                            if ($isAfterCutoff) {
                                                $cutoffTooltip = "⚠️ Đã quá hạn chốt khách!\n❌ Không thể nhận thêm booking\n❌ Không thể tăng số khách\n✅ Chỉ xem và ghi chú nội bộ";
                                            } elseif ($daysUntilCutoff !== null && $daysUntilCutoff <= 2) {
                                                $cutoffTooltip = "⏰ Còn {$daysUntilCutoff} ngày đến cutoff!\nSau cutoff sẽ không thể nhận booking mới";
                                            }
                                        @endphp
                                        <span class="badge {{ $cutoffBadge }} ms-2" 
                                              title="{{ $cutoffTooltip }}"
                                              data-bs-toggle="tooltip"
                                              data-bs-html="true"
                                              style="cursor: help;">
                                            <i class="fas {{ $isAfterCutoff ? 'fa-lock' : 'fa-stopwatch' }} me-1"></i>{{ $cutoffLabel }}
                                        </span>
                                    @endif
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
                                    <small class="d-block text-white-50">Doanh thu</small>
                                    <span class="fw-bold">{{ number_format($totalRevenue, 0, ',', '.') }}đ</span>
                                </div>
                                
                                <!-- Trạng thái đoàn -->
                                <span class="badge {{ $badgeClass }} rounded-pill px-3 py-2">
                                    <i class="fas {{ $badgeIcon }} me-1"></i>{{ $badgeLabel }}
                                </span>
                                
                                <!-- Trạng thái sẵn sàng vận hành -->
                                @if($groupStatus !== 'finished')
                                    <span class="badge {{ $isOperationReady ? 'bg-success' : 'bg-warning text-dark' }} rounded-pill px-2 py-1"
                                          title="{{ $isOperationReady ? 'Sẵn sàng vận hành' : 'Chưa đủ điều kiện: ' . (!$hasGuide ? 'Thiếu HDV. ' : '') . (!$hasVehicle ? 'Thiếu xe. ' : '') . (!$isConfirmed ? 'Chưa chốt đoàn.' : '') }}"
                                          data-bs-toggle="tooltip">
                                        <i class="fas {{ $isOperationReady ? 'fa-check-circle' : 'fa-exclamation-triangle' }}"></i>
                                    </span>
                                @endif
                                
                                <!-- Nút Xem booking -->
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
                                
                                <!-- DROPDOWN ĐIỀU HÀNH - Đặt trong HEADER -->
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light text-dark fw-semibold dropdown-toggle" type="button" 
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-cogs me-1"></i> Điều hành
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow">
                                        <li class="dropdown-header text-muted">
                                            <small>Quản lý đoàn {{ $group['date'] ? $group['date']->format('d/m/Y') : '' }}</small>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <a class="dropdown-item" href="javascript:void(0)" 
                                               onclick="openAssignGuideModal('{{ $group['date'] ? $group['date']->format('Y-m-d') : 'no-date' }}', {{ $group['tour_id'] ?? 'null' }}, {{ $group['departure_id'] ?? 'null' }})">
                                                <i class="fas fa-user-tie me-2 text-info"></i> 
                                                {{ $group['guide'] ? 'Đổi HDV' : 'Gán HDV' }}
                                                @if($group['guide'])
                                                    <small class="text-muted ms-2">({{ $group['guide']->name }})</small>
                                                @endif
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="javascript:void(0)" 
                                               onclick="openAssignVehicleModal('{{ $group['date'] ? $group['date']->format('Y-m-d') : 'no-date' }}', {{ $group['tour_id'] ?? 'null' }}, {{ $group['departure_id'] ?? 'null' }})">
                                                <i class="fas fa-bus me-2 text-warning"></i> 
                                                {{ $group['vehicle_type'] ? 'Đổi xe' : 'Gán xe' }}
                                                @if($group['vehicle_type'])
                                                    <small class="text-muted ms-2">({{ $group['vehicle_type'] }} chỗ)</small>
                                                @endif
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <a class="dropdown-item" href="javascript:void(0)" 
                                               onclick="openOperationNoteModal('{{ $group['date'] ? $group['date']->format('Y-m-d') : 'no-date' }}', {{ $group['departure_id'] ?? 'null' }})">
                                                <i class="fas fa-sticky-note me-2 text-secondary"></i> Ghi chú điều hành
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="javascript:void(0)" 
                                               onclick="openSendPreTourInfoModal('{{ $group['date'] ? $group['date']->format('Y-m-d') : 'no-date' }}')">
                                                <i class="fas fa-paper-plane me-2 text-primary"></i> Gửi thông tin tour
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Group Summary - Thông tin tổng hợp + Actions theo cutoff -->
                    <div class="card-body bg-light py-3">
                        <div class="row align-items-center">
                            <!-- Thống kê khách -->
                            <div class="col-md-2 text-center">
                                <small class="text-muted d-block">Người lớn</small>
                                <strong class="text-primary">{{ $totalAdults }}</strong>
                            </div>
                            <div class="col-md-2 text-center">
                                <small class="text-muted d-block">Trẻ em</small>
                                <strong class="text-info">{{ $totalChildren }}</strong>
                            </div>
                            <div class="col-md-2 text-center">
                                <small class="text-muted d-block">Em bé</small>
                                <strong class="text-secondary">{{ $totalInfants }}</strong>
                            </div>
                            
                            <!-- Thông tin điều hành + Actions theo cutoff -->
                            <div class="col-md-6">
                                <div class="d-flex gap-2 flex-wrap align-items-center">
                                    <!-- Badges thông tin đã gán -->
                                    @if($group['guide'])
                                        <span class="badge bg-info">
                                            <i class="fas fa-user-tie me-1"></i>HDV: {{ $group['guide']->name }}
                                        </span>
                                    @else
                                        <span class="badge bg-light text-muted border {{ $isAfterCutoff ? 'border-danger' : '' }}">
                                            <i class="fas fa-user-tie me-1"></i>Chưa gán HDV
                                            @if($isAfterCutoff)
                                                <i class="fas fa-exclamation text-danger ms-1"></i>
                                            @endif
                                        </span>
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
                                        <span class="badge bg-light text-muted border {{ $isAfterCutoff ? 'border-danger' : '' }}">
                                            <i class="fas fa-bus me-1"></i>Chưa gán xe
                                            @if($isAfterCutoff)
                                                <i class="fas fa-exclamation text-danger ms-1"></i>
                                            @endif
                                        </span>
                                    @endif
                                    
                                    <!-- ACTIONS THEO CUTOFF -->
                                    @if($groupStatus !== 'finished' && $groupStatus !== 'departed')
                                        @if($isBeforeCutoff)
                                            <!-- TRƯỚC CUTOFF: Các nút điều hành tạm -->
                                            @if(!$isConfirmed)
                                                <button class="btn btn-sm btn-outline-primary"
                                                    onclick="openConfirmGroupModal('{{ $group['date'] ? $group['date']->format('Y-m-d') : 'no-date' }}', {{ $totalGuests }})"
                                                    @if(!($group['can_confirm_group'] ?? true)) 
                                                        disabled 
                                                        title="Chưa đủ điều kiện chốt đoàn"
                                                    @endif>
                                                    <i class="fas fa-clipboard-check me-1"></i> Cập nhật chốt đoàn
                                                </button>
                                            @else
                                                <span class="badge bg-success px-3 py-2">
                                                    <i class="fas fa-check-double me-1"></i>Đã chốt: {{ $group['confirmed_guests_count'] ?? $totalGuests }} khách
                                                </span>
                                            @endif
                                        @elseif($isAtCutoff)
                                            <!-- ĐẾN CUTOFF: Button CHỐT ĐOÀN nổi bật -->
                                            @if(!$isConfirmed)
                                                <button class="btn btn-sm btn-warning fw-bold animate-pulse"
                                                    onclick="openConfirmGroupModal('{{ $group['date'] ? $group['date']->format('Y-m-d') : 'no-date' }}', {{ $totalGuests }})"
                                                    @if(!($group['can_confirm_group'] ?? true)) disabled @endif>
                                                    <i class="fas fa-exclamation-triangle me-1"></i> CHỐT ĐOÀN NGAY!
                                                </button>
                                                <small class="text-warning d-block w-100 mt-1">
                                                    <i class="fas fa-info-circle"></i> Sau khi chốt, booking sẽ bị khóa
                                                </small>
                                            @else
                                                <span class="badge bg-success px-3 py-2">
                                                    <i class="fas fa-check-double me-1"></i>Đã chốt: {{ $group['confirmed_guests_count'] ?? $totalGuests }} khách
                                                </span>
                                            @endif
                                        @else
                                            <!-- SAU CUTOFF: Disable editing, chỉ xem -->
                                            @if($isConfirmed)
                                                <span class="badge bg-success px-3 py-2">
                                                    <i class="fas fa-lock me-1"></i>Đã chốt: {{ $group['confirmed_guests_count'] ?? $totalGuests }} khách
                                                </span>
                                            @else
                                                <span class="badge bg-danger px-3 py-2" 
                                                      title="Đã quá hạn cutoff, liên hệ Admin để override"
                                                      data-bs-toggle="tooltip">
                                                    <i class="fas fa-lock me-1"></i>Quá cutoff - Chưa chốt
                                                </span>
                                                @if(auth()->user()->hasRole('admin'))
                                                    <button class="btn btn-sm btn-outline-danger"
                                                        onclick="openConfirmGroupModal('{{ $group['date'] ? $group['date']->format('Y-m-d') : 'no-date' }}', {{ $totalGuests }})">
                                                        <i class="fas fa-unlock me-1"></i> Admin Override
                                                    </button>
                                                @endif
                                            @endif
                                        @endif
                                    @else
                                        <!-- ĐÃ KHỞI HÀNH HOẶC KẾT THÚC -->
                                        <span class="badge bg-dark px-3 py-2">
                                            <i class="fas fa-lock me-1"></i>{{ $isConfirmed ? 'Đã chốt' : 'Không chốt' }}: {{ $group['confirmed_guests_count'] ?? $totalGuests }} khách
                                        </span>
                                    @endif
                                </div>
                                
                                @if(!($group['can_confirm_group'] ?? true) && !$isConfirmed && $isBeforeCutoff)
                                    <small class="text-danger d-block mt-2">
                                        <i class="fas fa-exclamation-triangle"></i> 
                                        Chưa thể chốt: {{ count($group['unconfirmed_bookings'] ?? []) }} booking chưa xác nhận, {{ count($group['unpaid_bookings'] ?? []) }} booking chưa thanh toán
                                    </small>
                                @endif
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
        /* Animate pulse for cutoff warning */
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.6; }
        }
        .animate-pulse {
            animation: pulse 1.5s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        
        /* Card border for after cutoff */
        .departure-card.border-start {
            border-left-width: 4px !important;
        }
        
        /* Badge styling improvements */
        .badge {
            font-weight: 500;
        }
        
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
        // Cache để lưu bookings đã load (tránh gọi AJAX lại)
        const bookingsCache = {};
        
        // URL base cho API (dùng prefix thay vì route với placeholder)
        const BOOKINGS_API_BASE = '{{ url('admin/bookings/by-departure') }}/';

        document.addEventListener('DOMContentLoaded', function() {

            // Đảm bảo meta[name="csrf-token"] tồn tại trong layout của bạn
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
            if (!csrfToken) {
                console.error(
                    'Lỗi: Không tìm thấy CSRF Token. Hãy thêm <meta name="csrf-token" content="{{ csrf_token() }}"> vào layout chính.'
                    );
            }

            // Helper functions
            function escapeHtml(string) {
                const div = document.createElement('div');
                div.appendChild(document.createTextNode(string ?? ''));
                return div.innerHTML;
            }

            function formatCurrency(value) {
                const number = Number(value || 0);
                return number.toLocaleString('vi-VN') + ' VNĐ';
            }

            /**
             * TRẠNG THÁI BOOKING (mỗi dòng booking)
             * - pending: Chờ xác nhận
             * - confirmed: Đã xác nhận  
             * - paid: Đã thanh toán
             * - cancelled: Đã hủy
             */
            /**
             * TRẠNG THÁI BOOKING: HOLD | PENDING | CONFIRMED | CANCELLED
             */
            function buildStatusBadge(status) {
                const map = {
                    hold: { class: 'bg-secondary', icon: 'fa-pause-circle', label: 'HOLD' },
                    pending: { class: 'bg-warning text-dark', icon: 'fa-clock', label: 'PENDING' },
                    confirmed: { class: 'bg-success', icon: 'fa-check-circle', label: 'CONFIRMED' },
                    paid: { class: 'bg-info', icon: 'fa-money-bill-wave', label: 'PAID' },
                    cancelled: { class: 'bg-danger', icon: 'fa-times-circle', label: 'CANCELLED' },
                };
                return map[status] || { class: 'bg-secondary', icon: 'fa-question-circle', label: status?.toUpperCase() || 'UNKNOWN' };
            }

            /**
             * NGUỒN BOOKING: Website, Zalo Sale, Facebook, Phone
             */
            function buildSourceBadge(source) {
                const s = (source || 'website').toLowerCase();
                const map = {
                    website: { class: 'bg-primary', icon: 'fa-globe', label: '🌐 Website' },
                    zalo: { class: 'bg-info', icon: 'fa-comment-dots', label: '💬 Zalo Sale' },
                    facebook: { class: 'bg-primary', icon: 'fa-facebook', label: '📘 Facebook' },
                    phone: { class: 'bg-success', icon: 'fa-phone', label: '📞 Điện thoại' },
                };
                return map[s] || { class: 'bg-secondary', icon: 'fa-question', label: source || 'Khác' };
            }
            
            /**
             * Build action buttons based on booking status and cutoff
             */
            function buildActionButtons(booking, metadata = {}) {
                let actions = '';
                const status = (booking.status || '').toLowerCase();
                const isAfterCutoff = metadata.isAfterCutoff || false;
                const tourStatus = metadata.tourStatus || 'open';
                const departureStatus = metadata.departureStatus || 'open';
                
                // Xác định quyền theo trạng thái tour
                const isCompleted = tourStatus === 'completed' || departureStatus === 'completed';
                const isRunning = tourStatus === 'running' || departureStatus === 'running';
                const isConfirmed = tourStatus === 'confirmed' || departureStatus === 'confirmed';
                
                // Nút Xem chi tiết luôn có
                actions += `<a href="${booking.url}" class="btn btn-sm btn-outline-primary" title="Xem chi tiết">
                    <i class="fas fa-eye"></i>
                </a>`;
                
                if (isCompleted) {
                    // ĐÃ KẾT THÚC: Chỉ xem, đối soát
                    actions += `<span class="ms-1 text-muted" title="Tour đã kết thúc - chỉ xem và đối soát" data-bs-toggle="tooltip">
                        <i class="fas fa-flag-checkered"></i>
                    </span>`;
                } else if (isRunning) {
                    // ĐANG CHẠY: Không cho hủy
                    actions += `<span class="ms-1 text-info" title="Tour đang diễn ra - không thể hủy" data-bs-toggle="tooltip">
                        <i class="fas fa-plane-departure"></i>
                    </span>`;
                } else if (isAfterCutoff || isConfirmed) {
                    // SAU CUTOFF hoặc ĐÃ CHỐT: Chỉ xem, không cho sửa
                    const tooltip = isConfirmed ? 'Đoàn đã chốt - không thể chỉnh sửa' : 'Đã quá cutoff - không thể chỉnh sửa';
                    actions += `<span class="ms-1 text-muted" title="${tooltip}" data-bs-toggle="tooltip">
                        <i class="fas fa-lock"></i>
                    </span>`;
                } else {
                    // TRƯỚC CUTOFF: Các action theo status
                    if (status === 'hold') {
                        // HOLD: Xác nhận hoặc Huỷ giữ chỗ
                        actions += `<button class="btn btn-sm btn-outline-success ms-1" onclick="confirmBooking(${booking.id})" title="Xác nhận booking">
                            <i class="fas fa-check"></i>
                        </button>`;
                        actions += `<button class="btn btn-sm btn-outline-danger ms-1" onclick="cancelHoldBooking(${booking.id})" title="Huỷ giữ chỗ">
                            <i class="fas fa-times"></i>
                        </button>`;
                    } else if (status === 'confirmed' || status === 'paid') {
                        // CONFIRMED: In danh sách khách
                        actions += `<button class="btn btn-sm btn-outline-secondary ms-1" onclick="printGuestList(${booking.id})" title="In danh sách khách">
                            <i class="fas fa-print"></i>
                        </button>`;
                    }
                }
                
                return actions;
            }

            function renderBookingTable(panel, bookings = [], metadata = {}) {
                const isAfterCutoff = metadata.isAfterCutoff || false;
                const tourStatus = metadata.tourStatus || 'open';
                const departureStatus = metadata.departureStatus || 'open';
                const capacityWarning = metadata.capacityWarning || false;
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
                    const sourceMeta = buildSourceBadge(booking.booking_source);
                    const totalGuests = (booking.adults || 0) + (booking.children || 0) + (booking.infants || 0);
                    const guestTooltip = `Người lớn: ${booking.adults || 0}, Trẻ em: ${booking.children || 0}, Em bé: ${booking.infants || 0}`;
                    const actionButtons = buildActionButtons(booking, metadata);
                    const saleStaff = booking.sale_staff_name || '-';
                    
                    return `
                        <tr class="${booking.status === 'cancelled' ? 'table-secondary text-muted' : ''}">
                            <td class="px-3">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle me-2" style="width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; background-color: #6f42c1; color: white; font-weight: bold; font-size: 12px;">
                                        ${escapeHtml(booking.profile_initial)}
                                    </div>
                                    <div>
                                        <div class="fw-semibold">${escapeHtml(booking.customer_name)}</div>
                                        <small class="text-muted">${escapeHtml(booking.customer_email || booking.customer_phone || '')}</small>
                                        <div><small class="text-primary fw-bold">#${escapeHtml(booking.code)}</small></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-3 text-center">
                                <span class="badge bg-light text-dark border" 
                                      style="cursor: help; font-size: 14px; padding: 8px 12px;"
                                      data-bs-toggle="tooltip" 
                                      data-bs-placement="top" 
                                      title="${guestTooltip}">
                                    <i class="fas fa-users me-1 text-primary"></i>
                                    <strong>${totalGuests}</strong>
                                </span>
                            </td>
                            <td class="px-3 text-end">
                                <span class="fw-bold text-success">${formatCurrency(booking.total_amount)}</span>
                            </td>
                            <td class="px-3 text-center">
                                <span class="badge ${sourceMeta.class}" style="font-size: 11px;">
                                    ${sourceMeta.label}
                                </span>
                            </td>
                            <td class="px-3 text-center">
                                <small class="text-muted">${escapeHtml(saleStaff)}</small>
                            </td>
                            <td class="px-3 text-center">
                                <span class="badge ${statusMeta.class} px-2 py-1">
                                    <i class="fas ${statusMeta.icon} me-1"></i>${statusMeta.label}
                                </span>
                            </td>
                            <td class="px-3 text-center">
                                <div class="btn-group btn-group-sm">
                                    ${actionButtons}
                                </div>
                            </td>
                        </tr>
                    `;
                }).join('');

                wrapper.classList.remove('d-none');
                wrapper.innerHTML = `
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="border-0 py-3 px-3" style="min-width: 200px;">KHÁCH HÀNG</th>
                                    <th class="border-0 py-3 px-3 text-center" style="width: 90px;">SỐ KHÁCH</th>
                                    <th class="border-0 py-3 px-3 text-end" style="width: 130px;">TỔNG TIỀN</th>
                                    <th class="border-0 py-3 px-3 text-center" style="width: 100px;">NGUỒN</th>
                                    <th class="border-0 py-3 px-3 text-center" style="width: 100px;">SALE</th>
                                    <th class="border-0 py-3 px-3 text-center" style="width: 110px;">TRẠNG THÁI</th>
                                    <th class="border-0 py-3 px-3 text-center" style="width: 120px;">THAO TÁC</th>
                                </tr>
                            </thead>
                            <tbody>${rows}</tbody>
                        </table>
                    </div>
                `;
                
                // Initialize tooltips for guest count
                const tooltipTriggerList = wrapper.querySelectorAll('[data-bs-toggle="tooltip"]');
                tooltipTriggerList.forEach(el => new bootstrap.Tooltip(el));
            }

            // AJAX: Fetch bookings từ server
            async function fetchBookingsByDeparture(departureId) {
                // Check cache first
                if (bookingsCache[departureId]) {
                    console.log('[AJAX] Using cached data for departure:', departureId);
                    return bookingsCache[departureId];
                }
                
                const url = BOOKINGS_API_BASE + departureId;
                console.log('[AJAX] Fetching bookings from:', url);
                
                try {
                    const response = await fetch(url, {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        }
                    });
                    
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    
                    const result = await response.json();
                    console.log('[AJAX] Response:', result);
                    
                    if (result.success && result.data) {
                        // Cache the result with all metadata
                        bookingsCache[departureId] = {
                            bookings: result.data,
                            isAfterCutoff: result.is_after_cutoff || false,
                            cutoffDate: result.cutoff_date,
                            daysUntilCutoff: result.days_until_cutoff,
                            departureStatus: result.departure_status || 'open',
                            tourStatus: result.tour_status || 'open',
                            totalGuests: result.total_guests || 0,
                            vehicleCapacity: result.vehicle_capacity,
                            capacityWarning: result.capacity_warning || false
                        };
                        return bookingsCache[departureId];
                    }
                    return { bookings: [], isAfterCutoff: false, departureStatus: 'open', tourStatus: 'open' };
                } catch (error) {
                    console.error('[AJAX] Error fetching bookings:', error);
                    return { bookings: [], isAfterCutoff: false, departureStatus: 'open', tourStatus: 'open' };
                }
            }

            async function loadPanelOnce(panel) {
                if (!panel || panel.dataset.loaded === 'true') return;
                const departureId = panel.dataset.departureKey;
                
                // Show loading state
                const wrapper = panel.querySelector('.booking-table-wrapper');
                const placeholder = panel.querySelector('.booking-placeholder');
                if (placeholder) {
                    placeholder.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Đang tải danh sách booking...';
                }
                
                const result = await fetchBookingsByDeparture(departureId);
                renderBookingTable(panel, result.bookings, result);
                panel.dataset.loaded = 'true';
            }

            function setupLazyBookingTables() {
                const toggleButtons = document.querySelectorAll('[data-toggle-bookings]');
                toggleButtons.forEach(btn => {
                    btn.addEventListener('click', async function () {
                        const targetId = this.dataset.target;
                        const departureId = String(this.dataset.departureId);
                        const panel = document.getElementById(targetId) || this.closest('.departure-card')?.querySelector(`#${targetId}`);
                        console.log('[Xem booking] Click', { departureId, targetId, hasPanel: !!panel });
                        if (!panel) return;

                        // Check if already loaded
                        if (panel.dataset.loaded === 'true') {
                            console.log('[Xem booking] Already loaded, skipping fetch');
                            return;
                        }

                        // Show loading state
                        const placeholder = panel.querySelector('.booking-placeholder');
                        if (placeholder) {
                            placeholder.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Đang tải danh sách booking...';
                        }

                        // Fetch via AJAX
                        const result = await fetchBookingsByDeparture(departureId);
                        console.log('[Xem booking] Loaded bookings', { departureId, count: result.bookings?.length });

                        renderBookingTable(panel, result.bookings, result);
                        panel.dataset.loaded = 'true';
                    });
                });

                // Phòng trường hợp người dùng mở collapse bằng các cách khác
                const collapses = document.querySelectorAll('.collapse[data-departure-key]');
                collapses.forEach(panel => {
                    panel.addEventListener('show.bs.collapse', async function () {
                        await loadPanelOnce(panel);
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

        // B3: Gán HDV (từ dropdown Điều hành)
        async function openAssignGuideModal(departureDate, tourId, departureId = null) {
            // Hiển thị modal với loading state
            const modalHtml = `
                <div class="modal fade" id="assignGuideModal" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header bg-info text-white">
                                <h5 class="modal-title"><i class="fas fa-user-tie me-2"></i>Gán hướng dẫn viên</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <form id="assignGuideForm">
                                <div class="modal-body">
                                    <input type="hidden" name="departure_date" value="${departureDate}">
                                    <input type="hidden" name="tour_id" value="${tourId}">
                                    <input type="hidden" name="departure_id" value="${departureId || ''}">
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
                                    <button type="submit" class="btn btn-info">
                                        <i class="fas fa-check me-1"></i> Xác nhận gán HDV
                                    </button>
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

        // B4: Gán xe (từ dropdown Điều hành)
        async function openAssignVehicleModal(departureDate, tourId, departureId = null) {
            // Hiển thị modal với loading state
            const modalHtml = `
                <div class="modal fade" id="assignVehicleModal" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header bg-warning">
                                <h5 class="modal-title"><i class="fas fa-bus me-2"></i>Gán xe</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <form id="assignVehicleForm">
                                <div class="modal-body">
                                    <input type="hidden" name="departure_date" value="${departureDate}">
                                    <input type="hidden" name="tour_id" value="${tourId}">
                                    <input type="hidden" name="departure_id" value="${departureId || ''}">
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
                                    <button type="submit" class="btn btn-warning">
                                        <i class="fas fa-check me-1"></i> Xác nhận gán xe
                                    </button>
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

        // B6: Ghi chú điều hành (từ dropdown Điều hành)
        function openOperationNoteModal(departureDate, departureId = null) {
            const modalHtml = `
                <div class="modal fade" id="operationNoteModal" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header bg-secondary text-white">
                                <h5 class="modal-title"><i class="fas fa-sticky-note me-2"></i>Ghi chú điều hành</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <form id="operationNoteForm">
                                <div class="modal-body">
                                    <input type="hidden" name="departure_date" value="${departureDate}">
                                    <input type="hidden" name="departure_id" value="${departureId || ''}">
                                    <div class="mb-3">
                                        <label for="operation_note" class="form-label">Ghi chú cho đoàn</label>
                                        <textarea class="form-control" id="operation_note" name="operation_note" rows="5" 
                                                  placeholder="Nhập ghi chú điều hành cho đoàn này...&#10;Ví dụ:&#10;- Điểm đón khách: ...&#10;- Lưu ý đặc biệt: ...&#10;- Yêu cầu ăn uống: ..."></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="internal_note" class="form-label">Ghi chú nội bộ (chỉ admin thấy)</label>
                                        <textarea class="form-control" id="internal_note" name="internal_note" rows="3" 
                                                  placeholder="Ghi chú nội bộ cho team điều hành..."></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-1"></i> Lưu ghi chú
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            `;
            
            document.body.insertAdjacentHTML('beforeend', modalHtml);
            const modal = new bootstrap.Modal(document.getElementById('operationNoteModal'));
            modal.show();
            
            document.getElementById('operationNoteForm').addEventListener('submit', async function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                
                // TODO: Implement API endpoint for saving operation notes
                // For now, just show success message
                showAlert('info', 'Chức năng lưu ghi chú đang được phát triển');
                modal.hide();
                
                /* Uncomment when API is ready:
                try {
                    const response = await fetch('/admin/bookings/save-operation-note', {
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
                */
            });
            
            document.getElementById('operationNoteModal').addEventListener('hidden.bs.modal', function() {
                this.remove();
            });
        }
        
        // ==========================================
        // BOOKING ACTIONS (từ table booking)
        // ==========================================
        
        /**
         * Xác nhận booking (chuyển từ HOLD sang PENDING hoặc CONFIRMED)
         */
        async function confirmBooking(bookingId) {
            if (!confirm('Bạn có chắc muốn xác nhận booking này?')) return;
            
            try {
                const response = await fetch(`{{ url('admin/bookings') }}/${bookingId}/confirm`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });
                
                const data = await response.json();
                if (data.success) {
                    showAlert('success', data.message || 'Đã xác nhận booking thành công!');
                    // Clear cache and reload
                    Object.keys(bookingsCache).forEach(key => delete bookingsCache[key]);
                    location.reload();
                } else {
                    showAlert('danger', data.message || 'Có lỗi xảy ra khi xác nhận booking');
                }
            } catch (error) {
                console.error('Error confirming booking:', error);
                showAlert('danger', 'Lỗi: ' + error.message);
            }
        }
        
        /**
         * Huỷ giữ chỗ (booking HOLD)
         */
        async function cancelHoldBooking(bookingId) {
            if (!confirm('Bạn có chắc muốn huỷ giữ chỗ này? Booking sẽ chuyển sang trạng thái CANCELLED.')) return;
            
            try {
                const response = await fetch(`{{ url('admin/bookings') }}/${bookingId}/cancel`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });
                
                const data = await response.json();
                if (data.success) {
                    showAlert('success', data.message || 'Đã huỷ giữ chỗ thành công!');
                    // Clear cache and reload
                    Object.keys(bookingsCache).forEach(key => delete bookingsCache[key]);
                    location.reload();
                } else {
                    showAlert('danger', data.message || 'Có lỗi xảy ra khi huỷ giữ chỗ');
                }
            } catch (error) {
                console.error('Error cancelling hold:', error);
                showAlert('danger', 'Lỗi: ' + error.message);
            }
        }
        
        /**
         * In danh sách khách của booking
         */
        function printGuestList(bookingId) {
            window.open(`{{ url('admin/bookings') }}/${bookingId}/print-guests`, '_blank');
        }
    </script>
@endsection



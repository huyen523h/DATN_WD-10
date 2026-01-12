@extends('layouts.admin')

@section('title', 'Chi tiết lịch khởi hành')

@section('content')
<div class="container mt-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.tours.index') }}">Quản lý Tour</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.tours.manage', $departure->tour_id) }}">{{ $departure->tour->title }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.departures.index') }}?tour_id={{ $departure->tour_id }}">Lịch khởi hành</a></li>
            <li class="breadcrumb-item active">{{ \Carbon\Carbon::parse($departure->departure_date)->format('d/m/Y') }}</li>
        </ol>
    </nav>

    @php
    $departureDateObj = \Carbon\Carbon::parse($departure->departure_date);
    $today = \Carbon\Carbon::today();
    $daysUntilDeparture = $today->diffInDays($departureDateObj, false);
    $isPast = $departureDateObj->isPast();
    $isFull = $departure->seats_available <= 0 || $departure->status === 'sold_out';
        $isCompleted = $departure->tour_status === 'completed';
        $isLocked = $departure->tour_status === 'locked' || $departure->status === 'sold_out';
        $isFinished = $departure->status === 'finished' || $departure->tour_status === 'completed';
        // Quy tắc khóa chỉnh sửa
        $canEditAll = !($isFinished || $isLocked);
        $canEditSchedule = !$isFinished;
        // Biến tương thích cũ
        $canEdit = $canEditAll;
        @endphp

        <!-- Tour Context -->
        <div class="card shadow-sm mb-3 border-left border-primary" style="border-left-width: 4px;">
            <div class="card-body py-2">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <strong>{{ $departure->tour->title }}</strong>
                        <span class="text-muted ms-2">| Ngày khởi hành: {{ \Carbon\Carbon::parse($departure->departure_date)->format('d/m/Y') }}</span>
                    </div>
                    <a href="{{ route('admin.tours.manage', $departure->tour_id) }}" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-home"></i> Quản lý Tour
                    </a>
                </div>
            </div>
        </div>



        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold text-primary">
                <i class="fas fa-calendar-day"></i> Chi tiết lịch khởi hành #{{ $departure->id }}
            </h4>
            <a href="{{ route('admin.departures.index') }}?tour_id={{ $departure->tour_id }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại danh sách
            </a>
        </div>

        <!-- Header (Master) + Small KPI Cards -->
        <div class="mb-4">
            <div class="card shadow-sm mb-3">
                <div class="card-body py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0 fw-bold text-primary">{{ $departure->tour->title ?? 'Tour' }}</h5>
                        <small class="text-muted">Ngày khởi hành: {{ \Carbon\Carbon::parse($departure->departure_date)->format('d/m/Y') }} • Trạng thái: {{ ucfirst($departure->status) }}</small>
                    </div>
                    <div class="text-end">
                        <a href="{{ route('admin.departures.index') }}?tour_id={{ $departure->tour_id }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Quay lại
                        </a>
                    </div>
                </div>
            </div>

            <div class="row g-3">
                @php
                    $totalBookings = $bookings->count();
                    $totalPassengers = $bookings->sum('adults') + $bookings->sum('children');
                    $revenue = $bookings->sum('total_amount');
                    $occupancyPercent = $departure->seats_total ? round(($totalPassengers / $departure->seats_total) * 100) : 0;
                @endphp

                <div class="col-sm-6 col-md-3">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <div class="small text-muted">Tổng booking</div>
                            <div class="h4 fw-bold">{{ $totalBookings }}</div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-md-3">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <div class="small text-muted">Tổng khách</div>
                            <div class="h4 fw-bold">{{ $totalPassengers }} / {{ $departure->seats_total }}</div>
                            <div class="progress mt-2" style="height:8px;">
                                <div class="progress-bar bg-success" role="progressbar" style="width: {{ $occupancyPercent }}%;" aria-valuenow="{{ $occupancyPercent }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-md-3">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <div class="small text-muted">Doanh thu (ước tính)</div>
                            <div class="h4 fw-bold">{{ number_format($revenue) }}₫</div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-md-3">
                    <div class="card shadow-sm h-100">
                        <div class="card-body d-flex flex-column justify-content-between">
                            <div class="small text-muted">Hành động nhanh</div>
                            <div class="mt-2 d-flex gap-2 justify-content-end">
                                <form method="POST" action="{{ route('admin.departures.update_operating', $departure->id) }}">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="lock_departure" value="1">
                                    <button class="btn btn-sm btn-danger" title="Chốt đoàn" @if(!$canEditAll) disabled @endif>
                                        <i class="fas fa-lock"></i> Chốt đoàn
                                    </button>
                                </form>

                                <a href="{{ route('admin.departures.customers.export', $departure->id) }}" class="btn btn-sm btn-outline-primary" title="Xuất danh sách">
                                    <i class="fas fa-file-export"></i> Xuất
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Alert -->
        @if($isPast)
        <div class="alert alert-secondary mb-4">
            <i class="fas fa-info-circle"></i>
            <strong>Lịch khởi hành đã kết thúc</strong> - Một số thao tác đã bị khóa để bảo vệ dữ liệu.
        </div>
        @elseif($isFull)
        <div class="alert alert-warning mb-4">
            <i class="fas fa-exclamation-triangle"></i>
            <strong>Lịch đã hết chỗ</strong> - Không thể thực hiện một số thao tác khi đã đầy.
        </div>
        @elseif($isCompleted)
        <div class="alert alert-info mb-4">
            <i class="fas fa-check-circle"></i>
            <strong>Tour đã hoàn thành</strong> - Một số thao tác đã bị khóa.
        </div>
        @endif

        <!-- Tabs Navigation - Chuẩn hóa theo nghiệp vụ -->
        <ul class="nav nav-tabs mb-4" id="departureTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview" type="button" role="tab">
                    <i class="fas fa-eye"></i> Tổng quan
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="schedule-tab" data-bs-toggle="tab" data-bs-target="#schedule" type="button" role="tab">
                    <i class="fas fa-calendar-alt"></i> Lịch trình
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="services-tab" data-bs-toggle="tab" data-bs-target="#services" type="button" role="tab">
                    <i class="fas fa-concierge-bell"></i> Dịch vụ
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="guides-tab" data-bs-toggle="tab" data-bs-target="#guides" type="button" role="tab">
                    <i class="fas fa-user-tie"></i> Hướng dẫn viên
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="customers-tab" data-bs-toggle="tab" data-bs-target="#customers" type="button" role="tab">
                    <i class="fas fa-users"></i> Khách
                </button>
            </li>
        </ul>

        <!-- Tabs Content -->
        <div class="tab-content" id="departureTabsContent">
            <!-- Tab 1: Tổng quan -->
            <div class="tab-pane fade show active" id="overview" role="tabpanel">
                <div class="card shadow-sm border-0 mb-3">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-info-circle"></i> Thông tin khởi hành</h5>
                    </div>
                    <div class="card-body">
                        @php
                        $bookedCount = isset($bookedGuests) ? (int) $bookedGuests : null;
                        $remainingSeats = !is_null($bookedCount)
                        ? max($departure->seats_total - $bookedCount, 0)
                        : null;

                        $status = $departure->status;
                        $statusBadge = 'bg-secondary';
                        $statusText = 'Không xác định';

                        if ($status === 'cancelled') {
                        $statusBadge = 'bg-danger';
                        $statusText = 'Đã hủy';
                        } elseif ($status === 'finished') {
                        $statusBadge = 'bg-secondary';
                        $statusText = 'Đã kết thúc';
                        } elseif ($status === 'sold_out') {
                        $statusBadge = 'bg-warning text-dark';
                        $statusText = 'Đã đủ khách';
                        } elseif ($status === 'upcoming') {
                        $statusBadge = 'bg-info';
                        $statusText = 'Sắp khởi hành';
                        } elseif ($status === 'available') {
                        $statusBadge = 'bg-success';
                        $statusText = 'Đang mở bán';
                        }
                        @endphp

                        <div class="row g-3">
                            <div class="col-md-6">
                                <table class="table table-sm table-borderless mb-0">
                                    <tbody>
                                        <tr>
                                            <th style="width: 40%;">ID</th>
                                            <td>{{ $departure->id }}</td>
                                        </tr>
                                        <tr>
                                            <th>Tour</th>
                                            <td>{{ $departure->tour->title ?? 'Chưa xác định' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Ngày khởi hành</th>
                                            <td>{{ \Carbon\Carbon::parse($departure->departure_date)->format('d/m/Y') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Trạng thái</th>
                                            <th class="text-center">Thao tác</th>
                                            <td>
                                                <span class="badge {{ $statusBadge }}">{{ $statusText }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Ngày tạo</th>
                                            <td>{{ $departure->created_at ? $departure->created_at->format('d/m/Y H:i') : '---' }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-sm table-borderless mb-0">
                                    <tbody>
                                        @if(!is_null($bookedCount))
                                        <tr>
                                            <th style="width: 40%;">Đã đặt</th>
                                            <td>
                                                <span class="fw-bold text-primary">
                                                    {{ $bookedCount }} / {{ $departure->seats_total }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Còn trống</th>
                                            <td>
                                                <span class="badge bg-{{ $remainingSeats > 0 ? 'success' : 'danger' }}">
                                                    {{ $remainingSeats }}
                                                </span>
                                            </td>
                                        </tr>
                                        @else
                                        <tr>
                                            <th style="width: 40%;">Tổng ghế</th>
                                            <td>{{ $departure->seats_total }}</td>
                                        </tr>
                                        @endif

                                        <tr>
                                            <th>Số ghế tối thiểu</th>
                                            <td class="fw-bold text-primary">{{ number_format($departure->seats_min) }}</td>
                                        </tr>

                                        <tr>
                                            <th>Giá người lớn</th>
                                            <td class="fw-bold text-primary">{{ number_format($departure->price ?? 0, 0, ',', '.') }}₫</td>
                                        </tr>
                                        <tr>
                                            <th>Giá trẻ em</th>
                                            <td>{{ number_format($departure->child_price ?? 0, 0, ',', '.') }}₫</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Thông tin vận hành -->
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0"><i class="fas fa-cogs"></i> Thông tin vận hành</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered align-middle mb-0">
                            <tbody>
                                <tr>
                                    <th style="width: 200px;">Hướng dẫn viên</th>
                                    <td>
                                        @if($departure->guide)
                                        @php
                                        $guideName = $departure->guideProfile->full_name ?? $departure->guide->name;
                                        $guideCode = $departure->guideProfile->code ?? null;
                                        @endphp
                                        <span class="fw-bold">{{ $guideName }}</span>
                                        @if($guideCode)
                                        <span class="badge bg-secondary ms-2">Mã: {{ $guideCode }}</span>
                                        @endif
                                        @else
                                        <span class="text-muted fst-italic">Chưa gán</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Xe</th>
                                    <td>
                                        @if($departure->vehicle)
                                        <span class="fw-bold">
                                            {{ $departure->vehicle->vehicle_type ?? 'Không rõ loại' }}
                                            - {{ $departure->vehicle->license_plate ?? 'Không rõ biển số' }}
                                        </span>
                                        @elseif($departure->vehicle_type || $departure->vehicle_details)
                                        <span class="fw-bold">
                                            {{ $departure->vehicle_type ? 'Xe ' . $departure->vehicle_type . ' chỗ' : '' }}
                                            {{ $departure->vehicle_details ? ' - ' . $departure->vehicle_details : '' }}
                                        </span>
                                        @else
                                        <span class="text-muted fst-italic">Chưa gán</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Số khách đã đặt</th>
                                    <td>
                                        {{ $bookedGuests ?? 0 }} khách
                                    </td>
                                </tr>
                                <tr>
                                    <th>Số khách tối đa</th>
                                    <td>{{ $departure->seats_total }} khách</td>
                                </tr>
                                <tr>
                                    <th>Thời hạn chốt đoàn</th>
                                    <td>
                                        @php
                                        $departureDate = $departure->departure_date ? \Carbon\Carbon::parse($departure->departure_date) : null;
                                        @endphp
                                        @if($departureDate)
                                        {{ $departureDate->copy()->subDays(3)->format('d/m/Y') }}
                                        @else
                                        <span class="text-muted fst-italic">Không xác định (chưa có ngày khởi hành)</span>
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Tab 2: Lịch trình -->
            <div class="tab-pane fade" id="schedule" role="tabpanel">
                <!-- Lịch trình áp dụng - Timeline View -->
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-gradient" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 text-white">
                                <i class="fas fa-calendar-alt"></i> Lịch trình áp dụng - Timeline
                            </h5>
                            <span class="badge bg-light text-dark">
                                {{ ($departure->tour->schedules ?? collect())->count() }} ngày
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        @php
                        $schedules = $departure->tour->schedules ?? collect();
                        $groupedSchedules = $schedules->groupBy('day_number');
                        @endphp

                        @if($schedules->count() > 0)
                        <div class="alert alert-info mb-3">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-info-circle fa-2x me-3"></i>
                                <div>
                                    <strong>Lịch trình này áp dụng cho departure #{{ $departure->id }}</strong>
                                    <br>
                                    <small>Ngày khởi hành: <strong>{{ \Carbon\Carbon::parse($departure->departure_date)->format('d/m/Y') }}</strong></small>
                                    <br>
                                    <small class="fw-bold text-uppercase">Lịch trình được kế thừa từ Tour gốc (READ-ONLY)</small>
                                </div>
                            </div>
                        </div>

                        <!-- Timeline -->
                        <div class="timeline-container">
                            @foreach($groupedSchedules as $dayNumber => $daySchedules)
                            @php
                            $firstSchedule = $daySchedules->first();
                            $departureDate = \Carbon\Carbon::parse($departure->departure_date);
                            $currentDayDate = $departureDate->copy()->addDays($dayNumber - 1);
                            @endphp
                            <div class="timeline-item mb-4">
                                <div class="row">
                                    <!-- Timeline Line -->
                                    <div class="col-md-1 text-center">
                                        <div class="timeline-marker">
                                            <div class="timeline-dot bg-primary">
                                                <span class="text-white fw-bold">{{ $dayNumber }}</span>
                                            </div>
                                            @if(!$loop->last)
                                            <div class="timeline-line"></div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Content -->
                                    <div class="col-md-11">
                                        <div class="card shadow-sm border-left border-primary" style="border-left-width: 4px;">
                                            <div class="card-header bg-light">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <h6 class="mb-0 fw-bold text-primary">
                                                        <i class="fas fa-calendar-day"></i> Ngày {{ $dayNumber }} - {{ $currentDayDate->format('d/m/Y') }}
                                                    </h6>
                                                    @if($firstSchedule->guide)
                                                    <span class="badge bg-info">
                                                        <i class="fas fa-user-tie"></i> {{ $firstSchedule->guide->name }}
                                                    </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                @foreach($daySchedules as $schedule)
                                                <div class="schedule-item mb-3 {{ !$loop->last ? 'border-bottom pb-3' : '' }}">
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <h6 class="fw-bold mb-2">
                                                                <i class="fas fa-map-pin text-danger"></i> {{ $schedule->title }}
                                                            </h6>
                                                            @if($schedule->description)
                                                            <p class="text-muted mb-2" style="font-size: 0.9rem; line-height: 1.6;">
                                                                {{ Str::limit($schedule->description, 200) }}
                                                            </p>
                                                            @endif
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="schedule-meta">
                                                                @if($schedule->location)
                                                                <div class="mb-2">
                                                                    <i class="fas fa-map-marker-alt text-primary"></i>
                                                                    <strong>Địa điểm:</strong>
                                                                    <span class="ms-1">{{ $schedule->location }}</span>
                                                                </div>
                                                                @endif
                                                                @if($schedule->start_time)
                                                                <div class="mb-2">
                                                                    <i class="fas fa-clock text-success"></i>
                                                                    <strong>Giờ khởi hành:</strong>
                                                                    <span class="ms-1">{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}</span>
                                                                </div>
                                                                @endif
                                                                @if($schedule->guide && $schedule->guide->id != ($firstSchedule->guide->id ?? null))
                                                                <div class="mb-2">
                                                                    <i class="fas fa-user-tie text-info"></i>
                                                                    <strong>HDV phụ trách:</strong>
                                                                    <span class="ms-1">{{ $schedule->guide->name }}</span>
                                                                </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="alert alert-warning text-center py-4">
                            <i class="fas fa-exclamation-triangle fa-3x mb-3 text-warning"></i>
                            <h5>Chưa có lịch trình nào được thiết lập cho tour này.</h5>
                            <p class="text-muted mb-0">Vui lòng tạo lịch trình cho tour trước khi xem chi tiết.</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Tab 3: Dịch vụ -->
            <div class="tab-pane fade" id="services" role="tabpanel">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="fas fa-concierge-bell"></i> Dịch vụ</h5>
                    </div>
                    <div class="card-body">
                        @if(!$canEditAll)
                        <div class="alert alert-warning mb-3">
                            <i class="fas fa-lock"></i>
                            Không thể chỉnh sửa thông tin dịch vụ khi lịch đã chốt hoặc đã kết thúc.
                        </div>
                        @endif
                        <form action="{{ route('admin.departures.update_operating', $departure->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="vehicle_id" class="form-label fw-bold">
                                        <i class="fas fa-car"></i> Xe
                                    </label>
                                    <select
                                        class="form-select @error('vehicle_id') is-invalid @enderror"
                                        id="vehicle_id"
                                        name="vehicle_id"
                                        @if(!$canEditAll) disabled @endif>
                                        <option value="">-- Chọn xe --</option>
                                        @foreach($vehicles as $vehicle)
                                        <option value="{{ $vehicle->id }}"
                                            {{ old('vehicle_id', $departure->vehicle_id) == $vehicle->id ? 'selected' : '' }}>
                                            {{ $vehicle->license_plate }}
                                            @if($vehicle->brand)
                                            - {{ $vehicle->brand }}
                                            @endif
                                            @if($vehicle->vehicle_type)
                                            ({{ $vehicle->vehicle_type }} chỗ)
                                            @endif
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('vehicle_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    @if($departure->vehicle)
                                    <div class="mt-2 p-2 bg-light rounded">
                                        <small>
                                            <strong>Biển số:</strong> {{ $departure->vehicle->license_plate }}<br>
                                            @if($departure->vehicle->driver_name)
                                            <strong>Tài xế:</strong> {{ $departure->vehicle->driver_name }}
                                            @if($departure->vehicle->driver_phone)
                                            - {{ $departure->vehicle->driver_phone }}
                                            @endif
                                            @endif
                                        </small>
                                    </div>
                                    @endif
                                </div>

                                <div class="col-md-6">
                                    <label for="bus_company" class="form-label fw-bold">
                                        <i class="fas fa-building"></i> Nhà xe
                                    </label>
                                    <input
                                        type="text"
                                        class="form-control @error('bus_company') is-invalid @enderror"
                                        id="bus_company"
                                        name="bus_company"
                                        value="{{ old('bus_company', $departure->bus_company) }}"
                                        placeholder="Nhập tên nhà xe"
                                        @if(!$canEditAll) readonly @endif>
                                    @error('bus_company')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="assembly_time" class="form-label fw-bold">
                                        <i class="fas fa-clock"></i> Giờ tập trung
                                    </label>
                                    <input
                                        type="time"
                                        class="form-control @error('assembly_time') is-invalid @enderror"
                                        id="assembly_time"
                                        name="assembly_time"
                                        value="{{ old('assembly_time', $departure->assembly_time ? \Carbon\Carbon::parse($departure->assembly_time)->format('H:i') : '') }}"
                                        @if(!$canEditAll) readonly @endif>
                                    @error('assembly_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="pickup_point" class="form-label fw-bold">
                                        <i class="fas fa-map-marker-alt"></i> Điểm đón
                                    </label>
                                    <input
                                        type="text"
                                        class="form-control @error('pickup_point') is-invalid @enderror"
                                        id="pickup_point"
                                        name="pickup_point"
                                        value="{{ old('pickup_point', $departure->pickup_point) }}"
                                        placeholder="Nhập địa điểm đón khách"
                                        @if(!$canEditAll) readonly @endif>
                                    @error('pickup_point')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="departure_instructions" class="form-label fw-bold">
                                    <i class="fas fa-info-circle"></i> Hướng dẫn khởi hành
                                </label>
                                <textarea
                                    class="form-control @error('departure_instructions') is-invalid @enderror"
                                    id="departure_instructions"
                                    name="departure_instructions"
                                    rows="4"
                                    placeholder="Nhập hướng dẫn khởi hành..."
                                    @if(!$canEditAll) readonly @endif>{{ old('departure_instructions', $departure->departure_instructions) }}</textarea>
                                @error('departure_instructions')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.departures.index') }}?tour_id={{ $departure->tour_id }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Hủy
                                </a>
                                <button type="submit" class="btn btn-primary" @if(!$canEditAll) disabled title="Không thể lưu khi lịch đã chốt hoặc đã kết thúc" @endif>
                                    <i class="fas fa-save"></i> Lưu thông tin dịch vụ
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Tab 4: Hướng dẫn viên -->
            <div class="tab-pane fade" id="guides" role="tabpanel">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fas fa-user-tie"></i> Hướng dẫn viên</h5>
                    </div>
                    <div class="card-body">
                        @if($departure->guide || $departure->backupGuide)
                        <!-- Trường hợp ĐÃ có HDV -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="card border-success">
                                    <div class="card-header bg-success text-white">
                                        <h6 class="mb-0"><i class="fas fa-user-tie"></i> HDV chính</h6>
                                    </div>
                                    <div class="card-body">
                                        @if($departure->guide)
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="avatar-circle bg-success text-white me-3">
                                                <i class="fas fa-user fa-2x"></i>
                                            </div>
                                            <div>
                                                <h5 class="mb-1">{{ $departure->guide->name }}</h5>
                                                @if($departure->guide->phone)
                                                <p class="text-muted mb-0">
                                                    <i class="fas fa-phone"></i> {{ $departure->guide->phone }}
                                                </p>
                                                @endif
                                                @if($departure->guide->email)
                                                <p class="text-muted mb-0">
                                                    <i class="fas fa-envelope"></i> {{ $departure->guide->email }}
                                                </p>
                                                @endif
                                            </div>
                                        </div>
                                        @else
                                        <p class="text-muted mb-0">Chưa gán</p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card border-info">
                                    <div class="card-header bg-info text-white">
                                        <h6 class="mb-0"><i class="fas fa-user-shield"></i> HDV phụ</h6>
                                    </div>
                                    <div class="card-body">
                                        @if($departure->backupGuide)
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="avatar-circle bg-info text-white me-3">
                                                <i class="fas fa-user fa-2x"></i>
                                            </div>
                                            <div>
                                                <h5 class="mb-1">{{ $departure->backupGuide->name }}</h5>
                                                @if($departure->backupGuide->phone)
                                                <p class="text-muted mb-0">
                                                    <i class="fas fa-phone"></i> {{ $departure->backupGuide->phone }}
                                                </p>
                                                @endif
                                                @if($departure->backupGuide->email)
                                                <p class="text-muted mb-0">
                                                    <i class="fas fa-envelope"></i> {{ $departure->backupGuide->email }}
                                                </p>
                                                @endif
                                            </div>
                                        </div>
                                        @else
                                        <p class="text-muted mb-0">Chưa gán</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Form phân công HDV -->
                        @if($canEditAll)
                        <div id="assign-guide-btn" class="card border-primary">
                            <div class="card-header bg-primary text-white">
                                <h6 class="mb-0"><i class="fas fa-edit"></i> Phân công hướng dẫn viên</h6>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('admin.departures.update_operating', $departure->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="guide_id_form" class="form-label fw-bold">
                                                <i class="fas fa-user-tie"></i> Hướng dẫn viên chính
                                            </label>
                                            <select
                                                class="form-select @error('guide_id') is-invalid @enderror"
                                                id="guide_id_form"
                                                name="guide_id">
                                                <option value="">-- Chọn hướng dẫn viên --</option>
                                                @foreach($guides as $guide)
                                                <option value="{{ $guide->id }}"
                                                    {{ old('guide_id', $departure->guide_id) == $guide->id ? 'selected' : '' }}>
                                                    {{ $guide->name }}
                                                    @if($guide->phone)
                                                    - {{ $guide->phone }}
                                                    @endif
                                                </option>
                                                @endforeach
                                            </select>
                                            @error('guide_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label for="backup_guide_id_form" class="form-label fw-bold">
                                                <i class="fas fa-user-shield"></i> Hướng dẫn viên dự phòng
                                            </label>
                                            <select
                                                class="form-select @error('backup_guide_id') is-invalid @enderror"
                                                id="backup_guide_id_form"
                                                name="backup_guide_id">
                                                <option value="">-- Chọn hướng dẫn viên dự phòng --</option>
                                                @foreach($guides as $guide)
                                                <option value="{{ $guide->id }}"
                                                    {{ old('backup_guide_id', $departure->backup_guide_id) == $guide->id ? 'selected' : '' }}>
                                                    {{ $guide->name }}
                                                    @if($guide->phone)
                                                    - {{ $guide->phone }}
                                                    @endif
                                                </option>
                                                @endforeach
                                            </select>
                                            @error('backup_guide_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-success">
                                            <i class="fas fa-save"></i> Lưu phân công
                                        </button>
                                        @if($departure->guide || $departure->backupGuide)
                                        <button type="button" class="btn btn-outline-danger" onclick="if(confirm('Bạn có chắc muốn gỡ tất cả HDV?')) { document.getElementById('guide_id_form').value=''; document.getElementById('backup_guide_id_form').value=''; this.form.submit(); }">
                                            <i class="fas fa-times"></i> Gỡ tất cả
                                        </button>
                                        @endif
                                    </div>
                                </form>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Tab 5: Khách -->
            <div class="tab-pane fade" id="customers" role="tabpanel">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-users"></i> Danh sách khách</h5>
                    </div>
                    <div class="card-body">
                        <!-- Hành động nhanh + Bộ lọc & Tìm kiếm -->
                        <div class="d-flex flex-wrap gap-2 mb-3 align-items-center">
                            <form action="{{ route('admin.departures.customers', $departure->id) }}" method="GET" class="d-flex gap-2 align-items-center">
                                <input type="text" name="q" class="form-control form-control-sm" placeholder="Tìm theo mã booking hoặc tên khách" value="{{ request('q') }}" style="min-width:240px;">
                                <select name="status" class="form-select form-select-sm">
                                    <option value="">Tất cả trạng thái</option>
                                    <option value="pending" {{ request('status')=='pending' ? 'selected' : '' }}>Chờ</option>
                                    <option value="confirmed" {{ request('status')=='confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
                                    <option value="paid" {{ request('status')=='paid' ? 'selected' : '' }}>Đã thanh toán</option>
                                    <option value="cancelled" {{ request('status')=='cancelled' ? 'selected' : '' }}>Đã hủy</option>
                                </select>
                                <select name="guide_id" class="form-select form-select-sm">
                                    <option value="">Tất cả HDV</option>
                                    @foreach($guides as $g)
                                    <option value="{{ $g->id }}" {{ request('guide_id') == $g->id ? 'selected' : '' }}>{{ $g->name }}</option>
                                    @endforeach
                                </select>
                                <button class="btn btn-sm btn-outline-primary" type="submit">Lọc</button>
                                <a href="{{ route('admin.departures.customers', $departure->id) }}" class="btn btn-sm btn-light">Reset</a>
                            </form>

                            <div class="ms-auto d-flex gap-2">
                                <a href="{{ route('admin.departures.customers.export', $departure->id) }}" class="btn btn-outline-success btn-sm">
                                    <i class="fas fa-file-export"></i> Xuất (CSV)
                                </a>
                                <a href="{{ route('admin.departures.customers', $departure->id) }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus"></i> Thêm khách
                                </a>
                            </div>
                        </div>

                        @php
                            $filteredBookings = $bookings;
                            if(request('q')) {
                                $q = strtolower(request('q'));
                                $filteredBookings = $filteredBookings->filter(function($b) use($q) {
                                    return str_contains(strtolower('#'.$b->id), $q) || str_contains(strtolower($b->user->name ?? ''), $q);
                                });
                            }
                            if(request('status')) { $filteredBookings = $filteredBookings->where('status', request('status')); }
                            if(request('guide_id')) {
                                $gid = request('guide_id');
                                $filteredBookings = $filteredBookings->filter(function($b) use($gid) {
                                    return ($b->departure && ($b->departure->guide_id == $gid || $b->departure->backup_guide_id == $gid));
                                });
                            }
                        @endphp

                        <!-- Tổng quan phân loại -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-circle bg-primary text-white me-3" style="width:50px;height:50px;">
                                                <i class="fas fa-user"></i>
                                            </div>
                                            <div>
                                                <div class="text-muted small">Người lớn (>11)</div>
                                                <div class="h5 mb-0">{{ $adultCount }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-circle bg-info text-white me-3" style="width:50px;height:50px;">
                                                <i class="fas fa-child"></i>
                                            </div>
                                            <div>
                                                <div class="text-muted small">Trẻ em (2–11)</div>
                                                <div class="h5 mb-0">{{ $childCount }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Bảng danh sách khách -->
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Khách hàng</th>
                                        <th>Liên hệ</th>
                                        <th class="text-center">Người lớn</th>
                                        <th class="text-center">Trẻ em</th>
                                        <th>Trạng thái</th>
                                        <th>Ngày đặt</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($bookings as $booking)
                                    <tr>
                                        <td><strong>#{{ $booking->id }}</strong></td>
                                        <td>{{ $booking->user->name ?? 'Khách lẻ' }}</td>
                                        <td>
                                            <div class="small text-muted">
                                                @if($booking->user && $booking->user->phone)
                                                <div><i class="fas fa-phone"></i> {{ $booking->user->phone }}</div>
                                                @endif
                                                @if($booking->user && $booking->user->email)
                                                <div><i class="fas fa-envelope"></i> {{ $booking->user->email }}</div>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="text-center fw-bold">{{ $booking->adults }}</td>
                                        <td class="text-center fw-bold text-info">{{ $booking->children }}</td>
                                        <td>
                                                @php
                                                $status = $booking->status;
                                                $badge = 'bg-secondary';
                                                $text = ucfirst($status);
                                                if ($status === 'pending') { $badge = 'bg-warning text-dark'; $text = 'Chờ'; }
                                                elseif ($status === 'confirmed') { $badge = 'bg-info text-dark'; $text = 'Đã xác nhận'; }
                                                elseif ($status === 'paid') { $badge = 'bg-success'; $text = 'Đã thanh toán'; }
                                                elseif ($status === 'completed') { $badge = 'bg-primary'; $text = 'Hoàn thành'; }
                                                elseif ($status === 'cancelled') { $badge = 'bg-danger'; $text = 'Hủy'; }
                                                @endphp
                                                <span class="badge {{ $badge }}">{{ $text }}</span>
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ $booking->created_at ? $booking->created_at->format('d/m/Y') : '---' }}</small>
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex gap-1 justify-content-center">
                                                @if($booking->user && $booking->user->email)
                                                <a href="mailto:{{ $booking->user->email }}?subject=Nhắc thanh toán - Booking #{{ $booking->id }}" class="btn btn-sm btn-outline-danger" title="Gửi email nhắc nợ">
                                                    <i class="fas fa-envelope"></i>
                                                </a>
                                                @endif

                                                <button class="btn btn-sm btn-outline-success" title="Xác nhận thanh toán (tương tác)" onclick="alert('Xác nhận thanh toán cho booking #'+{{ $booking->id }});">
                                                    <i class="fas fa-credit-card"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted py-4">
                                            <i class="fas fa-users-slash fa-2x mb-2"></i>
                                            <div>Chưa có khách cho lịch khởi hành này.</div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab 2: Thông tin vận hành (Cũ - Ẩn) -->
        <div class="tab-pane fade d-none" id="operating" role="tabpanel">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-cog"></i> Thông tin vận hành</h5>
                </div>
                <div class="card-body">
                    @if(!$canEdit)
                    <div class="alert alert-warning mb-3">
                        <i class="fas fa-lock"></i>
                        Không thể chỉnh sửa thông tin vận hành khi lịch đã kết thúc hoặc đã hoàn thành.
                    </div>
                    @endif
                    <form action="{{ route('admin.departures.update_operating', $departure->id) }}" method="POST" id="operating-form">
                        @csrf
                        @method('PUT')

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="guide_id" class="form-label fw-bold">
                                    <i class="fas fa-user-tie"></i> Hướng dẫn viên chính
                                </label>
                                <select
                                    class="form-select @error('guide_id') is-invalid @enderror"
                                    id="guide_id"
                                    name="guide_id"
                                    @if(!$canEdit) disabled @endif>
                                    <option value="">-- Chọn hướng dẫn viên --</option>
                                    @foreach($guides as $guide)
                                    <option value="{{ $guide->id }}"
                                        {{ old('guide_id', $departure->guide_id) == $guide->id ? 'selected' : '' }}>
                                        {{ $guide->name }}
                                        @if($guide->phone)
                                        - {{ $guide->phone }}
                                        @endif
                                    </option>
                                    @endforeach
                                </select>
                                @error('guide_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                @if($departure->guide && $departure->guide->phone)
                                <small class="text-muted">
                                    <i class="fas fa-phone"></i> Số điện thoại: {{ $departure->guide->phone }}
                                </small>
                                @endif
                            </div>

                            <div class="col-md-6">
                                <label for="backup_guide_id" class="form-label fw-bold">
                                    <i class="fas fa-user-shield"></i> Hướng dẫn viên dự phòng
                                </label>
                                <select
                                    class="form-select @error('backup_guide_id') is-invalid @enderror"
                                    id="backup_guide_id"
                                    name="backup_guide_id"
                                    @if(!$canEdit) disabled @endif>
                                    <option value="">-- Chọn hướng dẫn viên dự phòng --</option>
                                    @foreach($guides as $guide)
                                    <option value="{{ $guide->id }}"
                                        {{ old('backup_guide_id', $departure->backup_guide_id) == $guide->id ? 'selected' : '' }}>
                                        {{ $guide->name }}
                                        @if($guide->phone)
                                        - {{ $guide->phone }}
                                        @endif
                                    </option>
                                    @endforeach
                                </select>
                                @error('backup_guide_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="vehicle_id" class="form-label fw-bold">
                                    <i class="fas fa-car"></i> Xe
                                </label>
                                <select
                                    class="form-select @error('vehicle_id') is-invalid @enderror"
                                    id="vehicle_id"
                                    name="vehicle_id"
                                    @if(!$canEdit) disabled @endif>
                                    <option value="">-- Chọn xe --</option>
                                    @foreach($vehicles as $vehicle)
                                    <option value="{{ $vehicle->id }}"
                                        {{ old('vehicle_id', $departure->vehicle_id) == $vehicle->id ? 'selected' : '' }}>
                                        {{ $vehicle->license_plate }}
                                        @if($vehicle->brand)
                                        - {{ $vehicle->brand }}
                                        @endif
                                        @if($vehicle->vehicle_type)
                                        ({{ $vehicle->vehicle_type }} chỗ)
                                        @endif
                                    </option>
                                    @endforeach
                                </select>
                                @error('vehicle_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                @if($departure->vehicle)
                                <small class="text-muted">
                                    Biển số: <strong>{{ $departure->vehicle->license_plate }}</strong>
                                    @if($departure->vehicle->driver_name)
                                    | Tài xế: {{ $departure->vehicle->driver_name }}
                                    @if($departure->vehicle->driver_phone)
                                    - {{ $departure->vehicle->driver_phone }}
                                    @endif
                                    @endif
                                </small>
                                @endif
                            </div>

                            <div class="col-md-6">
                                <label for="bus_company" class="form-label fw-bold">
                                    <i class="fas fa-building"></i> Nhà xe
                                </label>
                                <input
                                    type="text"
                                    class="form-control @error('bus_company') is-invalid @enderror"
                                    id="bus_company"
                                    name="bus_company"
                                    value="{{ old('bus_company', $departure->bus_company) }}"
                                    placeholder="Nhập tên nhà xe..."
                                    @if(!$canEdit) readonly @endif>
                                @error('bus_company')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="assembly_time" class="form-label fw-bold">
                                    <i class="fas fa-clock"></i> Giờ tập trung
                                </label>
                                <input
                                    type="time"
                                    class="form-control @error('assembly_time') is-invalid @enderror"
                                    id="assembly_time"
                                    name="assembly_time"
                                    value="{{ old('assembly_time', $departure->assembly_time ? \Carbon\Carbon::parse($departure->assembly_time)->format('H:i') : ($departure->departure_time ? \Carbon\Carbon::parse($departure->departure_time)->format('H:i') : '')) }}"
                                    @if(!$canEdit) readonly @endif>
                                @error('assembly_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="pickup_point" class="form-label fw-bold">
                                    <i class="fas fa-map-marker-alt"></i> Điểm đón
                                </label>
                                <input
                                    type="text"
                                    class="form-control @error('pickup_point') is-invalid @enderror"
                                    id="pickup_point"
                                    name="pickup_point"
                                    value="{{ old('pickup_point', $departure->pickup_point ?? $departure->departure_location ?? $departure->meeting_point) }}"
                                    placeholder="Nhập điểm đón..."
                                    @if(!$canEdit) readonly @endif>
                                @error('pickup_point')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="departure_instructions" class="form-label fw-bold">
                                <i class="fas fa-info-circle"></i> Hướng dẫn tập trung
                            </label>
                            <textarea
                                class="form-control @error('departure_instructions') is-invalid @enderror"
                                id="departure_instructions"
                                name="departure_instructions"
                                rows="3"
                                placeholder="Nhập hướng dẫn tập trung..."
                                @if(!$canEdit) readonly @endif>{{ old('departure_instructions', $departure->departure_instructions) }}</textarea>
                            @error('departure_instructions')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.departures.index') }}?tour_id={{ $departure->tour_id }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Hủy
                            </a>
                            <button type="submit" class="btn btn-primary" @if(!$canEdit) disabled title="Không thể lưu khi lịch đã kết thúc hoặc đã hoàn thành" @endif>
                                <i class="fas fa-save"></i> Lưu thông tin vận hành
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Tab 3: Thông tin điều hành -->
        <div class="tab-pane fade" id="management" role="tabpanel">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="fas fa-clipboard-list"></i> Thông tin điều hành</h5>
                </div>
                <div class="card-body">
                    @if(!$canEdit)
                    <div class="alert alert-warning mb-3">
                        <i class="fas fa-lock"></i>
                        Không thể chỉnh sửa thông tin điều hành khi lịch đã kết thúc hoặc đã hoàn thành.
                    </div>
                    @endif
                    <form action="{{ route('admin.departures.update_management', $departure->id) }}" method="POST" enctype="multipart/form-data" id="management-form">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="management_notes" class="form-label fw-bold">
                                <i class="fas fa-sticky-note"></i> Ghi chú điều hành
                            </label>
                            <textarea
                                class="form-control @error('management_notes') is-invalid @enderror"
                                id="management_notes"
                                name="management_notes"
                                rows="5"
                                placeholder="Nhập ghi chú điều hành..."
                                @if(!$canEdit) readonly @endif>{{ old('management_notes', $departure->management_notes) }}</textarea>
                            @error('management_notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="tour_status" class="form-label fw-bold">
                                <i class="fas fa-flag"></i> Trạng thái tour
                            </label>
                            <select
                                class="form-select @error('tour_status') is-invalid @enderror"
                                id="tour_status"
                                name="tour_status"
                                @if(!$canEdit) disabled @endif>
                                <option value="preparing" {{ old('tour_status', $departure->tour_status ?? 'preparing') === 'preparing' ? 'selected' : '' }}>
                                    Chuẩn bị
                                </option>
                                <option value="running" {{ old('tour_status', $departure->tour_status ?? 'preparing') === 'running' ? 'selected' : '' }}>
                                    Đang chạy
                                </option>
                                <option value="completed" {{ old('tour_status', $departure->tour_status ?? 'preparing') === 'completed' ? 'selected' : '' }}>
                                    Hoàn thành
                                </option>
                                <option value="has_issue" {{ old('tour_status', $departure->tour_status ?? 'preparing') === 'has_issue' ? 'selected' : '' }}>
                                    Có sự cố
                                </option>
                            </select>
                            @error('tour_status')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="guest_list_file" class="form-label fw-bold">
                                <i class="fas fa-file-pdf"></i> File đính kèm: Danh sách khách (PDF)
                            </label>
                            @if($departure->guest_list_file)
                            <div class="mb-2">
                                <a href="{{ asset('storage/' . $departure->guest_list_file) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-download"></i> Xem file hiện tại
                                </a>
                                <small class="text-muted d-block mt-1">File: {{ basename($departure->guest_list_file) }}</small>
                            </div>
                            @endif
                            <input
                                type="file"
                                class="form-control @error('guest_list_file') is-invalid @enderror"
                                id="guest_list_file"
                                name="guest_list_file"
                                accept=".pdf"
                                @if(!$canEdit) disabled @endif>
                            <small class="form-text text-muted">Chỉ chấp nhận file PDF</small>
                            @error('guest_list_file')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.departures.index') }}?tour_id={{ $departure->tour_id }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Hủy
                            </a>
                            <button type="submit" class="btn btn-primary" @if(!$canEdit) disabled title="Không thể lưu khi lịch đã kết thúc hoặc đã hoàn thành" @endif>
                                <i class="fas fa-save"></i> Lưu thông tin điều hành
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
</div>


<!-- Action Buttons -->
<div class="card shadow-sm border-0 mt-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h6 class="text-muted mb-0">Thao tác khác</h6>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.departures.edit', $departure->id) }}"
                    class="btn btn-warning text-white"
                    @if(!$canEdit) onclick="alert('Không thể chỉnh sửa khi lịch đã kết thúc hoặc đã hoàn thành'); return false;" @endif>
                    <i class="fas fa-edit"></i> Chỉnh sửa thông tin khởi hành
                </a>
                <form action="{{ route('admin.departures.destroy', $departure->id) }}" method="POST"
                    onsubmit="return confirm('Bạn có chắc muốn xóa lịch này?')" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="btn btn-danger"
                        @if($isPast || $isCompleted) disabled title="Không thể xóa lịch đã kết thúc hoặc đã hoàn thành" @endif>
                        <i class="fas fa-trash-alt"></i> Xóa
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
</div>

@push('styles')
<style>
    .nav-tabs .nav-link {
        color: #495057;
    }

    .nav-tabs .nav-link.active {
        color: #0d6efd;
        font-weight: 600;
    }

    .table th {
        background-color: #f8f9fa;
        font-weight: 600;
    }

    .card[style*="gradient"] {
        border: none;
    }

    .btn-light:hover {
        transform: translateY(-2px);
        transition: all 0.3s ease;
    }

    form:has(button[disabled]) {
        opacity: 0.7;
    }

    input[disabled],
    select[disabled],
    textarea[disabled] {
        background-color: #e9ecef;
        cursor: not-allowed;
    }

    /* Timeline Styles */
    .timeline-container {
        position: relative;
        padding-left: 20px;
    }

    .timeline-item {
        position: relative;
    }

    .timeline-marker {
        position: relative;
        height: 100%;
    }

    .timeline-dot {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        position: relative;
        z-index: 2;
    }

    .timeline-line {
        position: absolute;
        left: 50%;
        top: 50px;
        width: 2px;
        height: calc(100% + 20px);
        background: linear-gradient(to bottom, #667eea, #764ba2);
        transform: translateX(-50%);
        z-index: 1;
    }

    .schedule-item {
        transition: all 0.3s ease;
    }

    .schedule-item:hover {
        background-color: #f8f9fa;
        border-radius: 8px;
        padding: 10px;
        margin: -10px;
    }

    .schedule-meta {
        font-size: 0.9rem;
    }

    .schedule-meta i {
        width: 20px;
    }

    .avatar-circle {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>
@endpush

@push('scripts')
<script>
    document.getElementById('operating-form')?.addEventListener('submit', function(e) {
        @if(!$canEdit)
        e.preventDefault();
        alert('Không thể lưu khi lịch đã kết thúc hoặc đã hoàn thành');
        return false;
        @endif
    });

    document.getElementById('management-form')?.addEventListener('submit', function(e) {
        @if(!$canEdit)
        e.preventDefault();
        alert('Không thể lưu khi lịch đã kết thúc hoặc đã hoàn thành');
        return false;
        @endif
    });
</script>
@endpush
@endsection
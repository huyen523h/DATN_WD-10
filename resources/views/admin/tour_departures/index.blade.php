@extends('layouts.admin')

@section('title', 'Danh sách lịch khởi hành')

@push('styles')
<style>
    .stats-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        border-left: 4px solid;
    }
    .stats-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15) !important;
    }
    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
        cursor: pointer;
    }
    
    /* Fix table layout */
    .table {
        table-layout: fixed;
        width: 100%;
    }
    
    .table th,
    .table td {
        vertical-align: middle;
        text-align: center;
        padding: 14px 12px !important;
        word-wrap: break-word;
        font-size: 0.9rem;
        line-height: 1.5;
        border: none !important;
        box-sizing: border-box;
    }
    
    .table th {
        background-color: #f8f9fa;
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase;
        color: #495057;
        border-bottom: 2px solid #dee2e6;
        white-space: nowrap;
    }
    
    /* Column widths - Tối ưu theo thứ tự: ID | Ngày | Trạng thái | Ghế | Giá | Ngày tạo | Thao tác */
    .table th:nth-child(1),
    .table td:nth-child(1) {
        width: 5%;
    }
    
    .table th:nth-child(2),
    .table td:nth-child(2) {
        width: 12%;
    }
    
    .table th:nth-child(3),
    .table td:nth-child(3) {
        width: 12%;
    }
    
    .table th:nth-child(4),
    .table td:nth-child(4) {
        width: 12%;
    }
    
    .table th:nth-child(5),
    .table td:nth-child(5) {
        width: 12%;
    }
    
    .table th:nth-child(6),
    .table td:nth-child(6) {
        width: 10%;
    }
    
    .table th:nth-child(7),
    .table td:nth-child(7) {
        width: 12%;
        text-align: center !important;
    }
    
    .badge {
        font-size: 0.75rem;
        padding: 0.35em 0.65em;
        font-weight: 600;
        white-space: nowrap;
    }
    
    .action-buttons {
        white-space: nowrap;
    }
    
    .action-buttons .btn {
        margin: 1px;
        transition: all 0.2s ease;
        min-width: 32px;
    }
    
    .action-buttons .btn:hover {
        transform: scale(1.05);
    }
    
    .price-cell {
        font-weight: 600;
        color: #0d6efd;
        white-space: nowrap;
    }
    
    .seats-cell {
        font-weight: 600;
    }
    
    .seats-available {
        color: #198754;
    }
    
    .seats-low {
        color: #ffc107;
    }
    
    .seats-full {
        color: #dc3545;
    }
    
    .tour-title-cell {
        text-align: left !important;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>
@endpush

@section('content')
    <div class="container-fluid mt-4">
        <!-- Breadcrumb -->
        @if(isset($tour))
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.tours.index') }}">Quản lý Tour</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.tours.manage', $tour->id) }}">{{ Str::limit($tour->title, 50) }}</a></li>
                <li class="breadcrumb-item active">Lịch khởi hành</li>
            </ol>
        </nav>
        @endif

        <!-- Tour Context Info -->
        @if(isset($tour))
        <div class="card shadow-sm mb-4 border-left border-success" style="border-left-width: 4px;">
            <div class="card-body py-3">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <div>
                        <h5 class="mb-1">
                            <i class="fas fa-map-marked-alt text-success"></i> 
                            <strong>{{ $tour->title }}</strong>
                        </h5>
                        <p class="text-muted mb-0">
                            <span class="badge bg-light text-dark">ID: {{ $tour->id }}</span>
                            @if($tour->duration_days)
                                <span class="badge bg-light text-dark ms-2">{{ $tour->duration_days }} ngày</span>
                            @endif
                            @if($tour->status)
                                <span class="badge bg-{{ $tour->status === 'active' ? 'success' : 'secondary' }} ms-2">
                                    {{ $tour->status === 'active' ? 'Đang hoạt động' : 'Tạm dừng' }}
                                </span>
                            @endif
                        </p>
                    </div>
                    <a href="{{ route('admin.tours.manage', $tour->id) }}" class="btn btn-primary mt-2 mt-md-0">
                        <i class="fas fa-home"></i> Quản lý Tour
                    </a>
                </div>
            </div>
        </div>
        @endif

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card stats-card shadow-sm border-left border-primary" style="border-left-width: 4px;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-2">Tổng lịch khởi hành</h6>
                                <h3 class="mb-0 text-primary">{{ $totalDepartures ?? $departures->total() }}</h3>
                            </div>
                            <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-calendar-alt fa-2x text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card stats-card shadow-sm border-left border-success" style="border-left-width: 4px;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-2">Còn chỗ</h6>
                                <h3 class="mb-0 text-success">{{ $availableDepartures ?? 0 }}</h3>
                            </div>
                            <div class="bg-success bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-check-circle fa-2x text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card stats-card shadow-sm border-left border-danger" style="border-left-width: 4px;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-2">Hết chỗ</h6>
                                <h3 class="mb-0 text-danger">{{ $soldOutDepartures ?? 0 }}</h3>
                            </div>
                            <div class="bg-danger bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-times-circle fa-2x text-danger"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card stats-card shadow-sm border-left border-secondary" style="border-left-width: 4px;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-2">Đã kết thúc</h6>
                                <h3 class="mb-0 text-secondary">{{ $finishedDepartures ?? 0 }}</h3>
                            </div>
                            <div class="bg-secondary bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-flag-checkered fa-2x text-secondary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Header with Actions -->
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
            <div>
                <h4 class="fw-bold text-primary mb-1">
                    <i class="fas fa-plane-departure"></i> Danh sách lịch khởi hành
                </h4>
                @if(isset($tour))
                    <p class="text-muted mb-0">
                        <strong>Tour:</strong> {{ $tour->title }}
                        @if($tour->duration_days)
                            <span class="badge bg-light text-dark ms-2">{{ $tour->duration_days }} ngày</span>
                        @endif
                    </p>
                @endif
            </div>
            <div class="mt-2 mt-md-0">
                @if(isset($tour))
                    <a href="{{ route('admin.tours.manage', $tour->id) }}" class="btn btn-outline-secondary me-2">
                        <i class="fas fa-arrow-left"></i> Quay lại
                    </a>
                @endif
                <a href="{{ route('admin.departures.create') }}{{ isset($tour) ? '?tour_id=' . $tour->id : '' }}" 
                   class="btn btn-primary">
                    <i class="fas fa-plus"></i> Thêm lịch khởi hành
                </a>
            </div>
        </div>

        <!-- Alerts -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-times-circle"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('warning'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle"></i> {{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Table Card -->
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-list"></i> Danh sách
                    </h5>
                    <span class="badge bg-primary">{{ $departures->total() }} lịch khởi hành</span>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0 py-3 px-3">ID</th>
                                <th class="border-0 py-3 px-3">NGÀY KHỞI HÀNH</th>
                                <th class="border-0 py-3 px-3">TRẠNG THÁI</th>
                                <th class="border-0 py-3 px-3">GHẾ TRỐNG/TỔNG</th>
                                <th class="border-0 py-3 px-3">GIÁ NGƯỜI LỚN</th>
                                <th class="border-0 py-3 px-3">NGÀY TẠO</th>
                                <th class="border-0 py-3 px-3 text-end">THAO TÁC</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($departures as $departure)
                                @php
                                    $departureDate = \Carbon\Carbon::parse($departure->departure_date);
                                    $today = \Carbon\Carbon::today();
                                    $daysUntilDeparture = $today->diffInDays($departureDate, false);
                                    $isPast = $departureDate->isPast();
                                    $isLocked = $departure->tour_status === 'locked' || $departure->status === 'sold_out';
                                    $isFinished = $departure->status === 'finished' || $departure->tour_status === 'completed';
                                    
                                    $seatsPercentage = $departure->seats_total > 0 
                                        ? ($departure->seats_available / $departure->seats_total) * 100 
                                        : 0;
                                    $seatsClass = $seatsPercentage > 50 ? 'seats-available' : ($seatsPercentage > 20 ? 'seats-low' : 'seats-full');
                                    
                                    // Xác định trạng thái theo quy tắc mới
                                    $departureStatus = 'available'; // Mặc định
                                    $statusBadge = 'bg-success';
                                    $statusText = 'Đang bán';
                                    $statusIcon = 'fa-shopping-cart';
                                    
                                    if ($isFinished) {
                                        $departureStatus = 'finished';
                                        $statusBadge = 'bg-secondary';
                                        $statusText = 'Đã kết thúc';
                                        $statusIcon = 'fa-archive';
                                    } elseif ($isLocked) {
                                        $departureStatus = 'locked';
                                        $statusBadge = 'bg-warning text-dark';
                                        $statusText = 'Đã chốt';
                                        $statusIcon = 'fa-lock';
                                    } elseif ($daysUntilDeparture >= 0 && $daysUntilDeparture <= 7) {
                                        $departureStatus = 'upcoming';
                                        $statusBadge = 'bg-info';
                                        $statusText = 'Sắp khởi hành';
                                        $statusIcon = 'fa-clock';
                                    }
                                @endphp
                                <tr onclick="window.location='{{ route('admin.departures.show', $departure->id) }}'" style="cursor: pointer;">
                                    <td class="px-3">
                                        <strong class="text-primary">#{{ $departure->id }}</strong>
                                    </td>
                                    <td class="px-3">
                                        <div>
                                            <i class="fas fa-calendar text-primary"></i>
                                            <strong>{{ $departureDate->format('d/m/Y') }}</strong>
                                        </div>
                                        @if($daysUntilDeparture >= 0 && $daysUntilDeparture <= 7)
                                            <small class="text-info d-block">
                                                <i class="fas fa-hourglass-half"></i> Còn {{ $daysUntilDeparture }} ngày
                                            </small>
                                        @elseif($isPast)
                                            <small class="text-muted d-block">Đã qua</small>
                                        @endif
                                    </td>
                                    <td class="px-3">
                                        <span class="badge {{ $statusBadge }}">
                                            <i class="fas {{ $statusIcon }}"></i> {{ $statusText }}
                                        </span>
                                    </td>
                                    <td class="px-3">
                                        <div class="seats-cell {{ $seatsClass }}">
                                            <div>
                                                <strong class="fs-6">{{ $departure->seats_available }}</strong>
                                                <span class="text-muted">/{{ $departure->seats_total }}</span>
                                            </div>
                                            <div class="progress mt-1" style="height: 5px;">
                                                <div class="progress-bar bg-{{ $seatsPercentage > 50 ? 'success' : ($seatsPercentage > 20 ? 'warning' : 'danger') }}" 
                                                     role="progressbar" 
                                                     style="width: {{ $seatsPercentage }}%">
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-3 price-cell">
                                        <strong>{{ number_format($departure->price ?? 0, 0, ',', '.') }}₫</strong>
                                    </td>
                                    <td class="px-3">
                                        <small class="text-muted">
                                            {{ $departure->created_at ? $departure->created_at->format('d/m/Y') : '---' }}
                                        </small>
                                    </td>
                                    <td class="px-3 text-end action-buttons" onclick="event.stopPropagation();">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('admin.departures.show', $departure->id) }}"
                                                class="btn btn-sm btn-outline-primary" 
                                                title="Xem chi tiết"
                                                onclick="event.stopPropagation();">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if(isset($tour))
                                            <a href="{{ route('admin.tours.manage', $departure->tour_id) }}"
                                                class="btn btn-sm btn-outline-info" 
                                                title="Quản lý Tour"
                                                onclick="event.stopPropagation();">
                                                <i class="fas fa-cog"></i>
                                            </a>
                                            @endif
                                            @if (!$isFinished && !$isLocked)
                                                <a href="{{ route('admin.departures.edit', $departure->id) }}"
                                                    class="btn btn-sm btn-outline-warning" 
                                                    title="Chỉnh sửa"
                                                    onclick="event.stopPropagation();">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('admin.departures.destroy', $departure->id) }}"
                                                    method="POST" 
                                                    class="d-inline"
                                                    onsubmit="return confirm('Bạn có chắc muốn xóa lịch này?')"
                                                    onclick="event.stopPropagation();">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Xóa">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            @elseif($isLocked)
                                                <button type="button" class="btn btn-sm btn-outline-secondary" disabled title="Đã chốt - Chỉ xem">
                                                    <i class="fas fa-lock"></i>
                                                </button>
                                            @else
                                                <button type="button" class="btn btn-sm btn-outline-secondary" disabled title="Đã kết thúc - Chỉ xem">
                                                    <i class="fas fa-archive"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="fas fa-inbox fa-3x mb-3"></i>
                                            <p class="mb-0">Chưa có lịch khởi hành nào</p>
                                            <a href="{{ route('admin.departures.create') }}{{ isset($tour) ? '?tour_id=' . $tour->id : '' }}" 
                                               class="btn btn-primary mt-3">
                                                <i class="fas fa-plus"></i> Thêm lịch khởi hành đầu tiên
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($departures->hasPages())
            <div class="card-footer bg-white border-top">
                <div class="d-flex justify-content-center">
                    {{ $departures->links() }}
                </div>
            </div>
            @endif
        </div>
    </div>
@endsection

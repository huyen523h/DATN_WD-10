@php
    $todayStats = [
        'total' => \App\Models\CheckInOut::today()->count(),
        'check_ins' => \App\Models\CheckInOut::today()->checkIn()->count(),
        'check_outs' => \App\Models\CheckInOut::today()->checkOut()->count(),
        'pending' => \App\Models\CheckInOut::today()->pending()->count(),
    ];
    
    $weekStats = [
        'total' => \App\Models\CheckInOut::thisWeek()->count(),
        'check_ins' => \App\Models\CheckInOut::thisWeek()->checkIn()->count(),
        'check_outs' => \App\Models\CheckInOut::thisWeek()->checkOut()->count(),
    ];
    
    $recentCheckIns = \App\Models\CheckInOut::with(['user', 'booking.tour'])
        ->orderBy('created_at', 'desc')
        ->limit(5)
        ->get();
@endphp

<div class="row mb-4">
    <!-- Today's Stats -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Check-in/out hôm nay
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $todayStats['total'] }}
                        </div>
                        <div class="text-xs text-muted">
                            <i class="fas fa-arrow-up text-success"></i>
                            {{ $todayStats['check_ins'] }} check-in, {{ $todayStats['check_outs'] }} check-out
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clock fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Check-in hôm nay
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $todayStats['check_ins'] }}
                        </div>
                        <div class="text-xs text-muted">
                            <i class="fas fa-sign-in-alt text-success"></i>
                            Khách hàng bắt đầu tour
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-sign-in-alt fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Check-out hôm nay
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $todayStats['check_outs'] }}
                        </div>
                        <div class="text-xs text-muted">
                            <i class="fas fa-sign-out-alt text-info"></i>
                            Khách hàng kết thúc tour
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-sign-out-alt fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Chờ xác nhận
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $todayStats['pending'] }}
                        </div>
                        <div class="text-xs text-muted">
                            <i class="fas fa-clock text-warning"></i>
                            Cần xác nhận
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Check-ins -->
<div class="row">
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-history me-2"></i>Check-in/out gần đây
                </h6>
                <a href="{{ route('admin.check-in-out.index') }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-eye me-1"></i>Xem tất cả
                </a>
            </div>
            <div class="card-body">
                @forelse($recentCheckIns as $checkIn)
                <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                    <div class="avatar-sm bg-{{ $checkIn->isCheckIn() ? 'success' : 'info' }} text-white rounded-circle d-flex align-items-center justify-content-center me-3">
                        <i class="fas {{ $checkIn->isCheckIn() ? 'fa-sign-in-alt' : 'fa-sign-out-alt' }}"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="mb-1">{{ $checkIn->user->name }}</h6>
                                <p class="text-muted mb-1 small">{{ $checkIn->booking->tour->title }}</p>
                                <span class="badge bg-{{ $checkIn->status_badge }} small">
                                    @switch($checkIn->status)
                                        @case('pending')
                                            <i class="fas fa-clock me-1"></i>Chờ xác nhận
                                            @break
                                        @case('confirmed')
                                            <i class="fas fa-check me-1"></i>Đã xác nhận
                                            @break
                                        @case('cancelled')
                                            <i class="fas fa-times me-1"></i>Đã hủy
                                            @break
                                    @endswitch
                                </span>
                            </div>
                            <div class="text-end">
                                <small class="text-muted">{{ $checkIn->created_at->format('d/m H:i') }}</small>
                                <div>
                                    <a href="{{ route('admin.check-in-out.show', $checkIn) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-4">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Chưa có check-in/check-out nào</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-chart-pie me-2"></i>Thống kê tuần
                </h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">Tổng cộng</span>
                        <span class="fw-bold">{{ $weekStats['total'] }}</span>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-primary" style="width: 100%"></div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">Check-in</span>
                        <span class="fw-bold text-success">{{ $weekStats['check_ins'] }}</span>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-success" style="width: {{ $weekStats['total'] > 0 ? ($weekStats['check_ins'] / $weekStats['total']) * 100 : 0 }}%"></div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">Check-out</span>
                        <span class="fw-bold text-info">{{ $weekStats['check_outs'] }}</span>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-info" style="width: {{ $weekStats['total'] > 0 ? ($weekStats['check_outs'] / $weekStats['total']) * 100 : 0 }}%"></div>
                    </div>
                </div>

                <hr>

                <div class="text-center">
                    <a href="{{ route('admin.check-in-out.statistics-page') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-chart-bar me-1"></i>Xem báo cáo chi tiết
                    </a>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-bolt me-2"></i>Thao tác nhanh
                </h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.check-in-out.create') }}" class="btn btn-success">
                        <i class="fas fa-plus me-2"></i>Tạo check-in/out mới
                    </a>
                    <a href="{{ route('admin.check-in-out.index', ['status' => 'pending']) }}" class="btn btn-warning">
                        <i class="fas fa-clock me-2"></i>Xem chờ xác nhận
                    </a>
                    <a href="{{ route('admin.check-in-out.index', ['type' => 'check_in']) }}" class="btn btn-info">
                        <i class="fas fa-sign-in-alt me-2"></i>Xem check-in
                    </a>
                    <a href="{{ route('admin.check-in-out.index', ['type' => 'check_out']) }}" class="btn btn-secondary">
                        <i class="fas fa-sign-out-alt me-2"></i>Xem check-out
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.avatar-sm {
    width: 40px;
    height: 40px;
    font-size: 14px;
}

.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}

.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}

.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}

.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}

.card {
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15) !important;
}

.badge {
    font-size: 0.75rem;
    font-weight: 600;
}

.progress {
    border-radius: 0.375rem;
}

.progress-bar {
    border-radius: 0.375rem;
}
</style>

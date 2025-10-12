@extends('layouts.employee')

@section('title', 'Dashboard Nhân viên')
@section('page-title', 'Dashboard')
@section('page-description', 'Tổng quan công việc và thông tin cá nhân')

@section('content')
<div class="row">
    <!-- Welcome Card -->
    <div class="col-12 mb-4">
        <div class="employee-card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h4 class="mb-2">
                            <i class="fas fa-sun text-warning me-2"></i>
                            Chào mừng, {{ $employee->name }}!
                        </h4>
                        <p class="text-muted mb-3">
                            Chúc bạn một ngày làm việc hiệu quả. Dưới đây là tổng quan về công việc của bạn.
                        </p>
                        <div class="d-flex flex-wrap gap-2">
                            <span class="badge bg-primary">
                                <i class="fas fa-user-tie me-1"></i>
                                {{ $employee->position ?? 'Nhân viên' }}
                            </span>
                            <span class="badge bg-success">
                                <i class="fas fa-building me-1"></i>
                                {{ $employee->department ?? 'Chưa phân phòng' }}
                            </span>
                            <span class="badge bg-info">
                                <i class="fas fa-calendar me-1"></i>
                                Ngày vào: {{ $employee->hire_date->format('d/m/Y') }}
                            </span>
                        </div>
                    </div>
                    <div class="col-md-4 text-end">
                        <div class="d-flex flex-column align-items-end">
                            <img src="{{ $employee->avatar_url }}" 
                                 alt="Avatar" 
                                 class="rounded-circle mb-3" 
                                 style="width: 100px; height: 100px; object-fit: cover;">
                            <a href="{{ route('tours.index') }}" 
                               class="btn btn-employee-primary" 
                               target="_blank"
                               style="min-width: 140px;">
                                <i class="fas fa-globe me-2"></i>
                                Xem trang web
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="employee-stats">
    <div class="stat-card">
        <div class="stat-icon primary">
            <i class="fas fa-tasks"></i>
        </div>
        <h5 class="mb-1">Nhiệm vụ hôm nay</h5>
        <h3 class="mb-0">5</h3>
        <small class="text-muted">3 hoàn thành, 2 đang làm</small>
    </div>

    <div class="stat-card">
        <div class="stat-icon success">
            <i class="fas fa-check-circle"></i>
        </div>
        <h5 class="mb-1">Hoàn thành tuần</h5>
        <h3 class="mb-0">23</h3>
        <small class="text-muted">Tăng 15% so với tuần trước</small>
    </div>

    <div class="stat-card">
        <div class="stat-icon warning">
            <i class="fas fa-clock"></i>
        </div>
        <h5 class="mb-1">Giờ làm việc</h5>
        <h3 class="mb-0">8.5h</h3>
        <small class="text-muted">Hôm nay</small>
    </div>

    <div class="stat-card">
        <div class="stat-icon danger">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <h5 class="mb-1">Cần xử lý</h5>
        <h3 class="mb-0">2</h3>
        <small class="text-muted">Ưu tiên cao</small>
    </div>
</div>

<div class="row">
    <!-- Recent Tasks -->
    <div class="col-lg-8">
        <div class="employee-card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-tasks text-primary me-2"></i>
                    Nhiệm vụ gần đây
                </h5>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">Xử lý đơn đặt tour</h6>
                            <p class="mb-1 text-muted">Kiểm tra và xác nhận đơn đặt tour từ khách hàng</p>
                            <small class="text-muted">
                                <i class="fas fa-clock me-1"></i>
                                Hạn: 15:00 hôm nay
                            </small>
                        </div>
                        <span class="badge bg-warning">Đang làm</span>
                    </div>

                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">Cập nhật thông tin tour</h6>
                            <p class="mb-1 text-muted">Cập nhật giá và lịch trình tour Hạ Long</p>
                            <small class="text-muted">
                                <i class="fas fa-clock me-1"></i>
                                Hạn: 17:00 hôm nay
                            </small>
                        </div>
                        <span class="badge bg-success">Hoàn thành</span>
                    </div>

                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">Liên hệ khách hàng</h6>
                            <p class="mb-1 text-muted">Gọi điện xác nhận thông tin khách hàng</p>
                            <small class="text-muted">
                                <i class="fas fa-clock me-1"></i>
                                Hạn: 10:00 ngày mai
                            </small>
                        </div>
                        <span class="badge bg-primary">Chờ xử lý</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="col-lg-4">
        <div class="employee-card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-bolt text-warning me-2"></i>
                    Thao tác nhanh
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('tours.index') }}" class="btn btn-outline-primary" target="_blank">
                        <i class="fas fa-globe me-2"></i>
                        Xem trang web
                    </a>
                    <a href="#" class="btn btn-outline-success">
                        <i class="fas fa-plus me-2"></i>
                        Tạo tour mới
                    </a>
                    <a href="#" class="btn btn-outline-info">
                        <i class="fas fa-users me-2"></i>
                        Quản lý khách hàng
                    </a>
                    <a href="#" class="btn btn-outline-warning">
                        <i class="fas fa-calendar me-2"></i>
                        Xem lịch làm việc
                    </a>
                    <a href="{{ route('employee.profile') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-user me-2"></i>
                        Cập nhật thông tin
                    </a>
                </div>
            </div>
        </div>

        <!-- Employee Info -->
        <div class="employee-card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle text-info me-2"></i>
                    Thông tin cá nhân
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-12">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-id-card text-muted me-2"></i>
                            <div>
                                <small class="text-muted">Mã nhân viên</small>
                                <div class="fw-bold">{{ $employee->employee_code }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-envelope text-muted me-2"></i>
                            <div>
                                <small class="text-muted">Email</small>
                                <div class="fw-bold">{{ $employee->email }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-phone text-muted me-2"></i>
                            <div>
                                <small class="text-muted">Số điện thoại</small>
                                <div class="fw-bold">{{ $employee->phone ?? 'Chưa cập nhật' }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-shield-alt text-muted me-2"></i>
                            <div>
                                <small class="text-muted">Vai trò</small>
                                <div class="fw-bold">{{ $employee->role->display_name ?? 'Chưa phân quyền' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto refresh stats every 5 minutes
    setInterval(function() {
        // You can add AJAX call here to refresh stats
        console.log('Refreshing stats...');
    }, 300000);
</script>
@endpush

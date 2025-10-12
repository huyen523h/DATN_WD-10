@extends('layouts.admin')

@section('title', 'Chi tiết Nhân viên - Admin')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.employees.index') }}">Quản lý Nhân viên</a></li>
<li class="breadcrumb-item active">Chi tiết nhân viên</li>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2><i class="fas fa-user text-primary"></i> Chi tiết nhân viên: {{ $employee->name }}</h2>
        <p class="text-muted mb-0">Thông tin chi tiết về nhân viên</p>
    </div>
    <div>
        <a href="{{ route('admin.employees.edit', $employee) }}" class="btn btn-warning me-2">
            <i class="fas fa-edit"></i> Chỉnh sửa
        </a>
        <a href="{{ route('admin.employees.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <!-- Employee Card -->
        <div class="admin-card">
            <div class="card-body text-center">
                @if($employee->avatar)
                    <img src="{{ $employee->avatar_url }}" alt="{{ $employee->name }}" 
                         class="rounded-circle mb-3" style="width: 120px; height: 120px; object-fit: cover;">
                @else
                    <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" 
                         style="width: 120px; height: 120px;">
                        <i class="fas fa-user text-primary" style="font-size: 3rem;"></i>
                    </div>
                @endif
                
                <h4 class="mb-1">{{ $employee->name }}</h4>
                <p class="text-muted mb-2">{{ $employee->position ?: 'Chưa xác định' }}</p>
                
                @if($employee->status === 'active')
                    <span class="badge bg-success fs-6">
                        <i class="fas fa-check-circle me-1"></i>Hoạt động
                    </span>
                @elseif($employee->status === 'inactive')
                    <span class="badge bg-warning fs-6">
                        <i class="fas fa-pause-circle me-1"></i>Không hoạt động
                    </span>
                @else
                    <span class="badge bg-danger fs-6">
                        <i class="fas fa-times-circle me-1"></i>Nghỉ việc
                    </span>
                @endif
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="admin-card mt-3">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-bolt me-2"></i>Thao tác nhanh</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.employees.edit', $employee) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-2"></i>Chỉnh sửa thông tin
                    </a>
                    <button class="btn btn-info" onclick="alert('Tính năng đang phát triển')">
                        <i class="fas fa-envelope me-2"></i>Gửi email
                    </button>
                    <button class="btn btn-secondary" onclick="alert('Tính năng đang phát triển')">
                        <i class="fas fa-print me-2"></i>In thông tin
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <!-- Employee Details -->
        <div class="admin-card">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Thông tin chi tiết</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Mã nhân viên:</label>
                            <p class="mb-0">
                                <span class="badge bg-info fs-6">{{ $employee->employee_code }}</span>
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Email:</label>
                            <p class="mb-0">{{ $employee->email }}</p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Số điện thoại:</label>
                            <p class="mb-0">{{ $employee->phone ?: 'Chưa cập nhật' }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Ngày vào làm:</label>
                            <p class="mb-0">{{ $employee->hire_date->format('d/m/Y') }}</p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Chức vụ:</label>
                            <p class="mb-0">{{ $employee->position ?: 'Chưa xác định' }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Phòng ban:</label>
                            <p class="mb-0">{{ $employee->department ?: 'Chưa xác định' }}</p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Lương:</label>
                            <p class="mb-0">
                                @if($employee->salary)
                                    {{ number_format($employee->salary, 0, ',', '.') }} VNĐ
                                @else
                                    Chưa cập nhật
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Vai trò:</label>
                            <p class="mb-0">
                                <span class="badge bg-secondary">{{ $employee->role->display_name ?? 'Chưa phân quyền' }}</span>
                            </p>
                        </div>
                    </div>
                </div>

                @if($employee->address)
                <div class="mb-3">
                    <label class="form-label fw-bold">Địa chỉ:</label>
                    <p class="mb-0">{{ $employee->address }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Work History -->
        <div class="admin-card mt-3">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-history me-2"></i>Lịch sử làm việc</h6>
            </div>
            <div class="card-body">
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-marker bg-success"></div>
                        <div class="timeline-content">
                            <h6 class="mb-1">Bắt đầu làm việc</h6>
                            <p class="text-muted mb-0">{{ $employee->hire_date->format('d/m/Y') }}</p>
                        </div>
                    </div>
                    
                    <div class="timeline-item">
                        <div class="timeline-marker bg-info"></div>
                        <div class="timeline-content">
                            <h6 class="mb-1">Tạo tài khoản</h6>
                            <p class="text-muted mb-0">{{ $employee->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                    
                    @if($employee->updated_at != $employee->created_at)
                    <div class="timeline-item">
                        <div class="timeline-marker bg-warning"></div>
                        <div class="timeline-content">
                            <h6 class="mb-1">Cập nhật lần cuối</h6>
                            <p class="text-muted mb-0">{{ $employee->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e9ecef;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -22px;
    top: 5px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 2px solid #fff;
    box-shadow: 0 0 0 2px #e9ecef;
}

.timeline-content {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
    border-left: 3px solid #007bff;
}
</style>
@endsection

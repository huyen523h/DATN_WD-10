@extends('layouts.admin')

@section('title', 'Quản lý Nhân viên - Admin')

@section('breadcrumb')
<li class="breadcrumb-item active">Quản lý Nhân viên</li>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2><i class="fas fa-users text-primary"></i> Quản lý nhân viên</h2>
        <p class="text-muted mb-0">Quản lý thông tin nhân viên và phân quyền</p>
    </div>
    <a href="{{ route('admin.employees.create') }}" class="btn btn-admin-primary">
        <i class="fas fa-plus"></i> Thêm nhân viên
    </a>
</div>

<!-- Search and Filter -->
<div class="row mb-4">
    <div class="col-12">
        <div class="admin-card">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.employees.index') }}" class="row g-3">
                    <div class="col-md-4">
                        <label for="search" class="form-label">Tìm kiếm</label>
                        <input type="text" class="form-control" id="search" name="search" 
                               value="{{ request('search') }}" placeholder="Tên, email, mã NV...">
                    </div>
                    <div class="col-md-3">
                        <label for="status" class="form-label">Trạng thái</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">Tất cả</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Hoạt động</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Không hoạt động</option>
                            <option value="terminated" {{ request('status') == 'terminated' ? 'selected' : '' }}>Nghỉ việc</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="department" class="form-label">Phòng ban</label>
                        <select class="form-select" id="department" name="department">
                            <option value="">Tất cả</option>
                            <option value="IT" {{ request('department') == 'IT' ? 'selected' : '' }}>IT</option>
                            <option value="HR" {{ request('department') == 'HR' ? 'selected' : '' }}>Nhân sự</option>
                            <option value="Sales" {{ request('department') == 'Sales' ? 'selected' : '' }}>Kinh doanh</option>
                            <option value="Marketing" {{ request('department') == 'Marketing' ? 'selected' : '' }}>Marketing</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> Tìm kiếm
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="admin-card">
            <div class="card-body">
                @if($employees->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th><i class="fas fa-user me-2"></i>Nhân viên</th>
                                    <th><i class="fas fa-id-badge me-2"></i>Mã NV</th>
                                    <th><i class="fas fa-briefcase me-2"></i>Chức vụ</th>
                                    <th><i class="fas fa-building me-2"></i>Phòng ban</th>
                                    <th><i class="fas fa-shield-alt me-2"></i>Vai trò</th>
                                    <th><i class="fas fa-toggle-on me-2"></i>Trạng thái</th>
                                    <th class="text-end"><i class="fas fa-cogs me-2"></i>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($employees as $employee)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                @if($employee->avatar)
                                                    <img src="{{ $employee->avatar_url }}" alt="{{ $employee->name }}" 
                                                         class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                                                @else
                                                    <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                        <i class="fas fa-user text-primary"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h6 class="mb-0">{{ $employee->name }}</h6>
                                                <small class="text-muted">{{ $employee->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $employee->employee_code }}</span>
                                    </td>
                                    <td>
                                        <span class="text-muted">{{ $employee->position ?: 'Chưa xác định' }}</span>
                                    </td>
                                    <td>
                                        <span class="text-muted">{{ $employee->department ?: 'Chưa xác định' }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $employee->role->display_name ?? 'Chưa phân quyền' }}</span>
                                    </td>
                                    <td>
                                        @if($employee->status === 'active')
                                            <span class="badge bg-success">
                                                <i class="fas fa-check-circle me-1"></i>Hoạt động
                                            </span>
                                        @elseif($employee->status === 'inactive')
                                            <span class="badge bg-warning">
                                                <i class="fas fa-pause-circle me-1"></i>Không hoạt động
                                            </span>
                                        @else
                                            <span class="badge bg-danger">
                                                <i class="fas fa-times-circle me-1"></i>Nghỉ việc
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.employees.show', $employee) }}" 
                                               class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i> Xem
                                            </a>
                                            <a href="{{ route('admin.employees.edit', $employee) }}" 
                                               class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i> Sửa
                                            </a>
                                            @if(!$employee->user)
                                                <form action="{{ route('admin.employees.create-account', $employee) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" 
                                                            class="btn btn-sm btn-success"
                                                            onclick="return confirm('Tạo tài khoản đăng nhập cho nhân viên \'{{ $employee->name }}\'? Mật khẩu mặc định: 123456')">
                                                        <i class="fas fa-user-plus"></i> Tạo TK
                                                    </button>
                                                </form>
                                            @else
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check me-1"></i>Đã có TK
                                                </span>
                                            @endif
                                            <form action="{{ route('admin.employees.destroy', $employee) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="btn btn-sm btn-danger"
                                                        onclick="return confirm('Bạn có chắc chắn muốn xóa nhân viên \'{{ $employee->name }}\'? Hành động này không thể hoàn tác.')">
                                                    <i class="fas fa-trash"></i> Xóa
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    @if($employees->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $employees->links() }}
                        </div>
                    @endif
                @else
                    <!-- Empty State -->
                    <div class="text-center py-5">
                        <div class="mb-4">
                            <i class="fas fa-users text-muted" style="font-size: 4rem;"></i>
                        </div>
                        <h4 class="text-muted mb-3">Chưa có nhân viên nào</h4>
                        <p class="text-muted mb-4">Bắt đầu bằng cách thêm nhân viên đầu tiên.</p>
                        <a href="{{ route('admin.employees.create') }}" class="btn btn-admin-primary">
                            <i class="fas fa-plus me-2"></i>Thêm nhân viên đầu tiên
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

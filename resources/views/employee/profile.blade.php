@extends('layouts.employee')

@section('title', 'Thông tin cá nhân')
@section('page-title', 'Thông tin cá nhân')
@section('page-description', 'Quản lý thông tin cá nhân và cài đặt tài khoản')

@section('content')
<div class="row">
    <div class="col-lg-4">
        <!-- Profile Card -->
        <div class="employee-card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-user text-primary me-2"></i>
                    Thông tin cá nhân
                </h5>
            </div>
            <div class="card-body text-center">
                <div class="mb-3">
                    <img src="{{ $employee->avatar_url }}" 
                         alt="Avatar" 
                         class="rounded-circle" 
                         style="width: 120px; height: 120px; object-fit: cover;">
                </div>
                <h5 class="mb-1">{{ $employee->name }}</h5>
                <p class="text-muted mb-2">{{ $employee->position ?? 'Nhân viên' }}</p>
                <p class="text-muted mb-3">{{ $employee->department ?? 'Chưa phân phòng' }}</p>
                
                <div class="row g-2 text-start">
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
                            <i class="fas fa-calendar text-muted me-2"></i>
                            <div>
                                <small class="text-muted">Ngày vào làm</small>
                                <div class="fw-bold">{{ $employee->hire_date->format('d/m/Y') }}</div>
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
                    <div class="col-12">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-toggle-on text-muted me-2"></i>
                            <div>
                                <small class="text-muted">Trạng thái</small>
                                <div>
                                    @if($employee->status === 'active')
                                        <span class="badge bg-success">
                                            <i class="fas fa-check-circle me-1"></i>Hoạt động
                                        </span>
                                    @elseif($employee->status === 'inactive')
                                        <span class="badge bg-warning">
                                            <i class="fas fa-pause-circle me-1"></i>Tạm dừng
                                        </span>
                                    @else
                                        <span class="badge bg-danger">
                                            <i class="fas fa-times-circle me-1"></i>Đã nghỉ việc
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <!-- Profile Form -->
        <div class="employee-card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-edit text-primary me-2"></i>
                    Cập nhật thông tin
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('employee.profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Họ và tên <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name', $employee->name) }}" 
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="phone" class="form-label">Số điện thoại</label>
                                <input type="tel" 
                                       class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" 
                                       name="phone" 
                                       value="{{ old('phone', $employee->phone) }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Địa chỉ</label>
                        <textarea class="form-control @error('address') is-invalid @enderror" 
                                  id="address" 
                                  name="address" 
                                  rows="3">{{ old('address', $employee->address) }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="avatar" class="form-label">Ảnh đại diện</label>
                        <input type="file" 
                               class="form-control @error('avatar') is-invalid @enderror" 
                               id="avatar" 
                               name="avatar" 
                               accept="image/*">
                        @error('avatar')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Chọn ảnh JPG, PNG hoặc GIF (tối đa 2MB)</div>
                    </div>

                    <hr class="my-4">

                    <h6 class="mb-3">
                        <i class="fas fa-lock text-warning me-2"></i>
                        Thay đổi mật khẩu
                    </h6>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="current_password" class="form-label">Mật khẩu hiện tại</label>
                                <input type="password" 
                                       class="form-control @error('current_password') is-invalid @enderror" 
                                       id="current_password" 
                                       name="current_password">
                                @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="new_password" class="form-label">Mật khẩu mới</label>
                                <input type="password" 
                                       class="form-control @error('new_password') is-invalid @enderror" 
                                       id="new_password" 
                                       name="new_password">
                                @error('new_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="new_password_confirmation" class="form-label">Xác nhận mật khẩu</label>
                                <input type="password" 
                                       class="form-control" 
                                       id="new_password_confirmation" 
                                       name="new_password_confirmation">
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-employee-primary">
                            <i class="fas fa-save me-2"></i>
                            Cập nhật thông tin
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Account Security -->
        <div class="employee-card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-shield-alt text-success me-2"></i>
                    Bảo mật tài khoản
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center p-3 border rounded">
                            <div class="flex-shrink-0">
                                <i class="fas fa-envelope text-primary" style="font-size: 1.5rem;"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1">Email đăng nhập</h6>
                                <p class="text-muted mb-0">{{ $employee->email }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center p-3 border rounded">
                            <div class="flex-shrink-0">
                                <i class="fas fa-calendar text-success" style="font-size: 1.5rem;"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1">Ngày tạo tài khoản</h6>
                                <p class="text-muted mb-0">{{ $employee->user->created_at->format('d/m/Y H:i') }}</p>
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
    // Preview avatar when selected
    document.getElementById('avatar').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                // You can add preview functionality here
                console.log('Avatar preview:', e.target.result);
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@endpush

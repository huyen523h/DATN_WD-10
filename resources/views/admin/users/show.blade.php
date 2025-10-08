<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết Người dùng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block bg-dark sidebar collapse">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link text-white" href="{{ route('admin.users.index') }}">
                                <i class="fas fa-tachometer-alt"></i> Bảng điều khiển
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white active" href="{{ route('admin.users.index') }}">
                                <i class="fas fa-users"></i> Quản lý Người dùng
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Chi tiết Người dùng: {{ $user->name }}</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary me-2">
                            <i class="fas fa-arrow-left"></i> Quay lại Danh sách
                        </a>
                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Chỉnh sửa
                        </a>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Thông tin Cá nhân</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Họ và Tên</label>
                                            <p class="form-control-plaintext">{{ $user->name }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Địa chỉ Email</label>
                                            <p class="form-control-plaintext">{{ $user->email }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Số điện thoại</label>
                                            <p class="form-control-plaintext">{{ $user->phone ?? 'Chưa có' }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Vai trò</label>
                                            <p class="form-control-plaintext">
                                                <span class="badge bg-{{ $user->role == 'admin' ? 'danger' : ($user->role == 'staff' ? 'warning' : 'info') }} fs-6">
                                                    @if($user->role == 'admin') Quản trị viên
                                                    @elseif($user->role == 'staff') Nhân viên
                                                    @else Khách hàng
                                                    @endif
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Địa chỉ</label>
                                    <p class="form-control-plaintext">{{ $user->address ?? 'Chưa có' }}</p>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Trạng thái Tài khoản</label>
                                    <p class="form-control-plaintext">
                                        <span class="badge bg-{{ $user->is_active ? 'success' : 'secondary' }} fs-6">
                                            {{ $user->is_active ? 'Hoạt động' : 'Không hoạt động' }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="card mt-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Account Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">User ID</label>
                                            <p class="form-control-plaintext">{{ $user->id }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Email Verified</label>
                                            <p class="form-control-plaintext">
                                                @if($user->email_verified_at)
                                                    <span class="badge bg-success">Verified</span>
                                                    <br><small class="text-muted">{{ $user->email_verified_at->format('M d, Y H:i') }}</small>
                                                @else
                                                    <span class="badge bg-warning">Not Verified</span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Created At</label>
                                            <p class="form-control-plaintext">{{ $user->created_at->format('M d, Y H:i') }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Last Updated</label>
                                            <p class="form-control-plaintext">{{ $user->updated_at->format('M d, Y H:i') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title mb-0">Quick Actions</h6>
                            </div>
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary">
                                        <i class="fas fa-edit"></i> Edit User
                                    </a>
                                    
                                    @php
                                        $actionText = $user->is_active ? 'deactivate' : 'activate';
                                    @endphp
                                    <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-{{ $user->is_active ? 'warning' : 'success' }} w-100"
                                                data-action="{{ $actionText }}"
                                                onclick="return confirm('Are you sure you want to ' + this.dataset.action + ' this user?')">
                                            <i class="fas fa-{{ $user->is_active ? 'ban' : 'check' }}"></i> 
                                            {{ $user->is_active ? 'Deactivate' : 'Activate' }} User
                                        </button>
                                    </form>
                                    
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger w-100"
                                                onclick="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                                            <i class="fas fa-trash"></i> Delete User
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="card mt-3">
                            <div class="card-header">
                                <h6 class="card-title mb-0">Role Permissions</h6>
                            </div>
                            <div class="card-body">
                                @if($user->role == 'admin')
                                    <div class="alert alert-danger">
                                        <h6 class="alert-heading">Admin Access</h6>
                                        <small>Full system access including user management, system settings, and all features.</small>
                                    </div>
                                @elseif($user->role == 'staff')
                                    <div class="alert alert-warning">
                                        <h6 class="alert-heading">Staff Access</h6>
                                        <small>Limited access to manage customers and view reports. Cannot manage other staff or admin accounts.</small>
                                    </div>
                                @else
                                    <div class="alert alert-info">
                                        <h6 class="alert-heading">Customer Access</h6>
                                        <small>Basic user access to view and manage their own information only.</small>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

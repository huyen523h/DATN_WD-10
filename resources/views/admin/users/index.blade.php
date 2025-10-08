<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Người dùng</title>
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
                            <a class="nav-link text-white" href="#">
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
                    <h1 class="h2">Quản lý Người dùng</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Thêm Người dùng Mới
                        </a>
                    </div>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Debug info -->
                @if(request()->hasAny(['search', 'role', 'status']))
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <strong>Bộ lọc hiện tại:</strong>
                        @if(request('search'))
                            <br>Tìm kiếm: "{{ request('search') }}"
                        @endif
                        @if(request('role'))
                            <br>Vai trò: {{ request('role') }}
                        @endif
                        @if(request('status'))
                            <br>Trạng thái: {{ request('status') }}
                        @endif
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Filters -->
                <div class="card mb-4">
                    <div class="card-body">
                        <form method="GET" action="{{ route('admin.users.index') }}">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label for="search" class="form-label">Tìm kiếm</label>
                                    <input type="text" class="form-control" id="search" name="search" 
                                           value="{{ request('search') }}" placeholder="Tên hoặc Email">
                                </div>
                                <div class="col-md-3">
                                    <label for="role" class="form-label">Vai trò</label>
                                    <select class="form-select" id="role" name="role">
                                        <option value="">Tất cả Vai trò</option>
                                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Quản trị viên</option>
                                        <option value="staff" {{ request('role') == 'staff' ? 'selected' : '' }}>Nhân viên</option>
                                        <option value="customer" {{ request('role') == 'customer' ? 'selected' : '' }}>Khách hàng</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="status" class="form-label">Trạng thái</label>
                                    <select class="form-select" id="status" name="status">
                                        <option value="">Tất cả Trạng thái</option>
                                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Hoạt động</option>
                                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Không hoạt động</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">&nbsp;</label>
                                    <div class="d-grid gap-2">
                                        <button type="submit" class="btn btn-outline-primary">Lọc</button>
                                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Xóa bộ lọc</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Users Table -->
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Tên</th>
                                        <th>Email</th>
                                        <th>Vai trò</th>
                                        <th>Số điện thoại</th>
                                        <th>Trạng thái</th>
                                        <th>Ngày tạo</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($users as $user)
                                        @php
                                            $actionText = $user->is_active ? 'deactivate' : 'activate';
                                        @endphp
                                        <tr>
                                            <td>{{ $user->id }}</td>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>
                                                <span class="badge bg-{{ $user->role == 'admin' ? 'danger' : ($user->role == 'staff' ? 'warning' : 'info') }}">
                                                    @if($user->role == 'admin') Quản trị viên
                                                    @elseif($user->role == 'staff') Nhân viên
                                                    @else Khách hàng
                                                    @endif
                                                </span>
                                            </td>
                                            <td>{{ $user->phone ?? 'Chưa có' }}</td>
                                            <td>
                                                <span class="badge bg-{{ $user->is_active ? 'success' : 'secondary' }}">
                                                    {{ $user->is_active ? 'Hoạt động' : 'Không hoạt động' }}
                                                </span>
                                            </td>
                                            <td>{{ $user->created_at->format('M d, Y') }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-outline-info">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-outline-{{ $user->is_active ? 'warning' : 'success' }}"
                                                                data-action="{{ $actionText }}"
                                                                onclick="return confirm('Are you sure you want to ' + this.dataset.action + ' this user?')">
                                                            <i class="fas fa-{{ $user->is_active ? 'ban' : 'check' }}"></i>
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                                                onclick="return confirm('Are you sure you want to delete this user?')">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center">Không tìm thấy người dùng nào.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center">
                            {{ $users->links() }}
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

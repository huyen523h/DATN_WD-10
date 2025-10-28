@extends('layouts.admin')

@section('title', 'Quản lý Người dùng')

@section('content')
<style>
.user-table-wrapper {
    width: 100%;
    overflow-x: auto;
}
.user-table {
    width: 100%;
    border-collapse: collapse;
    table-layout: fixed;
}
.user-table thead {
    position: sticky;
    top: 0;
    background-color: #f8f9fa;
    z-index: 2;
}
.user-table th, .user-table td {
    border: 1px solid #dee2e6;
    padding: 10px 8px;
    text-align: center;
    vertical-align: middle;
    font-size: 14px;
    white-space: nowrap;
}

/* Định chiều rộng từng cột */
.user-table th:nth-child(1), .user-table td:nth-child(1) { width: 60px; }
.user-table th:nth-child(2), .user-table td:nth-child(2) { width: 180px; text-align: left; }
.user-table th:nth-child(3), .user-table td:nth-child(3) { width: 220px; text-align: left; }
.user-table th:nth-child(4), .user-table td:nth-child(4) { width: 140px; }
.user-table th:nth-child(5), .user-table td:nth-child(5) { width: 160px; }
.user-table th:nth-child(6), .user-table td:nth-child(6) { width: 120px; }
.user-table th:nth-child(7), .user-table td:nth-child(7) { width: 160px; }

/* Vai trò */
.user-table .badge {
    font-size: 13px;
    padding: 5px 10px;
    border-radius: 8px;
}

/* Button nhóm thao tác */
.user-table .btn-group {
    display: flex;
    justify-content: center;
    gap: 4px;
}
.user-table .btn {
    padding: 4px 6px;
    font-size: 12px;
    line-height: 1;
}

/* Hover hàng */
.user-table tbody tr:hover {
    background-color: #f1f5ff;
}
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
                    <h3 class="card-title mb-0">Danh sách Người dùng</h3>
                    <a href="{{ route('admin.users.create') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-plus"></i> Thêm Người dùng
                    </a>
                </div>

                <div class="card-body">
                    <!-- Search Form -->
                    <form method="GET" class="mb-3">
                        <div class="row g-2 align-items-center">
                            <div class="col-md-4">
                                <input type="text" name="search" class="form-control"
                                       placeholder="Tìm kiếm tên, email, SĐT..."
                                       value="{{ request('search') }}">
                            </div>
                            <div class="col-md-3">
                                <select name="role" class="form-control">
                                    <option value="">Tất cả vai trò</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->name }}"
                                            {{ request('role') == $role->name ? 'selected' : '' }}>
                                            {{ ucfirst($role->name) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 d-grid">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> Tìm
                                </button>
                            </div>
                            <div class="col-md-3 d-grid">
                                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Xóa bộ lọc
                                </a>
                            </div>
                        </div>
                    </form>

                    <!-- Users Table -->
                    <div class="user-table-wrapper">
                        <table class="user-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Tên</th>
                                    <th>Email</th>
                                    <th>Số điện thoại</th>
                                    <th>Vai trò</th>
                                    <th>Ngày tạo</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                    <tr>
                                        <td>{{ $user->id }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->phone ?? 'N/A' }}</td>
                                        <td>
                                            @foreach($user->roles as $role)
                                                <span class="badge bg-{{ $role->name == 'admin' ? 'danger' : ($role->name == 'staff' ? 'warning' : 'info') }}">
                                                    {{ ucfirst($role->name) }}
                                                </span>
                                            @endforeach
                                        </td>
                                        <td>{{ $user->created_at ? $user->created_at->format('d/m/Y') : 'N/A' }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('admin.users.show', $user) }}" 
                                                   class="btn btn-info btn-sm" title="Xem">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.users.edit', $user) }}" 
                                                   class="btn btn-warning btn-sm" title="Sửa">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('admin.users.destroy', $user) }}" 
                                                      method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa người dùng này?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" title="Xóa">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">
                                            <i class="fas fa-user-slash fa-2x mb-2"></i><br>
                                            Không tìm thấy người dùng nào.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-3">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

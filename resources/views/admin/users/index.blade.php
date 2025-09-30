@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h4 mb-0">
            @if(auth()->user()->isAdmin())
                Quản lý người dùng
            @elseif(auth()->user()->isStaff())
                Quản lý khách hàng
            @else
                Quản lý người dùng
            @endif
        </h1>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
            <i class="ion-plus"></i>
            @if(auth()->user()->isStaff() && !auth()->user()->isAdmin())
                Thêm khách hàng
            @else
                Thêm người dùng
            @endif
        </a>
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

    <div class="card shadow-sm">
        <div class="card-body">
            <!-- Filters -->
            <form method="GET" class="row g-3 mb-4">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Tìm kiếm theo tên, email, số điện thoại..."
                           value="{{ request('search') }}">
                </div>
                @if(auth()->user()->isAdmin())
                    <div class="col-md-3">
                        <select name="role" class="form-select">
                            <option value="">Tất cả vai trò</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}" {{ request('role') == $role->name ? 'selected' : '' }}>
                                    {{ ucfirst($role->name) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @else
                    <div class="col-md-3">
                        <div class="form-control bg-light text-center">Chỉ hiển thị khách hàng</div>
                    </div>
                @endif
                <div class="col-md-2">
                    <button type="submit" class="btn btn-outline-secondary">Lọc</button>
                </div>
                <div class="col-md-3 text-end">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Xóa bộ lọc</a>
                </div>
            </form>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-striped align-middle mb-0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tên</th>
                            <th>Email</th>
                            <th>Điện thoại</th>
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
                                <td>{{ $user->phone ?? '-' }}</td>
                                <td>
                                    @foreach($user->roles as $role)
                                        <span class="badge bg-{{ $role->name == 'admin' ? 'danger' : ($role->name == 'staff' ? 'warning' : 'info') }}">
                                            {{ ucfirst($role->name) }}
                                        </span>
                                    @endforeach
                                </td>
                                <td>{{ $user->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-outline-info">
                                            Xem
                                        </a>
                                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-outline-primary">
                                            Sửa
                                        </a>
                                        @if($user->id !== auth()->id() && auth()->user()->isAdmin())
                                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                                  style="display: inline;" onsubmit="return confirm('Bạn có chắc muốn xóa người dùng này?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    Xóa
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">Không có người dùng nào.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($users->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $users->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection



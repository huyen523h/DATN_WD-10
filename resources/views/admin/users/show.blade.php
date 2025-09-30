@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h4 mb-0">Chi tiết người dùng</h1>
        <div>
            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary">
                <i class="ion-edit"></i> Chỉnh sửa
            </a>
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                <i class="ion-arrow-left-b"></i> Quay lại
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="card-title mb-0">Thông tin cá nhân</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <strong>Tên:</strong>
                            <p class="mb-1">{{ $user->name }}</p>
                        </div>

                        <div class="col-md-6 mb-3">
                            <strong>Email:</strong>
                            <p class="mb-1">{{ $user->email }}</p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <strong>Số điện thoại:</strong>
                            <p class="mb-1">{{ $user->phone ?? 'Chưa cập nhật' }}</p>
                        </div>

                        <div class="col-md-6 mb-3">
                            <strong>Địa chỉ:</strong>
                            <p class="mb-1">{{ $user->address ?? 'Chưa cập nhật' }}</p>
                        </div>
                    </div>

                    <div class="mb-4">
                        <strong>Vai trò:</strong>
                        <div class="mt-2">
                            @foreach($user->roles as $role)
                                <span class="badge bg-{{ $role->name == 'admin' ? 'danger' : ($role->name == 'staff' ? 'warning' : 'info') }} me-2">
                                    {{ ucfirst($role->name) }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm mb-3">
                <div class="card-header">
                    <h6 class="card-title mb-0">Thông tin hệ thống</h6>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-6">ID:</dt>
                        <dd class="col-sm-6">{{ $user->id }}</dd>

                        <dt class="col-sm-6">Ngày tạo:</dt>
                        <dd class="col-sm-6">{{ $user->created_at->format('d/m/Y H:i') }}</dd>

                        <dt class="col-sm-6">Cập nhật cuối:</dt>
                        <dd class="col-sm-6">{{ $user->updated_at->format('d/m/Y H:i') }}</dd>

                        @if($user->roles->isNotEmpty())
                            <dt class="col-sm-6">Ngày phân vai trò:</dt>
                            <dd class="col-sm-6">
                                @foreach($user->roles as $role)
                                    <div>
                                        {{ ucfirst($role->name) }}:
                                    </div>
                                @endforeach
                            </dd>
                        @endif
                    </dl>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header">
                    <h6 class="card-title mb-0">Thao tác</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary">
                            <i class="ion-edit"></i> Chỉnh sửa thông tin
                        </a>

                        @if($user->id !== auth()->id())
                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                  onsubmit="return confirm('Bạn có chắc muốn xóa người dùng này?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger w-100">
                                    <i class="ion-trash-a"></i> Xóa người dùng
                                </button>
                            </form>
                        @else
                            <button class="btn btn-secondary w-100" disabled>
                                <i class="ion-locked"></i> Không thể xóa chính mình
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

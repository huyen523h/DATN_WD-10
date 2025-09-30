@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h4 mb-0">Chỉnh sửa người dùng</h1>
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
            <i class="ion-arrow-left-b"></i> Quay lại
        </a>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="card-title mb-0">Thông tin người dùng</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.users.update', $user) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Tên <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                           id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                           id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Mật khẩu mới</label>
                            <div class="row">
                                <div class="col-md-6">
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                           placeholder="Mật khẩu mới" name="password">
                                </div>
                                <div class="col-md-6">
                                    <input type="password" class="form-control"
                                           placeholder="Xác nhận mật khẩu mới" name="password_confirmation">
                                </div>
                            </div>
                            <small class="form-text text-muted">Để trống nếu không muốn thay đổi mật khẩu</small>
                            @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Số điện thoại</label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                           id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="address" class="form-label">Địa chỉ</label>
                                    <input type="text" class="form-control @error('address') is-invalid @enderror"
                                           id="address" name="address" value="{{ old('address', $user->address) }}">
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Vai trò <span class="text-danger">*</span></label>
                            @foreach($roles as $role)
                                <div class="form-check">
                                    <input class="form-check-input @error('role') is-invalid @enderror"
                                           type="radio" value="{{ $role->name }}"
                                           id="role_{{ $role->name }}" name="role"
                                           {{ (old('role', $user->roles->first()->name ?? '') == $role->name) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="role_{{ $role->name }}">
                                        {{ ucfirst($role->name) }}
                                        @if($role->description)
                                            <small class="text-muted">({{ $role->description }})</small>
                                        @endif
                                    </label>
                                </div>
                            @endforeach
                            @error('role')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                                Hủy
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="ion-checkmark"></i> Cập nhật
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h6 class="card-title mb-0">Thông tin hệ thống</h6>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-4">ID:</dt>
                        <dd class="col-sm-8">{{ $user->id }}</dd>

                        <dt class="col-sm-4">Ngày tạo:</dt>
                        <dd class="col-sm-8">{{ $user->created_at->format('d/m/Y H:i') }}</dd>

                        <dt class="col-sm-4">Cập nhật:</dt>
                        <dd class="col-sm-8">{{ $user->updated_at->format('d/m/Y H:i') }}</dd>

                        <dt class="col-sm-6">Vai trò hiện tại:</dt>
                        <dd class="col-sm-6">
                            @foreach($user->roles as $role)
                                <span class="badge bg-{{ $role->name == 'admin' ? 'danger' : ($role->name == 'staff' ? 'warning' : 'info') }}">
                                    {{ ucfirst($role->name) }}
                                </span>
                            @endforeach
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

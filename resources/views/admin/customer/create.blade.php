@extends('layouts.admin')

@section('title', 'Thêm khách hàng')

@section('content')
<div class="container">

    <div class="card shadow-sm p-4">
        <h3 class="fw-bold mb-3"><i class="fas fa-user-plus text-primary me-2"></i>Thêm khách hàng</h3>

        <form method="POST" action="{{ route('admin.customer.store') }}">
            @csrf

            <div class="mb-3">
                <label class="fw-semibold">Tên khách hàng</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="fw-semibold">Email</label>
                <input type="email" name="email" class="form-control">
            </div>

            <div class="mb-3">
                <label class="fw-semibold">Số điện thoại</label>
                <input type="text" name="phone" class="form-control">
            </div>

            <div class="mb-3">
                <label class="fw-semibold">Địa chỉ</label>
                <input type="text" name="address" class="form-control">
            </div>

            <button class="btn btn-primary">Lưu</button>
            <a href="{{ route('admin.customer.index') }}" class="btn btn-secondary">Hủy</a>
        </form>
    </div>

</div>
@endsection

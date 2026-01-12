@extends('layouts.admin')

@section('title', 'Sửa khách hàng')

@section('content')
<div class="container">

    <div class="card shadow-sm p-4">

        <h3 class="fw-bold mb-3"><i class="fas fa-edit text-warning me-2"></i>Sửa khách hàng</h3>

        <form method="POST" action="{{ route('admin.customers.update', $customer->id) }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="fw-semibold">Tên khách hàng</label>
                <input type="text" name="name" class="form-control" value="{{ $customer->name }}" required>
            </div>

            <div class="mb-3">
                <label class="fw-semibold">Email</label>
                <input type="email" name="email" class="form-control" value="{{ $customer->email }}">
            </div>

            <div class="mb-3">
                <label class="fw-semibold">Số điện thoại</label>
                <input type="text" name="phone" class="form-control" value="{{ $customer->phone }}">
            </div>

            <div class="mb-3">
                <label class="fw-semibold">Địa chỉ</label>
                <input type="text" name="address" class="form-control" value="{{ $customer->address }}">
            </div>

            <button class="btn btn-primary">Cập nhật</button>
            <a href="{{ route('admin.customers.index') }}" class="btn btn-secondary">Hủy</a>
        </form>

    </div>
</div>
@endsection

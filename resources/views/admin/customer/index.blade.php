@extends('layouts.admin')

@section('title', 'Quản lý khách hàng')

@section('content')
<div class="container-fluid">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold"><i class="fas fa-users text-primary me-2"></i>Danh sách khách hàng</h2>
            <p class="text-muted">Quản lý thông tin, lịch sử đặt tour và trạng thái khách hàng</p>
        </div>
        <a href="{{ route('admin.customer.create') }}" class="btn btn-primary">
            <i class="fas fa-user-plus me-1"></i> Thêm khách hàng
        </a>
    </div>

    <!-- Search -->
    <form class="card shadow-sm p-3 mb-4" method="GET">
        <div class="row g-3">
            <div class="col-md-4">
                <label class="fw-semibold">Tìm kiếm</label>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Tên, email hoặc số điện thoại"
                    class="form-control" />
            </div>

            <div class="col-md-3">
                <label class="fw-semibold">Loại khách hàng</label>
                <select name="type" class="form-select">
                    <option value="">Tất cả</option>
                    <option value="vip">VIP</option>
                    <option value="active">Đang hoạt động</option>
                    <option value="new">Khách mới</option>
                </select>
            </div>

            <div class="col-md-2 d-flex align-items-end">
                <button class="btn btn-primary w-100">
                    <i class="fas fa-search me-1"></i> Lọc
                </button>
            </div>
        </div>
    </form>

    <!-- Table -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th></th>
                            <th>Tên khách hàng</th>
                            <th>Thông tin liên hệ</th>
                            <th>Hoạt động</th>
                            <th>Trạng thái</th>
                            <th>Ngày tham gia</th>
                            <th class="text-center">Thao tác</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($customers as $customer)
                        <tr>
                            <td class="fw-bold">{{ $customer->name }}</td>

                            <td>
                                <div>{{ $customer->email }}</div>
                                <small class="text-muted">{{ $customer->phone }}</small>
                            </td>

                            <td>
                                {{ $customer->bookings_count }} lần đặt
                            </td>

                            <td>
                                @if ($customer->bookings_count >= 5)
                                    <span class="badge bg-warning text-dark">VIP</span>
                                @elseif($customer->bookings_count > 0)
                                    <span class="badge bg-success">Đang hoạt động</span>
                                @else
                                    <span class="badge bg-secondary">Khách mới</span>
                                @endif
                            </td>

                            <td>
                               {{ optional($customer->created_at)->format('d/m/Y') ?? '—' }}
                                <small class="text-muted">{{ $customer->created_at?->diffForHumans() ?? '—' }}</small>
                            </td>

                            <td class="text-center">
                                <a href="{{ route('admin.customer.show', $customer->id) }}"
                                    class="btn btn-outline-info btn-sm">
                                    <i class="fas fa-eye"></i>
                                </a>

                                <a href="{{ route('admin.customer.edit', $customer->id) }}"
                                    class="btn btn-outline-warning btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <form action="{{ route('admin.customer.destroy', $customer->id) }}"
                                    method="POST"
                                    class="d-inline">
                                    @csrf @method('DELETE')
                                    <button onclick="return confirm('Bạn chắc chắn muốn xóa?');"
                                        class="btn btn-outline-danger btn-sm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>

                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                <i>Không có khách hàng nào</i>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>
        </div>

        <div class="card-footer bg-white">
            {{ $customers->links() }}
        </div>
    </div>
</div>
@endsection

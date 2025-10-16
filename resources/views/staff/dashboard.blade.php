@extends('layouts.app')

@section('title', 'Staff Dashboard - Tour365')

@section('content')
<div class="container-fluid py-4">
    <!-- Welcome Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="fas fa-user-tie fa-2x"></i>
                        </div>
                        <div>
                            <h2 class="mb-1">Chào mừng, {{ Auth::user()->name }}!</h2>
                            <p class="mb-0 opacity-75">Bảng điều khiển nhân viên Tour365</p>
                        </div>
                        <div class="ms-auto text-end">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-calendar-alt me-2"></i>
                                <span>{{ now()->format('d/m/Y') }}</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-clock me-2"></i>
                                <span>{{ now()->format('H:i') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Tổng số tour</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_tours'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-map-marked-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Tổng đặt tour</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_bookings'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Chờ xác nhận</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['pending_bookings'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Đã xác nhận</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['confirmed_bookings'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Bookings -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-list me-2"></i>Đặt tour gần đây
                    </h6>
                </div>
                <div class="card-body">
                    @if($recent_bookings->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Tour</th>
                                        <th>Khách hàng</th>
                                        <th>Ngày đặt</th>
                                        <th>Trạng thái</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recent_bookings as $booking)
                                    <tr>
                                        <td>{{ $booking->tour->title }}</td>
                                        <td>{{ $booking->user->name }}</td>
                                        <td>{{ $booking->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            @switch($booking->status)
                                                @case('pending')
                                                    <span class="badge bg-warning">Chờ xác nhận</span>
                                                    @break
                                                @case('confirmed')
                                                    <span class="badge bg-success">Đã xác nhận</span>
                                                    @break
                                                @case('cancelled')
                                                    <span class="badge bg-danger">Đã hủy</span>
                                                    @break
                                                @case('completed')
                                                    <span class="badge bg-info">Hoàn thành</span>
                                                    @break
                                            @endswitch
                                        </td>
                                        <td>
                                            <a href="{{ route('staff.bookings.show', $booking) }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-eye"></i> Xem
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">Chưa có đặt tour nào.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-bolt me-2"></i>Thao tác nhanh
                    </h6>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <a href="{{ route('staff.tours') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-map-marked-alt text-primary me-2"></i>
                            <div>
                                <div class="fw-bold">Xem tours</div>
                                <small class="text-muted">Danh sách tất cả tours</small>
                            </div>
                        </a>
                        <a href="{{ route('staff.bookings') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-calendar-check text-success me-2"></i>
                            <div>
                                <div class="fw-bold">Quản lý đặt tour</div>
                                <small class="text-muted">Xem và cập nhật đặt tour</small>
                            </div>
                        </a>
                        <a href="{{ route('staff.customers') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-users text-info me-2"></i>
                            <div>
                                <div class="fw-bold">Khách hàng</div>
                                <small class="text-muted">Danh sách khách hàng</small>
                            </div>
                        </a>
                        <a href="{{ route('staff.profile') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-user-cog text-warning me-2"></i>
                            <div>
                                <div class="fw-bold">Hồ sơ cá nhân</div>
                                <small class="text-muted">Cập nhật thông tin</small>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

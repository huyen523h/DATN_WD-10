@extends('layouts.admin')

@section('title', 'Chi tiết khách hàng')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Chi tiết khách hàng</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.customers') }}">Khách hàng</a></li>
                    <li class="breadcrumb-item active">{{ $user->name }}</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('admin.customers') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Customer Info -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Thông tin khách hàng</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h4 class="text-primary">{{ $user->name }}</h4>
                            <p class="text-muted mb-1"><i class="fas fa-envelope"></i> {{ $user->email }}</p>
                            @if($user->phone)
                                <p class="text-muted mb-1"><i class="fas fa-phone"></i> {{ $user->phone }}</p>
                            @endif
                            @if($user->address)
                                <p class="text-muted mb-1"><i class="fas fa-map-marker-alt"></i> {{ $user->address }}</p>
                            @endif
                        </div>
                        <div class="col-md-4 text-right">
                            <span class="badge badge-{{ $user->status === 'active' ? 'success' : 'secondary' }} badge-lg">
                                {{ $user->status === 'active' ? 'Hoạt động' : 'Không hoạt động' }}
                            </span>
                            <p class="text-muted mt-2">
                                <small>Tham gia: {{ $user->created_at->format('d/m/Y') }}</small>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bookings -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Lịch sử đặt tour ({{ $user->bookings->count() }})</h6>
                </div>
                <div class="card-body">
                    @if($user->bookings->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Tour</th>
                                        <th>Ngày khởi hành</th>
                                        <th>Số khách</th>
                                        <th>Tổng tiền</th>
                                        <th>Trạng thái</th>
                                        <th>Ngày đặt</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($user->bookings as $booking)
                                    <tr>
                                        <td>
                                            <strong>{{ $booking->tour->title }}</strong>
                                        </td>
                                        <td>{{ $booking->departure->departure_date->format('d/m/Y') }}</td>
                                        <td>{{ $booking->adults + $booking->children + $booking->infants }}</td>
                                        <td>{{ number_format($booking->total_amount) }} VNĐ</td>
                                        <td>
                                            <span class="badge badge-{{ $booking->status === 'confirmed' ? 'success' : ($booking->status === 'pending' ? 'warning' : 'secondary') }}">
                                                {{ ucfirst($booking->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $booking->created_at->format('d/m/Y') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Khách hàng chưa đặt tour nào</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Reviews -->
            @if($user->reviews->count() > 0)
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Đánh giá ({{ $user->reviews->count() }})</h6>
                </div>
                <div class="card-body">
                    @foreach($user->reviews as $review)
                    <div class="border-bottom pb-3 mb-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="mb-1">{{ $review->tour->title }}</h6>
                                <div class="text-warning mb-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star{{ $i <= $review->rating ? '' : '-o' }}"></i>
                                    @endfor
                                    <span class="ms-2">{{ $review->rating }}/5</span>
                                </div>
                                <p class="text-muted mb-0">{{ $review->comment }}</p>
                            </div>
                            <small class="text-muted">{{ $review->created_at->format('d/m/Y') }}</small>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Statistics -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Thống kê</h6>
                </div>
                <div class="card-body">
                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="stat-icon stat-icon-primary">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <div class="stat-value">{{ $user->bookings->count() }}</div>
                            <div class="stat-label">Tổng đặt tour</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon stat-icon-success">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="stat-value">{{ $user->bookings->where('status', 'confirmed')->count() }}</div>
                            <div class="stat-label">Đã xác nhận</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon stat-icon-info">
                                <i class="fas fa-star"></i>
                            </div>
                            <div class="stat-value">{{ $user->reviews->count() }}</div>
                            <div class="stat-label">Đánh giá</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon stat-icon-warning">
                                <i class="fas fa-wallet"></i>
                            </div>
                            <div class="stat-value">{{ number_format($user->bookings->sum('total_amount')) }}</div>
                            <div class="stat-label">Tổng chi tiêu (VNĐ)</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Support Tickets -->
            @if($user->supportTickets->count() > 0)
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Yêu cầu hỗ trợ</h6>
                </div>
                <div class="card-body">
                    @foreach($user->supportTickets->take(3) as $ticket)
                    <div class="border-bottom pb-2 mb-2">
                        <h6 class="mb-1">{{ $ticket->title }}</h6>
                        <span class="badge badge-{{ $ticket->status === 'open' ? 'warning' : ($ticket->status === 'resolved' ? 'success' : 'secondary') }}">
                            {{ ucfirst($ticket->status) }}
                        </span>
                        <small class="text-muted d-block">{{ $ticket->created_at->format('d/m/Y') }}</small>
                    </div>
                    @endforeach
                    @if($user->supportTickets->count() > 3)
                        <a href="{{ route('admin.support') }}?user_id={{ $user->id }}" class="btn btn-sm btn-outline-primary">Xem tất cả</a>
                    @endif
                </div>
            </div>
            @endif

            <!-- Customer Actions -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Hành động</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="mailto:{{ $user->email }}" class="btn btn-primary">
                            <i class="fas fa-envelope"></i> Gửi email
                        </a>
                        @if($user->phone)
                            <a href="tel:{{ $user->phone }}" class="btn btn-success">
                                <i class="fas fa-phone"></i> Gọi điện
                            </a>
                        @endif
                        <form action="{{ route('admin.customers.destroy', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa khách hàng này?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="fas fa-trash"></i> Xóa khách hàng
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

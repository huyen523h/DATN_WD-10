@extends('layouts.admin')

@section('title', 'Danh sách khách - Lịch khởi hành #' . $departure->id)

@section('content')
    <div class="container mt-4">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.tours.index') }}">Quản lý Tour</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.tours.manage', $departure->tour_id) }}">{{ $departure->tour->title }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.departures.index') }}?tour_id={{ $departure->tour_id }}">Lịch khởi hành</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.departures.show', $departure->id) }}">Chi tiết #{{ $departure->id }}</a></li>
                <li class="breadcrumb-item active">Danh sách khách</li>
            </ol>
        </nav>

        <!-- Departure Info -->
        <div class="card shadow-sm mb-4 border-left border-primary" style="border-left-width: 4px;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1">
                            <i class="fas fa-users text-primary"></i> 
                            Danh sách khách - Lịch khởi hành #{{ $departure->id }}
                        </h4>
                        <p class="text-muted mb-0">
                            <strong>Tour:</strong> {{ $departure->tour->title }} | 
                            <strong>Ngày khởi hành:</strong> {{ \Carbon\Carbon::parse($departure->departure_date)->format('d/m/Y') }}
                        </p>
                    </div>
                    <a href="{{ route('admin.departures.show', $departure->id) }}" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left"></i> Quay lại chi tiết
                    </a>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card shadow-sm border-left border-info" style="border-left-width: 4px;">
                    <div class="card-body">
                        <h6 class="text-muted mb-2">Tổng số khách</h6>
                        <h3 class="mb-0">{{ $bookings->sum('adults') + $bookings->sum('children') + $bookings->sum('infants') }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm border-left border-success" style="border-left-width: 4px;">
                    <div class="card-body">
                        <h6 class="text-muted mb-2">Số đặt tour</h6>
                        <h3 class="mb-0">{{ $bookings->count() }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm border-left border-warning" style="border-left-width: 4px;">
                    <div class="card-body">
                        <h6 class="text-muted mb-2">Đã xác nhận</h6>
                        <h3 class="mb-0">{{ $bookings->where('status', 'confirmed')->count() }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm border-left border-danger" style="border-left-width: 4px;">
                    <div class="card-body">
                        <h6 class="text-muted mb-2">Đã hủy</h6>
                        <h3 class="mb-0">{{ $bookings->where('status', 'cancelled')->count() }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bookings Table -->
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-list"></i> Danh sách đặt tour
                </h5>
            </div>
            <div class="card-body">
                @if($bookings->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Khách hàng</th>
                                    <th>Người lớn</th>
                                    <th>Trẻ em</th>
                                    <th>Em bé</th>
                                    <th>Tổng tiền</th>
                                    <th>Trạng thái</th>
                                    <th>Ngày đặt</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($bookings as $booking)
                                <tr>
                                    <td><strong>#{{ $booking->id }}</strong></td>
                                    <td>
                                        <div>
                                            <strong>{{ $booking->user->name }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $booking->user->email }}</small>
                                            @if($booking->user->phone)
                                                <br>
                                                <small class="text-muted"><i class="fas fa-phone"></i> {{ $booking->user->phone }}</small>
                                            @endif
                                        </div>
                                    </td>
                                    <td>{{ $booking->adults }}</td>
                                    <td>{{ $booking->children }}</td>
                                    <td>{{ $booking->infants }}</td>
                                    <td>
                                        <strong class="text-primary">
                                            {{ number_format($booking->total_price, 0, ',', '.') }}₫
                                        </strong>
                                    </td>
                                    <td>
                                        @if($booking->status === 'pending')
                                            <span class="badge bg-warning text-dark">Chờ xác nhận</span>
                                        @elseif($booking->status === 'confirmed')
                                            <span class="badge bg-success">Đã xác nhận</span>
                                        @elseif($booking->status === 'cancelled')
                                            <span class="badge bg-danger">Đã hủy</span>
                                        @elseif($booking->status === 'completed')
                                            <span class="badge bg-info">Hoàn thành</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $booking->status }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $booking->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <a href="{{ route('admin.bookings.show', $booking->id) }}" 
                                           class="btn btn-sm btn-outline-primary" 
                                           title="Xem chi tiết">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-info text-center">
                        <i class="fas fa-info-circle"></i> 
                        Chưa có đặt tour nào cho lịch khởi hành này.
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection


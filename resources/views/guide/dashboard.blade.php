@extends('layouts.app')

@section('title', 'Dashboard - Hướng dẫn viên')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="fw-bold text-primary">
                <i class="fas fa-user-tie me-2"></i>Dashboard Hướng dẫn viên
            </h2>
            <p class="text-muted">Xin chào, <strong>{{ auth()->user()->name }}</strong></p>
        </div>
    </div>

    <!-- Thống kê -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px;">
                            <i class="fas fa-calendar-alt fa-2x"></i>
                        </div>
                        <div>
                            <h3 class="mb-0">{{ $totalTours }}</h3>
                            <small class="text-muted">Tổng số tour</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px;">
                            <i class="fas fa-plane fa-2x"></i>
                        </div>
                        <div>
                            <h3 class="mb-0">{{ $upcomingTours }}</h3>
                            <small class="text-muted">Tour sắp tới</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px;">
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                        <div>
                            <h3 class="mb-0">{{ $totalGuests }}</h3>
                            <small class="text-muted">Tổng số khách</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="mb-3"><i class="fas fa-bolt me-2"></i>Thao tác nhanh</h5>
                    <div class="row">
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('guide.feedback.index') }}" class="btn btn-outline-primary w-100">
                                <i class="fas fa-comment-dots me-2"></i>Phản hồi đánh giá
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('guide.dashboard') }}?view=calendar" class="btn btn-outline-info w-100">
                                <i class="fas fa-calendar-alt me-2"></i>Lịch làm việc
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Danh sách tour -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-list me-2"></i>Danh sách tour được gán</h5>
        </div>
        <div class="card-body">
            @if($departures->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Tour</th>
                                <th>Ngày khởi hành</th>
                                <th>Số khách</th>
                                <th>Xe</th>
                                <th>Trạng thái</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($departures as $departure)
                                <tr>
                                    <td>
                                        <strong>{{ $departure->tour->title }}</strong>
                                    </td>
                                    <td>
                                        <i class="fas fa-calendar text-primary me-1"></i>
                                        {{ $departure->departure_date->format('d/m/Y') }}
                                    </td>
                                    <td>
                                        {{ $departure->bookings->where('status', '!=', 'cancelled')->sum(function($b) { return $b->adults + $b->children + $b->infants; }) }} khách
                                    </td>
                                    <td>
                                        @if($departure->vehicle_type)
                                            <span class="badge bg-warning text-dark">Xe {{ $departure->vehicle_type }} chỗ</span>
                                        @else
                                            <span class="text-muted">Chưa gán</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($departure->departure_date >= now())
                                            <span class="badge bg-success">Sắp khởi hành</span>
                                        @else
                                            <span class="badge bg-secondary">Đã khởi hành</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('guide.departures.show', $departure->id) }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-eye"></i> Chi tiết
                                            </a>
                                            <a href="{{ route('guide.departures.customers', $departure->id) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-users"></i> Khách
                                            </a>
                                            <a href="{{ route('guide.tour-logs.index', $departure->id) }}" class="btn btn-sm btn-success">
                                                <i class="fas fa-book"></i> Nhật ký
                                            </a>
                                            <a href="{{ route('guide.check-ins.index', $departure->id) }}" class="btn btn-sm btn-warning">
                                                <i class="fas fa-check-circle"></i> Check-in
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-calendar-times fa-4x text-muted mb-3"></i>
                    <p class="text-muted">Bạn chưa được gán tour nào</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection


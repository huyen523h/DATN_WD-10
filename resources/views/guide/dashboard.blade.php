{{-- @extends('layouts.app')

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
            @if ($departures->count() > 0)
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
                            @foreach ($departures as $departure)
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
                                        @if ($departure->vehicle_type)
                                            <span class="badge bg-warning text-dark">Xe {{ $departure->vehicle_type }} chỗ</span>
                                        @else
                                            <span class="text-muted">Chưa gán</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($departure->departure_date >= now())
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
 --}}

@extends('layouts.app')

@section('title', 'Dashboard - Hướng dẫn viên')

@section('content')
    <div class="container py-5">

        <!-- HEADER -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold text-primary">
                    <i class="fas fa-user-tie me-2"></i>Dashboard Hướng dẫn viên
                </h2>
                <p class="text-muted">
                    Xin chào, <strong>{{ auth()->user()->name }}</strong>
                </p>
            </div>

            <a href="{{ route('guide.calendar') }}" class="btn btn-outline-info">
                <i class="fas fa-calendar-alt me-2"></i>Lịch làm việc
            </a>
        </div>

        <!-- KPI -->
        <div class="row mb-4">
            <div class="col-md-4 mb-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3"
                            style="width:60px;height:60px">
                            <i class="fas fa-calendar-alt fa-2x"></i>
                        </div>
                        <div>
                            <h3 class="mb-0">{{ $totalTours }}</h3>
                            <small class="text-muted">Tổng số tour</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3"
                            style="width:60px;height:60px">
                            <i class="fas fa-plane fa-2x"></i>
                        </div>
                        <div>
                            <h3 class="mb-0">{{ $upcomingTours }}</h3>
                            <small class="text-muted">Tour sắp tới</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center me-3"
                            style="width:60px;height:60px">
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

        <!-- QUICK ACTION -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <h5 class="mb-3">
                    <i class="fas fa-bolt me-2"></i>Thao tác nhanh
                </h5>

                <div class="row">
                    <div class="col-md-3 mb-2">
                        <a href="{{ route('guide.tour-logs.logg') }}" class="btn btn-outline-success w-100">
                            <i class="fas fa-book me-2"></i>Nhật ký tour
                        </a>
                    </div>

                    <div class="col-md-3 mb-2">
                        <a href="{{ route('guide.feedback.index') }}" class="btn btn-outline-secondary w-100">
                            <i class="fas fa-comment-dots me-2"></i>Phản hồi đánh giá
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- TOUR LIST -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-list me-2"></i>Tour bạn đang phụ trách
                </h5>
            </div>

            <div class="card-body">
                @if ($departures->count())
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
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
                                @foreach ($departures as $departure)
                                    <tr>
                                        <td><strong>{{ $departure->tour->title }}</strong></td>

                                        <td>
                                            <i class="fas fa-calendar text-primary me-1"></i>
                                            {{ $departure->departure_date->format('d/m/Y') }}
                                        </td>

                                        <td>
                                            {{ $departure->bookings->where('status', '!=', 'cancelled')->sum(fn($b) => $b->adults + $b->children + $b->infants) }}
                                            khách
                                        </td>

                                        <td>
                                            @if ($departure->vehicle_type)
                                                <span class="badge bg-warning text-dark">
                                                    Xe {{ $departure->vehicle_type }} chỗ
                                                </span>
                                            @else
                                                <span class="text-muted">Chưa gán</span>
                                            @endif
                                        </td>

                                        <td>
                                            @if ($departure->departure_date >= now())
                                                <span class="badge bg-success">Sắp khởi hành</span>
                                            @else
                                                <span class="badge bg-secondary">Đã khởi hành</span>
                                            @endif
                                        </td>

                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('guide.departures.show', $departure->id) }}"
                                                    class="btn btn-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>

                                                <a href="{{ route('guide.departures.customers', $departure->id) }}"
                                                    class="btn btn-info">
                                                    <i class="fas fa-users"></i>
                                                </a>

                                                <a href="{{ route('guide.tour-logs.index', $departure->id) }}"
                                                    class="btn btn-success">
                                                    <i class="fas fa-book"></i>
                                                </a>

                                                <a href="{{ route('guide.roll-calls.index', $departure->id) }}"
                                                    class="btn btn-warning">
                                                    <i class="fas fa-check-circle"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center py-4">
                        Chưa có tour nào được gán
                    </p>
                @endif
            </div>
        </div>

    </div>
@endsection

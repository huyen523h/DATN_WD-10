@extends('layouts.admin')

@section('title', 'Quản lý Tour: ' . $tour->title)

@section('content')
<div class="container-fluid mt-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.tours.index') }}">Quản lý Tour</a></li>
            <li class="breadcrumb-item active">{{ $tour->title }}</li>
        </ol>
    </nav>

    <!-- Tour Context Info -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0">
                        <i class="fas fa-map-marked-alt"></i> {{ $tour->title }}
                    </h4>
                    <small class="text-white-50">
                        ID: {{ $tour->id }} | 
                        @if($tour->duration_days)
                            Thời gian: {{ $tour->duration_days }} ngày {{ $tour->duration_nights ? $tour->duration_nights . ' đêm' : '' }}
                        @endif
                    </small>
                </div>
                <div class="text-end">
                    <span class="badge bg-light text-dark">
                        @if($tour->status)
                            Trạng thái: {{ $tour->status }}
                        @else
                            Trạng thái: Đang hoạt động
                        @endif
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Management Modules -->
    <div class="row">
        <!-- Lịch trình -->
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card h-100 shadow-sm border-0 module-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-body text-center text-white">
                    <div class="mb-3 icon-wrapper">
                        <i class="fas fa-calendar-alt fa-3x"></i>
                    </div>
                    <h5 class="card-title text-white fw-bold">Lịch trình</h5>
                    <p class="text-white-50 small mb-3">Quản lý lịch trình từng ngày</p>
                    <div class="mb-3">
                        <span class="badge bg-white text-primary fw-bold">{{ $stats['total_schedules'] }} ngày</span>
                    </div>
                    <a href="{{ route('admin.tours.schedule-management', $tour->id) }}" class="btn btn-light btn-sm">
                        <i class="fas fa-arrow-right"></i> Quản lý
                    </a>
                </div>
            </div>
        </div>

        <!-- Lịch khởi hành -->
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card h-100 shadow-sm border-0 module-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <div class="card-body text-center text-white">
                    <div class="mb-3 icon-wrapper">
                        <i class="fas fa-plane-departure fa-3x"></i>
                    </div>
                    <h5 class="card-title text-white fw-bold">Lịch khởi hành</h5>
                    <p class="text-white-50 small mb-3">Quản lý các ngày khởi hành</p>
                    <div class="mb-3">
                        <span class="badge bg-white text-danger fw-bold">{{ $stats['total_departures'] }} lịch</span>
                        @if($stats['upcoming_departures'] > 0)
                            <span class="badge bg-warning text-dark fw-bold">{{ $stats['upcoming_departures'] }} sắp tới</span>
                        @endif
                    </div>
                    <a href="{{ route('admin.departures.index') }}?tour_id={{ $tour->id }}" class="btn btn-light btn-sm">
                        <i class="fas fa-arrow-right"></i> Quản lý
                    </a>
                </div>
            </div>
        </div>

        <!-- Đặt tour / Khách hàng -->
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card h-100 shadow-sm border-0 module-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                <div class="card-body text-center text-white">
                    <div class="mb-3 icon-wrapper">
                        <i class="fas fa-users fa-3x"></i>
                    </div>
                    <h5 class="card-title text-white fw-bold">Đặt tour</h5>
                    <p class="text-white-50 small mb-3">Quản lý đặt tour và khách hàng</p>
                    <div class="mb-3">
                        <span class="badge bg-white text-info fw-bold">{{ $stats['total_bookings'] }} đặt tour</span>
                    </div>
                    <a href="{{ route('admin.bookings') }}?tour_id={{ $tour->id }}" class="btn btn-light btn-sm">
                        <i class="fas fa-arrow-right"></i> Quản lý
                    </a>
                </div>
            </div>
        </div>

        <!-- Hướng dẫn viên -->
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card h-100 shadow-sm border-0 module-card" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                <div class="card-body text-center text-white">
                    <div class="mb-3 icon-wrapper">
                        <i class="fas fa-user-tie fa-3x"></i>
                    </div>
                    <h5 class="card-title text-white fw-bold">Hướng dẫn viên</h5>
                    <p class="text-white-50 small mb-3">Quản lý phân công HDV</p>
                    <div class="mb-3">
                        <span class="badge bg-white text-warning fw-bold">Xem trong departure</span>
                    </div>
                    <a href="{{ route('admin.departures.index') }}?tour_id={{ $tour->id }}" class="btn btn-light btn-sm">
                        <i class="fas fa-arrow-right"></i> Xem
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Departures -->
    @if($tour->departures->count() > 0)
    <div class="card shadow-sm mt-4">
        <div class="card-header bg-light">
            <h5 class="mb-0"><i class="fas fa-list"></i> Lịch khởi hành gần đây</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Ngày khởi hành</th>
                            <th>Ghế</th>
                            <th>Giá</th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tour->departures->take(5) as $departure)
                        <tr>
                            <td>#{{ $departure->id }}</td>
                            <td>{{ \Carbon\Carbon::parse($departure->departure_date)->format('d/m/Y') }}</td>
                            <td>
                                <span class="badge bg-{{ $departure->seats_available > 0 ? 'success' : 'danger' }}">
                                    {{ $departure->seats_available }}/{{ $departure->seats_total }}
                                </span>
                            </td>
                            <td>{{ number_format($departure->price ?? 0, 0, ',', '.') }}₫</td>
                            <td>
                                @if($departure->status === 'available')
                                    <span class="badge bg-success">Còn chỗ</span>
                                @elseif($departure->status === 'sold_out')
                                    <span class="badge bg-danger">Hết chỗ</span>
                                @else
                                    <span class="badge bg-secondary">{{ $departure->status }}</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.departures.show', $departure->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i> Chi tiết
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($tour->departures->count() > 5)
            <div class="text-center mt-3">
                <a href="{{ route('admin.departures.index') }}?tour_id={{ $tour->id }}" class="btn btn-sm btn-outline-secondary">
                    Xem tất cả ({{ $tour->departures->count() }})
                </a>
            </div>
            @endif
        </div>
    </div>
    @endif

    <!-- Quick Actions -->
    <div class="card shadow-sm mt-4">
        <div class="card-header bg-light">
            <h5 class="mb-0"><i class="fas fa-bolt"></i> Thao tác nhanh</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 mb-2">
                    <a href="{{ route('admin.tours.edit', $tour->id) }}" class="btn btn-outline-primary w-100">
                        <i class="fas fa-edit"></i> Chỉnh sửa Tour
                    </a>
                </div>
                <div class="col-md-3 mb-2">
                    <a href="{{ route('admin.tours.show', $tour->id) }}" class="btn btn-outline-info w-100">
                        <i class="fas fa-eye"></i> Xem chi tiết Tour
                    </a>
                </div>
                <div class="col-md-3 mb-2">
                    <a href="{{ route('admin.schedules.create', $tour->id) }}" class="btn btn-outline-success w-100">
                        <i class="fas fa-plus"></i> Thêm lịch trình
                    </a>
                </div>
                <div class="col-md-3 mb-2">
                    <a href="{{ route('admin.departures.create') }}?tour_id={{ $tour->id }}" class="btn btn-outline-warning w-100">
                        <i class="fas fa-plus"></i> Thêm lịch khởi hành
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    /* Module Cards Animation */
    .module-card {
        transition: all 0.3s ease;
        cursor: pointer;
    }
    .module-card:hover {
        transform: translateY(-10px) scale(1.02);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2) !important;
    }
    .module-card .icon-wrapper {
        transition: transform 0.3s ease;
    }
    .module-card:hover .icon-wrapper {
        transform: scale(1.1) rotate(5deg);
    }
    .module-card .btn {
        transition: all 0.3s ease;
    }
    .module-card:hover .btn {
        transform: translateX(5px);
    }

    /* Tour Context Card */
    .card-header.bg-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    }

    /* Recent Departures Table */
    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
        transform: scale(1.01);
        transition: all 0.2s ease;
    }

    /* Quick Actions */
    .btn-outline-primary:hover,
    .btn-outline-info:hover,
    .btn-outline-success:hover,
    .btn-outline-warning:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }
</style>
@endpush
@endsection


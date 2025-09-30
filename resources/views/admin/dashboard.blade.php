@extends('layouts.admin')

@section('title', 'Admin Dashboard - Tour365')

@section('breadcrumb')
<li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1"><i class="fas fa-tachometer-alt text-primary"></i> Dashboard</h2>
        <p class="text-muted mb-0">Chào mừng trở lại, {{ Auth::user()->name }}!</p>
    </div>
    <div class="text-end">
        <div class="text-muted small">
            <i class="fas fa-calendar"></i> {{ now()->format('d/m/Y') }}
        </div>
        <div class="text-muted small">
            <i class="fas fa-clock"></i> {{ now()->format('H:i') }}
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stats-card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="stats-label">Tổng Tours</div>
                            <div class="stats-number">{{ $stats['total_tours'] }}</div>
                            <div class="stats-change text-success">
                                <i class="fas fa-arrow-up"></i> +12% so với tháng trước
                            </div>
                        </div>
                        <div class="stats-icon bg-primary">
                            <i class="fas fa-map-marked-alt"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stats-card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="stats-label">Tổng Đặt Tour</div>
                            <div class="stats-number">{{ $stats['total_bookings'] }}</div>
                            <div class="stats-change text-success">
                                <i class="fas fa-arrow-up"></i> +8% so với tháng trước
                            </div>
                        </div>
                        <div class="stats-icon bg-success">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stats-card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="stats-label">Tổng Khách Hàng</div>
                            <div class="stats-number">{{ $stats['total_customers'] }}</div>
                            <div class="stats-change text-info">
                                <i class="fas fa-arrow-up"></i> +15% so với tháng trước
                            </div>
                        </div>
                        <div class="stats-icon bg-info">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stats-card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="stats-label">Chờ Xử Lý</div>
                            <div class="stats-number">{{ $stats['pending_bookings'] }}</div>
                            <div class="stats-change text-warning">
                                <i class="fas fa-exclamation-triangle"></i> Cần xử lý
                            </div>
                        </div>
                        <div class="stats-icon bg-warning">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Doanh thu theo tháng</h6>
                </div>
                <div class="card-body">
                    <canvas id="revenueChart" height="100"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Tours phổ biến</h6>
                </div>
                <div class="card-body">
                    <canvas id="toursChart" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="row">
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Đặt tour gần đây</h6>
                    <a href="{{ route('admin.bookings') }}" class="btn btn-sm btn-primary">Xem tất cả</a>
                </div>
                <div class="card-body">
                    @if($recent_bookings->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Khách hàng</th>
                                        <th>Tour</th>
                                        <th>Ngày đặt</th>
                                        <th>Tổng tiền</th>
                                        <th>Trạng thái</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recent_bookings as $booking)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm me-2">
                                                        {{ strtoupper(substr($booking->user->name, 0, 1)) }}
                                                    </div>
                                                    <div>
                                                        <div class="fw-bold">{{ $booking->user->name }}</div>
                                                        <small class="text-muted">{{ $booking->user->email }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="fw-bold">{{ $booking->tour->title }}</div>
                                                <small class="text-muted">{{ $booking->departure->departure_date }}</small>
                                            </td>
                                            <td>{{ $booking->created_at->format('d/m/Y H:i') }}</td>
                                            <td class="fw-bold text-success">{{ number_format($booking->total_amount, 0, ',', '.') }}đ</td>
                                            <td>
                                                <span class="badge 
                                                    @if($booking->status === 'pending') bg-warning
                                                    @elseif($booking->status === 'confirmed') bg-success
                                                    @elseif($booking->status === 'cancelled') bg-danger
                                                    @else bg-secondary
                                                    @endif">
                                                    @switch($booking->status)
                                                        @case('pending') Chờ xác nhận @break
                                                        @case('confirmed') Đã xác nhận @break
                                                        @case('cancelled') Đã hủy @break
                                                        @case('completed') Hoàn thành @break
                                                        @default {{ $booking->status }} @break
                                                    @endswitch
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('bookings.show', $booking) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Chưa có đặt tour nào</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-lg-5">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Thống kê nhanh</h6>
                </div>
                <div class="card-body">
                    <div class="quick-stats">
                        <div class="quick-stat-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <span>Tours hôm nay</span>
                                <span class="badge bg-primary">5</span>
                            </div>
                        </div>
                        <div class="quick-stat-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <span>Đặt tour hôm nay</span>
                                <span class="badge bg-success">12</span>
                            </div>
                        </div>
                        <div class="quick-stat-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <span>Khách hàng mới</span>
                                <span class="badge bg-info">3</span>
                            </div>
                        </div>
                        <div class="quick-stat-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <span>Doanh thu hôm nay</span>
                                <span class="badge bg-warning">15.5M</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card shadow-sm mt-3">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Thao tác nhanh</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.tours') }}" class="btn btn-outline-primary">
                            <i class="fas fa-plus"></i> Thêm tour mới
                        </a>
                        <a href="{{ route('admin.bookings') }}" class="btn btn-outline-success">
                            <i class="fas fa-list"></i> Xem đặt tour
                        </a>
                        <a href="{{ route('admin.customers') }}" class="btn btn-outline-info">
                            <i class="fas fa-users"></i> Quản lý khách hàng
                        </a>
                        <a href="{{ route('admin.settings') }}" class="btn btn-outline-warning">
                            <i class="fas fa-cog"></i> Cài đặt hệ thống
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
<style>
.stats-card {
    transition: transform 0.3s ease;
}

.stats-card:hover {
    transform: translateY(-5px);
}

.stats-label {
    font-size: 0.875rem;
    font-weight: 600;
    color: #6B7280;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.stats-number {
    font-size: 2rem;
    font-weight: 700;
    color: #1F2937;
    line-height: 1;
}

.stats-change {
    font-size: 0.75rem;
    font-weight: 500;
}

.stats-icon {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
}

.avatar-sm {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 0.875rem;
}

.quick-stats {
    space-y: 1rem;
}

.quick-stat-item {
    padding: 0.75rem 0;
    border-bottom: 1px solid #E5E7EB;
}

.quick-stat-item:last-child {
    border-bottom: none;
}

.quick-stat-item .badge {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
}
</style>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6'],
            datasets: [{
                label: 'Doanh thu (triệu VNĐ)',
                data: [12, 19, 3, 5, 2, 3],
                borderColor: '#4F46E5',
                backgroundColor: 'rgba(79, 70, 229, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Tours Chart
    const toursCtx = document.getElementById('toursChart').getContext('2d');
    new Chart(toursCtx, {
        type: 'doughnut',
        data: {
            labels: ['Trong nước', 'Nước ngoài', 'Du lịch sinh thái', 'Du lịch văn hóa'],
            datasets: [{
                data: [40, 30, 20, 10],
                backgroundColor: ['#4F46E5', '#10B981', '#F59E0B', '#EF4444']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
});
</script>
@endsection
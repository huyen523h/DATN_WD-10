@extends('layouts.admin')

@section('title', 'Admin Dashboard - Tour365')

@section('breadcrumb')
<li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
<!-- Welcome Header -->
<div class="fade-in">
    <div class="card mb-6 welcome-banner">
        <div class="card-body">
            <div class="welcome-content">
                <div class="welcome-left">
                    <div class="welcome-avatar">
                        <i class="fas fa-user-circle"></i>
                    </div>
                    <div class="welcome-text">
                        <h1>Chào mừng trở lại, {{ Auth::user()->name }}!</h1>
                        <p>Quản lý hệ thống Tour365 một cách hiệu quả</p>
                    </div>
                </div>
                <div class="welcome-right">
                    <div class="welcome-date">
                        <i class="fas fa-calendar-alt"></i>
                        <span>{{ now()->format('d/m/Y') }}</span>
                    </div>
                    <div class="welcome-time">
                        <i class="fas fa-clock"></i>
                        <span>{{ now()->format('H:i') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="stats-grid mb-6">
        <div class="stat-card slide-in-left">
            <div class="stat-header">
                <div class="stat-icon stat-icon-primary">
                    <i class="fas fa-map-marked-alt"></i>
                </div>
            </div>
            <div class="stat-value">{{ $stats['total_tours'] }}</div>
            <div class="stat-label">Tổng Tours</div>
            <div class="stat-change positive">
                <i class="fas fa-arrow-up"></i>
                <span>+12% tháng này</span>
            </div>
        </div>

        <div class="stat-card slide-in-left" style="animation-delay: 0.1s;">
            <div class="stat-header">
                <div class="stat-icon stat-icon-success">
                    <i class="fas fa-calendar-check"></i>
                </div>
            </div>
            <div class="stat-value">{{ $stats['total_bookings'] }}</div>
            <div class="stat-label">Tổng Đặt Tour</div>
            <div class="stat-change positive">
                <i class="fas fa-arrow-up"></i>
                <span>+8% tháng này</span>
            </div>
        </div>

        <div class="stat-card slide-in-left" style="animation-delay: 0.2s;">
            <div class="stat-header">
                <div class="stat-icon stat-icon-warning">
                    <i class="fas fa-users"></i>
                </div>
            </div>
            <div class="stat-value">{{ $stats['total_customers'] }}</div>
            <div class="stat-label">Tổng Khách Hàng</div>
            <div class="stat-change positive">
                <i class="fas fa-arrow-up"></i>
                <span>+15% tháng này</span>
            </div>
        </div>

        <div class="stat-card slide-in-left" style="animation-delay: 0.3s;">
            <div class="stat-header">
                <div class="stat-icon stat-icon-danger">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
            <div class="stat-value">{{ $stats['pending_bookings'] }}</div>
            <div class="stat-label">Chờ Xử Lý</div>
            <div class="stat-change negative">
                <i class="fas fa-exclamation-triangle"></i>
                <span>Cần xử lý</span>
            </div>
        </div>
    </div>

    <!-- Charts and Recent Activity -->
    <div class="charts-section mb-6">
        <!-- Revenue Chart -->
        <div class="chart-card slide-in-right">
            <div class="chart-header">
                <h5 class="chart-title">
                    <i class="fas fa-chart-line"></i>
                    Doanh thu theo tháng
                </h5>
                <div class="chart-actions">
                    <button class="chart-btn" title="Tải xuống">
                        <i class="fas fa-download"></i>
                    </button>
                    <button class="chart-btn" title="Phóng to">
                        <i class="fas fa-expand"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <canvas id="revenueChart" height="300"></canvas>
            </div>
        </div>

        <!-- Popular Tours -->
        <div class="chart-card slide-in-right" style="animation-delay: 0.2s;">
            <div class="chart-header">
                <h5 class="chart-title">
                    <i class="fas fa-chart-pie"></i>
                    Tours phổ biến
                </h5>
                <div class="chart-actions">
                    <button class="chart-btn" title="Xem chi tiết">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <canvas id="toursChart" height="200"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Bookings and Quick Stats -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Bookings -->
        <div class="lg:col-span-2">
            <div class="recent-bookings">
                <div class="table-header">
                    <h3 class="table-title">
                        <i class="fas fa-calendar-check"></i>
                        Đặt tour gần đây
                    </h3>
                    <a href="{{ route('admin.bookings') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-eye mr-1"></i>
                        Xem tất cả
                    </a>
                </div>
                @if($recent_bookings->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="professional-table">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Khách hàng</th>
                                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tour</th>
                                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày đặt</th>
                                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tổng tiền</th>
                                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                                        <th class="px-6 py-4 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach($recent_bookings as $booking)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-6 py-4">
                                                <div class="flex items-center">
                                                    <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center mr-3">
                                                        <i class="fas fa-user text-indigo-600"></i>
                                                    </div>
                                                    <div>
                                                        <div class="text-sm font-medium text-gray-900">{{ $booking->user->name }}</div>
                                                        <div class="text-sm text-gray-500">{{ $booking->user->email }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $booking->tour->title }}</div>
                                                <div class="text-sm text-gray-500">
                                                    <i class="fas fa-calendar-alt mr-1"></i>
                                                    {{ $booking->departure->departure_date }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="text-sm text-gray-900">
                                                    <i class="fas fa-clock mr-1 text-gray-400"></i>
                                                    {{ $booking->created_at->format('d/m/Y H:i') }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="text-sm font-bold text-green-600">
                                                    {{ number_format($booking->total_amount, 0, ',', '.') }}đ
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                @switch($booking->status)
                                                    @case('pending')
                                                        <span class="badge badge-warning">
                                                            <i class="fas fa-circle mr-1" style="font-size: 8px;"></i>
                                                            Chờ xác nhận
                                                        </span>
                                                        @break
                                                    @case('confirmed')
                                                        <span class="badge badge-success">
                                                            <i class="fas fa-circle mr-1" style="font-size: 8px;"></i>
                                                            Đã xác nhận
                                                        </span>
                                                        @break
                                                    @case('cancelled')
                                                        <span class="badge badge-danger">
                                                            <i class="fas fa-circle mr-1" style="font-size: 8px;"></i>
                                                            Đã hủy
                                                        </span>
                                                        @break
                                                    @case('completed')
                                                        <span class="badge badge-info">
                                                            <i class="fas fa-circle mr-1" style="font-size: 8px;"></i>
                                                            Hoàn thành
                                                        </span>
                                                        @break
                                                    @default
                                                        <span class="badge badge-secondary">
                                                            {{ $booking->status }}
                                                        </span>
                                                @endswitch
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                <a href="{{ route('admin.bookings.show', $booking) }}" 
                                                   class="btn btn-primary btn-sm" 
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
                        <div class="text-center py-8">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-calendar-times text-2xl text-gray-400"></i>
                            </div>
                            <h4 class="text-lg font-medium text-gray-900 mb-2">Chưa có đặt tour nào</h4>
                            <p class="text-gray-500">Các đặt tour mới sẽ hiển thị ở đây</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Quick Stats and Actions -->
        <div class="space-y-6">
            <!-- Quick Stats -->
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-chart-bar text-indigo-600 mr-2"></i>
                        Thống kê nhanh
                    </h3>
                </div>
                <div class="card-body">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="text-center p-4 bg-indigo-50 rounded-lg">
                            <i class="fas fa-map-marked-alt text-indigo-600 text-2xl mb-2"></i>
                            <div class="text-lg font-bold text-gray-900">5</div>
                            <div class="text-sm text-gray-500">Tours hôm nay</div>
                        </div>
                        <div class="text-center p-4 bg-green-50 rounded-lg">
                            <i class="fas fa-calendar-check text-green-600 text-2xl mb-2"></i>
                            <div class="text-lg font-bold text-gray-900">12</div>
                            <div class="text-sm text-gray-500">Đặt tour hôm nay</div>
                        </div>
                        <div class="text-center p-4 bg-blue-50 rounded-lg">
                            <i class="fas fa-user-plus text-blue-600 text-2xl mb-2"></i>
                            <div class="text-lg font-bold text-gray-900">3</div>
                            <div class="text-sm text-gray-500">Khách hàng mới</div>
                        </div>
                        <div class="text-center p-4 bg-yellow-50 rounded-lg">
                            <i class="fas fa-money-bill-wave text-yellow-600 text-2xl mb-2"></i>
                            <div class="text-lg font-bold text-gray-900">15.5M</div>
                            <div class="text-sm text-gray-500">Doanh thu hôm nay</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="quick-actions">
                <h3 class="quick-actions-title">
                    <i class="fas fa-bolt"></i>
                    Thao tác nhanh
                </h3>
                <div class="actions-grid">
                    <a href="{{ route('admin.tours.index') }}" class="action-item">
                        <div class="action-icon">
                            <i class="fas fa-plus"></i>
                        </div>
                        <div class="action-text">
                            <div class="action-title">Thêm tour mới</div>
                            <div class="action-desc">Tạo tour du lịch mới</div>
                        </div>
                    </a>
                    <a href="{{ route('admin.bookings') }}" class="action-item">
                        <div class="action-icon">
                            <i class="fas fa-list"></i>
                        </div>
                        <div class="action-text">
                            <div class="action-title">Xem đặt tour</div>
                            <div class="action-desc">Quản lý đặt tour</div>
                        </div>
                    </a>
                    <a href="{{ route('admin.customers') }}" class="action-item">
                        <div class="action-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="action-text">
                            <div class="action-title">Quản lý khách hàng</div>
                            <div class="action-desc">Danh sách khách hàng</div>
                        </div>
                    </a>
                    <a href="{{ route('admin.settings') }}" class="action-item">
                        <div class="action-icon">
                            <i class="fas fa-cog"></i>
                        </div>
                        <div class="action-text">
                            <div class="action-title">Cài đặt hệ thống</div>
                            <div class="action-desc">Cấu hình hệ thống</div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .mb-6 { margin-bottom: 1.5rem; }
    .mb-8 { margin-bottom: 2rem; }
    .py-8 { padding-top: 2rem; padding-bottom: 2rem; }
    .text-2xl { font-size: 1.5rem; }
    .text-lg { font-size: 1.125rem; }
    .text-sm { font-size: 0.875rem; }
    .text-xs { font-size: 0.75rem; }
    .font-bold { font-weight: 700; }
    .font-semibold { font-weight: 600; }
    .font-medium { font-weight: 500; }
    .text-gray-900 { color: var(--gray-900); }
    .text-gray-500 { color: var(--gray-500); }
    .text-indigo-600 { color: var(--primary-600); }
    .text-green-600 { color: var(--success-600); }
    .text-blue-600 { color: var(--info-600); }
    .text-yellow-600 { color: var(--warning-600); }
    .text-gray-400 { color: var(--gray-400); }
    .mr-1 { margin-right: 0.25rem; }
    .mr-2 { margin-right: 0.5rem; }
    .mr-3 { margin-right: 0.75rem; }
    .mr-4 { margin-right: 1rem; }
    .mb-1 { margin-bottom: 0.25rem; }
    .mb-2 { margin-bottom: 0.5rem; }
    .mb-4 { margin-bottom: 1rem; }
    .px-6 { padding-left: 1.5rem; padding-right: 1.5rem; }
    .py-4 { padding-top: 1rem; padding-bottom: 1rem; }
    .py-8 { padding-top: 2rem; padding-bottom: 2rem; }
    .w-10 { width: 2.5rem; }
    .h-10 { height: 2.5rem; }
    .w-16 { width: 4rem; }
    .h-16 { height: 4rem; }
    .w-12 { width: 3rem; }
    .h-12 { height: 3rem; }
    .bg-indigo-100 { background-color: var(--primary-100); }
    .bg-indigo-50 { background-color: var(--primary-50); }
    .bg-green-50 { background-color: var(--success-50); }
    .bg-blue-50 { background-color: var(--info-50); }
    .bg-yellow-50 { background-color: var(--warning-50); }
    .bg-gray-100 { background-color: var(--gray-100); }
    .bg-gray-50 { background-color: var(--gray-50); }
    .rounded-lg { border-radius: var(--radius); }
    .rounded-full { border-radius: 9999px; }
    .flex { display: flex; }
    .items-center { align-items: center; }
    .justify-center { justify-content: center; }
    .justify-between { justify-content: space-between; }
    .text-center { text-align: center; }
    .text-left { text-align: left; }
    .text-right { text-align: right; }
    .uppercase { text-transform: uppercase; }
    .tracking-wider { letter-spacing: 0.05em; }
    .divide-y > * + * { border-top: 1px solid var(--gray-200); }
    .divide-gray-200 > * + * { border-color: var(--gray-200); }
    .hover\:bg-gray-50:hover { background-color: var(--gray-50); }
    .hover\:bg-gray-100:hover { background-color: var(--gray-100); }
    .transition-colors { transition: background-color 0.3s ease; }
    .overflow-x-auto { overflow-x: auto; }
    .grid { display: grid; }
    .grid-cols-2 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    .gap-3 { gap: 0.75rem; }
    .gap-4 { gap: 1rem; }
    .gap-6 { gap: 1.5rem; }
    .space-y-3 > * + * { margin-top: 0.75rem; }
    .space-y-6 > * + * { margin-top: 1.5rem; }
    .p-3 { padding: 0.75rem; }
    .p-4 { padding: 1rem; }
    .opacity-90 { opacity: 0.9; }
    
    @media (min-width: 1024px) {
        .lg\:col-span-2 { grid-column: span 2 / span 2; }
        .lg\:col-span-3 { grid-column: span 3 / span 3; }
        .lg\:grid-cols-3 { grid-template-columns: repeat(3, minmax(0, 1fr)); }
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
                borderColor: '#6366F1',
                backgroundColor: 'rgba(99, 102, 241, 0.1)',
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#6366F1',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2,
                pointRadius: 6
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
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
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
                backgroundColor: ['#6366F1', '#10B981', '#F59E0B', '#EF4444'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true
                    }
                }
            }
        }
    });
});
</script>
@endsection
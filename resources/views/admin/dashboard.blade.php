@extends('layouts.admin')

@section('title', 'Dashboard - Quản lý Tour')

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-tachometer-alt me-2 text-primary"></i>
                Dashboard Tổng quan
            </h1>
            <p class="text-muted mb-0">Chào mừng trở lại! Đây là tổng quan hệ thống của bạn.</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-primary btn-sm" onclick="refreshDashboard()">
                <i class="fas fa-sync-alt me-1"></i>Làm mới
            </button>
            <div class="dropdown">
                <button class="btn btn-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="fas fa-calendar me-1"></i>Hôm nay
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#" onclick="filterByPeriod('today')">Hôm nay</a></li>
                    <li><a class="dropdown-item" href="#" onclick="filterByPeriod('week')">Tuần này</a></li>
                    <li><a class="dropdown-item" href="#" onclick="filterByPeriod('month')">Tháng này</a></li>
                    <li><a class="dropdown-item" href="#" onclick="filterByPeriod('year')">Năm này</a></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-primary bg-gradient rounded-3 p-3">
                                <i class="fas fa-route text-white fa-2x"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="text-muted small">Tổng số tour</div>
                            <div class="h4 mb-0 text-gray-800" id="total-tours">{{ $stats['total_tours'] ?? 0 }}</div>
                            <div class="text-success small">
                                <i class="fas fa-arrow-up me-1"></i>
                                <span id="tours-growth">+12%</span> so với tháng trước
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-success bg-gradient rounded-3 p-3">
                                <i class="fas fa-calendar-check text-white fa-2x"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="text-muted small">Lịch khởi hành</div>
                            <div class="h4 mb-0 text-gray-800" id="total-departures">{{ $stats['total_departures'] ?? 0 }}</div>
                            <div class="text-info small">
                                <i class="fas fa-calendar me-1"></i>
                                <span id="upcoming-departures">{{ $stats['upcoming_departures'] ?? 0 }}</span> sắp tới
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-warning bg-gradient rounded-3 p-3">
                                <i class="fas fa-user-tie text-white fa-2x"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="text-muted small">Hướng dẫn viên</div>
                            <div class="h4 mb-0 text-gray-800" id="total-guides">{{ $stats['total_guides'] ?? 0 }}</div>
                            <div class="text-success small">
                                <i class="fas fa-check-circle me-1"></i>
                                <span id="active-guides">{{ $stats['active_guides'] ?? 0 }}</span> đang hoạt động
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-info bg-gradient rounded-3 p-3">
                                <i class="fas fa-users text-white fa-2x"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="text-muted small">Khách hàng</div>
                            <div class="h4 mb-0 text-gray-800" id="total-customers">{{ $stats['total_customers'] ?? 0 }}</div>
                            <div class="text-primary small">
                                <i class="fas fa-user-plus me-1"></i>
                                <span id="new-customers">{{ $stats['new_customers'] ?? 0 }}</span> mới tháng này
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row g-4 mb-4">
        <div class="col-xl-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-chart-line me-2 text-primary"></i>
                            Doanh thu theo tháng
                        </h5>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                2024
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">2024</a></li>
                                <li><a class="dropdown-item" href="#">2023</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="revenueChart" height="300"></canvas>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 pb-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-pie me-2 text-primary"></i>
                        Tour phổ biến
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="popularToursChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Tables Row -->
    <div class="row g-4">
        <div class="col-xl-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-calendar-alt me-2 text-primary"></i>
                            Lịch khởi hành gần đây
                        </h5>
                        <a href="{{ route('admin.tour-schedule-management') }}" class="btn btn-sm btn-outline-primary">
                            Xem tất cả <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="border-0 ps-4">Tour</th>
                                    <th class="border-0">Ngày khởi hành</th>
                                    <th class="border-0">HDV chính</th>
                                    <th class="border-0">HDV dự phòng</th>
                                    <th class="border-0">Trạng thái</th>
                                    <th class="border-0 pe-4">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody id="recent-departures">
                                <!-- Data will be loaded via AJAX -->
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div>
                                        Đang tải dữ liệu...
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pb-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-bell me-2 text-primary"></i>
                        Thông báo mới nhất
                    </h5>
                </div>
                <div class="card-body">
                    <div id="recent-notifications">
                        <!-- Notifications will be loaded via AJAX -->
                        <div class="text-center py-3">
                            <div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div>
                            Đang tải thông báo...
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-header bg-white border-0 pb-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-bolt me-2 text-primary"></i>
                        Thao tác nhanh
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.tour-schedule-management') }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-plus me-2"></i>Tạo lịch khởi hành mới
                        </a>
                        <a href="{{ route('admin.guides.create') }}" class="btn btn-outline-success btn-sm">
                            <i class="fas fa-user-plus me-2"></i>Thêm hướng dẫn viên
                        </a>
                        <a href="{{ route('admin.tours.create') }}" class="btn btn-outline-info btn-sm">
                            <i class="fas fa-route me-2"></i>Tạo tour mới
                        </a>
                        <button class="btn btn-outline-warning btn-sm" onclick="exportReport()">
                            <i class="fas fa-download me-2"></i>Xuất báo cáo
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    initializeCharts();
    loadDashboardData();
    
    // Auto refresh every 5 minutes
    setInterval(loadDashboardData, 300000);
});

function initializeCharts() {
    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: ['T1', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'T8', 'T9', 'T10', 'T11', 'T12'],
            datasets: [{
                label: 'Doanh thu (triệu VNĐ)',
                data: [120, 150, 180, 220, 280, 320, 350, 380, 420, 450, 480, 520],
                borderColor: '#6366F1',
                backgroundColor: 'rgba(99, 102, 241, 0.1)',
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
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0,0,0,0.05)'
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

    // Popular Tours Chart
    const toursCtx = document.getElementById('popularToursChart').getContext('2d');
    new Chart(toursCtx, {
        type: 'doughnut',
        data: {
            labels: ['Sapa', 'Hạ Long', 'Đà Nẵng', 'Phú Quốc', 'Khác'],
            datasets: [{
                data: [30, 25, 20, 15, 10],
                backgroundColor: [
                    '#6366F1',
                    '#10B981',
                    '#F59E0B',
                    '#EF4444',
                    '#8B5CF6'
                ]
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
}

async function loadDashboardData() {
    try {
        // Load recent departures
        const departuresResponse = await fetch('/api/dashboard/recent-departures');
        const departuresData = await departuresResponse.json();
        
        if (departuresData.success) {
            renderRecentDepartures(departuresData.data);
        }

        // Load notifications
        const notificationsResponse = await fetch('/api/tour-schedules/notifications/recent');
        const notificationsData = await notificationsResponse.json();
        
        if (notificationsData.success) {
            renderNotifications(notificationsData.data);
        }
    } catch (error) {
        console.error('Error loading dashboard data:', error);
    }
}

function renderRecentDepartures(departures) {
    const tbody = document.getElementById('recent-departures');
    
    if (departures.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="6" class="text-center py-4 text-muted">
                    <i class="fas fa-calendar-times fa-2x mb-2 d-block"></i>
                    Không có lịch khởi hành nào
                </td>
            </tr>
        `;
        return;
    }

    tbody.innerHTML = departures.map(departure => `
        <tr>
            <td class="ps-4">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-primary bg-gradient rounded-2 p-2">
                            <i class="fas fa-route text-white"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <div class="fw-semibold">${departure.tour_title}</div>
                        <div class="text-muted small">${departure.tour_code || 'N/A'}</div>
                    </div>
                </div>
            </td>
            <td>
                <div class="fw-semibold">${formatDate(departure.departure_date)}</div>
                <div class="text-muted small">${departure.departure_time || 'Chưa xác định'}</div>
            </td>
            <td>
                <div class="d-flex align-items-center">
                    <div class="bg-success bg-gradient rounded-circle p-1 me-2">
                        <i class="fas fa-user text-white" style="font-size: 10px;"></i>
                    </div>
                    <span class="small">${departure.guide_name || 'Chưa gán'}</span>
                </div>
            </td>
            <td>
                <div class="d-flex align-items-center">
                    <div class="bg-warning bg-gradient rounded-circle p-1 me-2">
                        <i class="fas fa-user text-white" style="font-size: 10px;"></i>
                    </div>
                    <span class="small">${departure.backup_guide_name || 'Chưa gán'}</span>
                </div>
            </td>
            <td>
                <span class="badge ${getStatusBadgeClass(departure.preparation_status)}">
                    ${getStatusText(departure.preparation_status)}
                </span>
            </td>
            <td class="pe-4">
                <button class="btn btn-sm btn-outline-primary" onclick="editDeparture(${departure.id})">
                    <i class="fas fa-edit"></i>
                </button>
            </td>
        </tr>
    `).join('');
}

function renderNotifications(notifications) {
    const container = document.getElementById('recent-notifications');
    
    if (notifications.length === 0) {
        container.innerHTML = `
            <div class="text-center py-3 text-muted">
                <i class="fas fa-bell-slash fa-2x mb-2 d-block"></i>
                Không có thông báo mới
            </div>
        `;
        return;
    }

    container.innerHTML = notifications.map(notification => `
        <div class="d-flex align-items-start mb-3 ${notification.read_at ? 'opacity-75' : ''}">
            <div class="flex-shrink-0">
                <div class="bg-${getNotificationColor(notification.type)} bg-gradient rounded-circle p-2">
                    <i class="fas fa-${getNotificationIcon(notification.type)} text-white" style="font-size: 12px;"></i>
                </div>
            </div>
            <div class="flex-grow-1 ms-3">
                <div class="fw-semibold small">${notification.title}</div>
                <div class="text-muted small">${notification.message}</div>
                <div class="text-muted" style="font-size: 11px;">${formatTimeAgo(notification.created_at)}</div>
            </div>
        </div>
    `).join('');
}

// Utility functions
function formatDate(dateString) {
    return new Date(dateString).toLocaleDateString('vi-VN');
}

function formatTimeAgo(dateString) {
    const now = new Date();
    const date = new Date(dateString);
    const diffInMinutes = Math.floor((now - date) / (1000 * 60));
    
    if (diffInMinutes < 60) return `${diffInMinutes} phút trước`;
    if (diffInMinutes < 1440) return `${Math.floor(diffInMinutes / 60)} giờ trước`;
    return `${Math.floor(diffInMinutes / 1440)} ngày trước`;
}

function getStatusBadgeClass(status) {
    const classes = {
        'pending': 'bg-warning',
        'ready': 'bg-success',
        'confirmed': 'bg-primary',
        'cancelled': 'bg-danger',
        'draft': 'bg-secondary'
    };
    return classes[status] || 'bg-secondary';
}

function getStatusText(status) {
    const texts = {
        'pending': 'Đang chuẩn bị',
        'ready': 'Sẵn sàng',
        'confirmed': 'Đã xác nhận',
        'cancelled': 'Đã hủy',
        'draft': 'Nháp'
    };
    return texts[status] || 'Không xác định';
}

function getNotificationColor(type) {
    const colors = {
        'info': 'primary',
        'success': 'success',
        'warning': 'warning',
        'error': 'danger',
        'departure': 'info',
        'guide': 'success'
    };
    return colors[type] || 'primary';
}

function getNotificationIcon(type) {
    const icons = {
        'info': 'info-circle',
        'success': 'check-circle',
        'warning': 'exclamation-triangle',
        'error': 'exclamation-circle',
        'departure': 'calendar',
        'guide': 'user-tie'
    };
    return icons[type] || 'bell';
}

function refreshDashboard() {
    loadDashboardData();
    location.reload();
}

function filterByPeriod(period) {
    console.log('Filter by period:', period);
    // Implement filtering logic
}

function editDeparture(id) {
    window.location.href = `/admin/tour-schedule-management?departure=${id}`;
}

function exportReport() {
    console.log('Export report');
    // Implement export logic
}
</script>
@endsection
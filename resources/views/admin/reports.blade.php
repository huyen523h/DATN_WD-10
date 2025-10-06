@extends('layouts.admin')

@section('title', 'Báo cáo & Thống kê - Admin')

@section('breadcrumb')
<li class="breadcrumb-item active">Báo cáo & Thống kê</li>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2><i class="fas fa-chart-bar text-primary"></i> Báo cáo & Thống kê</h2>
        <p class="text-muted mb-0">Phân tích dữ liệu và báo cáo doanh thu</p>
    </div>
    <div class="btn-group">
        <button class="btn btn-outline-primary" onclick="exportReport('pdf')">
            <i class="fas fa-file-pdf"></i> Xuất PDF
        </button>
        <button class="btn btn-outline-success" onclick="exportReport('excel')">
            <i class="fas fa-file-excel"></i> Xuất Excel
        </button>
    </div>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card">
            <div class="d-flex align-items-center">
                <div class="flex-grow-1">
                    <div class="stats-label">Tổng doanh thu</div>
                    <div class="stats-number">{{ number_format($stats['total_revenue'], 0, ',', '.') }}đ</div>
                    <div class="stats-change text-success">
                        <i class="fas fa-arrow-up"></i> +15% so với tháng trước
                    </div>
                </div>
                <div class="stats-icon bg-success">
                    <i class="fas fa-dollar-sign"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card">
            <div class="d-flex align-items-center">
                <div class="flex-grow-1">
                    <div class="stats-label">Doanh thu tháng này</div>
                    <div class="stats-number">{{ number_format($stats['monthly_revenue'], 0, ',', '.') }}đ</div>
                    <div class="stats-change text-info">
                        <i class="fas fa-calendar"></i> Tháng {{ now()->month }}
                    </div>
                </div>
                <div class="stats-icon bg-info">
                    <i class="fas fa-chart-line"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card">
            <div class="d-flex align-items-center">
                <div class="flex-grow-1">
                    <div class="stats-label">Tổng đặt tour</div>
                    <div class="stats-number">{{ $stats['total_bookings'] }}</div>
                    <div class="stats-change text-warning">
                        <i class="fas fa-calendar-check"></i> Tất cả thời gian
                    </div>
                </div>
                <div class="stats-icon bg-warning">
                    <i class="fas fa-calendar-check"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card">
            <div class="d-flex align-items-center">
                <div class="flex-grow-1">
                    <div class="stats-label">Tour hoàn thành</div>
                    <div class="stats-number">{{ $stats['completed_bookings'] }}</div>
                    <div class="stats-change text-success">
                        <i class="fas fa-check-circle"></i> Thành công
                    </div>
                </div>
                <div class="stats-icon bg-primary">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row mb-4">
    <div class="col-xl-8 col-lg-7">
        <div class="admin-card">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">Doanh thu theo tháng</h6>
            </div>
            <div class="card-body">
                <canvas id="revenueChart" height="100"></canvas>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-lg-5">
        <div class="admin-card">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">Tours phổ biến</h6>
            </div>
            <div class="card-body">
                <canvas id="toursChart" height="100"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Detailed Reports -->
<div class="row">
    <div class="col-lg-6 mb-4">
        <div class="admin-card">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">Top Tours bán chạy</h6>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-bold">Đà Nẵng - Hội An</div>
                            <small class="text-muted">3 ngày 2 đêm</small>
                        </div>
                        <div class="text-end">
                            <div class="fw-bold text-success">25 đặt tour</div>
                            <small class="text-muted">125M VNĐ</small>
                        </div>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-bold">Phú Quốc</div>
                            <small class="text-muted">4 ngày 3 đêm</small>
                        </div>
                        <div class="text-end">
                            <div class="fw-bold text-success">18 đặt tour</div>
                            <small class="text-muted">90M VNĐ</small>
                        </div>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-bold">Sapa</div>
                            <small class="text-muted">2 ngày 1 đêm</small>
                        </div>
                        <div class="text-end">
                            <div class="fw-bold text-success">15 đặt tour</div>
                            <small class="text-muted">45M VNĐ</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6 mb-4">
        <div class="admin-card">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">Thống kê theo tháng</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Tháng</th>
                                <th>Đặt tour</th>
                                <th>Doanh thu</th>
                                <th>Tăng trưởng</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Tháng 9/2024</td>
                                <td>45</td>
                                <td>225M</td>
                                <td><span class="text-success">+12%</span></td>
                            </tr>
                            <tr>
                                <td>Tháng 8/2024</td>
                                <td>38</td>
                                <td>190M</td>
                                <td><span class="text-success">+8%</span></td>
                            </tr>
                            <tr>
                                <td>Tháng 7/2024</td>
                                <td>35</td>
                                <td>175M</td>
                                <td><span class="text-danger">-5%</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
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
                data: [120, 190, 300, 500, 200, 300],
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

function exportReport(format) {
    if (format === 'pdf') {
        alert('Tính năng xuất PDF đang được phát triển');
    } else if (format === 'excel') {
        alert('Tính năng xuất Excel đang được phát triển');
    }
}
</script>
@endsection

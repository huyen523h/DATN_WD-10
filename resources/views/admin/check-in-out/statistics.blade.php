@extends('layouts.admin')

@section('title', 'Thống kê Check-in/Check-out')

@section('content')
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-chart-bar me-2"></i>Thống kê Check-in/Check-out
            </h1>
            <p class="text-muted mb-0">Phân tích và báo cáo chi tiết về check-in/check-out</p>
        </div>
        <div>
            <a href="{{ route('admin.check-in-out.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Quay lại
            </a>
        </div>
    </div>

    <!-- Period Filter -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-filter me-2"></i>Bộ lọc thời gian
            </h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <label class="form-label">Khoảng thời gian</label>
                    <select id="periodSelect" class="form-select">
                        <option value="today">Hôm nay</option>
                        <option value="week">Tuần này</option>
                        <option value="month">Tháng này</option>
                        <option value="year">Năm nay</option>
                        <option value="custom">Tùy chỉnh</option>
                    </select>
                </div>
                <div class="col-md-3" id="customDateFrom" style="display: none;">
                    <label class="form-label">Từ ngày</label>
                    <input type="date" id="dateFrom" class="form-control">
                </div>
                <div class="col-md-3" id="customDateTo" style="display: none;">
                    <label class="form-label">Đến ngày</label>
                    <input type="date" id="dateTo" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label">&nbsp;</label>
                    <button class="btn btn-primary w-100" onclick="loadStatistics()">
                        <i class="fas fa-sync-alt me-2"></i>Tải dữ liệu
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4" id="statsCards">
        <!-- Cards will be loaded here -->
    </div>

    <!-- Charts -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-line me-2"></i>Biểu đồ theo thời gian
                    </h6>
                </div>
                <div class="card-body">
                    <canvas id="timeChart" height="100"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-pie me-2"></i>Phân bố trạng thái
                    </h6>
                </div>
                <div class="card-body">
                    <canvas id="statusChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-table me-2"></i>Chi tiết theo ngày
            </h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="detailsTable">
                    <thead>
                        <tr>
                            <th>Ngày</th>
                            <th>Check-in</th>
                            <th>Check-out</th>
                            <th>Tổng cộng</th>
                            <th>Đã xác nhận</th>
                            <th>Chờ xác nhận</th>
                            <th>Đã hủy</th>
                        </tr>
                    </thead>
                    <tbody id="detailsTableBody">
                        <!-- Data will be loaded here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
let timeChart, statusChart;

// Period selection handler
document.getElementById('periodSelect').addEventListener('change', function() {
    const customDivs = document.querySelectorAll('#customDateFrom, #customDateTo');
    if (this.value === 'custom') {
        customDivs.forEach(div => div.style.display = 'block');
    } else {
        customDivs.forEach(div => div.style.display = 'none');
    }
});

// Load statistics
function loadStatistics() {
    const period = document.getElementById('periodSelect').value;
    const dateFrom = document.getElementById('dateFrom').value;
    const dateTo = document.getElementById('dateTo').value;
    
    let url = `/admin/check-in-out-statistics?period=${period}`;
    if (period === 'custom' && dateFrom && dateTo) {
        url += `&date_from=${dateFrom}&date_to=${dateTo}`;
    }
    
    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateStatsCards(data.data);
                updateCharts(data);
                updateDetailsTable(data.daily_stats);
            }
        })
        .catch(error => {
            console.error('Error loading statistics:', error);
            showAlert('error', 'Có lỗi xảy ra khi tải dữ liệu');
        });
}

// Update statistics cards
function updateStatsCards(stats) {
    const cardsHtml = `
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Tổng cộng
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                ${stats.total}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-list fa-2x text-gray-300"></i>
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
                                Check-in
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                ${stats.check_ins}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-sign-in-alt fa-2x text-gray-300"></i>
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
                                Check-out
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                ${stats.check_outs}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-sign-out-alt fa-2x text-gray-300"></i>
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
                                Chờ xác nhận
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                ${stats.pending}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    document.getElementById('statsCards').innerHTML = cardsHtml;
}

// Update charts
function updateCharts(data) {
    // Time chart
    const timeCtx = document.getElementById('timeChart').getContext('2d');
    if (timeChart) {
        timeChart.destroy();
    }
    
    const timeData = data.daily_stats || {};
    const labels = Object.keys(timeData).sort();
    const checkInData = labels.map(date => timeData[date].check_in || 0);
    const checkOutData = labels.map(date => timeData[date].check_out || 0);
    
    timeChart = new Chart(timeCtx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Check-in',
                data: checkInData,
                borderColor: '#1cc88a',
                backgroundColor: 'rgba(28, 200, 138, 0.1)',
                tension: 0.4
            }, {
                label: 'Check-out',
                data: checkOutData,
                borderColor: '#36b9cc',
                backgroundColor: 'rgba(54, 185, 204, 0.1)',
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
    
    // Status chart
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    if (statusChart) {
        statusChart.destroy();
    }
    
    statusChart = new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: ['Đã xác nhận', 'Chờ xác nhận', 'Đã hủy'],
            datasets: [{
                data: [data.data.confirmed || 0, data.data.pending || 0, data.data.cancelled || 0],
                backgroundColor: ['#1cc88a', '#f6c23e', '#e74a3b'],
                borderWidth: 0
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

// Update details table
function updateDetailsTable(dailyStats) {
    const tbody = document.getElementById('detailsTableBody');
    let html = '';
    
    Object.keys(dailyStats).sort().forEach(date => {
        const stats = dailyStats[date];
        const total = (stats.check_in || 0) + (stats.check_out || 0);
        const confirmed = stats.confirmed || 0;
        const pending = stats.pending || 0;
        const cancelled = stats.cancelled || 0;
        
        html += `
            <tr>
                <td>${formatDate(date)}</td>
                <td><span class="badge bg-success">${stats.check_in || 0}</span></td>
                <td><span class="badge bg-info">${stats.check_out || 0}</span></td>
                <td><strong>${total}</strong></td>
                <td><span class="badge bg-success">${confirmed}</span></td>
                <td><span class="badge bg-warning">${pending}</span></td>
                <td><span class="badge bg-danger">${cancelled}</span></td>
            </tr>
        `;
    });
    
    tbody.innerHTML = html || '<tr><td colspan="7" class="text-center text-muted">Không có dữ liệu</td></tr>';
}

// Format date
function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('vi-VN');
}

// Show alert
function showAlert(type, message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.insertBefore(alertDiv, document.body.firstChild);
    
    setTimeout(() => {
        alertDiv.remove();
    }, 5000);
}

// Load initial data
document.addEventListener('DOMContentLoaded', function() {
    loadStatistics();
});
</script>
@endpush

@push('styles')
<style>
.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}

.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}

.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}

.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}

.card {
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15) !important;
}

.badge {
    font-size: 0.875rem;
    font-weight: 600;
}

.alert {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 9999;
    min-width: 300px;
}
</style>
@endpush

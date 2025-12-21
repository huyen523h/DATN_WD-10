@extends('layouts.admin')

@section('title', 'Báo cáo & Thống kê - Admin')

@section('breadcrumb')
<li class="breadcrumb-item active">Báo cáo & Thống kê</li>
@endsection

@section('content')
{{-- <div class="d-flex justify-content-between align-items-center mb-4">
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
</div> --}}

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="h3 mb-0 text-gray-800"><i class="fas fa-chart-bar text-primary"></i> Báo cáo & Thống kê</h2>
    </div>
    
    {{-- FORM LỌC NGÀY - TÍNH NĂNG "XỊN" NHẤT CẦN CÓ --}}
    <form action="{{ route('admin.reports') }}" method="GET" class="d-flex shadow-sm p-2 bg-white rounded">
        <div class="input-group me-2">
            <span class="input-group-text bg-white border-0"><i class="fas fa-calendar-alt"></i></span>
            <input type="date" name="start_date" class="form-control border-0" value="{{ $startDate }}">
        </div>
        <div class="vr"></div>
        <div class="input-group mx-2">
            <span class="input-group-text bg-white border-0"><i class="fas fa-arrow-right"></i></span>
            <input type="date" name="end_date" class="form-control border-0" value="{{ $endDate }}">
        </div>
        <button type="submit" class="btn btn-primary btn-sm px-3">Lọc</button>
    </form>
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
            {{-- <div class="card-body">
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
            </div> --}}

            <div class="card-body">
                <div class="list-group list-group-flush">
                    @forelse($topTours as $item)
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <div>
                                <div class="fw-bold text-dark">{{ $item->tour->name ?? 'Tour đã bị xóa' }}</div>
                                <small class="text-muted">
                                    <i class="fas fa-clock"></i> {{ $item->tour->duration ?? 'N/A' }} 
                                </small>
                            </div>
                            <div class="text-end">
                                <div class="fw-bold text-success">{{ $item->total }} lượt đặt</div>
                                {{-- Giả sử giá tour * số lượt (tạm tính) --}}
                                <small class="text-muted">{{ number_format(($item->tour->price ?? 0) * $item->total) }}đ</small>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-3 text-muted">Chưa có dữ liệu trong khoảng thời gian này</div>
                    @endforelse
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
{{-- Thêm thư viện Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // --- SỬA LỖI TẠI ĐÂY ---
    // Thay vì dùng json, ta dùng json_encode trực tiếp để tránh lỗi cú pháp
    const rawData = {!! json_encode($chartData) !!};
    
    // Kiểm tra xem dữ liệu có tồn tại không để tránh lỗi JS
    if (!rawData || rawData.length === 0) {
        console.warn("Không có dữ liệu biểu đồ");
        return;
    }

    // Tách mảng ngày và doanh thu
    const labels = rawData.map(item => item.date);
    const data = rawData.map(item => item.total);

    // 2. VẼ BIỂU ĐỒ DOANH THU (LINE CHART)
    const revenueCanvas = document.getElementById('revenueChart');
    
    // Kiểm tra nếu thẻ canvas tồn tại thì mới vẽ
    if (revenueCanvas) {
        const revenueCtx = revenueCanvas.getContext('2d');
        
        // Tạo gradient màu tím
        let gradient = revenueCtx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(79, 70, 229, 0.2)');
        gradient.addColorStop(1, 'rgba(79, 70, 229, 0)');

        new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Doanh thu',
                    data: data,
                    borderColor: '#4F46E5',
                    backgroundColor: gradient,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#4F46E5',
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let value = context.parsed.y;
                                return ' ' + new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(value);
                            }
                        }
                    }
                },
                scales: {
                    x: { grid: { display: false } },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return new Intl.NumberFormat('vi-VN').format(value) + 'đ';
                            }
                        },
                        border: { dash: [4, 4] }
                    }
                }
            }
        });
    }

    // 3. BIỂU ĐỒ TRÒN (Nếu có)
    const toursCanvas = document.getElementById('toursChart');
    if (toursCanvas) {
        const toursCtx = toursCanvas.getContext('2d');
        new Chart(toursCtx, {
            type: 'doughnut',
            data: {
                labels: ['Hoàn thành', 'Đang xử lý', 'Đã hủy'],
                datasets: [{
                    data: [
                        {{ $stats['completed_bookings'] ?? 0 }}, 
                        {{ ($stats['total_bookings'] ?? 0) - ($stats['completed_bookings'] ?? 0) }},
                        0 
                    ], 
                    backgroundColor: ['#10B981', '#F59E0B', '#EF4444'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '75%',
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    }
});
</script>
@endsection
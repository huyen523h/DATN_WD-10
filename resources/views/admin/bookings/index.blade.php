@extends('layouts.admin')

@section('title', 'Quản lý Đặt Tour - Admin')

@section('breadcrumb')
<li class="breadcrumb-item active">Quản lý Đặt Tour</li>
@endsection

@section('content')
<!-- Header Section -->
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="fw-bold text-dark mb-2">
                    <i class="fas fa-calendar-check text-primary me-2"></i>
                    Quản lý Đặt Tour
                </h2>
                <p class="text-muted mb-0">Quản lý tất cả các đặt tour trong hệ thống</p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-primary">
                    <i class="fas fa-download me-1"></i>
                    Xuất Excel
                </button>
                <button class="btn btn-outline-secondary">
                    <i class="fas fa-filter me-1"></i>
                    Lọc nâng cao
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Success Alert -->
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert">
        <div class="d-flex align-items-center">
            <i class="fas fa-check-circle me-2"></i>
            <span>{{ session('success') }}</span>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                            <i class="fas fa-calendar-check text-primary fa-lg"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-1">Tổng đặt tour</h6>
                        <h4 class="mb-0 fw-bold">{{ $bookings->total() }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-warning bg-opacity-10 rounded-circle p-3">
                            <i class="fas fa-clock text-warning fa-lg"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-1">Chờ xác nhận</h6>
                        <h4 class="mb-0 fw-bold">{{ $bookings->where('status', 'pending')->count() }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-success bg-opacity-10 rounded-circle p-3">
                            <i class="fas fa-check-circle text-success fa-lg"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-1">Đã xác nhận</h6>
                        <h4 class="mb-0 fw-bold">{{ $bookings->where('status', 'confirmed')->count() }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-info bg-opacity-10 rounded-circle p-3">
                            <i class="fas fa-money-bill-wave text-info fa-lg"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-1">Tổng doanh thu</h6>
                        <h4 class="mb-0 fw-bold">{{ number_format($bookings->sum('total_amount'), 0, ',', '.') }}đ</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Search and Filter -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-4">
                <h5 class="mb-0 fw-bold text-dark">
                    <i class="fas fa-search text-primary me-2"></i>
                    Tìm kiếm và lọc
                </h5>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('admin.bookings') }}" class="row g-3">
                    <div class="col-md-3">
                        <label for="search" class="form-label fw-semibold">Tìm kiếm</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="fas fa-search text-muted"></i>
                            </span>
                            <input type="text" class="form-control border-start-0" id="search" name="search" 
                                   value="{{ request('search') }}" placeholder="Mã đặt tour, tên khách hàng...">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label for="status" class="form-label fw-semibold">Trạng thái</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">Tất cả trạng thái</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ xác nhận</option>
                            <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="date_from" class="form-label fw-semibold">Từ ngày</label>
                        <input type="date" class="form-control" id="date_from" name="date_from" 
                               value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="date_to" class="form-label fw-semibold">Đến ngày</label>
                        <input type="date" class="form-control" id="date_to" name="date_to" 
                               value="{{ request('date_to') }}">
                    </div>
                    <div class="col-12">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search me-1"></i>
                                Tìm kiếm
                            </button>
                            <a href="{{ route('admin.bookings') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-1"></i>
                                Xóa bộ lọc
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Bookings Table -->
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-4">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold text-dark">
                        <i class="fas fa-list text-primary me-2"></i>
                        Danh sách đặt tour ({{ $bookings->total() }} đặt tour)
                    </h5>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-download me-1"></i>
                            Xuất báo cáo
                        </button>
                        <button class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-print me-1"></i>
                            In danh sách
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                @if($bookings->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0 py-3 px-4 fw-semibold text-muted">Mã đặt tour</th>
                                    <th class="border-0 py-3 px-4 fw-semibold text-muted">Khách hàng</th>
                                    <th class="border-0 py-3 px-4 fw-semibold text-muted">Tour</th>
                                    <th class="border-0 py-3 px-4 fw-semibold text-muted">Ngày khởi hành</th>
                                    <th class="border-0 py-3 px-4 fw-semibold text-muted">Số khách</th>
                                    <th class="border-0 py-3 px-4 fw-semibold text-muted">Tổng tiền</th>
                                    <th class="border-0 py-3 px-4 fw-semibold text-muted">Trạng thái</th>
                                    <th class="border-0 py-3 px-4 fw-semibold text-muted">Ngày đặt</th>
                                    <th class="border-0 py-3 px-4 fw-semibold text-muted text-center">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($bookings as $booking)
                                    <tr class="border-bottom">
                                        <td class="py-4 px-4">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-primary bg-opacity-10 rounded p-2 me-3">
                                                    <i class="fas fa-receipt text-primary"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0 fw-bold text-dark">#{{ $booking->id }}</h6>
                                                    <small class="text-muted">Mã đặt tour</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-4 px-4">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-info bg-opacity-10 rounded-circle p-2 me-3">
                                                    <i class="fas fa-user text-info"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0 fw-bold text-dark">{{ $booking->user->name }}</h6>
                                                    <small class="text-muted">{{ $booking->user->email }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-4 px-4">
                                            <h6 class="mb-0 fw-bold text-dark">{{ $booking->tour->title }}</h6>
                                            <small class="text-muted">
                                                <i class="fas fa-clock me-1"></i>
                                                {{ $booking->tour->duration_days }} ngày
                                            </small>
                                        </td>
                                        <td class="py-4 px-4">
                                            <small class="text-muted">
                                                <i class="fas fa-calendar-alt me-1"></i>
                                                {{ $booking->departure->departure_date ?? 'N/A' }}
                                            </small>
                                        </td>
                                        <td class="py-4 px-4">
                                            <div class="d-flex flex-column">
                                                <span class="fw-bold text-dark">{{ $booking->adults }} người lớn</span>
                                                @if($booking->children > 0)
                                                    <small class="text-muted">{{ $booking->children }} trẻ em</small>
                                                @endif
                                                @if($booking->infants > 0)
                                                    <small class="text-muted">{{ $booking->infants }} em bé</small>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="py-4 px-4">
                                            <h6 class="mb-0 fw-bold text-success">
                                                {{ number_format($booking->total_amount, 0, ',', '.') }}đ
                                            </h6>
                                        </td>
                                        <td class="py-4 px-4">
                                            <form action="{{ route('admin.bookings.update', $booking) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <select name="status" onchange="this.form.submit()" 
                                                        class="form-select form-select-sm 
                                                        @if($booking->status === 'pending') border-warning
                                                        @elseif($booking->status === 'confirmed') border-success
                                                        @elseif($booking->status === 'cancelled') border-danger
                                                        @elseif($booking->status === 'completed') border-info
                                                        @endif">
                                                    <option value="pending" {{ $booking->status=='pending'?'selected':'' }}>Chờ xác nhận</option>
                                                    <option value="confirmed" {{ $booking->status=='confirmed'?'selected':'' }}>Đã xác nhận</option>
                                                    <option value="cancelled" {{ $booking->status=='cancelled'?'selected':'' }}>Đã hủy</option>
                                                    <option value="completed" {{ $booking->status=='completed'?'selected':'' }}>Hoàn thành</option>
                                                </select>
                                            </form>
                                        </td>
                                        <td class="py-4 px-4">
                                            <small class="text-muted">
                                                <i class="fas fa-calendar me-1"></i>
                                                {{ $booking->created_at->format('d/m/Y H:i') }}
                                            </small>
                                        </td>
                                        <td class="py-4 px-4 text-center">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.bookings.show', $booking) }}" 
                                                   class="btn btn-outline-info btn-sm" 
                                                   title="Xem chi tiết"
                                                   data-bs-toggle="tooltip">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <button class="btn btn-outline-warning btn-sm" 
                                                        title="Gửi email"
                                                        data-bs-toggle="tooltip">
                                                    <i class="fas fa-envelope"></i>
                                                </button>
                                                <form action="{{ route('admin.bookings.destroy', $booking) }}" 
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-outline-danger btn-sm" 
                                                            onclick="return confirm('Bạn có chắc chắn muốn xóa đặt tour này?')" 
                                                            title="Xóa"
                                                            data-bs-toggle="tooltip">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center p-4 border-top">
                        <div class="text-muted">
                            Hiển thị {{ $bookings->firstItem() }} - {{ $bookings->lastItem() }} 
                            trong tổng số {{ $bookings->total() }} đặt tour
                        </div>
                        <div>
                            {{ $bookings->links() }}
                        </div>
                    </div>
                @else
                    <!-- Empty State -->
                    <div class="text-center py-5">
                        <div class="mb-4">
                            <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center" 
                                 style="width: 120px; height: 120px;">
                                <i class="fas fa-calendar-times fa-3x text-muted"></i>
                            </div>
                        </div>
                        <h4 class="fw-bold text-dark mb-3">Chưa có đặt tour nào</h4>
                        <p class="text-muted mb-4">Các đặt tour mới sẽ hiển thị ở đây</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .card {
        border-radius: 12px;
        transition: all 0.3s ease;
    }
    
    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important;
    }
    
    .table tbody tr:hover {
        background-color: #f8f9fa;
    }
    
    .badge {
        border-radius: 8px;
        font-weight: 500;
    }
    
    .bg-opacity-10 {
        background-color: rgba(var(--bs-primary-rgb), 0.1) !important;
    }
    
    .text-primary {
        color: #0EA5E9 !important;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #0EA5E9 0%, #38BDF8 100%);
        border: none;
        border-radius: 8px;
    }
    
    .btn-primary:hover {
        background: linear-gradient(135deg, #0284C7 0%, #0EA5E9 100%);
        transform: translateY(-1px);
    }
    
    .shadow-sm {
        box-shadow: 0 2px 4px rgba(0,0,0,0.08) !important;
    }
    
    .border-0 {
        border: none !important;
    }
    
    .fw-bold {
        font-weight: 700 !important;
    }
    
    .fw-semibold {
        font-weight: 600 !important;
    }
    
    .btn-group .btn {
        border-radius: 8px;
        margin: 0 2px;
    }
    
    .form-select {
        border-radius: 8px;
        font-size: 0.875rem;
    }
    
    .form-select:focus {
        border-color: #0EA5E9;
        box-shadow: 0 0 0 0.2rem rgba(14, 165, 233, 0.25);
    }
</style>
@endsection

@section('scripts')
<script>
    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endsection

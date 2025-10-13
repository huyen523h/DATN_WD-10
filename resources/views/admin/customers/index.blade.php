@extends('layouts.admin')

@section('title', 'Quản lý Khách hàng - Admin')

@section('breadcrumb')
<li class="breadcrumb-item active">Quản lý Khách hàng</li>
@endsection

@section('content')
<!-- Header Section -->
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="fw-bold text-dark mb-2">
                    <i class="fas fa-users text-primary me-2"></i>
                    Quản lý Khách hàng
                </h2>
                <p class="text-muted mb-0">Quản lý thông tin và hoạt động của khách hàng</p>
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
                <button class="btn btn-primary">
                    <i class="fas fa-user-plus me-1"></i>
                    Thêm khách hàng
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
                            <i class="fas fa-users text-primary fa-lg"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-1">Tổng khách hàng</h6>
                        <h4 class="mb-0 fw-bold">{{ $customers->total() }}</h4>
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
                            <i class="fas fa-user-check text-success fa-lg"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-1">Khách hàng mới</h6>
                        <h4 class="mb-0 fw-bold">{{ $customers->where('created_at', '>=', now()->subDays(30))->count() }}</h4>
                        <small class="text-success">+12% so với tháng trước</small>
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
                            <i class="fas fa-star text-warning fa-lg"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-1">Khách VIP</h6>
                        <h4 class="mb-0 fw-bold">{{ $customers->where('bookings_count', '>=', 5)->count() }}</h4>
                        <small class="text-warning">Khách hàng thân thiết</small>
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
                            <i class="fas fa-chart-line text-info fa-lg"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-1">Tỷ lệ tăng trưởng</h6>
                        <h4 class="mb-0 fw-bold">+15.2%</h4>
                        <small class="text-info">So với tháng trước</small>
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
                <form method="GET" action="{{ route('admin.customers') }}" class="row g-3">
                    <div class="col-md-4">
                        <label for="search" class="form-label fw-semibold">Tìm kiếm</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="fas fa-search text-muted"></i>
                            </span>
                            <input type="text" class="form-control border-start-0" id="search" name="search" 
                                   value="{{ request('search') }}" placeholder="Tên, email, số điện thoại...">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label for="status" class="form-label fw-semibold">Loại khách hàng</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">Tất cả khách hàng</option>
                            <option value="new" {{ request('status') == 'new' ? 'selected' : '' }}>Khách hàng mới</option>
                            <option value="vip" {{ request('status') == 'vip' ? 'selected' : '' }}>Khách VIP</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Khách hàng tích cực</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="date_from" class="form-label fw-semibold">Từ ngày</label>
                        <input type="date" class="form-control" id="date_from" name="date_from" 
                               value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-2">
                        <label for="sort" class="form-label fw-semibold">Sắp xếp</label>
                        <select class="form-select" id="sort" name="sort">
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Mới nhất</option>
                            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Cũ nhất</option>
                            <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Tên A-Z</option>
                            <option value="bookings" {{ request('sort') == 'bookings' ? 'selected' : '' }}>Nhiều đặt tour</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search me-1"></i>
                                Tìm kiếm
                            </button>
                            <a href="{{ route('admin.customers') }}" class="btn btn-outline-secondary">
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

<!-- Customers Table -->
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-4">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold text-dark">
                        <i class="fas fa-list text-primary me-2"></i>
                        Danh sách khách hàng ({{ $customers->total() }} khách hàng)
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
                @if($customers->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0 py-3 px-4 fw-semibold text-muted">Khách hàng</th>
                                    <th class="border-0 py-3 px-4 fw-semibold text-muted">Thông tin liên hệ</th>
                                    <th class="border-0 py-3 px-4 fw-semibold text-muted">Hoạt động</th>
                                    <th class="border-0 py-3 px-4 fw-semibold text-muted">Trạng thái</th>
                                    <th class="border-0 py-3 px-4 fw-semibold text-muted">Ngày tham gia</th>
                                    <th class="border-0 py-3 px-4 fw-semibold text-muted text-center">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($customers as $customer)
                                    <tr class="border-bottom">
                                        <td class="py-4 px-4">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                                                    <i class="fas fa-user text-primary"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0 fw-bold text-dark">{{ $customer->name }}</h6>
                                                    <small class="text-muted">ID: #{{ $customer->id }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-4 px-4">
                                            <div class="d-flex flex-column">
                                                <span class="fw-bold text-dark">
                                                    <i class="fas fa-envelope text-muted me-1"></i>
                                                    {{ $customer->email }}
                                                </span>
                                                @if($customer->phone)
                                                    <small class="text-muted">
                                                        <i class="fas fa-phone text-muted me-1"></i>
                                                        {{ $customer->phone }}
                                                    </small>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="py-4 px-4">
                                            <div class="d-flex flex-column">
                                                <span class="fw-bold text-dark">
                                                    <i class="fas fa-calendar-check text-primary me-1"></i>
                                                    {{ $customer->bookings->count() }} đặt tour
                                                </span>
                                                @if($customer->bookings->count() > 0)
                                                    <small class="text-muted">
                                                        <i class="fas fa-money-bill-wave text-success me-1"></i>
                                                        {{ number_format($customer->bookings->sum('total_amount'), 0, ',', '.') }}đ
                                                    </small>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="py-4 px-4">
                                            @if($customer->bookings->count() >= 5)
                                                <span class="badge bg-warning bg-opacity-10 text-warning">
                                                    <i class="fas fa-crown me-1"></i>
                                                    VIP
                                                </span>
                                            @elseif($customer->bookings->count() > 0)
                                                <span class="badge bg-success bg-opacity-10 text-success">
                                                    <i class="fas fa-check-circle me-1"></i>
                                                    Hoạt động
                                                </span>
                                            @else
                                                <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                                    <i class="fas fa-user me-1"></i>
                                                    Mới
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-4 px-4">
                                            <small class="text-muted">
                                                <i class="fas fa-calendar me-1"></i>
                                                {{ $customer->created_at->format('d/m/Y') }}
                                            </small>
                                            <br>
                                            <small class="text-muted">
                                                {{ $customer->created_at->diffForHumans() }}
                                            </small>
                                        </td>
                                        <td class="py-4 px-4 text-center">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.customers.show', $customer) }}" 
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
                                                <button class="btn btn-outline-primary btn-sm" 
                                                        title="Lịch sử đặt tour"
                                                        data-bs-toggle="tooltip">
                                                    <i class="fas fa-history"></i>
                                                </button>
                                                <form action="{{ route('admin.customers.destroy', $customer) }}" 
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-outline-danger btn-sm" 
                                                            onclick="return confirm('Bạn có chắc chắn muốn xóa khách hàng này?')" 
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
                            Hiển thị {{ $customers->firstItem() }} - {{ $customers->lastItem() }} 
                            trong tổng số {{ $customers->total() }} khách hàng
                        </div>
                        <div>
                            {{ $customers->links() }}
                        </div>
                    </div>
                @else
                    <!-- Empty State -->
                    <div class="text-center py-5">
                        <div class="mb-4">
                            <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center" 
                                 style="width: 120px; height: 120px;">
                                <i class="fas fa-users fa-3x text-muted"></i>
                            </div>
                        </div>
                        <h4 class="fw-bold text-dark mb-3">Chưa có khách hàng nào</h4>
                        <p class="text-muted mb-4">Các khách hàng mới sẽ hiển thị ở đây</p>
                        <button class="btn btn-primary">
                            <i class="fas fa-user-plus me-1"></i>
                            Thêm khách hàng đầu tiên
                        </button>
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

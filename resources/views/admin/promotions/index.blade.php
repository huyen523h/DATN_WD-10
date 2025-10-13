@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="fw-bold text-dark mb-2">
                        <i class="fas fa-tags text-primary me-2"></i>
                        Quản lý Mã giảm giá
                    </h2>
                    <p class="text-muted mb-0">Quản lý và theo dõi các mã giảm giá của hệ thống</p>
                </div>
                <a href="{{ route('admin.promotions.create') }}" class="btn btn-primary btn-lg shadow-sm">
                    <i class="fas fa-plus me-2"></i>
                    Tạo mã giảm giá mới
                </a>
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
                                <i class="fas fa-percentage text-primary"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Tổng mã giảm giá</h6>
                            <h4 class="mb-0 fw-bold">{{ $promotions->total() }}</h4>
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
                                <i class="fas fa-check-circle text-success"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Đang hoạt động</h6>
                            <h4 class="mb-0 fw-bold">{{ $promotions->where('status', 'active')->count() }}</h4>
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
                                <i class="fas fa-clock text-warning"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Sắp hết hạn</h6>
                            <h4 class="mb-0 fw-bold">{{ $promotions->where('end_date', '<=', now()->addDays(7))->count() }}</h4>
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
                                <i class="fas fa-chart-line text-info"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Tỷ lệ sử dụng</h6>
                            <h4 class="mb-0 fw-bold">85%</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold text-dark">
                            <i class="fas fa-list me-2 text-primary"></i>
                            Danh sách mã giảm giá
                        </h5>
                        <div class="d-flex gap-2">
                            <button class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-download me-1"></i>
                                Xuất Excel
                            </button>
                            <button class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-filter me-1"></i>
                                Lọc
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($promotions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="border-0 py-3 px-4 fw-semibold text-muted">Mã giảm giá</th>
                                        <th class="border-0 py-3 px-4 fw-semibold text-muted">Mô tả</th>
                                        <th class="border-0 py-3 px-4 fw-semibold text-muted">Giá trị</th>
                                        <th class="border-0 py-3 px-4 fw-semibold text-muted">Thời gian</th>
                                        <th class="border-0 py-3 px-4 fw-semibold text-muted">Trạng thái</th>
                                        <th class="border-0 py-3 px-4 fw-semibold text-muted text-center">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($promotions as $promotion)
                                    <tr class="border-bottom">
                                        <td class="py-4 px-4">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-primary bg-opacity-10 rounded p-2 me-3">
                                                    <i class="fas fa-tag text-primary"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0 fw-bold text-dark">{{ $promotion->code }}</h6>
                                                    <small class="text-muted">Mã: {{ $promotion->code }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-4 px-4">
                                            <p class="mb-0 text-dark">{{ $promotion->description ?: 'Không có mô tả' }}</p>
                                        </td>
                                        <td class="py-4 px-4">
                                            <div class="d-flex flex-column">
                                                @if($promotion->discount_percent)
                                                    <span class="badge bg-info bg-opacity-10 text-info px-3 py-2 mb-1">
                                                        <i class="fas fa-percentage me-1"></i>
                                                        {{ $promotion->discount_percent }}%
                                                    </span>
                                                @endif
                                                @if($promotion->discount_amount)
                                                    <span class="badge bg-success bg-opacity-10 text-success px-3 py-2">
                                                        <i class="fas fa-money-bill-wave me-1"></i>
                                                        {{ number_format($promotion->discount_amount, 0, ',', '.') }}đ
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="py-4 px-4">
                                            <div class="d-flex flex-column">
                                                <small class="text-muted">
                                                    <i class="fas fa-calendar-alt me-1"></i>
                                                    Từ: {{ $promotion->start_date->format('d/m/Y') }}
                                                </small>
                                                <small class="text-muted">
                                                    <i class="fas fa-calendar-times me-1"></i>
                                                    Đến: {{ $promotion->end_date->format('d/m/Y') }}
                                                </small>
                                                @if($promotion->end_date->isPast())
                                                    <span class="badge bg-danger bg-opacity-10 text-danger mt-1">
                                                        <i class="fas fa-exclamation-triangle me-1"></i>
                                                        Đã hết hạn
                                                    </span>
                                                @elseif($promotion->end_date->diffInDays(now()) <= 7)
                                                    <span class="badge bg-warning bg-opacity-10 text-warning mt-1">
                                                        <i class="fas fa-clock me-1"></i>
                                                        Sắp hết hạn
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="py-4 px-4">
                                            <span class="badge 
                                                @if($promotion->status === 'active') 
                                                    bg-success bg-opacity-10 text-success
                                                @else 
                                                    bg-secondary bg-opacity-10 text-secondary
                                                @endif px-3 py-2">
                                                <i class="fas fa-circle me-1" style="font-size: 8px;"></i>
                                                {{ $promotion->status === 'active' ? 'Hoạt động' : 'Không hoạt động' }}
                                            </span>
                                        </td>
                                        <td class="py-4 px-4 text-center">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.promotions.edit', $promotion) }}" 
                                                   class="btn btn-outline-warning btn-sm" 
                                                   title="Chỉnh sửa"
                                                   data-bs-toggle="tooltip">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button class="btn btn-outline-info btn-sm" 
                                                        title="Xem chi tiết"
                                                        data-bs-toggle="tooltip">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <form action="{{ route('admin.promotions.destroy', $promotion) }}" 
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-outline-danger btn-sm" 
                                                            onclick="return confirm('Bạn có chắc chắn muốn xóa mã giảm giá này?')" 
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
                                Hiển thị {{ $promotions->firstItem() }} - {{ $promotions->lastItem() }} 
                                trong tổng số {{ $promotions->total() }} kết quả
                            </div>
                            <div>
                                {{ $promotions->links() }}
                            </div>
                        </div>
                    @else
                        <!-- Empty State -->
                        <div class="text-center py-5">
                            <div class="mb-4">
                                <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center" 
                                     style="width: 120px; height: 120px;">
                                    <i class="fas fa-tags fa-3x text-muted"></i>
                                </div>
                            </div>
                            <h4 class="fw-bold text-dark mb-3">Chưa có mã giảm giá nào</h4>
                            <p class="text-muted mb-4">Hãy tạo mã giảm giá đầu tiên để bắt đầu quản lý khuyến mãi</p>
                            <a href="{{ route('admin.promotions.create') }}" class="btn btn-primary btn-lg">
                                <i class="fas fa-plus me-2"></i>
                                Tạo mã giảm giá đầu tiên
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Custom Styles -->
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
    
    .btn-group .btn {
        border-radius: 8px;
        margin: 0 2px;
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
</style>

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



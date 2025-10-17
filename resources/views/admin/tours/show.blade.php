@extends('layouts.admin')

@section('title', 'Chi tiết Tour - Admin')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.tours.index') }}">Tours</a></li>
<li class="breadcrumb-item active">Chi tiết</li>
@endsection

@section('content')
<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2><i class="fas fa-eye text-primary"></i> Chi tiết Tour</h2>
        <p class="text-muted mb-0">{{ $tour->title }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.tours.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
        <a href="{{ route('admin.tours.edit', $tour) }}" class="btn btn-warning">
            <i class="fas fa-edit"></i> Chỉnh sửa
        </a>
    </div>
</div>

<!-- Tour Information -->
<div class="row">
    <!-- Main Content -->
    <div class="col-lg-8">
        <!-- Basic Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle text-primary"></i> Thông tin cơ bản
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-item">
                            <label class="info-label">
                                <i class="fas fa-heading text-primary"></i> Tên tour
                            </label>
                            <div class="info-value">{{ $tour->title }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-item">
                            <label class="info-label">
                                <i class="fas fa-tags text-primary"></i> Danh mục
                            </label>
                            <div class="info-value">
                                <span class="badge bg-primary">{{ $tour->category->name }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="info-item">
                    <label class="info-label">
                        <i class="fas fa-align-left text-primary"></i> Mô tả
                    </label>
                    <div class="info-value">{{ $tour->description }}</div>
                </div>
                
                <div class="row">
                    <div class="col-md-4">
                        <div class="info-item">
                            <label class="info-label">
                                <i class="fas fa-map-marker-alt text-primary"></i> Địa điểm
                            </label>
                            <div class="info-value">{{ $tour->location ?? 'Chưa cập nhật' }}</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-item">
                            <label class="info-label">
                                <i class="fas fa-calendar-day text-primary"></i> Số ngày
                            </label>
                            <div class="info-value">{{ $tour->duration_days ?? 'Chưa cập nhật' }} ngày</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-item">
                            <label class="info-label">
                                <i class="fas fa-moon text-primary"></i> Số đêm
                            </label>
                            <div class="info-value">{{ $tour->duration_nights ?? 'Chưa cập nhật' }} đêm</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Images -->
        @if($tour->images->count() > 0)
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-images text-primary"></i> Hình ảnh tour
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($tour->images as $image)
                    <div class="col-md-4 mb-3">
                        <div class="position-relative">
                            <img src="{{ Storage::url($image->image_url) }}" alt="Tour Image" class="img-fluid rounded" style="height: 200px; width: 100%; object-fit: cover;">
                            @if($image->is_cover)
                                <span class="badge bg-primary position-absolute top-0 start-0 m-2">Ảnh bìa</span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Pricing -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-money-bill-wave text-primary"></i> Giá tour
                </h5>
            </div>
            <div class="card-body">
                <div class="info-item">
                    <label class="info-label">
                        <i class="fas fa-dollar-sign text-primary"></i> Giá gốc
                    </label>
                    <div class="info-value">
                        <strong class="text-success fs-5">{{ number_format($tour->price) }} VNĐ</strong>
                    </div>
                </div>
                
                @if($tour->discount_price)
                <div class="info-item">
                    <label class="info-label">
                        <i class="fas fa-percentage text-primary"></i> Giá khuyến mãi
                    </label>
                    <div class="info-value">
                        <strong class="text-danger fs-5">{{ number_format($tour->discount_price) }} VNĐ</strong>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Status -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-toggle-on text-primary"></i> Trạng thái
                </h5>
            </div>
            <div class="card-body">
                <div class="info-item">
                    <label class="info-label">
                        <i class="fas fa-info-circle text-primary"></i> Trạng thái hiện tại
                    </label>
                    <div class="info-value">
                        @switch($tour->status)
                            @case('active')
                                <span class="badge bg-success">
                                    <i class="fas fa-check-circle"></i> Hoạt động
                                </span>
                                @break
                            @case('inactive')
                                <span class="badge bg-secondary">
                                    <i class="fas fa-pause-circle"></i> Không hoạt động
                                </span>
                                @break
                            @case('draft')
                                <span class="badge bg-warning">
                                    <i class="fas fa-edit"></i> Bản nháp
                                </span>
                                @break
                            @default
                                <span class="badge bg-info">{{ $tour->status }}</span>
                        @endswitch
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-bar text-primary"></i> Thống kê
                </h5>
            </div>
            <div class="card-body">
                <div class="info-item">
                    <label class="info-label">
                        <i class="fas fa-bookmark text-primary"></i> Số lượt đặt tour
                    </label>
                    <div class="info-value">
                        <span class="badge bg-info fs-6">{{ $tour->bookings->count() }}</span>
                    </div>
                </div>
                
                <div class="info-item">
                    <label class="info-label">
                        <i class="fas fa-heart text-primary"></i> Số lượt yêu thích
                    </label>
                    <div class="info-value">
                        <span class="badge bg-danger fs-6">{{ $tour->wishlists->count() }}</span>
                    </div>
                </div>
                
                <div class="info-item">
                    <label class="info-label">
                        <i class="fas fa-star text-primary"></i> Đánh giá trung bình
                    </label>
                    <div class="info-value">
                        <span class="badge bg-warning fs-6">
                            {{ $tour->reviews->avg('rating') ? number_format($tour->reviews->avg('rating'), 1) : 'Chưa có' }}/5
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Timestamps -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-clock text-primary"></i> Thời gian
                </h5>
            </div>
            <div class="card-body">
                <div class="info-item">
                    <label class="info-label">
                        <i class="fas fa-calendar-plus text-primary"></i> Ngày tạo
                    </label>
                    <div class="info-value">{{ $tour->created_at->format('d/m/Y H:i') }}</div>
                </div>
                
                <div class="info-item">
                    <label class="info-label">
                        <i class="fas fa-calendar-edit text-primary"></i> Cập nhật lần cuối
                    </label>
                    <div class="info-value">{{ $tour->updated_at->format('d/m/Y H:i') }}</div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-cogs text-primary"></i> Thao tác
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.tours.edit', $tour) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Chỉnh sửa tour
                    </a>
                    
                    <form action="{{ route('admin.tours.destroy', $tour) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa tour này?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="fas fa-trash"></i> Xóa tour
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Bookings -->
@if($tour->bookings->count() > 0)
<div class="card mt-4">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-list text-primary"></i> Đặt tour gần đây
        </h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Khách hàng</th>
                        <th>Số người</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                        <th>Ngày đặt</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tour->bookings->take(5) as $booking)
                    <tr>
                        <td>{{ $booking->id }}</td>
                        <td>{{ $booking->user->name }}</td>
                        <td>{{ $booking->adult_count + $booking->child_count }}</td>
                        <td>{{ number_format($booking->total_amount) }} VNĐ</td>
                        <td>
                            <span class="badge bg-{{ $booking->status == 'confirmed' ? 'success' : ($booking->status == 'pending' ? 'warning' : 'secondary') }}">
                                {{ ucfirst($booking->status) }}
                            </span>
                        </td>
                        <td>{{ $booking->created_at->format('d/m/Y') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif
@endsection

@section('styles')
<style>
.info-item {
    margin-bottom: 1rem;
}

.info-label {
    display: block;
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.25rem;
    font-size: 0.875rem;
}

.info-value {
    color: #6b7280;
    font-size: 0.95rem;
    line-height: 1.5;
}

.card {
    border: none;
    box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
}

.card-header {
    background-color: #f8fafc;
    border-bottom: 1px solid #e5e7eb;
}

.card-header h5 {
    color: #374151;
    font-weight: 600;
}

.badge {
    font-size: 0.75rem;
    padding: 0.375rem 0.75rem;
}

.btn {
    border-radius: 0.5rem;
    font-weight: 500;
}

.btn-primary {
    background-color: #3b82f6;
    border-color: #3b82f6;
}

.btn-primary:hover {
    background-color: #2563eb;
    border-color: #2563eb;
}

.btn-warning {
    background-color: #f59e0b;
    border-color: #f59e0b;
}

.btn-warning:hover {
    background-color: #d97706;
    border-color: #d97706;
}

.btn-danger {
    background-color: #ef4444;
    border-color: #ef4444;
}

.btn-danger:hover {
    background-color: #dc2626;
    border-color: #dc2626;
}

.btn-outline-secondary {
    color: #6b7280;
    border-color: #d1d5db;
}

.btn-outline-secondary:hover {
    background-color: #f3f4f6;
    border-color: #9ca3af;
    color: #374151;
}
</style>
@endsection
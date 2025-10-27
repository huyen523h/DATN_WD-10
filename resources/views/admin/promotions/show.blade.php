@extends('layouts.admin')

@section('title', 'Chi tiết mã giảm giá')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Chi tiết mã giảm giá</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.promotions') }}">Mã giảm giá</a></li>
                    <li class="breadcrumb-item active">{{ $promotion->code }}</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('admin.promotions.edit', $promotion) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Chỉnh sửa
            </a>
            <a href="{{ route('admin.promotions') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Promotion Details -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Thông tin mã giảm giá</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h4 class="text-primary">{{ $promotion->code }}</h4>
                            <p class="text-muted">{{ $promotion->description ?? 'Không có mô tả' }}</p>
                            
                            <div class="row mt-3">
                                <div class="col-sm-6">
                                    <p><strong>Loại giảm giá:</strong> 
                                        @if($promotion->discount_percent)
                                            {{ $promotion->discount_percent }}% 
                                        @else
                                            {{ number_format($promotion->discount_amount) }} VNĐ
                                        @endif
                                    </p>
                                    <p><strong>Ngày bắt đầu:</strong> {{ $promotion->start_date->format('d/m/Y') }}</p>
                                </div>
                                <div class="col-sm-6">
                                    <p><strong>Ngày kết thúc:</strong> {{ $promotion->end_date->format('d/m/Y') }}</p>
                                    <p><strong>Trạng thái:</strong> 
                                        <span class="badge badge-{{ $promotion->status === 'active' ? 'success' : 'secondary' }} badge-lg">
                                            {{ $promotion->status === 'active' ? 'Hoạt động' : 'Không hoạt động' }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-center">
                            <div class="promotion-badge p-4 bg-primary text-white rounded">
                                <h3 class="mb-2">{{ $promotion->code }}</h3>
                                @if($promotion->discount_percent)
                                    <h2 class="mb-0">{{ $promotion->discount_percent }}%</h2>
                                    <small>GIẢM GIÁ</small>
                                @else
                                    <h2 class="mb-0">{{ number_format($promotion->discount_amount) }}</h2>
                                    <small>VNĐ GIẢM</small>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Usage Statistics -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Thống kê sử dụng</h6>
                </div>
                <div class="card-body">
                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="stat-icon stat-icon-primary">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <div class="stat-value">{{ $promotion->bookings->count() }}</div>
                            <div class="stat-label">Lần sử dụng</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon stat-icon-success">
                                <i class="fas fa-percentage"></i>
                            </div>
                            <div class="stat-value">{{ number_format($promotion->bookings->sum('discount_amount')) }}</div>
                            <div class="stat-label">Tổng giảm giá (VNĐ)</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon stat-icon-info">
                                <i class="fas fa-dollar-sign"></i>
                            </div>
                            <div class="stat-value">{{ number_format($promotion->bookings->sum('total_amount')) }}</div>
                            <div class="stat-label">Tổng doanh thu (VNĐ)</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon stat-icon-{{ $promotion->isActive() ? 'success' : 'warning' }}">
                                <i class="fas fa-{{ $promotion->isActive() ? 'check-circle' : 'clock' }}"></i>
                            </div>
                            <div class="stat-value">{{ $promotion->isActive() ? 'Hoạt động' : 'Hết hạn' }}</div>
                            <div class="stat-label">Trạng thái</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Booking History -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Lịch sử sử dụng ({{ $promotion->bookings->count() }})</h6>
                </div>
                <div class="card-body">
                    @if($promotion->bookings->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Khách hàng</th>
                                        <th>Tour</th>
                                        <th>Giảm giá</th>
                                        <th>Tổng tiền</th>
                                        <th>Ngày sử dụng</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($promotion->bookings as $booking)
                                    <tr>
                                        <td>
                                            <strong>{{ $booking->user->name }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $booking->user->email }}</small>
                                        </td>
                                        <td>{{ $booking->tour->title }}</td>
                                        <td class="text-success">-{{ number_format($booking->discount_amount) }} VNĐ</td>
                                        <td>{{ number_format($booking->total_amount) }} VNĐ</td>
                                        <td>{{ $booking->created_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-ticket-alt fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Chưa có ai sử dụng mã giảm giá này</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Promotion Status -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Trạng thái</h6>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        @if($promotion->isActive())
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle fa-2x mb-2"></i>
                                <h5>Đang hoạt động</h5>
                                <p class="mb-0">Mã giảm giá có thể sử dụng</p>
                            </div>
                        @else
                            <div class="alert alert-secondary">
                                <i class="fas fa-times-circle fa-2x mb-2"></i>
                                <h5>Không hoạt động</h5>
                                <p class="mb-0">Mã giảm giá không thể sử dụng</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Promotion Actions -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Hành động</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.promotions.edit', $promotion) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Chỉnh sửa mã giảm giá
                        </a>
                        
                        @if($promotion->status === 'active')
                            <form action="{{ route('admin.promotions.update', $promotion) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="inactive">
                                <button type="submit" class="btn btn-secondary w-100">
                                    <i class="fas fa-pause"></i> Tạm dừng
                                </button>
                            </form>
                        @else
                            <form action="{{ route('admin.promotions.update', $promotion) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="active">
                                <button type="submit" class="btn btn-success w-100">
                                    <i class="fas fa-play"></i> Kích hoạt
                                </button>
                            </form>
                        @endif

                        <form action="{{ route('admin.promotions.destroy', $promotion) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa mã giảm giá này?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="fas fa-trash"></i> Xóa mã giảm giá
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Promotion Info -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Thông tin chi tiết</h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <td><strong>ID:</strong></td>
                            <td>{{ $promotion->id }}</td>
                        </tr>
                        <tr>
                            <td><strong>Tạo lúc:</strong></td>
                            <td>{{ $promotion->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Cập nhật:</strong></td>
                            <td>{{ $promotion->updated_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Thời gian còn lại:</strong></td>
                            <td>
                                @if($promotion->end_date > now())
                                    {{ $promotion->end_date->diffForHumans() }}
                                @else
                                    <span class="text-danger">Đã hết hạn</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.promotion-badge {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}
</style>
@endsection

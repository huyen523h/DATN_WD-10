@extends('layouts.admin')

@section('title', 'Chi tiết mã giảm giá')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Chi tiết mã giảm giá</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.promotions.index') }}">Mã giảm giá</a></li>
                    <li class="breadcrumb-item active">{{ $promotion->code }}</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('admin.promotions.edit', $promotion) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Chỉnh sửa
            </a>
            <a href="{{ route('admin.promotions.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </div>
    </div>

    <div class="row">
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
                                <div class="col-sm-6">
                                    <p><strong>Số lượng:</strong> 
                                        <span class="text-info font-weight-bold">{{ $promotion->used_count }}</span> / {{ $promotion->quantity }} mã
                                        <small class="text-muted">(Còn lại: {{ $promotion->quantity - $promotion->used_count }})</small>
                                    </p>

                                    <p><strong>Đơn tối thiểu:</strong> 
                                        @if($promotion->min_order_value > 0)
                                            <span class="text-danger fw-bold">{{ number_format($promotion->min_order_value) }} VNĐ</span>
                                        @else
                                            <span class="badge badge-success">Không yêu cầu</span>
                                        @endif
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
                            <div class="stat-value">{{ $promotion->used_count }}</div>
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
                            @php
                                $now = now();
                                $statusLabel = '';
                                $icon = '';
                                $color = '';

                                if ($promotion->status == 'inactive') {
                                    $statusLabel = 'Đang tạm dừng';
                                    $icon = 'pause-circle';
                                    $color = 'warning';
                                } elseif ($now < $promotion->start_date) {
                                    $statusLabel = 'Chưa bắt đầu';
                                    $icon = 'hourglass-start';
                                    $color = 'info';
                                } elseif ($now > $promotion->end_date) {
                                    $statusLabel = 'Đã hết hạn';
                                    $icon = 'times-circle';
                                    $color = 'danger';
                                } elseif ($promotion->used_count >= $promotion->quantity) {
                                    $statusLabel = 'Đã hết mã';
                                    $icon = 'ban';
                                    $color = 'danger';
                                } else {
                                    $statusLabel = 'Đang diễn ra';
                                    $icon = 'check-circle';
                                    $color = 'success';
                                }
                            @endphp

                            <div class="stat-icon stat-icon-{{ $color }}">
                                <i class="fas fa-{{ $icon }}"></i>
                            </div>
                            <div class="stat-value text-{{ $color }}" style="font-size: 1.2rem;">
                                {{ $statusLabel }}
                            </div>
                            <div class="stat-label">Trạng thái thực tế</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card shadow mb-4">
                 <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Lịch sử đơn hàng</h6>
                </div>
                <div class="card-body">
                    @if($promotion->bookings->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Khách hàng</th>
                                        <th>Giảm giá</th>
                                        <th>Tổng tiền</th>
                                        <th>Ngày dùng</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($promotion->bookings as $booking)
                                    <tr>
                                        <td>{{ $booking->user->name ?? 'N/A' }}</td>
                                        <td class="text-success">-{{ number_format($booking->discount_amount) }}đ</td>
                                        <td>{{ number_format($booking->total_amount) }}đ</td>
                                        <td>{{ $booking->created_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center text-muted py-3">
                            <small>Chưa có dữ liệu chi tiết đơn hàng (Nếu "Lần sử dụng" > 0 mà bảng này trống, có thể do dữ liệu cũ chưa liên kết).</small>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Hành động</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        {{-- Nút Chỉnh sửa --}}
                        <a href="{{ route('admin.promotions.edit', $promotion) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Chỉnh sửa mã giảm giá
                        </a>
                        
                        {{-- Nút Tạm dừng / Kích hoạt --}}
                        @if($promotion->status === 'active')
                            <form action="{{ route('admin.promotions.update', $promotion) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="inactive">
                                <button type="submit" class="btn btn-secondary w-100">
                                    <i class="fas fa-pause"></i> Tạm dừng
                                </button>
                            </form>
                        @else
                            <form action="{{ route('admin.promotions.update', $promotion) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="active">
                                <button type="submit" class="btn btn-success w-100">
                                    <i class="fas fa-play"></i> Kích hoạt
                                </button>
                            </form>
                        @endif

                        {{-- [FIX] Nút Xóa Thông Minh (Giống trang Index) --}}
                        @if($promotion->used_count > 0)
                             <button type="button" class="btn btn-secondary w-100" 
                                    onclick="Swal.fire({
                                        icon: 'error',
                                        title: 'Không thể xóa!',
                                        text: 'Mã này đã có {{ $promotion->used_count }} lượt sử dụng. Bạn chỉ có thể Tạm dừng.',
                                        confirmButtonText: 'Đã hiểu'
                                    })">
                                <i class="fas fa-trash-alt"></i> Xóa mã giảm giá
                            </button>
                        @else
                            <form action="{{ route('admin.promotions.destroy', $promotion) }}" method="POST" class="delete-form-show">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-danger w-100 btn-delete-show">
                                    <i class="fas fa-trash"></i> Xóa mã giảm giá
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Chi tiết hệ thống</h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr><td><strong>ID:</strong></td><td>{{ $promotion->id }}</td></tr>
                        <tr><td><strong>Tạo:</strong></td><td>{{ $promotion->created_at->format('d/m/Y H:i') }}</td></tr>
                        <tr><td><strong>Cập nhật:</strong></td><td>{{ $promotion->updated_at->format('d/m/Y H:i') }}</td></tr>
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

@section('scripts')
{{-- Thêm SweetAlert --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Xử lý nút xóa trong trang chi tiết
        const deleteButton = document.querySelector('.btn-delete-show');
        if(deleteButton){
            deleteButton.addEventListener('click', function(e) {
                e.preventDefault();
                const form = this.closest('.delete-form-show');
                Swal.fire({
                    title: 'Bạn chắc chắn muốn xóa?',
                    text: "Hành động này không thể hoàn tác!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Vâng, xóa nó!',
                    cancelButtonText: 'Hủy bỏ'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        }
    });
</script>
@endsection
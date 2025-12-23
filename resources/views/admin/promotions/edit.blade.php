@extends('layouts.admin')

@section('title', 'Chỉnh sửa mã giảm giá')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-edit"></i> Chỉnh sửa mã: {{ $promotion->code }}</h2>
                <a href="{{ route('admin.promotions.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
            </div>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <h6>Có lỗi xảy ra:</h6>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-percentage"></i> Thông tin mã giảm giá
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.promotions.update', $promotion) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        {{-- DÒNG 1: MÃ + SỐ LƯỢNG + TRẠNG THÁI --}}
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="code" class="form-label fw-bold">Mã giảm giá <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control text-uppercase" id="code" name="code" 
                                           value="{{ old('code', $promotion->code) }}" required>
                                    <div class="form-text">Mã duy nhất, viết liền không dấu.</div>
                                </div>
                            </div>
                            
                            {{-- [QUAN TRỌNG] Ô Quantity đã được thêm vào đây --}}
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="quantity" class="form-label fw-bold">Số lượng phát hành <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="quantity" name="quantity" 
                                           value="{{ old('quantity', $promotion->quantity) }}" min="1" required>
                                    <div class="form-text">
                                        Đã sử dụng: <span class="text-success fw-bold">{{ $promotion->used_count }}</span> lần.
                                        (Không được nhập nhỏ hơn số đã dùng)
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="status" class="form-label fw-bold">Trạng thái <span class="text-danger">*</span></label>
                                    <select class="form-select" id="status" name="status" required>
                                        <option value="active" {{ old('status', $promotion->status)=='active'?'selected':'' }}>Hoạt động</option>
                                        <option value="inactive" {{ old('status', $promotion->status)=='inactive'?'selected':'' }}>Tạm khóa</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- DÒNG 2: THỜI GIAN --}}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="start_date" class="form-label fw-bold">Ngày bắt đầu <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="start_date" name="start_date" 
                                           value="{{ old('start_date', $promotion->start_date->format('Y-m-d')) }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="end_date" class="form-label fw-bold">Ngày kết thúc <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="end_date" name="end_date" 
                                           value="{{ old('end_date', $promotion->end_date->format('Y-m-d')) }}" required>
                                </div>
                            </div>
                        </div>

                        <hr>

                        {{-- DÒNG 3: GIÁ TRỊ & ĐIỀU KIỆN --}}
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="discount_percent" class="form-label fw-bold text-primary">Giảm theo %</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="discount_percent" name="discount_percent" 
                                               value="{{ old('discount_percent', $promotion->discount_percent) }}" min="0" max="100" step="0.1">
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="discount_amount" class="form-label fw-bold text-success">Giảm theo Tiền mặt</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="discount_amount" name="discount_amount" 
                                               value="{{ old('discount_amount', $promotion->discount_amount) }}" min="0" step="1000">
                                        <span class="input-group-text">VNĐ</span>
                                    </div>
                                </div>
                            </div>
                            
                            {{-- [QUAN TRỌNG] Ô Min Order đã thêm vào --}}
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="min_order_value" class="form-label fw-bold text-danger">Đơn hàng tối thiểu</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="min_order_value" name="min_order_value" 
                                               value="{{ old('min_order_value', $promotion->min_order_value) }}" min="0" step="1000">
                                        <span class="input-group-text">VNĐ</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label fw-bold">Mô tả chi tiết</label>
                            <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $promotion->description) }}</textarea>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Cập nhật thay đổi
                            </button>
                            <a href="{{ route('admin.promotions.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Hủy bỏ
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const codeInput = document.getElementById('code');
    if (codeInput) {
        codeInput.addEventListener('input', function() {
            this.value = this.value.toUpperCase().replace(/\s/g, '');
        });
    }
});
</script>
@endsection
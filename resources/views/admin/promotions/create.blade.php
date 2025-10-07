@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-plus"></i> Tạo mã giảm giá</h2>
                <a href="{{ route('admin.promotions') }}" class="btn btn-secondary">
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
                    <form action="{{ route('admin.promotions.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="code" class="form-label">Mã giảm giá <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="code" name="code" 
                                           value="{{ old('code') }}" placeholder="VD: WELCOME10" required>
                                    <div class="form-text">Mã phải là duy nhất và viết hoa</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Trạng thái <span class="text-danger">*</span></label>
                                    <select class="form-select" id="status" name="status" required>
                                        <option value="active" {{ old('status')=='active'?'selected':'' }}>Hoạt động</option>
                                        <option value="inactive" {{ old('status')=='inactive'?'selected':'' }}>Không hoạt động</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Mô tả</label>
                            <textarea class="form-control" id="description" name="description" rows="3" 
                                      placeholder="Mô tả về mã giảm giá">{{ old('description') }}</textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="discount_percent" class="form-label">Giảm theo phần trăm (%)</label>
                                    <input type="number" class="form-control" id="discount_percent" name="discount_percent" 
                                           value="{{ old('discount_percent') }}" min="0" max="100" step="0.01" 
                                           placeholder="VD: 10">
                                    <div class="form-text">Nhập từ 0 đến 100</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="discount_amount" class="form-label">Giảm theo số tiền (VND)</label>
                                    <input type="number" class="form-control" id="discount_amount" name="discount_amount" 
                                           value="{{ old('discount_amount') }}" min="0" step="1000" 
                                           placeholder="VD: 50000">
                                    <div class="form-text">Nhập số tiền giảm (VND)</div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="start_date" class="form-label">Ngày bắt đầu <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="start_date" name="start_date" 
                                           value="{{ old('start_date') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="end_date" class="form-label">Ngày kết thúc <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="end_date" name="end_date" 
                                           value="{{ old('end_date') }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <strong>Lưu ý:</strong> Bạn cần nhập ít nhất một trong hai loại giảm giá (phần trăm hoặc số tiền).
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Tạo mã giảm giá
                            </button>
                            <a href="{{ route('admin.promotions') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Hủy
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
            this.value = this.value.toUpperCase();
        });
    }
});
</script>
@endsection



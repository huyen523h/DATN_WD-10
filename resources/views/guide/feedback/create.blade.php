@extends('layouts.app')

@section('title', 'Gửi phản hồi đánh giá')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('guide.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('guide.feedback.index') }}">Phản hồi đánh giá</a></li>
                    <li class="breadcrumb-item active">Gửi mới</li>
                </ol>
            </nav>
            <h2 class="fw-bold text-primary">
                <i class="fas fa-paper-plane me-2"></i>Gửi phản hồi đánh giá
            </h2>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="{{ route('guide.feedback.store', $departure->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="feedback_type" class="form-label">Loại phản hồi <span class="text-danger">*</span></label>
                            <select class="form-select @error('feedback_type') is-invalid @enderror" id="feedback_type" name="feedback_type" required>
                                <option value="tour" {{ old('feedback_type') == 'tour' ? 'selected' : '' }}>Về tour</option>
                                <option value="service" {{ old('feedback_type') == 'service' ? 'selected' : '' }}>Về dịch vụ</option>
                                <option value="supplier" {{ old('feedback_type') == 'supplier' ? 'selected' : '' }}>Về nhà cung cấp</option>
                                <option value="other" {{ old('feedback_type') == 'other' ? 'selected' : '' }}>Khác</option>
                            </select>
                            @error('feedback_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3" id="supplier_name_group" style="display: none;">
                            <label for="supplier_name" class="form-label">Tên nhà cung cấp</label>
                            <input type="text" class="form-control @error('supplier_name') is-invalid @enderror" 
                                id="supplier_name" name="supplier_name" value="{{ old('supplier_name') }}" 
                                placeholder="VD: Nhà hàng ABC, Khách sạn XYZ...">
                            @error('supplier_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="subject" class="form-label">Tiêu đề <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('subject') is-invalid @enderror" 
                                id="subject" name="subject" value="{{ old('subject') }}" required>
                            @error('subject')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="content" class="form-label">Nội dung phản hồi <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('content') is-invalid @enderror" 
                                id="content" name="content" rows="8" required>{{ old('content') }}</textarea>
                            <small class="form-text text-muted">Tối thiểu 20 ký tự</small>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="rating" class="form-label">Đánh giá (1-5 sao)</label>
                            <select class="form-select" id="rating" name="rating">
                                <option value="">Không đánh giá</option>
                                <option value="1" {{ old('rating') == '1' ? 'selected' : '' }}>1 sao</option>
                                <option value="2" {{ old('rating') == '2' ? 'selected' : '' }}>2 sao</option>
                                <option value="3" {{ old('rating') == '3' ? 'selected' : '' }}>3 sao</option>
                                <option value="4" {{ old('rating') == '4' ? 'selected' : '' }}>4 sao</option>
                                <option value="5" {{ old('rating') == '5' ? 'selected' : '' }}>5 sao</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="suggestions" class="form-label">Đề xuất cải thiện</label>
                            <textarea class="form-control @error('suggestions') is-invalid @enderror" 
                                id="suggestions" name="suggestions" rows="4" 
                                placeholder="Đề xuất cách cải thiện...">{{ old('suggestions') }}</textarea>
                            @error('suggestions')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="images" class="form-label">Hình ảnh (nếu có)</label>
                            <input type="file" class="form-control @error('images.*') is-invalid @enderror" 
                                id="images" name="images[]" multiple accept="image/*">
                            <small class="form-text text-muted">Có thể chọn nhiều ảnh (tối đa 5MB mỗi ảnh)</small>
                            @error('images.*')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('guide.feedback.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Hủy
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-2"></i>Gửi phản hồi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0">Thông tin tour</h6>
                </div>
                <div class="card-body">
                    <p><strong>Tour:</strong> {{ $departure->tour->title }}</p>
                    <p><strong>Ngày khởi hành:</strong> {{ $departure->departure_date->format('d/m/Y') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('feedback_type').addEventListener('change', function() {
    const supplierGroup = document.getElementById('supplier_name_group');
    if (this.value === 'supplier') {
        supplierGroup.style.display = 'block';
    } else {
        supplierGroup.style.display = 'none';
    }
});
</script>
@endsection


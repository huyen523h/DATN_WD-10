@extends('layouts.app')

@section('title', 'Sửa phản hồi đánh giá')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('guide.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('guide.feedback.index') }}">Phản hồi đánh giá</a></li>
                    <li class="breadcrumb-item active">Sửa</li>
                </ol>
            </nav>
            <h2 class="fw-bold text-primary">
                <i class="fas fa-edit me-2"></i>Sửa phản hồi đánh giá
            </h2>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="{{ route('guide.feedback.update', $feedback->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="feedback_type" class="form-label">Loại phản hồi <span class="text-danger">*</span></label>
                            <select class="form-select @error('feedback_type') is-invalid @enderror" id="feedback_type" name="feedback_type" required>
                                <option value="tour" {{ old('feedback_type', $feedback->feedback_type) == 'tour' ? 'selected' : '' }}>Về tour</option>
                                <option value="service" {{ old('feedback_type', $feedback->feedback_type) == 'service' ? 'selected' : '' }}>Về dịch vụ</option>
                                <option value="supplier" {{ old('feedback_type', $feedback->feedback_type) == 'supplier' ? 'selected' : '' }}>Về nhà cung cấp</option>
                                <option value="other" {{ old('feedback_type', $feedback->feedback_type) == 'other' ? 'selected' : '' }}>Khác</option>
                            </select>
                            @error('feedback_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3" id="supplier_name_group" style="display: {{ old('feedback_type', $feedback->feedback_type) == 'supplier' ? 'block' : 'none' }};">
                            <label for="supplier_name" class="form-label">Tên nhà cung cấp</label>
                            <input type="text" class="form-control @error('supplier_name') is-invalid @enderror" 
                                id="supplier_name" name="supplier_name" value="{{ old('supplier_name', $feedback->supplier_name) }}" 
                                placeholder="VD: Nhà hàng ABC, Khách sạn XYZ...">
                            @error('supplier_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="subject" class="form-label">Tiêu đề <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('subject') is-invalid @enderror" 
                                id="subject" name="subject" value="{{ old('subject', $feedback->subject) }}" required>
                            @error('subject')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="content" class="form-label">Nội dung phản hồi <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('content') is-invalid @enderror" 
                                id="content" name="content" rows="8" required>{{ old('content', $feedback->content) }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="rating" class="form-label">Đánh giá (1-5 sao)</label>
                            <select class="form-select" id="rating" name="rating">
                                <option value="">Không đánh giá</option>
                                @for($i = 1; $i <= 5; $i++)
                                    <option value="{{ $i }}" {{ old('rating', $feedback->rating) == $i ? 'selected' : '' }}>
                                        {{ $i }} sao
                                    </option>
                                @endfor
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="suggestions" class="form-label">Đề xuất cải thiện</label>
                            <textarea class="form-control @error('suggestions') is-invalid @enderror" 
                                id="suggestions" name="suggestions" rows="4" 
                                placeholder="Đề xuất cách cải thiện...">{{ old('suggestions', $feedback->suggestions) }}</textarea>
                            @error('suggestions')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        @if($feedback->images && count($feedback->images) > 0)
                            <div class="mb-3">
                                <label class="form-label">Ảnh hiện tại</label>
                                <div class="row">
                                    @foreach($feedback->images as $index => $image)
                                        <div class="col-md-3 mb-2">
                                            <div class="position-relative">
                                                <img src="{{ asset('storage/' . $image) }}" alt="Image" 
                                                    class="img-fluid rounded" style="max-height: 100px; object-fit: cover; width: 100%;">
                                                <div class="form-check position-absolute top-0 end-0 m-1">
                                                    <input class="form-check-input" type="checkbox" 
                                                        name="remove_images[]" value="{{ $image }}" id="remove_{{ $index }}">
                                                    <label class="form-check-label text-white bg-dark rounded px-1" 
                                                        for="remove_{{ $index }}" style="font-size: 10px;">Xóa</label>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="mb-3">
                            <label for="images" class="form-label">Thêm hình ảnh mới</label>
                            <input type="file" class="form-control @error('images.*') is-invalid @enderror" 
                                id="images" name="images[]" multiple accept="image/*">
                            <small class="form-text text-muted">Có thể chọn nhiều ảnh (tối đa 5MB mỗi ảnh)</small>
                            @error('images.*')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('guide.feedback.show', $feedback->id) }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Hủy
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Cập nhật
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
                    @if($feedback->departure)
                        <p><strong>Tour:</strong> {{ $feedback->departure->tour->title }}</p>
                        <p><strong>Ngày khởi hành:</strong> {{ $feedback->departure->departure_date->format('d/m/Y') }}</p>
                    @else
                        <p class="text-muted">Không có thông tin tour</p>
                    @endif
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


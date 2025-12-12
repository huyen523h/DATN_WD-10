@extends('layouts.app')

@section('title', 'Sửa nhật ký tour')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('guide.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('guide.tour-logs.index', $departure->id) }}">Nhật ký tour</a></li>
                    <li class="breadcrumb-item active">Sửa</li>
                </ol>
            </nav>
            <h2 class="fw-bold text-primary">
                <i class="fas fa-edit me-2"></i>Sửa nhật ký tour
            </h2>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="{{ route('guide.tour-logs.update', [$departure->id, $log->id]) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="log_date" class="form-label">Ngày <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('log_date') is-invalid @enderror" 
                                id="log_date" name="log_date" value="{{ old('log_date', $log->log_date->format('Y-m-d')) }}" required>
                            @error('log_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="type" class="form-label">Loại nhật ký <span class="text-danger">*</span></label>
                            <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                                <option value="note" {{ old('type', $log->type) == 'note' ? 'selected' : '' }}>Ghi chú</option>
                                <option value="incident" {{ old('type', $log->type) == 'incident' ? 'selected' : '' }}>Sự cố</option>
                                <option value="feedback" {{ old('type', $log->type) == 'feedback' ? 'selected' : '' }}>Phản hồi khách</option>
                                <option value="other" {{ old('type', $log->type) == 'other' ? 'selected' : '' }}>Khác</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="content" class="form-label">Nội dung <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('content') is-invalid @enderror" 
                                id="content" name="content" rows="8" required>{{ old('content', $log->content) }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        @if($log->images && count($log->images) > 0)
                            <div class="mb-3">
                                <label class="form-label">Ảnh hiện tại</label>
                                <div class="row">
                                    @foreach($log->images as $index => $image)
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
                            <a href="{{ route('guide.tour-logs.show', [$departure->id, $log->id]) }}" class="btn btn-secondary">
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
                    <p><strong>Tour:</strong> {{ $departure->tour->title }}</p>
                    <p><strong>Ngày khởi hành:</strong> {{ $departure->departure_date->format('d/m/Y') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


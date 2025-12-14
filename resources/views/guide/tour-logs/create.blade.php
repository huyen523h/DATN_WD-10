@extends('layouts.app')

@section('title', 'Thêm nhật ký tour')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('guide.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('guide.tour-logs.index', $departure->id) }}">Nhật ký tour</a></li>
                    <li class="breadcrumb-item active">Thêm mới</li>
                </ol>
            </nav>
            <h2 class="fw-bold text-primary">
                <i class="fas fa-plus me-2"></i>Thêm nhật ký tour
            </h2>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="{{ route('guide.tour-logs.store', $departure->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="log_date" class="form-label">Ngày <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('log_date') is-invalid @enderror" 
                                id="log_date" name="log_date" value="{{ old('log_date', date('Y-m-d')) }}" required>
                            @error('log_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="type" class="form-label">Loại nhật ký <span class="text-danger">*</span></label>
                            <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                                <option value="note" {{ old('type') == 'note' ? 'selected' : '' }}>Ghi chú</option>
                                <option value="incident" {{ old('type') == 'incident' ? 'selected' : '' }}>Sự cố</option>
                                <option value="feedback" {{ old('type') == 'feedback' ? 'selected' : '' }}>Phản hồi khách</option>
                                <option value="other" {{ old('type') == 'other' ? 'selected' : '' }}>Khác</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="content" class="form-label">Nội dung <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('content') is-invalid @enderror" 
                                id="content" name="content" rows="8" required>{{ old('content') }}</textarea>
                            <small class="form-text text-muted">Tối thiểu 10 ký tự</small>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="images" class="form-label">Hình ảnh</label>
                            <input type="file" class="form-control @error('images.*') is-invalid @enderror" 
                                id="images" name="images[]" multiple accept="image/*">
                            <small class="form-text text-muted">Có thể chọn nhiều ảnh (tối đa 5MB mỗi ảnh)</small>
                            @error('images.*')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('guide.tour-logs.index', $departure->id) }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Hủy
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Lưu nhật ký
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
                    <p><strong>Điểm hẹn:</strong> {{ $departure->meeting_point ?? 'Chưa có' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


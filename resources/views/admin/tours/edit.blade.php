@extends('layouts.admin')

@section('title', 'Chỉnh sửa Tour - Admin')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.tours.index') }}">Tours</a></li>
<li class="breadcrumb-item active">Chỉnh sửa</li>
@endsection

@section('content')
<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2><i class="fas fa-edit text-primary"></i> Chỉnh sửa Tour</h2>
        <p class="text-muted mb-0">Cập nhật thông tin tour: {{ $tour->title }}</p>
    </div>
    <a href="{{ route('admin.tours.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left"></i> Quay lại
    </a>
</div>

<!-- Success/Error Messages -->
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Edit Form -->
<form action="{{ route('admin.tours.update', $tour) }}" method="POST" enctype="multipart/form-data" id="tourForm">
    @csrf
    @method('PUT')
    
    <!-- Basic Information -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="fas fa-info-circle text-primary"></i> Thông tin cơ bản
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-heading text-primary"></i> Tên tour *
                        </label>
                        <input type="text" name="title" class="form-control" value="{{ old('title', $tour->title) }}" required>
                        <div class="form-text">Tên tour sẽ hiển thị trên trang chủ và danh sách tours</div>
                        @error('title')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-tags text-primary"></i> Danh mục *
                        </label>
                        <select name="category_id" class="form-select" required>
                            <option value="">--- Chọn danh mục ---</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $tour->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-label">
                    <i class="fas fa-align-left text-primary"></i> Mô tả tour *
                </label>
                <textarea name="description" class="form-control" rows="4" required>{{ old('description', $tour->description) }}</textarea>
                <div class="form-text">Mô tả chi tiết sẽ giúp khách hàng hiểu rõ hơn về tour</div>
                @error('description')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-map-marker-alt text-primary"></i> Địa điểm
                        </label>
                        <input type="text" name="location" class="form-control" value="{{ old('location', $tour->location) }}">
                        @error('location')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-calendar-day text-primary"></i> Số ngày
                        </label>
                        <input type="number" name="duration_days" class="form-control" value="{{ old('duration_days', $tour->duration_days) }}" min="1">
                        @error('duration_days')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-moon text-primary"></i> Số đêm
                        </label>
                        <input type="number" name="duration_nights" class="form-control" value="{{ old('duration_nights', $tour->duration_nights) }}" min="0">
                        @error('duration_nights')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pricing -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="fas fa-money-bill-wave text-primary"></i> Giá tour
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-dollar-sign text-primary"></i> Giá gốc (VNĐ) *
                        </label>
                        <input type="number" name="price" class="form-control" value="{{ old('price', $tour->price) }}" required min="0" step="1000">
                        @error('price')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-percentage text-primary"></i> Giá khuyến mãi (VNĐ)
                        </label>
                        <input type="number" name="discount_price" class="form-control" value="{{ old('discount_price', $tour->discount_price) }}" min="0" step="1000">
                        <div class="form-text">Để trống nếu không có khuyến mãi</div>
                        @error('discount_price')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
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
            <div class="form-group">
                <label class="form-label">
                    <i class="fas fa-info-circle text-primary"></i> Trạng thái tour *
                </label>
                <select name="status" class="form-select" required>
                    <option value="active" {{ old('status', $tour->status) == 'active' ? 'selected' : '' }}>Hoạt động</option>
                    <option value="inactive" {{ old('status', $tour->status) == 'inactive' ? 'selected' : '' }}>Không hoạt động</option>
                    <option value="draft" {{ old('status', $tour->status) == 'draft' ? 'selected' : '' }}>Bản nháp</option>
                </select>
                @error('status')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>

    <!-- Current Images -->
    @if($tour->images->count() > 0)
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="fas fa-images text-primary"></i> Hình ảnh hiện tại
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                @foreach($tour->images as $image)
                <div class="col-md-3 mb-3">
                    <div class="position-relative">
                        <img src="{{ Storage::url($image->image_url) }}" alt="Tour Image" class="img-fluid rounded" style="height: 150px; width: 100%; object-fit: cover;">
                        @if($image->is_cover)
                            <span class="badge bg-primary position-absolute top-0 start-0 m-2">Ảnh bìa</span>
                        @endif
                        <form action="{{ route('admin.tours.images.delete', ['tour' => $tour->id, 'image' => $image->id]) }}" method="POST" class="d-inline position-absolute top-0 end-0 m-2" onsubmit="return confirm('Bạn có chắc chắn muốn xóa hình ảnh này?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Add New Images -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="fas fa-plus text-primary"></i> Thêm hình ảnh mới
            </h5>
        </div>
        <div class="card-body">
            <div class="form-group">
                <label class="form-label">
                    <i class="fas fa-images text-primary"></i> Hình ảnh tour
                </label>
                <input type="file" name="images[]" class="form-control" multiple accept="image/*">
                <div class="form-text">Có thể chọn nhiều hình ảnh cùng lúc (JPG, PNG, GIF - tối đa 2MB mỗi file)</div>
                @error('images.*')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>

    <!-- Submit Actions -->
    <div class="card submit-section">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-1">
                        <i class="fas fa-check-circle text-primary"></i> Hoàn tất chỉnh sửa
                    </h6>
                    <p class="text-muted mb-0">Kiểm tra lại thông tin trước khi lưu</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.tours.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times"></i> Hủy
                    </a>
                    <button type="submit" class="btn btn-primary" id="saveBtn">
                        <i class="fas fa-save"></i> Cập nhật tour
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@section('scripts')
<script>

// Form validation
document.getElementById('tourForm').addEventListener('submit', function(e) {
    const requiredFields = this.querySelectorAll('[required]');
    let isValid = true;
    let firstInvalidField = null;
    
    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            field.classList.add('is-invalid');
            isValid = false;
            
            if (!firstInvalidField) {
                firstInvalidField = field;
            }
        } else {
            field.classList.remove('is-invalid');
        }
    });
    
    if (!isValid) {
        e.preventDefault();
        
        if (firstInvalidField) {
            firstInvalidField.scrollIntoView({ 
                behavior: 'smooth', 
                block: 'center' 
            });
            firstInvalidField.focus();
        }
        
        alert('Vui lòng điền đầy đủ các trường bắt buộc!');
        return false;
    }
    
    // Show loading state
    const saveBtn = document.getElementById('saveBtn');
    const originalText = saveBtn.innerHTML;
    saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang cập nhật...';
    saveBtn.disabled = true;
});
</script>
@endsection
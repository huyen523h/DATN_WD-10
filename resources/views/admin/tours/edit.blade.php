@extends('layouts.admin')

@section('title', 'Sửa tour - Admin')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.tours') }}">Quản lý Tours</a></li>
<li class="breadcrumb-item active">Sửa tour</li>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2><i class="fas fa-edit text-primary"></i> Sửa tour: {{ $tour->title }}</h2>
        <p class="text-muted mb-0">Cập nhật thông tin tour du lịch</p>
    </div>
    <a href="{{ route('admin.tours') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left"></i> Quay lại
    </a>
</div>

<div class="row">
    <div class="col-12">
        <div class="admin-card">
            <div class="card-body">
                    <form method="POST" action="{{ route('admin.tours.update', $tour) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <!-- Basic Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-info-circle"></i> Thông tin cơ bản
                                </h5>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="title" class="form-label">Tên tour <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                           id="title" name="title" value="{{ old('title', $tour->title) }}" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="category_id" class="form-label">Danh mục <span class="text-danger">*</span></label>
                                    <select class="form-select @error('category_id') is-invalid @enderror" 
                                            id="category_id" name="category_id" required>
                                        <option value="">-- Chọn danh mục --</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" 
                                                    {{ old('category_id', $tour->category_id) == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="duration_days" class="form-label">Số ngày <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('duration_days') is-invalid @enderror" 
                                           id="duration_days" name="duration_days" value="{{ old('duration_days', $tour->duration_days) }}" 
                                           min="1" max="30" required>
                                    @error('duration_days')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="price" class="form-label">Giá tour (VNĐ) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('price') is-invalid @enderror" 
                                           id="price" name="price" value="{{ old('price', $tour->price) }}" 
                                           min="0" step="1000" required>
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Mô tả tour <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="5" required>{{ old('description', $tour->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Trạng thái</label>
                                    <select class="form-select @error('status') is-invalid @enderror" 
                                            id="status" name="status">
                                        <option value="active" {{ old('status', $tour->status) == 'active' ? 'selected' : '' }}>Hoạt động</option>
                                        <option value="inactive" {{ old('status', $tour->status) == 'inactive' ? 'selected' : '' }}>Tạm dừng</option>
                                        <option value="draft" {{ old('status', $tour->status) == 'draft' ? 'selected' : '' }}>Bản nháp</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Current Images -->
                        @if($tour->images->count() > 0)
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-images"></i> Hình ảnh hiện tại
                                </h5>
                                <div class="row">
                                    @foreach($tour->images as $image)
                                        <div class="col-md-3 mb-3">
                                            <div class="position-relative">
                                                <img src="{{ $image->image_url }}" alt="Tour image" 
                                                     class="img-fluid rounded" style="height: 150px; width: 100%; object-fit: cover;">
                                                <div class="position-absolute top-0 end-0 p-2">
                                                    <button type="button" class="btn btn-sm btn-danger" 
                                                            onclick="removeImage({{ $image->id }})">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                                @if($image->is_cover)
                                                    <div class="position-absolute bottom-0 start-0 p-2">
                                                        <span class="badge bg-primary">Ảnh bìa</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Add New Images -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-plus"></i> Thêm hình ảnh mới
                                </h5>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="images" class="form-label">Hình ảnh tour</label>
                            <input type="file" class="form-control @error('images') is-invalid @enderror" 
                                   id="images" name="images[]" multiple accept="image/*">
                            <div class="form-text">Có thể chọn nhiều hình ảnh cùng lúc</div>
                            @error('images')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Schedule -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-calendar-alt"></i> Lịch trình tour
                                </h5>
                            </div>
                        </div>

                        <div id="schedule-container">
                            @if($tour->schedules->count() > 0)
                                @foreach($tour->schedules as $schedule)
                                    <div class="schedule-item mb-3 p-3 border rounded">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <label class="form-label">Ngày</label>
                                                <input type="number" class="form-control" name="schedule_day[]" 
                                                       value="{{ $schedule->day }}" min="1" max="30">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Tiêu đề</label>
                                                <input type="text" class="form-control" name="schedule_title[]" 
                                                       value="{{ $schedule->title }}">
                                            </div>
                                            <div class="col-md-5">
                                                <label class="form-label">Mô tả</label>
                                                <textarea class="form-control" name="schedule_description[]" 
                                                          rows="2">{{ $schedule->description }}</textarea>
                                            </div>
                                            <div class="col-md-1">
                                                <label class="form-label">&nbsp;</label>
                                                <button type="button" class="btn btn-outline-danger btn-sm d-block" 
                                                        onclick="removeSchedule(this)">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="schedule-item mb-3 p-3 border rounded">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label class="form-label">Ngày</label>
                                            <input type="number" class="form-control" name="schedule_day[]" 
                                                   value="1" min="1" max="30">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Tiêu đề</label>
                                            <input type="text" class="form-control" name="schedule_title[]" 
                                                   placeholder="VD: Khởi hành từ Hà Nội">
                                        </div>
                                        <div class="col-md-5">
                                            <label class="form-label">Mô tả</label>
                                            <textarea class="form-control" name="schedule_description[]" 
                                                      rows="2" placeholder="Mô tả chi tiết hoạt động"></textarea>
                                        </div>
                                        <div class="col-md-1">
                                            <label class="form-label">&nbsp;</label>
                                            <button type="button" class="btn btn-outline-danger btn-sm d-block" 
                                                    onclick="removeSchedule(this)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <button type="button" class="btn btn-outline-primary" onclick="addSchedule()">
                            <i class="fas fa-plus"></i> Thêm ngày
                        </button>

                        <!-- Departures -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-plane-departure"></i> Lịch khởi hành
                                </h5>
                            </div>
                        </div>

                        <div id="departure-container">
                            @if($tour->departures->count() > 0)
                                @foreach($tour->departures as $departure)
                                    <div class="departure-item mb-3 p-3 border rounded">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label class="form-label">Ngày khởi hành</label>
                                                <input type="date" class="form-control" name="departure_date[]" 
                                                       value="{{ $departure->departure_date }}">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Tổng số chỗ</label>
                                                <input type="number" class="form-control" name="seats_total[]" 
                                                       value="{{ $departure->seats_total }}" min="1" max="100">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Chỗ còn lại</label>
                                                <input type="number" class="form-control" name="seats_available[]" 
                                                       value="{{ $departure->seats_available }}" min="0" max="100">
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">&nbsp;</label>
                                                <button type="button" class="btn btn-outline-danger btn-sm d-block" 
                                                        onclick="removeDeparture(this)">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="departure-item mb-3 p-3 border rounded">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label class="form-label">Ngày khởi hành</label>
                                            <input type="date" class="form-control" name="departure_date[]" 
                                                   value="{{ date('Y-m-d', strtotime('+7 days')) }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Tổng số chỗ</label>
                                            <input type="number" class="form-control" name="seats_total[]" 
                                                   value="20" min="1" max="100">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Chỗ còn lại</label>
                                            <input type="number" class="form-control" name="seats_available[]" 
                                                   value="20" min="0" max="100">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">&nbsp;</label>
                                            <button type="button" class="btn btn-outline-danger btn-sm d-block" 
                                                    onclick="removeDeparture(this)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <button type="button" class="btn btn-outline-primary" onclick="addDeparture()">
                            <i class="fas fa-plus"></i> Thêm lịch khởi hành
                        </button>

                        <!-- Submit Buttons -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('admin.tours') }}" class="btn btn-secondary">
                                        <i class="fas fa-times"></i> Hủy
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Cập nhật tour
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
function addSchedule() {
    const container = document.getElementById('schedule-container');
    const newItem = document.createElement('div');
    newItem.className = 'schedule-item mb-3 p-3 border rounded';
    newItem.innerHTML = `
        <div class="row">
            <div class="col-md-2">
                <label class="form-label">Ngày</label>
                <input type="number" class="form-control" name="schedule_day[]" 
                       value="${container.children.length + 1}" min="1" max="30">
            </div>
            <div class="col-md-4">
                <label class="form-label">Tiêu đề</label>
                <input type="text" class="form-control" name="schedule_title[]" 
                       placeholder="VD: Khởi hành từ Hà Nội">
            </div>
            <div class="col-md-5">
                <label class="form-label">Mô tả</label>
                <textarea class="form-control" name="schedule_description[]" 
                          rows="2" placeholder="Mô tả chi tiết hoạt động"></textarea>
            </div>
            <div class="col-md-1">
                <label class="form-label">&nbsp;</label>
                <button type="button" class="btn btn-outline-danger btn-sm d-block" 
                        onclick="removeSchedule(this)">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    `;
    container.appendChild(newItem);
}

function removeSchedule(button) {
    button.closest('.schedule-item').remove();
}

function addDeparture() {
    const container = document.getElementById('departure-container');
    const newItem = document.createElement('div');
    newItem.className = 'departure-item mb-3 p-3 border rounded';
    newItem.innerHTML = `
        <div class="row">
            <div class="col-md-4">
                <label class="form-label">Ngày khởi hành</label>
                <input type="date" class="form-control" name="departure_date[]" 
                       value="{{ date('Y-m-d', strtotime('+7 days')) }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Tổng số chỗ</label>
                <input type="number" class="form-control" name="seats_total[]" 
                       value="20" min="1" max="100">
            </div>
            <div class="col-md-3">
                <label class="form-label">Chỗ còn lại</label>
                <input type="number" class="form-control" name="seats_available[]" 
                       value="20" min="0" max="100">
            </div>
            <div class="col-md-2">
                <label class="form-label">&nbsp;</label>
                <button type="button" class="btn btn-outline-danger btn-sm d-block" 
                        onclick="removeDeparture(this)">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    `;
    container.appendChild(newItem);
}

function removeDeparture(button) {
    button.closest('.departure-item').remove();
}

function removeImage(imageId) {
    if (confirm('Bạn có chắc chắn muốn xóa hình ảnh này?')) {
        // Implement image deletion logic here
        console.log('Deleting image:', imageId);
    }
}

// Auto-update seats_available when seats_total changes
document.addEventListener('change', function(e) {
    if (e.target.name === 'seats_total[]') {
        const seatsAvailable = e.target.closest('.departure-item').querySelector('input[name="seats_available[]"]');
        seatsAvailable.value = e.target.value;
    }
});
</script>
@endsection

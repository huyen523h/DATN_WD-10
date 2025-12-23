@extends('layouts.admin')

@section('title', 'Thêm tour mới - Admin')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1"><i class="fas fa-plus text-primary"></i> Thêm tour mới</h2>
            <p class="text-muted mb-0">Nhập thông tin tour theo bố cục gợi ý để dễ quản trị</p>
        </div>
        <a href="{{ route('admin.tours.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <div class="d-flex">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <div>
                    <strong>Không thể lưu tour.</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.tours.store') }}" enctype="multipart/form-data" id="tourForm">
        @csrf

        <div class="row g-3">
            <div class="col-lg-8">
                <!-- (1) Thông tin tổng quan tour -->
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-0">
                        <h5 class="mb-0"><i class="fas fa-info-circle text-primary"></i> Thông tin tổng quan tour</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label for="title" class="form-label">Tên tour <span class="text-danger">*</span></label>
                                <input type="text" id="title" name="title"
                                       class="form-control @error('title') is-invalid @enderror"
                                       value="{{ old('title') }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="category_id" class="form-label">Danh mục <span class="text-danger">*</span></label>
                                <select id="category_id" name="category_id"
                                        class="form-select @error('category_id') is-invalid @enderror" required>
                                    <option value="">-- Chọn danh mục --</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row g-3 align-items-end mt-1">
                            <div class="col-md-3">
                                <label class="form-label d-flex align-items-center gap-1">
                                    Số ngày <span class="text-danger">*</span>
                                    <span class="text-muted small">(VD: 3 ngày 2 đêm)</span>
                                </label>
                                <input type="number" name="duration_days" class="form-control @error('duration_days') is-invalid @enderror"
                                       value="{{ old('duration_days', 1) }}" min="1" max="60" required>
                                @error('duration_days')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Số đêm</label>
                                <input type="number"
                                       name="duration_nights"
                                       class="form-control @error('duration_nights') is-invalid @enderror"
                                       value="{{ old('duration_nights', max(0, (int) old('duration_days') - 1)) }}"
                                       min="0" max="60">
                                @error('duration_nights')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Giá cơ bản (VNĐ) <span class="text-danger">*</span></label>
                                <input type="number" name="price" class="form-control @error('price') is-invalid @enderror"
                                       value="{{ old('price') }}" min="0" step="1000" required>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Trạng thái</label>
                                @php $status = old('status','active'); @endphp
                                <select name="status" class="form-select @error('status') is-invalid @enderror">
                                    <option value="active" {{ $status === 'active' ? 'selected' : '' }}>Hoạt động</option>
                                    <option value="inactive" {{ $status === 'inactive' ? 'selected' : '' }}>Tạm dừng</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- (2) Mô tả & hình ảnh tour -->
                <div class="card shadow-sm border-0 mt-3">
                    <div class="card-header bg-white border-0">
                        <h5 class="mb-0"><i class="fas fa-image text-primary"></i> Mô tả & hình ảnh tour</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Mô tả ngắn</label>
                            <textarea name="short_description" class="form-control @error('short_description') is-invalid @enderror" rows="2"
                                      placeholder="Hiển thị trên trang listing">{{ old('short_description') }}</textarea>
                            @error('short_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Mô tả chi tiết</label>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="6"
                                      placeholder="Thêm nội dung chi tiết, hành trình, dịch vụ...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Ảnh đại diện tour</label>
                                <input type="file" name="cover_image" class="form-control @error('cover_image') is-invalid @enderror" accept="image/*">
                                <div class="form-text">Ảnh hiển thị chính, ưu tiên kích thước 1200x630</div>
                                @error('cover_image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Thư viện ảnh tour</label>
                                <input type="file" name="images[]" class="form-control @error('images.*') is-invalid @enderror" multiple accept="image/*">
                                <div class="form-text">Chọn nhiều ảnh, ảnh đầu tiên sẽ ưu tiên nếu chưa có ảnh đại diện</div>
                                @error('images.*')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- (4) Lịch trình tour -->
                <div class="card shadow-sm border-0 mt-3">
                    <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-calendar-alt text-primary"></i> Lịch trình tour</h5>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="addScheduleDay()">
                                <i class="fas fa-plus"></i> Thêm ngày
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="schedule-container" class="d-flex flex-column gap-3">
                            <div class="schedule-item border rounded p-3 bg-light" data-day="1">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div class="badge bg-primary-subtle text-primary fw-semibold">Ngày 1</div>
                                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeScheduleDay(this)" aria-label="Xóa ngày">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label">Tiêu đề</label>
                                        <input type="text" name="schedule_title[]" class="form-control" placeholder="HÀ NỘI – HẠ LONG">
                                    </div>
                                    <div class="col-md-8">
                                        <label class="form-label">Mô tả chi tiết</label>
                                        <textarea name="schedule_description[]" class="form-control" rows="3" placeholder="Hoạt động, điểm tham quan, bữa ăn..."></textarea>
                                    </div>
                                </div>
                                <input type="hidden" name="schedule_day[]" value="1">
                            </div>
                        </div>
                        <div class="form-text mt-2">Gợi ý: thêm đủ số ngày, có thể xóa/thêm linh hoạt.</div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- (3) Giá theo đối tượng -->
                <div class="card shadow-sm border-0 ">
                    <div class="card-header bg-white border-0">
                        <h5 class="mb-0"><i class="fas fa-users text-primary"></i> Giá theo đối tượng</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label d-flex align-items-center gap-2">
                                <i class="fas fa-user text-primary"></i> Giá người lớn
                                <span class="text-muted small" data-bs-toggle="tooltip" title="Người lớn: > 11 tuổi">(?)</span>
                            </label>
                            <input type="number" name="price_adult" class="form-control" min="0" step="1000"
                                   value="{{ old('price_adult') }}" placeholder="Mặc định theo giá cơ bản">
                        </div>
                        <div class="mb-3">
                            <label class="form-label d-flex align-items-center gap-2">
                                <i class="fas fa-child text-success"></i> Giá trẻ em
                                <span class="text-muted small" data-bs-toggle="tooltip" title="Trẻ em: 2–11 tuổi">(?)</span>
                            </label>
                            <input type="number" name="price_child" class="form-control" min="0" step="1000"
                                   value="{{ old('price_child') }}" placeholder="Tùy chọn">
                        </div>
                        <div class="mb-3">
                            <label class="form-label d-flex align-items-center gap-2">
                                <i class="fas fa-baby text-info"></i> Giá em bé
                                <span class="text-muted small" data-bs-toggle="tooltip" title="Em bé: < 2 tuổi">(?)</span>
                            </label>
                            <input type="number" name="price_infant" class="form-control" min="0" step="1000"
                                   value="{{ old('price_infant') }}" placeholder="Miễn phí / tùy chọn">
                        </div>
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="checkbox" value="1" id="apply_same_price" name="apply_same_price"
                                   {{ old('apply_same_price') ? 'checked' : '' }}>
                            <label class="form-check-label" for="apply_same_price">
                                Áp dụng giá chung cho tất cả lịch khởi hành
                            </label>
                        </div>
                        <div class="form-text">Nếu bật, mọi lịch khởi hành sẽ ưu tiên dùng mức giá trên.</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end align-items-center mt-3">
            <a href="{{ route('admin.tours.index') }}" class="btn btn-outline-secondary me-2">
                <i class="fas fa-times"></i> Hủy
            </a>
            <button type="submit" class="btn btn-primary" id="saveBtn">
                <i class="fas fa-save"></i> Lưu tour
            </button>
        </div>
    </form>
@endsection

@section('styles')
    <style>
        .schedule-item {
            border: 1px dashed #d1d5db;
            background: #f8fafc;
        }
        .schedule-item .badge {
            padding: 0.5rem 0.75rem;
            border-radius: 999px;
        }
    </style>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(el => new bootstrap.Tooltip(el));
        });

        function updateScheduleLabels() {
            const items = document.querySelectorAll('#schedule-container .schedule-item');
            items.forEach((item, index) => {
                const dayNumber = index + 1;
                item.dataset.day = dayNumber;
                item.querySelector('.badge').textContent = `Ngày ${dayNumber}`;
                const hiddenDay = item.querySelector('input[name="schedule_day[]"]');
                if (hiddenDay) hiddenDay.value = dayNumber;
            });
        }

        function addScheduleDay() {
            const container = document.getElementById('schedule-container');
            const dayNumber = container.children.length + 1;

            const wrapper = document.createElement('div');
            wrapper.className = 'schedule-item border rounded p-3 bg-light';
            wrapper.dataset.day = dayNumber;
            wrapper.innerHTML = `
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div class="badge bg-primary-subtle text-primary fw-semibold">Ngày ${dayNumber}</div>
                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeScheduleDay(this)" aria-label="Xóa ngày">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Tiêu đề</label>
                        <input type="text" name="schedule_title[]" class="form-control" placeholder="Ngày ${dayNumber}">
                    </div>
                    <div class="col-md-8">
                        <label class="form-label">Mô tả chi tiết</label>
                        <textarea name="schedule_description[]" class="form-control" rows="3" placeholder="Hoạt động, điểm tham quan, bữa ăn..."></textarea>
                    </div>
                </div>
                <input type="hidden" name="schedule_day[]" value="${dayNumber}">
            `;

            container.appendChild(wrapper);
            updateScheduleLabels();
        }

        function removeScheduleDay(button) {
            const container = document.getElementById('schedule-container');
            if (container.children.length === 1) {
                return; // luôn giữ ít nhất 1 ngày
            }
            button.closest('.schedule-item').remove();
            updateScheduleLabels();
        }
    </script>
@endsection


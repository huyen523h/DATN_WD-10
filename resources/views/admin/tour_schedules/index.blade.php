@extends('layouts.admin')

@section('title', 'Quản lý lịch trình tour')

@section('content')
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3><i class="fas fa-calendar-alt"></i> Lịch trình Tour: {{ $tour->title }}</h3>
            <div>
            <a href="{{ route('admin.tours.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại danh sách Tour
            </a>
            <a href="{{ route('admin.schedules.create', $tour->id) }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Thêm ngày lịch trình
            </a>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($schedules->isEmpty())
            <div class="alert alert-info text-center py-4">
                <i class="fas fa-calendar-times fa-3x mb-3 text-info"></i>
                <h5>Chưa có lịch trình nào</h5>
                <p class="text-muted mb-3">Tạo lịch trình để quản lý tour hiệu quả hơn</p>
                <a href="{{ route('admin.schedules.create', $tour->id) }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Thêm lịch trình ngay
                </a>
            </div>
        @else
            <!-- Timeline View -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-gradient text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-timeline"></i> Timeline Lịch trình
                        </h5>
                        <span class="badge bg-light text-dark">{{ $schedules->count() }} ngày</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="alert alert-info mb-4">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-info-circle fa-2x me-3"></i>
                            <div>
                                <strong>Lịch trình template cho tour: {{ $tour->title }}</strong>
                                <br>
                                <small>Lịch trình này sẽ áp dụng cho tất cả departures trừ khi có departure cụ thể được chỉ định</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Accordion cho từng ngày -->
            <div class="accordion" id="scheduleAccordion">
                @foreach($schedules as $index => $schedule)
                    <div class="accordion-item mb-3 shadow-sm">
                        <h2 class="accordion-header" id="heading{{ $schedule->id }}">
                            <button class="accordion-button {{ $index === 0 ? '' : 'collapsed' }}" type="button" 
                                    data-bs-toggle="collapse" data-bs-target="#collapse{{ $schedule->id }}" 
                                    aria-expanded="{{ $index === 0 ? 'true' : 'false' }}" 
                                    aria-controls="collapse{{ $schedule->id }}">
                                <div class="d-flex justify-content-between align-items-center w-100 me-3">
                                    <div>
                                        <span class="badge bg-primary me-2">Ngày {{ $schedule->day_number }}</span>
                                        <strong>{{ $schedule->title }}</strong>
                                    </div>
                                    @if($schedule->departure)
                                        <small class="text-muted">
                                            <i class="fas fa-calendar-check"></i> Áp dụng cho lịch khởi hành #{{ $schedule->departure->id }}
                                        </small>
                                    @endif
                                </div>
                            </button>
                        </h2>
                        <div id="collapse{{ $schedule->id }}" 
                             class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}" 
                             aria-labelledby="heading{{ $schedule->id }}" 
                             data-bs-parent="#scheduleAccordion">
                            <div class="accordion-body">
                                <div class="row">
                                    <!-- Thông tin chính -->
                                    <div class="col-md-8">
                                        <div class="card border-0 bg-light">
                                            <div class="card-body">
                                                <h5 class="card-title mb-3">
                                                    <i class="fas fa-info-circle text-primary"></i> Thông tin ngày
                                                </h5>
                                                
                                                <div class="row mb-3">
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold text-muted small">MÔ TẢ NGÀY</label>
                                                        <p class="mb-0">{{ $schedule->description ?? 'Chưa có mô tả' }}</p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold text-muted small">ĐỊA ĐIỂM</label>
                                                        <p class="mb-0">
                                                            @if($schedule->location)
                                                                <i class="fas fa-map-marker-alt text-danger"></i> {{ $schedule->location }}
                                                            @else
                                                                <span class="text-muted">Chưa cập nhật</span>
                                                            @endif
                                                        </p>
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold text-muted small">GIỜ KHỞI HÀNH</label>
                                                        <p class="mb-0">
                                                            @if($schedule->start_time)
                                                                <i class="fas fa-clock text-primary"></i> 
                                                                {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}
                                                            @else
                                                                <span class="text-muted">Chưa cập nhật</span>
                                                            @endif
                                                        </p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold text-muted small">HƯỚNG DẪN VIÊN PHỤ TRÁCH</label>
                                                        <p class="mb-0">
                                                            @if($schedule->guide)
                                                                <i class="fas fa-user-tie text-success"></i> 
                                                                <strong>{{ $schedule->guide->name }}</strong>
                                                                @if($schedule->guide->phone)
                                                                    <br><small class="text-muted">{{ $schedule->guide->phone }}</small>
                                                                @endif
                                                            @else
                                                                <span class="text-muted">Chưa gán</span>
                                                            @endif
                                                        </p>
                                                    </div>
                                                </div>

                                                @if($schedule->departure)
                                                    <div class="alert alert-info mt-3 mb-0">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <div>
                                                                <i class="fas fa-info-circle"></i> 
                                                                <strong>Áp dụng cho lịch khởi hành #{{ $schedule->departure->id }}</strong>
                                                                <br>
                                                                <small>Ngày khởi hành: {{ \Carbon\Carbon::parse($schedule->departure->departure_date)->format('d/m/Y') }}</small>
                                                            </div>
                                                            <a href="{{ route('admin.departures.show', $schedule->departure->id) }}" 
                                                               class="btn btn-sm btn-outline-primary">
                                                                <i class="fas fa-external-link-alt"></i> Xem chi tiết
                                                            </a>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="alert alert-secondary mt-3 mb-0">
                                                        <i class="fas fa-info-circle"></i> 
                                                        <strong>Lịch trình template</strong> - Áp dụng cho tất cả departures
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Form chỉnh sửa nhanh (nếu có departure_id) -->
                                    <div class="col-md-4">
                                        <div class="card border-primary">
                                            <div class="card-header bg-primary text-white">
                                                <h6 class="mb-0"><i class="fas fa-edit"></i> Chỉnh sửa nhanh</h6>
                                            </div>
                                            <div class="card-body">
                                                <form action="{{ route('admin.schedules.update', [$tour->id, $schedule->id]) }}" method="POST" class="quick-edit-form">
                                                    @csrf
                                                    @method('PUT')
                                                    
                                                    <input type="hidden" name="day_number" value="{{ $schedule->day_number }}">
                                                    <input type="hidden" name="title" value="{{ $schedule->title }}">
                                                    <input type="hidden" name="description" value="{{ $schedule->description }}">
                                                    <input type="hidden" name="location" value="{{ $schedule->location }}">

                                                    <div class="mb-3">
                                                        <label class="form-label small fw-bold">Lịch khởi hành</label>
                                                        <select name="departure_id" class="form-select form-select-sm">
                                                            <option value="">-- Không chọn --</option>
                                                            @foreach($departures as $departure)
                                                                <option value="{{ $departure->id }}" 
                                                                    {{ $schedule->departure_id == $departure->id ? 'selected' : '' }}>
                                                                    #{{ $departure->id }} - {{ \Carbon\Carbon::parse($departure->departure_date)->format('d/m/Y') }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        @if($schedule->departure_id)
                                                            <small class="text-muted d-block mt-1">
                                                                <i class="fas fa-info-circle"></i> Áp dụng cho lịch khởi hành #{{ $schedule->departure->id }}
                                                            </small>
                                                        @endif
                                                    </div>

                                                    @if($schedule->departure_id)
                                                        <div class="mb-3">
                                                            <label class="form-label small fw-bold">Giờ khởi hành</label>
                                                            <input type="time" name="start_time" 
                                                                   class="form-control form-control-sm" 
                                                                   value="{{ $schedule->start_time ? \Carbon\Carbon::parse($schedule->start_time)->format('H:i') : '' }}">
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label small fw-bold">HDV phụ trách</label>
                                                            <select name="guide_id" class="form-select form-select-sm">
                                                                <option value="">-- Chọn HDV --</option>
                                                                @foreach($guides as $guide)
                                                                    <option value="{{ $guide->id }}" 
                                                                        {{ $schedule->guide_id == $guide->id ? 'selected' : '' }}>
                                                                        {{ $guide->name }}
                                                                        @if($guide->phone)
                                                                            - {{ $guide->phone }}
                                                                        @endif
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    @endif

                                                    <div class="d-grid gap-2">
                                                        <button type="submit" class="btn btn-sm btn-primary">
                                                            <i class="fas fa-save"></i> Lưu thay đổi
                                                        </button>
                                                        <a href="{{ route('admin.schedules.edit', [$tour->id, $schedule->id]) }}" 
                                                           class="btn btn-sm btn-outline-secondary">
                                                            <i class="fas fa-edit"></i> Chỉnh sửa đầy đủ
                                                        </a>
                                                        <form action="{{ route('admin.schedules.destroy', [$tour->id, $schedule->id]) }}" 
                                                              method="POST" 
                                                              class="d-inline"
                                                              onsubmit="return confirm('Bạn có chắc muốn xóa lịch trình này?')">
                                @csrf
                                @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger w-100">
                                    <i class="fas fa-trash-alt"></i> Xóa
                                </button>
                            </form>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    @push('styles')
    <style>
        .accordion-button {
            font-weight: 600;
            font-size: 1rem;
        }
        .accordion-button:not(.collapsed) {
            background: linear-gradient(135deg, #e7f1ff 0%, #cfe2ff 100%);
            color: #0d6efd;
            box-shadow: inset 0 -1px 0 rgba(0,0,0,0.125);
        }
        .card {
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1) !important;
        }
        .schedule-item {
            transition: all 0.3s ease;
        }
        .schedule-item:hover {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 10px;
            margin: -10px;
        }
        .badge {
            font-size: 0.8rem;
            padding: 0.4em 0.8em;
        }
        .form-label {
            font-size: 0.85rem;
            margin-bottom: 0.5rem;
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        // Hiển thị/ẩn các trường chỉnh sửa khi chọn departure_id
        document.querySelectorAll('select[name="departure_id"]').forEach(select => {
            select.addEventListener('change', function() {
                const form = this.closest('.quick-edit-form');
                const startTimeField = form.querySelector('input[name="start_time"]');
                const guideField = form.querySelector('select[name="guide_id"]');
                
                if (this.value) {
                    if (startTimeField) startTimeField.closest('.mb-3').style.display = 'block';
                    if (guideField) guideField.closest('.mb-3').style.display = 'block';
                } else {
                    if (startTimeField) startTimeField.closest('.mb-3').style.display = 'none';
                    if (guideField) guideField.closest('.mb-3').style.display = 'none';
                }
            });
            
            // Trigger change để set trạng thái ban đầu
            select.dispatchEvent(new Event('change'));
        });
    </script>
    @endpush
@endsection

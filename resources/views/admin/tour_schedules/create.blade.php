@extends('layouts.admin')

@section('title', 'Thêm lịch trình tour')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3><i class="fas fa-plus-circle"></i> Thêm lịch trình cho tour: {{ $tour->title }}</h3>
        <a href="{{ route('admin.schedules.index', $tour->id) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.schedules.store', $tour->id) }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">
                            Ngày thứ mấy <span class="text-danger">*</span>
                        </label>
                        <input type="number" name="day_number" class="form-control @error('day_number') is-invalid @enderror" 
                               value="{{ old('day_number') }}" required min="1">
                        @error('day_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">
                            Tiêu đề <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" 
                               value="{{ old('title') }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">
                        <i class="fas fa-align-left"></i> Mô tả ngày <span class="text-danger">*</span>
                    </label>
                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                              rows="5" required placeholder="Nhập mô tả chi tiết cho ngày này...">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">
                            <i class="fas fa-map-marker-alt"></i> Địa điểm
                        </label>
                        <input type="text" name="location" class="form-control @error('location') is-invalid @enderror" 
                               value="{{ old('location') }}" placeholder="Thành phố / điểm đến">
                        @error('location')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">
                            <i class="fas fa-clock"></i> Giờ khởi hành
                        </label>
                        <input type="time" name="start_time" class="form-control @error('start_time') is-invalid @enderror" 
                               value="{{ old('start_time') }}">
                        @error('start_time')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Điều phối xe & HDV</small>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">
                            <i class="fas fa-calendar-check"></i> Lịch khởi hành (tùy chọn)
                        </label>
                        <select name="departure_id" class="form-select @error('departure_id') is-invalid @enderror" id="departure_id">
                            <option value="">-- Không chọn --</option>
                            @foreach($departures as $departure)
                                <option value="{{ $departure->id }}" {{ old('departure_id') == $departure->id ? 'selected' : '' }}>
                                    #{{ $departure->id }} - {{ \Carbon\Carbon::parse($departure->departure_date)->format('d/m/Y') }}
                                </option>
                            @endforeach
                        </select>
                        @error('departure_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Nếu chọn, có thể chỉnh Giờ khởi hành và HDV phụ trách cho lịch khởi hành này</small>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">
                            <i class="fas fa-user-tie"></i> Hướng dẫn viên phụ trách
                        </label>
                        <select name="guide_id" class="form-select @error('guide_id') is-invalid @enderror" id="guide_id">
                            <option value="">-- Chọn HDV --</option>
                            @foreach($guides as $guide)
                                <option value="{{ $guide->id }}" {{ old('guide_id') == $guide->id ? 'selected' : '' }}>
                                    {{ $guide->name }}
                                    @if($guide->phone)
                                        - {{ $guide->phone }}
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        @error('guide_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Ai chịu trách nhiệm</small>
                    </div>
                </div>

                <div id="departure-note" class="alert alert-info" style="display: none;">
                    <i class="fas fa-info-circle"></i> 
                    <strong>Áp dụng cho lịch khởi hành #<span id="departure-id-display"></span></strong>
                    <br>
                    <small>Bạn có thể chỉnh Giờ khởi hành và HDV phụ trách cho lịch khởi hành này</small>
                </div>

                <div class="mt-4 d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.schedules.index', $tour->id) }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Hủy
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Lưu lịch trình
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('departure_id').addEventListener('change', function() {
        const noteDiv = document.getElementById('departure-note');
        const departureIdDisplay = document.getElementById('departure-id-display');
        
        if (this.value) {
            departureIdDisplay.textContent = this.value;
            noteDiv.style.display = 'block';
        } else {
            noteDiv.style.display = 'none';
        }
    });
    
    // Trigger để hiển thị note nếu đã chọn departure
    document.getElementById('departure_id').dispatchEvent(new Event('change'));
</script>
@endpush
@endsection

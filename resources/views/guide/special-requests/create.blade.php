@extends('layouts.app')

@section('title', 'Thêm yêu cầu đặc biệt')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('guide.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('guide.special-requests.index', $departure->id) }}">Yêu cầu đặc biệt</a></li>
                    <li class="breadcrumb-item active">Thêm mới</li>
                </ol>
            </nav>
            <h2 class="fw-bold text-primary">
                <i class="fas fa-plus me-2"></i>Thêm yêu cầu đặc biệt
            </h2>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="{{ route('guide.special-requests.store', $departure->id) }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="booking_id" class="form-label">Khách hàng <span class="text-danger">*</span></label>
                            <select class="form-select @error('booking_id') is-invalid @enderror" id="booking_id" name="booking_id" required>
                                <option value="">Chọn khách hàng</option>
                                @foreach($departure->bookings as $booking)
                                    <option value="{{ $booking->id }}" {{ old('booking_id') == $booking->id ? 'selected' : '' }}>
                                        {{ $booking->user->name }} ({{ $booking->adults + $booking->children + $booking->infants }} người)
                                    </option>
                                @endforeach
                            </select>
                            @error('booking_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="request_type" class="form-label">Loại yêu cầu <span class="text-danger">*</span></label>
                            <select class="form-select @error('request_type') is-invalid @enderror" id="request_type" name="request_type" required>
                                <option value="dietary" {{ old('request_type') == 'dietary' ? 'selected' : '' }}>Ăn uống (ăn chay, dị ứng...)</option>
                                <option value="medical" {{ old('request_type') == 'medical' ? 'selected' : '' }}>Y tế (bệnh lý, thuốc...)</option>
                                <option value="accessibility" {{ old('request_type') == 'accessibility' ? 'selected' : '' }}>Tiếp cận (khuyết tật, hỗ trợ...)</option>
                                <option value="other" {{ old('request_type') == 'other' ? 'selected' : '' }}>Khác</option>
                            </select>
                            @error('request_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="title" class="form-label">Tiêu đề <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                id="title" name="title" value="{{ old('title') }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Mô tả chi tiết <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                id="description" name="description" rows="6" required>{{ old('description') }}</textarea>
                            <small class="form-text text-muted">Tối thiểu 10 ký tự</small>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Trạng thái</label>
                            <select class="form-select" id="status" name="status">
                                <option value="pending" {{ old('status', 'pending') == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                                <option value="acknowledged" {{ old('status') == 'acknowledged' ? 'selected' : '' }}>Đã xác nhận</option>
                                <option value="fulfilled" {{ old('status') == 'fulfilled' ? 'selected' : '' }}>Đã thực hiện</option>
                            </select>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('guide.special-requests.index', $departure->id) }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Hủy
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Lưu yêu cầu
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


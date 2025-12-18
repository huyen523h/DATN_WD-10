@extends('layouts.app')

@section('title', 'Chi tiết yêu cầu đặc biệt')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('guide.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('guide.special-requests.index', $departure->id) }}">Yêu cầu đặc biệt</a></li>
                    <li class="breadcrumb-item active">Chi tiết</li>
                </ol>
            </nav>
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="fw-bold text-primary">
                    <i class="fas fa-clipboard-list me-2"></i>Chi tiết yêu cầu đặc biệt
                </h2>
                <a href="{{ route('guide.special-requests.index', $departure->id) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Quay lại
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">{{ $specialRequest->title }}</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Loại yêu cầu:</strong>
                        @if($specialRequest->request_type == 'dietary')
                            <span class="badge bg-info">Ăn uống</span>
                        @elseif($specialRequest->request_type == 'medical')
                            <span class="badge bg-danger">Y tế</span>
                        @elseif($specialRequest->request_type == 'accessibility')
                            <span class="badge bg-warning">Tiếp cận</span>
                        @else
                            <span class="badge bg-secondary">Khác</span>
                        @endif
                    </div>

                    <div class="mb-3">
                        <strong>Khách hàng:</strong>
                        <p>{{ $specialRequest->booking->user->name }}<br>
                        <small class="text-muted">{{ $specialRequest->booking->user->phone }} | {{ $specialRequest->booking->user->email }}</small></p>
                    </div>

                    <div class="mb-3">
                        <strong>Mô tả:</strong>
                        <p class="text-justify">{{ $specialRequest->description }}</p>
                    </div>

                    <div class="mb-3">
                        <strong>Trạng thái:</strong>
                        @if($specialRequest->status == 'pending')
                            <span class="badge bg-warning">Chờ xử lý</span>
                        @elseif($specialRequest->status == 'acknowledged')
                            <span class="badge bg-info">Đã xác nhận</span>
                        @elseif($specialRequest->status == 'fulfilled')
                            <span class="badge bg-success">Đã thực hiện</span>
                        @else
                            <span class="badge bg-secondary">Đã hủy</span>
                        @endif
                    </div>

                    @if($specialRequest->notes)
                        <div class="mb-3">
                            <strong>Ghi chú của HDV:</strong>
                            <p class="text-justify">{{ $specialRequest->notes }}</p>
                        </div>
                    @endif

                    <hr>

                    <form action="{{ route('guide.special-requests.update', [$departure->id, $specialRequest->id]) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="status" class="form-label">Cập nhật trạng thái</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="pending" {{ $specialRequest->status == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                                <option value="acknowledged" {{ $specialRequest->status == 'acknowledged' ? 'selected' : '' }}>Đã xác nhận</option>
                                <option value="fulfilled" {{ $specialRequest->status == 'fulfilled' ? 'selected' : '' }}>Đã thực hiện</option>
                                <option value="cancelled" {{ $specialRequest->status == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Ghi chú</label>
                            <textarea class="form-control" id="notes" name="notes" rows="4" 
                                placeholder="Ghi chú về việc xử lý yêu cầu...">{{ old('notes', $specialRequest->notes) }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Cập nhật
                        </button>
                    </form>
                </div>
                <div class="card-footer bg-transparent">
                    <small class="text-muted">
                        <i class="fas fa-clock me-1"></i>Tạo lúc: {{ $specialRequest->created_at->format('d/m/Y H:i') }}
                        @if($specialRequest->updated_at != $specialRequest->created_at)
                            <br><i class="fas fa-edit me-1"></i>Cập nhật lúc: {{ $specialRequest->updated_at->format('d/m/Y H:i') }}
                        @endif
                    </small>
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


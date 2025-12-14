@extends('layouts.app')

@section('title', 'Chi tiết nhật ký')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('guide.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('guide.tour-logs.index', $departure->id) }}">Nhật ký tour</a></li>
                    <li class="breadcrumb-item active">Chi tiết</li>
                </ol>
            </nav>
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="fw-bold text-primary">
                    <i class="fas fa-book-open me-2"></i>Chi tiết nhật ký
                </h2>
                <div>
                    <a href="{{ route('guide.tour-logs.edit', [$departure->id, $log->id]) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-2"></i>Sửa
                    </a>
                    <a href="{{ route('guide.tour-logs.index', $departure->id) }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Quay lại
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <span class="badge 
                            @if($log->type == 'note') bg-info
                            @elseif($log->type == 'incident') bg-danger
                            @elseif($log->type == 'feedback') bg-warning
                            @else bg-secondary
                            @endif">
                            @if($log->type == 'note') Ghi chú
                            @elseif($log->type == 'incident') Sự cố
                            @elseif($log->type == 'feedback') Phản hồi
                            @else Khác
                            @endif
                        </span>
                    </div>
                    <small class="text-muted">
                        <i class="fas fa-calendar me-1"></i>{{ $log->log_date->format('d/m/Y') }}
                    </small>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h5>Nội dung</h5>
                        <p class="text-justify">{{ $log->content }}</p>
                    </div>

                    @if($log->images && count($log->images) > 0)
                        <div class="mb-3">
                            <h5>Hình ảnh</h5>
                            <div class="row">
                                @foreach($log->images as $image)
                                    <div class="col-md-4 mb-3">
                                        <img src="{{ asset('storage/' . $image) }}" alt="Tour log image" 
                                            class="img-fluid rounded shadow-sm" style="max-height: 200px; object-fit: cover; width: 100%;">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
                <div class="card-footer bg-transparent">
                    <small class="text-muted">
                        <i class="fas fa-clock me-1"></i>Tạo lúc: {{ $log->created_at->format('d/m/Y H:i') }}
                        @if($log->updated_at != $log->created_at)
                            <br><i class="fas fa-edit me-1"></i>Cập nhật lúc: {{ $log->updated_at->format('d/m/Y H:i') }}
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
                    <p><strong>Điểm hẹn:</strong> {{ $departure->meeting_point ?? 'Chưa có' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


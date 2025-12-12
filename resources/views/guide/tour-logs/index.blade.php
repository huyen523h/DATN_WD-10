@extends('layouts.app')

@section('title', 'Nhật ký tour')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('guide.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('guide.departures.show', $departure->id) }}">{{ $departure->tour->title }}</a></li>
                    <li class="breadcrumb-item active">Nhật ký tour</li>
                </ol>
            </nav>
            <h2 class="fw-bold text-primary">
                <i class="fas fa-book me-2"></i>Nhật ký tour: {{ $departure->tour->title }}
            </h2>
            <p class="text-muted">
                Ngày khởi hành: <strong>{{ $departure->departure_date->format('d/m/Y') }}</strong>
            </p>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-12">
            <a href="{{ route('guide.tour-logs.create', $departure->id) }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Thêm nhật ký mới
            </a>
            <a href="{{ route('guide.departures.show', $departure->id) }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Quay lại
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($logs->count() > 0)
        <div class="row">
            @foreach($logs as $log)
                <div class="col-md-6 mb-4">
                    <div class="card h-100 shadow-sm">
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
                            <p class="card-text">{{ Str::limit($log->content, 150) }}</p>
                            @if($log->images && count($log->images) > 0)
                                <div class="mb-2">
                                    <small class="text-muted">
                                        <i class="fas fa-images me-1"></i>{{ count($log->images) }} ảnh
                                    </small>
                                </div>
                            @endif
                        </div>
                        <div class="card-footer bg-transparent">
                            <a href="{{ route('guide.tour-logs.show', [$departure->id, $log->id]) }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-eye"></i> Xem chi tiết
                            </a>
                            <a href="{{ route('guide.tour-logs.edit', [$departure->id, $log->id]) }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i> Sửa
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <i class="fas fa-book-open fa-4x text-muted mb-3"></i>
                <p class="text-muted">Chưa có nhật ký nào. Hãy bắt đầu ghi chú!</p>
                <a href="{{ route('guide.tour-logs.create', $departure->id) }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Thêm nhật ký đầu tiên
                </a>
            </div>
        </div>
    @endif
</div>
@endsection


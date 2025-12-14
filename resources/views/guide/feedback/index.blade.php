@extends('layouts.app')

@section('title', 'Phản hồi đánh giá')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('guide.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Phản hồi đánh giá</li>
                </ol>
            </nav>
            <h2 class="fw-bold text-primary">
                <i class="fas fa-comment-dots me-2"></i>Phản hồi đánh giá
            </h2>
            <p class="text-muted">Gửi phản hồi về tour, dịch vụ và nhà cung cấp</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row mb-3">
        <div class="col-12">
            <a href="{{ route('guide.dashboard') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Quay lại Dashboard
            </a>
        </div>
    </div>

    @if($feedbacks->count() > 0)
        <div class="row">
            @foreach($feedbacks as $feedback)
                <div class="col-md-6 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div>
                                <span class="badge 
                                    @if($feedback->feedback_type == 'tour') bg-primary
                                    @elseif($feedback->feedback_type == 'service') bg-info
                                    @elseif($feedback->feedback_type == 'supplier') bg-warning
                                    @else bg-secondary
                                    @endif">
                                    @if($feedback->feedback_type == 'tour') Tour
                                    @elseif($feedback->feedback_type == 'service') Dịch vụ
                                    @elseif($feedback->feedback_type == 'supplier') Nhà cung cấp
                                    @else Khác
                                    @endif
                                </span>
                                @if($feedback->rating)
                                    <span class="ms-2">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $feedback->rating)
                                                <i class="fas fa-star text-warning"></i>
                                            @else
                                                <i class="far fa-star text-muted"></i>
                                            @endif
                                        @endfor
                                    </span>
                                @endif
                            </div>
                            <span class="badge 
                                @if($feedback->status == 'pending') bg-warning
                                @elseif($feedback->status == 'reviewed') bg-info
                                @elseif($feedback->status == 'resolved') bg-success
                                @else bg-secondary
                                @endif">
                                @if($feedback->status == 'pending') Chờ xử lý
                                @elseif($feedback->status == 'reviewed') Đã xem
                                @elseif($feedback->status == 'resolved') Đã giải quyết
                                @else Đã bỏ qua
                                @endif
                            </span>
                        </div>
                        <div class="card-body">
                            <h6 class="card-title">{{ $feedback->subject }}</h6>
                            <p class="card-text">{{ Str::limit($feedback->content, 100) }}</p>
                            @if($feedback->departure)
                                <small class="text-muted">
                                    <i class="fas fa-calendar me-1"></i>
                                    {{ $feedback->departure->tour->title }} - {{ $feedback->departure->departure_date->format('d/m/Y') }}
                                </small>
                            @endif
                        </div>
                        <div class="card-footer bg-transparent">
                            <a href="{{ route('guide.feedback.show', $feedback->id) }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-eye"></i> Xem chi tiết
                            </a>
                            @if($feedback->status == 'pending')
                                <a href="{{ route('guide.feedback.edit', $feedback->id) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i> Sửa
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $feedbacks->links() }}
        </div>
    @else
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <i class="fas fa-comment-dots fa-4x text-muted mb-3"></i>
                <p class="text-muted">Bạn chưa gửi phản hồi nào.</p>
                <p class="text-muted">Hãy chọn một tour từ dashboard để gửi phản hồi.</p>
            </div>
        </div>
    @endif
</div>
@endsection


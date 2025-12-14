@extends('layouts.app')

@section('title', 'Chi tiết phản hồi')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('guide.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('guide.feedback.index') }}">Phản hồi đánh giá</a></li>
                    <li class="breadcrumb-item active">Chi tiết</li>
                </ol>
            </nav>
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="fw-bold text-primary">
                    <i class="fas fa-comment-dots me-2"></i>Chi tiết phản hồi
                </h2>
                <div>
                    @if($feedback->status == 'pending')
                        <a href="{{ route('guide.feedback.edit', $feedback->id) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-2"></i>Sửa
                        </a>
                    @endif
                    <a href="{{ route('guide.feedback.index') }}" class="btn btn-secondary">
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
                    <h5 class="card-title">{{ $feedback->subject }}</h5>
                    
                    @if($feedback->supplier_name)
                        <div class="mb-3">
                            <strong>Nhà cung cấp:</strong> {{ $feedback->supplier_name }}
                        </div>
                    @endif

                    <div class="mb-3">
                        <strong>Nội dung:</strong>
                        <p class="text-justify">{{ $feedback->content }}</p>
                    </div>

                    @if($feedback->suggestions)
                        <div class="mb-3">
                            <strong>Đề xuất cải thiện:</strong>
                            <p class="text-justify">{{ $feedback->suggestions }}</p>
                        </div>
                    @endif

                    @if($feedback->images && count($feedback->images) > 0)
                        <div class="mb-3">
                            <strong>Hình ảnh:</strong>
                            <div class="row mt-2">
                                @foreach($feedback->images as $image)
                                    <div class="col-md-4 mb-3">
                                        <img src="{{ asset('storage/' . $image) }}" alt="Feedback image" 
                                            class="img-fluid rounded shadow-sm" style="max-height: 200px; object-fit: cover; width: 100%;">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if($feedback->admin_response)
                        <div class="alert alert-info mt-3">
                            <strong>Phản hồi từ Admin:</strong>
                            <p class="mb-0 mt-2">{{ $feedback->admin_response }}</p>
                            @if($feedback->reviewedBy)
                                <small class="text-muted">
                                    - {{ $feedback->reviewedBy->name }} 
                                    @if($feedback->reviewed_at)
                                        ({{ $feedback->reviewed_at->format('d/m/Y H:i') }})
                                    @endif
                                </small>
                            @endif
                        </div>
                    @endif
                </div>
                <div class="card-footer bg-transparent">
                    <small class="text-muted">
                        <i class="fas fa-clock me-1"></i>Gửi lúc: {{ $feedback->created_at->format('d/m/Y H:i') }}
                        @if($feedback->updated_at != $feedback->created_at)
                            <br><i class="fas fa-edit me-1"></i>Cập nhật lúc: {{ $feedback->updated_at->format('d/m/Y H:i') }}
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
                    @if($feedback->departure)
                        <p><strong>Tour:</strong> {{ $feedback->departure->tour->title }}</p>
                        <p><strong>Ngày khởi hành:</strong> {{ $feedback->departure->departure_date->format('d/m/Y') }}</p>
                    @else
                        <p class="text-muted">Không có thông tin tour</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@extends('layouts.admin')

@section('title', 'Chi tiết nhật ký')

@section('content')
<div class="container">

    <div class="card shadow-sm p-4">

        <h3 class="fw-bold mb-3">
            <i class="fas fa-book text-primary me-2"></i>
            Nhật ký tour
        </h3>

        <p><strong>Tour:</strong> {{ $log->departure->tour->title ?? 'N/A' }}</p>

        <p><strong>Chuyến khởi hành:</strong>
            {{ $log->departure->departure_date?->format('d/m/Y') }}
        </p>

        <p><strong>Ngày ghi:</strong> {{ $log->log_date->format('d/m/Y') }}</p>

        <p><strong>Người ghi:</strong> {{ $log->guide->name ?? 'Hệ thống' }}</p>

        <p><strong>Loại:</strong>
            <span class="badge bg-info">{{ strtoupper($log->type) }}</span>
        </p>

        <p><strong>Nội dung:</strong></p>
        <div class="border p-3 rounded bg-light">
            {!! nl2br(e($log->content)) !!}
        </div>

        @if ($log->images)
        <hr>
        <h5 class="fw-bold">Ảnh đính kèm:</h5>

        <div class="row">
            @foreach ($log->images as $img)
            <div class="col-md-3 mb-3">
                <img src="{{ asset('storage/' . $img) }}" class="img-fluid rounded shadow-sm">
            </div>
            @endforeach
        </div>
        @endif

    </div>
</div>
@endsection

@extends('layouts.admin')

@section('title', 'Chỉnh sửa hướng dẫn viên')

@section('content')
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <div>
            <a href="{{ route('admin.guides.index') }}" class="text-decoration-none text-muted">
                <i class="fas fa-arrow-left me-1"></i> Quay lại danh sách
            </a>
            <h1 class="h3 mt-2">Chỉnh sửa: {{ $guide->full_name }}</h1>
        </div>
        <a href="{{ route('admin.guides.show', $guide) }}" class="btn btn-outline-secondary">
            <i class="fas fa-eye me-1"></i> Xem chi tiết
        </a>
    </div>

    @include('admin.guides._form', [
        'formAction' => route('admin.guides.update', $guide),
        'guide' => $guide,
        'categories' => $categories,
    ])
@endsection

@section('scripts')
    @include('admin.guides.scripts')
@endsection


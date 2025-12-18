@extends('layouts.admin')

@section('title', 'Thêm hướng dẫn viên')

@section('content')
    <div class="mb-4">
        <a href="{{ route('admin.guides.index') }}" class="text-decoration-none text-muted">
            <i class="fas fa-arrow-left me-1"></i> Quay lại danh sách
        </a>
        <h1 class="h3 mt-2">Thêm hướng dẫn viên</h1>
    </div>

    @include('admin.guides._form', [
        'formAction' => route('admin.guides.store'),
        'guide' => $guide,
        'categories' => $categories,
        'guideUsers' => $guideUsers ?? collect(),
    ])
@endsection

@section('scripts')
    @include('admin.guides.scripts')
@endsection


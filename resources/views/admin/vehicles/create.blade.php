@extends('layouts.admin')

@section('title', 'Thêm xe du lịch')

@section('content')
    <div class="mb-4">
        <a href="{{ route('admin.vehicles.index') }}" class="text-decoration-none text-muted">
            <i class="fas fa-arrow-left me-1"></i> Quay lại danh sách
        </a>
        <h1 class="h3 mt-2">Thêm xe du lịch</h1>
    </div>

    @include('admin.vehicles._form', [
        'formAction' => route('admin.vehicles.store'),
        'vehicle' => $vehicle,
    ])
@endsection



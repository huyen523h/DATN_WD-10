@extends('layouts.admin')

@section('title', 'Chỉnh sửa xe du lịch')

@section('content')
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <div>
            <a href="{{ route('admin.vehicles.index') }}" class="text-decoration-none text-muted">
                <i class="fas fa-arrow-left me-1"></i> Quay lại danh sách
            </a>
            <h1 class="h3 mt-2">Chỉnh sửa: {{ $vehicle->name }}</h1>
        </div>
    </div>

    @include('admin.vehicles._form', [
        'formAction' => route('admin.vehicles.update', $vehicle),
        'vehicle' => $vehicle,
    ])
@endsection



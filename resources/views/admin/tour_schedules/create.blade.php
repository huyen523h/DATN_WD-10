@extends('layouts.admin')

@section('title', 'Thêm lịch trình tour')

@section('content')
<div class="container mt-4">
    <h3>Thêm lịch trình cho tour: {{ $tour->title }}</h3>

    <form action="{{ route('admin.schedules.store', $tour->id) }}" method="POST" class="mt-3">
        @csrf

        <div class="mb-3">
            <label class="form-label fw-bold">Ngày thứ mấy <span class="text-danger">*</span></label>
            <input type="number" name="day_number" class="form-control" value="{{ old('day_number') }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Tiêu đề <span class="text-danger">*</span></label>
            <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Mô tả chi tiết <span class="text-danger">*</span></label>
            <textarea name="description" class="form-control" rows="6" required>{{ old('description') }}</textarea>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-success">Lưu lịch trình</button>
            <a href="{{ route('admin.schedules.index', $tour->id) }}" class="btn btn-secondary">Quay lại</a>
        </div>
    </form>
</div>
@endsection

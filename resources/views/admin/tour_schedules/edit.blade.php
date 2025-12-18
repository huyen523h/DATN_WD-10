@extends('layouts.admin')

@section('title', 'Sửa lịch trình tour')

@section('content')
<div class="container mt-4">
    <h3>Sửa lịch trình: Ngày {{ $schedule->day_number }} – {{ $schedule->title }}</h3>

    <form action="{{ route('admin.schedules.update', $schedule->id) }}" method="POST" class="mt-3">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label fw-bold">Ngày thứ mấy <span class="text-danger">*</span></label>
            <input type="number" name="day_number" class="form-control" 
                   value="{{ old('day_number', $schedule->day_number) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Tiêu đề <span class="text-danger">*</span></label>
            <input type="text" name="title" class="form-control" 
                   value="{{ old('title', $schedule->title) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Mô tả chi tiết <span class="text-danger">*</span></label>
            <textarea name="description" class="form-control" rows="6" required>{{ old('description', $schedule->description) }}</textarea>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-success">Cập nhật</button>
            <a href="{{ route('admin.schedules.index', $schedule->tour_id) }}" class="btn btn-secondary">Quay lại</a>
        </div>
    </form>
</div>
@endsection

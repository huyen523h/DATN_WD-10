@extends('layouts.admin')

@section('title', 'Sửa nhật ký')

@section('content')
<div class="container">

    <h2 class="fw-bold mb-4">
        <i class="fas fa-edit text-warning me-2"></i>
        Sửa nhật ký tour
    </h2>

    <div class="card shadow-sm p-4">
        <form method="POST" action="{{ route('admin.tour-logs.update', $log->id) }}">
            @csrf @method('PUT')

            <div class="mb-3">
                <label class="fw-semibold">Chuyến khởi hành</label>
                <select name="departure_id" class="form-select" required>
                    @foreach ($departures as $d)
                        <option value="{{ $d->id }}" 
                            {{ $log->departure_id == $d->id ? 'selected' : '' }}>
                            {{ $d->tour->title }} — {{ $d->departure_date?->format('d/m/Y') }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="fw-semibold">Ngày ghi</label>
                <input type="date" name="log_date" class="form-control"
                    value="{{ $log->log_date->format('Y-m-d') }}">
            </div>

            <div class="mb-3">
                <label class="fw-semibold">Loại nhật ký</label>
                <select name="type" class="form-select">
                    <option value="note" {{ $log->type == 'note' ? 'selected' : '' }}>Ghi chú</option>
                    <option value="incident" {{ $log->type == 'incident' ? 'selected' : '' }}>Sự cố</option>
                    <option value="expense" {{ $log->type == 'expense' ? 'selected' : '' }}>Chi phí</option>
                    <option value="feedback" {{ $log->type == 'feedback' ? 'selected' : '' }}>Phản hồi</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="fw-semibold">Nội dung</label>
                <textarea name="content" class="form-control" rows="4">{{ $log->content }}</textarea>
            </div>

            <button class="btn btn-primary">Cập nhật</button>

            <a href="{{ route('admin.tour-logs.index') }}" class="btn btn-secondary">Hủy</a>
        </form>
    </div>

</div>
@endsection

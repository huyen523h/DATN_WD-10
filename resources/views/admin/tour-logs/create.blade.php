@extends('layouts.admin')

@section('title', 'Thêm nhật ký')

@section('content')
<div class="container">

    <h2 class="fw-bold mb-4">
        <i class="fas fa-plus text-primary me-2"></i>
        Thêm nhật ký tour
    </h2>

    <div class="card shadow-sm p-4">
        <form method="POST" action="{{ route('admin.tour-logs.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label class="fw-semibold">Chuyến khởi hành</label>
                <select name="departure_id" class="form-select" required>
                    <option value="">— Chọn chuyến —</option>
                    @foreach ($departures as $d)
                        <option value="{{ $d->id }}">
                            {{ $d->tour->title }} — {{ $d->departure_date?->format('d/m/Y') }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="fw-semibold">Ngày ghi</label>
                <input type="date" name="log_date" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="fw-semibold">Loại nhật ký</label>
                <select name="type" class="form-select">
                    <option value="note">Ghi chú</option>
                    <option value="incident">Sự cố</option>
                    <option value="expense">Chi phí</option>
                    <option value="feedback">Phản hồi</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="fw-semibold">Nội dung</label>
                <textarea name="content" class="form-control" rows="4" required></textarea>
            </div>

            <div class="mb-3">
                <label class="fw-semibold">Ảnh đính kèm</label>
                <input type="file" name="images[]" class="form-control" multiple>
            </div>

            <button class="btn btn-primary">
                <i class="fas fa-save me-1"></i> Lưu
            </button>

            <a href="{{ route('admin.tour-logs.index') }}" class="btn btn-secondary">Hủy</a>
        </form>
    </div>
</div>
@endsection

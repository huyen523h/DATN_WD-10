@extends('layouts.admin')
@section('title', 'Thêm ngày khởi hành')

@section('content')
<div class="container-fluid">
    <h4 class="mb-4">Thêm ngày khởi hành mới</h4>

    <form action="{{ route('admin.tour_departures.store') }}" method="POST">
        @csrf
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="tour_id" class="form-label">Tour</label>
                <select name="tour_id" class="form-select" required>
                    <option value="">-- Chọn tour --</option>
                    @foreach($tours as $tour)
                        <option value="{{ $tour->id }}" {{ old('tour_id') == $tour->id ? 'selected' : '' }}>
                            {{ $tour->title }}
                        </option>
                    @endforeach
                </select>
                @error('tour_id') <div class="text-danger">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
                <label for="departure_date" class="form-label">Ngày khởi hành</label>
                <input type="date" name="departure_date" class="form-control" value="{{ old('departure_date') }}" required>
                @error('departure_date') <div class="text-danger">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-4">
                <label class="form-label">Tổng chỗ</label>
                <input type="number" name="seats_total" class="form-control" value="{{ old('seats_total') }}" min="1" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Còn lại</label>
                <input type="number" name="seats_available" class="form-control" value="{{ old('seats_available') }}" min="0" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Trạng thái</label>
                <select name="status" class="form-select" required>
                    <option value="available">Còn chỗ</option>
                    <option value="contact">Liên hệ</option>
                    <option value="sold_out">Hết chỗ</option>
                </select>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-4">
                <label class="form-label">Giá người lớn</label>
                <input type="number" name="price" class="form-control" value="{{ old('price') }}" step="0.01" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Giá trẻ em</label>
                <input type="number" name="child_price" class="form-control" value="{{ old('child_price') }}" step="0.01">
            </div>
            <div class="col-md-4">
                <label class="form-label">Giá trẻ nhỏ</label>
                <input type="number" name="infant_price" class="form-control" value="{{ old('infant_price') }}" step="0.01">
            </div>
        </div>

        <button class="btn btn-primary"><i class="fas fa-save"></i> Lưu lại</button>
        <a href="{{ route('admin.tour_departures.index') }}" class="btn btn-secondary">Hủy</a>
    </form>
</div>
@endsection

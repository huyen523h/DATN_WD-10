@extends('admin.layout')


@section('content')
    <h1>Sửa Tour: {{ $tour->title }}</h1>
    <form action="{{ route('admin.tours.update', $tour) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label>Tên Tour</label>
            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $tour->title) }}" required>
            @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="form-group">
            <label>Địa Điểm</label>
            <input type="text" name="location" class="form-control @error('location') is-invalid @enderror" value="{{ old('location', $tour->location) }}">
            @error('location') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="form-group">
            <label>Giá</label>
            <input type="number" name="price" step="0.01" class="form-control @error('price') is-invalid @enderror" value="{{ old('price', $tour->price) }}" required>
            @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="form-group">
            <label>Lịch Trình</label>
            <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="5">{{ old('description', $tour->description) }}</textarea>
            @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="form-group">
            <label>Ngày Khởi Hành</label>
            <input type="date" name="departure_date" class="form-control @error('departure_date') is-invalid @enderror" value="{{ old('departure_date', $tour->departure_date) }}">
            @error('departure_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="form-group">
            <label>Hình Ảnh Hiện Tại</label>
            @if ($tour->image)
                <img src="{{ asset('storage/' . $tour->image) }}" alt="Image" style="width: 100px;">
            @else
                Không có
            @endif
            <input type="file" name="image" class="form-control @error('image') is-invalid @enderror">
            @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <button type="submit" class="btn btn-success mt-3">Cập Nhật Tour</button>
    </form>
@endsection
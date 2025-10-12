@extends('admin.layout')


@section('content')
    <h1>Chi Tiết Tour: {{ $tour->title }}</h1>
    <p><strong>Địa Điểm:</strong> {{ $tour->location ?? 'Chưa có' }}</p>
    <p><strong>Giá:</strong> {{ number_format($tour->price, 2) }}</p>
    <p><strong>Lịch Trình:</strong> {{ $tour->description ?? 'Chưa có' }}</p>
    <p><strong>Ngày Khởi Hành:</strong> {{ $tour->departure_date ? $tour->departure_date->format('d/m/Y') : 'Chưa có' }}</p>
    <p><strong>Hình Ảnh:</strong>
        @if ($tour->image)
            <img src="{{ asset('storage/' . $tour->image) }}" alt="Image" style="width: 200px;">
        @else
            Không có
        @endif
    </p>
    <a href="{{ route('admin.tours.index') }}" class="btn btn-secondary">Quay Lại Danh Sách</a>
@endsection
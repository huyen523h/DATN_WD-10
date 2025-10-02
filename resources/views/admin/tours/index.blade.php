@extends('admin.layout')
 <!-- Thay bằng layout của bạn nếu khác, ví dụ layouts.app -->

@section('content')
    <h1>Quản Lý Tours</h1>
    <a href="{{ route('admin.tours.create') }}" class="btn btn-primary mb-3">Thêm Tour Mới</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên</th>
                <th>Địa Điểm</th>
                <th>Giá</th>
                <th>Lịch Trình</th>
                <th>Ngày Khởi Hành</th>
                <th>Hình Ảnh</th>
                <th>Hành Động</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($tours as $tour)
                <tr>
                    <td>{{ $tour->id }}</td>
                    <td>{{ $tour->title }}</td>
                    <td>{{ $tour->location }}</td>
                    <td>{{ number_format($tour->price, 2) }}</td>
                    <td>{{ Str::limit($tour->description, 50) }}</td>
                    <td>{{ $tour->departure_date ? $tour->departure_date->format('d/m/Y') : 'Chưa có' }}</td>
                    <td>
                        @if ($tour->image)
                            <img src="{{ asset('storage/' . $tour->image) }}" alt="Image" style="width: 100px;">
                        @else
                            Không có
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.tours.show', $tour) }}" class="btn btn-info btn-sm">Xem</a>
                        <a href="{{ route('admin.tours.edit', $tour) }}" class="btn btn-warning btn-sm">Sửa</a>
                        <form action="{{ route('admin.tours.destroy', $tour) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Xác nhận xóa?')">Xóa</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="8">Chưa có tour nào.</td></tr>
            @endforelse
        </tbody>
    </table>
@endsection
@extends('admin.layout')

@section('content')
    <h1>Quản Lý Tours</h1>
    <a href="{{ route('admin.tours.create') }}" class="btn btn-primary mb-3">Thêm Tour Mới</a>
    <table class="table table-bordered align-middle">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên tour</th>
                <th>Danh mục</th>
                <th>Giá</th>
                <th>Giá gốc</th>
                <th>Giá KM</th>
                <th>Trạng thái</th>
                <th>Chỗ tổng</th>
                <th>Hiển thị</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($tours as $tour)
                <tr>
                    <td>{{ $tour->id }}</td>
                    <td class="text-wrap">{{ $tour->title }}</td>
                    <td>{{ optional($tour->category)->name }}</td>
                    <td>{{ number_format($tour->price, 0, ',', '.') }}đ</td>
                    <td>{{ $tour->original_price ? number_format($tour->original_price, 0, ',', '.') . 'đ' : '-' }}</td>
                    <td>{{ $tour->discount_price ? number_format($tour->discount_price, 0, ',', '.') . 'đ' : '-' }}</td>
                    <td><span class="badge bg-secondary">{{ $tour->availability_status ?? 'available' }}</span></td>
                    <td>{{ $tour->available_seats ?? '-' }}</td>
                    <td><span class="badge {{ $tour->status === 'active' ? 'bg-success' : ($tour->status==='inactive'?'bg-warning':'bg-secondary') }}">{{ $tour->status }}</span></td>
                    <td class="d-flex gap-1">
                        <a href="{{ route('admin.tours.edit', $tour) }}" class="btn btn-sm btn-warning">Sửa</a>
                        <form action="{{ route('admin.tours.destroy', $tour) }}" method="POST" onsubmit="return confirm('Xóa tour này?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger">Xóa</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="10">Chưa có tour nào.</td></tr>
            @endforelse
        </tbody>
    </table>
@endsection

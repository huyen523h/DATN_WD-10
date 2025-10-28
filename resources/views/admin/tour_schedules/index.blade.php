@extends('layouts.admin')

@section('title', 'Quản lý lịch trình tour')

@section('content')
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>Lịch trình Tour: {{ $tour->title }}</h3>
            <a href="{{ route('admin.tours.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại danh sách Tour
            </a>
            <a href="{{ route('admin.schedules.create', $tour->id) }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Thêm ngày lịch trình
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th width="80">Ngày</th>
                    <th>Tiêu đề</th>
                    <th>Mô tả</th>
                    <th width="150">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($schedules as $item)
                    <tr>
                        <td class="text-center fw-bold">Ngày {{ $item->day_number }}</td>
                        <td>{{ $item->title }}</td>
                        <td>{{ Str::limit($item->description, 120) }}</td>
                        <td>
                            <a href="{{ route('admin.schedules.edit', $item->id) }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i> Sửa
                            </a>
                            <form action="{{ route('admin.schedules.destroy', $item->id) }}" method="POST"
                                class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('Xóa lịch trình này?')">
                                    <i class="fas fa-trash-alt"></i> Xóa
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted">Chưa có lịch trình nào.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection

@extends('layouts.admin')

@section('title', 'Quản lý tours')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Quản lý tours</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Tours</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('admin.tours.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Thêm tour mới
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Tìm kiếm tour..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="category_id" class="form-control">
                        <option value="">Tất cả danh mục</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-control">
                        <option value="">Tất cả trạng thái</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Hoạt động</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Không hoạt động</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Bản nháp</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search"></i> Tìm kiếm
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tours Table -->
    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tour</th>
                            <th>Danh mục</th>
                            <th>Giá</th>
                            <th>Thời gian</th>
                            <th>Trạng thái</th>
                            <th>Đặt tour</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($tours as $tour)
                            <tr>
                                <td>{{ $tour->id }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($tour->images->count() > 0)
                                            <img src="{{ $tour->images->first()->image_url }}" alt="{{ $tour->title }}" class="rounded me-2" style="width: 50px; height: 50px; object-fit: cover;">
                                        @endif
                                        <div>
                                            <strong>{{ $tour->title }}</strong>
                                            <br>
                                            <small class="text-muted">{{ Str::limit($tour->description, 50) }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $tour->category->name }}</td>
                                <td>{{ number_format($tour->price) }} VNĐ</td>
                                <td>{{ $tour->duration_days }} ngày</td>
                                <td>
                                    <span class="badge badge-{{ $tour->status === 'active' ? 'success' : ($tour->status === 'inactive' ? 'secondary' : 'warning') }}">
                                        {{ $tour->status === 'active' ? 'Hoạt động' : ($tour->status === 'inactive' ? 'Không hoạt động' : 'Bản nháp') }}
                                    </span>
                                </td>
                                <td>{{ $tour->bookings->count() }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.tours.show', $tour) }}" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.tours.edit', $tour) }}" class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.tours.destroy', $tour) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa tour này?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">Chưa có tour nào</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($tours->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $tours->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
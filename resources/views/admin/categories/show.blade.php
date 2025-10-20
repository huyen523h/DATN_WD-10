@extends('layouts.admin')

@section('title', 'Chi tiết danh mục')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Chi tiết danh mục</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.categories') }}">Danh mục</a></li>
                    <li class="breadcrumb-item active">{{ $category->name }}</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Chỉnh sửa
            </a>
            <a href="{{ route('admin.categories') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Category Info -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Thông tin danh mục</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="text-primary">{{ $category->name }}</h5>
                            <p class="text-muted">{{ $category->description ?? 'Không có mô tả' }}</p>
                        </div>
                        <div class="col-md-6">
                            <div class="text-right">
                                <span class="badge badge-{{ $category->status === 'active' ? 'success' : 'secondary' }} badge-lg">
                                    {{ $category->status === 'active' ? 'Hoạt động' : 'Không hoạt động' }}
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    @if($category->image_url)
                    <div class="mt-3">
                        <img src="{{ $category->image_url }}" alt="{{ $category->name }}" class="img-fluid rounded" style="max-height: 200px;">
                    </div>
                    @endif
                </div>
            </div>

            <!-- Tours in Category -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Tours trong danh mục ({{ $category->tours->count() }})</h6>
                </div>
                <div class="card-body">
                    @if($category->tours->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Tour</th>
                                        <th>Giá</th>
                                        <th>Trạng thái</th>
                                        <th>Đặt tour</th>
                                        <th>Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($category->tours as $tour)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($tour->images->count() > 0)
                                                    <img src="{{ $tour->images->first()->image_url }}" alt="{{ $tour->title }}" class="rounded me-2" style="width: 40px; height: 40px; object-fit: cover;">
                                                @endif
                                                <div>
                                                    <strong>{{ $tour->title }}</strong>
                                                    <br>
                                                    <small class="text-muted">{{ $tour->duration_days }} ngày</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ number_format($tour->price) }} VNĐ</td>
                                        <td>
                                            <span class="badge badge-{{ $tour->status === 'active' ? 'success' : 'secondary' }}">
                                                {{ $tour->status === 'active' ? 'Hoạt động' : 'Không hoạt động' }}
                                            </span>
                                        </td>
                                        <td>{{ $tour->bookings->count() }}</td>
                                        <td>
                                            <a href="{{ route('admin.tours.show', $tour) }}" class="btn btn-info btn-sm">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Chưa có tour nào trong danh mục này</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Statistics -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Thống kê</h6>
                </div>
                <div class="card-body">
                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="stat-icon stat-icon-primary">
                                <i class="fas fa-map-marked-alt"></i>
                            </div>
                            <div class="stat-value">{{ $category->tours->count() }}</div>
                            <div class="stat-label">Tổng tours</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon stat-icon-success">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="stat-value">{{ $category->tours->where('status', 'active')->count() }}</div>
                            <div class="stat-label">Tours hoạt động</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon stat-icon-info">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <div class="stat-value">{{ $category->tours->sum('bookings_count') }}</div>
                            <div class="stat-label">Tổng đặt tour</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon stat-icon-warning">
                                <i class="fas fa-dollar-sign"></i>
                            </div>
                            <div class="stat-value">{{ number_format($category->tours->avg('price')) }}</div>
                            <div class="stat-label">Giá trung bình (VNĐ)</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Category Actions -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Hành động</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Chỉnh sửa danh mục
                        </a>
                        <a href="{{ route('admin.tours.create') }}?category_id={{ $category->id }}" class="btn btn-success">
                            <i class="fas fa-plus"></i> Thêm tour mới
                        </a>
                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa danh mục này?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="fas fa-trash"></i> Xóa danh mục
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Category Info -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Thông tin chi tiết</h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <td><strong>ID:</strong></td>
                            <td>{{ $category->id }}</td>
                        </tr>
                        <tr>
                            <td><strong>Tạo lúc:</strong></td>
                            <td>{{ $category->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Cập nhật:</strong></td>
                            <td>{{ $category->updated_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

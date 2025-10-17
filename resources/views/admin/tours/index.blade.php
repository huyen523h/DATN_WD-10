@extends('layouts.admin')

@section('title', 'Quản lý Tours - Admin')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item active">Tours</li>
@endsection

@section('content')
<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2><i class="fas fa-map-marked-alt text-primary"></i> Quản lý Tours</h2>
        <p class="text-muted mb-0">Quản lý tất cả các tour du lịch trong hệ thống</p>
    </div>
    <a href="{{ route('admin.tours.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Thêm tour mới
    </a>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-filter"></i> Bộ lọc</h5>
    </div>
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Tìm kiếm</label>
                <input type="text" name="search" class="form-control" placeholder="Tìm kiếm tour..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Danh mục</label>
                <select name="category_id" class="form-select">
                    <option value="">Tất cả danh mục</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Trạng thái</label>
                <select name="status" class="form-select">
                    <option value="">Tất cả trạng thái</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Hoạt động</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Không hoạt động</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Bản nháp</option>
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search"></i> Tìm kiếm
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Tours Table -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-list"></i> Danh sách Tours</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
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
                                        @php $coverImage = $tour->images->where('is_cover', true)->first() ?? $tour->images->first(); @endphp
                                        <img src="{{ Storage::url($coverImage->image_url) }}" alt="{{ $tour->title }}" class="rounded me-2" style="width: 50px; height: 50px; object-fit: cover;" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                        <div class="bg-light rounded me-2 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; display: none;">
                                            <i class="fas fa-image text-muted"></i>
                                        </div>
                                    @else
                                        <div class="bg-light rounded me-2 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                            <i class="fas fa-image text-muted"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <strong>{{ $tour->title }}</strong>
                                        <br>
                                        <small class="text-muted">{{ Str::limit($tour->description, 50) }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-primary">{{ $tour->category->name }}</span>
                            </td>
                            <td>
                                <strong class="text-success">{{ number_format($tour->price) }} VNĐ</strong>
                            </td>
                            <td>{{ $tour->duration_days ?? 'N/A' }} ngày</td>
                            <td>
                                @switch($tour->status)
                                    @case('active')
                                        <span class="badge bg-success">
                                            <i class="fas fa-check-circle"></i> Hoạt động
                                        </span>
                                        @break
                                    @case('inactive')
                                        <span class="badge bg-secondary">
                                            <i class="fas fa-pause-circle"></i> Không hoạt động
                                        </span>
                                        @break
                                    @case('draft')
                                        <span class="badge bg-warning">
                                            <i class="fas fa-edit"></i> Bản nháp
                                        </span>
                                        @break
                                    @default
                                        <span class="badge bg-info">{{ $tour->status }}</span>
                                @endswitch
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $tour->bookings->count() }}</span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.tours.show', $tour) }}" class="btn btn-primary btn-sm" title="Xem chi tiết">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.tours.edit', $tour) }}" class="btn btn-warning btn-sm" title="Chỉnh sửa">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.tours.destroy', $tour) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa tour này?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Xóa">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="fas fa-box-open fa-3x mb-3"></i>
                                    <h5>Chưa có tour nào</h5>
                                    <p>Hãy tạo tour đầu tiên để bắt đầu!</p>
                                    <a href="{{ route('admin.tours.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus"></i> Thêm tour mới
                                    </a>
                                </div>
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
@endsection

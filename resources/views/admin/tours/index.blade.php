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

<!-- Success Message -->
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Tours Table -->
<div class="card">
    <div class="card-body">
        <!-- Filters -->
        <form method="GET" action="{{ route('admin.tours.index') }}" class="mb-4">
            <div class="row g-3">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Tìm kiếm tour..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
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
                    <select name="availability_status" class="form-select">
                        <option value="">Tất cả trạng thái</option>
                        <option value="available" {{ request('availability_status') == 'available' ? 'selected' : '' }}>Còn chỗ</option>
                        <option value="contact" {{ request('availability_status') == 'contact' ? 'selected' : '' }}>Liên hệ</option>
                        <option value="sold_out" {{ request('availability_status') == 'sold_out' ? 'selected' : '' }}>Hết chỗ</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search"></i> Tìm kiếm
                    </button>
                </div>
            </div>
        </form>

        <!-- Table -->
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="5%">ID</th>
                        <th width="30%">Tour</th>
                        <th width="15%">Danh mục</th>
                        <th width="12%">Giá</th>
                        <th width="10%">Thời gian</th>
                        <th width="10%">Trạng thái</th>
                        <th width="8%">Đặt tour</th>
                        <th width="10%" class="text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tours as $tour)
                        <tr>
                            <td>{{ $tour->id }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($tour->images->count() > 0)
                                        <img src="{{ $tour->images->first()->image_url }}" 
                                             alt="{{ $tour->title }}" 
                                             class="rounded me-2" 
                                             style="width: 50px; height: 50px; object-fit: cover;">
                                    @else
                                        <div class="rounded me-2 bg-light d-flex align-items-center justify-content-center" 
                                             style="width: 50px; height: 50px;">
                                            <i class="fas fa-image text-muted"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="fw-bold">{{ $tour->title }}</div>
                                        <small class="text-muted">{{ Str::limit($tour->description, 50) }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $tour->category->name }}</td>
                            <td>
                                <div class="fw-bold text-primary">{{ number_format($tour->price) }} đ</div>
                                @if($tour->original_price && $tour->original_price > $tour->price)
                                    <small class="text-muted text-decoration-line-through">
                                        {{ number_format($tour->original_price) }} đ
                                    </small>
                                @endif
                            </td>
                            <td>
                                @if($tour->duration_days)
                                    {{ $tour->duration_days }} ngày
                                    @if($tour->nights)
                                        / {{ $tour->nights }} đêm
                                    @endif
                                @else
                                    {{ $tour->duration ?? 'N/A' }}
                                @endif
                            </td>
                            <td>
                                @if($tour->availability_status == 'available')
                                    <span class="badge bg-success">Còn chỗ</span>
                                @elseif($tour->availability_status == 'contact')
                                    <span class="badge bg-warning">Liên hệ</span>
                                @else
                                    <span class="badge bg-danger">Hết chỗ</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="badge bg-info">{{ $tour->bookings->count() }}</span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.tours.show', $tour) }}" 
                                       class="btn btn-sm btn-info" 
                                       title="Xem chi tiết">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.tours.edit', $tour) }}" 
                                       class="btn btn-sm btn-warning" 
                                       title="Chỉnh sửa">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.tours.destroy', $tour) }}" 
                                          method="POST" 
                                          class="d-inline"
                                          onsubmit="return confirm('Bạn có chắc muốn xóa tour này?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Xóa">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Chưa có tour nào trong hệ thống</p>
                                <a href="{{ route('admin.tours.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Thêm tour đầu tiên
                                </a>
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

@push('styles')
<style>
.table th {
    font-weight: 600;
    color: #495057;
    background-color: #f8f9fa;
}

.btn-group .btn {
    padding: 0.25rem 0.5rem;
}

.badge {
    padding: 0.35em 0.65em;
    font-weight: 500;
}
</style>
@endpush

@extends('layouts.admin')

@section('title', 'Quản lý Banner')

@section('content')
<style>
/* --- Vùng hiển thị bảng --- */
.table-wrapper {
    width: 100%;
    overflow-x: auto;
}

/* --- Bảng chính --- */
.banner-table {
    width: 100%;
    border-collapse: collapse;
    table-layout: fixed;
}

.banner-table thead {
    position: sticky;
    top: 0;
    z-index: 1;
    background-color: #f8f9fa;
}

.banner-table th,
.banner-table td {
    padding: 10px 8px;
    border: 1px solid #dee2e6;
    text-align: center;
    vertical-align: middle;
    font-size: 14px;
    white-space: nowrap;
}

/* --- Cột định kích thước cố định --- */
.banner-table th:nth-child(1),
.banner-table td:nth-child(1) { width: 60px; }
.banner-table th:nth-child(2),
.banner-table td:nth-child(2) { width: 100px; }
.banner-table th:nth-child(3),
.banner-table td:nth-child(3) { width: 220px; text-align: left; padding-left: 12px; white-space: normal; }
.banner-table th:nth-child(4),
.banner-table td:nth-child(4),
.banner-table th:nth-child(5),
.banner-table td:nth-child(5),
.banner-table th:nth-child(6),
.banner-table td:nth-child(6) { width: 120px; }
.banner-table th:nth-child(7),
.banner-table td:nth-child(7) { width: 80px; }
.banner-table th:nth-child(8),
.banner-table td:nth-child(8) { width: 200px; }

/* --- Ảnh banner --- */
.banner-table img {
    width: 60px;
    height: 40px;
    object-fit: cover;
    border-radius: 4px;
}

/* --- Badge đồng nhất --- */
.banner-table .badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 80px;
    height: 26px;
    font-size: 13px;
    border-radius: 6px;
    font-weight: 500;
}

/* --- Nút thao tác --- */
.banner-table .btn-group {
    display: flex;
    justify-content: center;
    align-items: center;
    flex-wrap: wrap;
    gap: 4px;
}

.banner-table .btn-group .btn {
    padding: 4px 6px;
    font-size: 12px;
    line-height: 1;
}

/* --- Hover hàng --- */
.banner-table tbody tr:hover {
    background-color: #f1f5ff;
}

/* --- Giới hạn chiều cao hàng --- */
.banner-table tr {
    height: 60px;
}
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
                    <h3 class="card-title mb-0">Quản lý Banner</h3>
                    <a href="{{ route('admin.banners.create') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-plus"></i> Thêm Banner Mới
                    </a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($banners->count() > 0)
                        <div class="table-wrapper">
                            <table class="banner-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Hình ảnh</th>
                                        <th>Tiêu đề</th>
                                        <th>Loại</th>
                                        <th>Vị trí</th>
                                        <th>Trạng thái</th>
                                        <th>Thứ tự</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($banners as $banner)
                                        <tr>
                                            <td>{{ $banner->id }}</td>
                                            <td>
                                                @if($banner->image_url)
                                                    <img src="{{ asset($banner->image_url) }}" alt="{{ $banner->title }}">
                                                @else
                                                    <div class="bg-light d-flex align-items-center justify-content-center rounded" 
                                                         style="width: 60px; height: 40px;">
                                                        <i class="fas fa-image text-muted"></i>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>{{ $banner->title }}</td>
                                            <td>
                                                <span class="badge badge-{{ $banner->type == 'hero' ? 'primary' : ($banner->type == 'promotion' ? 'success' : ($banner->type == 'category' ? 'info' : 'warning')) }}">
                                                    {{ $banner->type_text }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge badge-{{ $banner->position == 'top' ? 'primary' : ($banner->position == 'middle' ? 'info' : ($banner->position == 'bottom' ? 'warning' : 'secondary')) }}">
                                                    {{ $banner->position_text }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge badge-{{ $banner->is_active ? 'success' : 'danger' }}">
                                                    {{ $banner->is_active ? 'Hoạt động' : 'Không hoạt động' }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge badge-light">{{ $banner->sort_order }}</span>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-outline-secondary btn-sm" 
                                                            onclick="moveBanner({{ $banner->id }}, 'up')" title="Lên trên">
                                                        <i class="fas fa-arrow-up"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-outline-secondary btn-sm" 
                                                            onclick="moveBanner({{ $banner->id }}, 'down')" title="Xuống dưới">
                                                        <i class="fas fa-arrow-down"></i>
                                                    </button>
                                                    <a href="{{ route('admin.banners.show', $banner) }}" 
                                                       class="btn btn-info btn-sm" title="Xem chi tiết">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.banners.edit', $banner) }}" 
                                                       class="btn btn-warning btn-sm" title="Chỉnh sửa">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('admin.banners.destroy', $banner) }}" 
                                                          method="POST" class="d-inline" 
                                                          onsubmit="return confirm('Bạn có chắc chắn muốn xóa banner này?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm" title="Xóa">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center mt-3">
                            {{ $banners->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-image fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Chưa có banner nào</h5>
                            <p class="text-muted mb-4">Hãy tạo banner đầu tiên để bắt đầu quản lý!</p>
                            <a href="{{ route('admin.banners.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Thêm Banner Mới
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function moveBanner(bannerId, direction) {
    fetch(`/admin/banners/${bannerId}/move`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ direction: direction })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Có lỗi xảy ra: ' + (data.message || 'Không thể di chuyển banner'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra khi di chuyển banner');
    });
}
</script>
@endsection

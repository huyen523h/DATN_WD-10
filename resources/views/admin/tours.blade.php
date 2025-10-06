@extends('layouts.admin')

@section('title', 'Quản lý Tours - Admin')

@section('breadcrumb')
<li class="breadcrumb-item active">Quản lý Tours</li>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2><i class="fas fa-map-marked-alt text-primary"></i> Quản lý Tours</h2>
        <p class="text-muted mb-0">Quản lý tất cả các tour du lịch trong hệ thống</p>
    </div>
    <a href="{{ route('admin.tours.create') }}" class="btn btn-admin-primary">
        <i class="fas fa-plus"></i> Thêm tour mới
    </a>
</div>

<!-- Search and Filter -->
<div class="row mb-4">
    <div class="col-12">
        <div class="admin-card">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.tours') }}" class="row g-3">
                        <div class="col-md-4">
                            <label for="search" class="form-label">Tìm kiếm</label>
                            <input type="text" class="form-control" id="search" name="search" 
                                   value="{{ request('search') }}" placeholder="Tên tour, mô tả...">
                        </div>
                        <div class="col-md-3">
                            <label for="category_id" class="form-label">Danh mục</label>
                            <select class="form-select" id="category_id" name="category_id">
                                <option value="">Tất cả danh mục</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" 
                                            {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="status" class="form-label">Trạng thái</label>
                            <select class="form-select" id="status" name="status">
                                <option value="">Tất cả trạng thái</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Hoạt động</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Tạm dừng</option>
                                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Bản nháp</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i>
                                </button>
                                <a href="{{ route('admin.tours') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-times"></i>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<!-- Tours Table -->
<div class="row">
    <div class="col-12">
        <div class="admin-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        Danh sách tours ({{ $tours->total() }} tours)
                    </h6>
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="selectAll()">
                            <i class="fas fa-check-square"></i> Chọn tất cả
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="bulkDelete()">
                            <i class="fas fa-trash"></i> Xóa đã chọn
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    @if($tours->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th width="50">
                                            <input type="checkbox" id="selectAll" onchange="toggleSelectAll()">
                                        </th>
                                        <th>Hình ảnh</th>
                                        <th>Tên tour</th>
                                        <th>Danh mục</th>
                                        <th>Giá</th>
                                        <th>Thời gian</th>
                                        <th>Trạng thái</th>
                                        <th>Đặt tour</th>
                                        <th>Ngày tạo</th>
                                        <th width="150">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($tours as $tour)
                                        <tr>
                                            <td>
                                                <input type="checkbox" class="tour-checkbox" value="{{ $tour->id }}">
                                            </td>
                                            <td>
                                                @if($tour->images->count() > 0)
                                                    <img src="{{ $tour->images->first()->image_url }}" 
                                                         alt="{{ $tour->title }}" 
                                                         class="tour-thumbnail">
                                                @else
                                                    <div class="tour-thumbnail bg-light d-flex align-items-center justify-content-center">
                                                        <i class="fas fa-image text-muted"></i>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="fw-bold">{{ $tour->title }}</div>
                                                <small class="text-muted">{{ Str::limit($tour->description, 50) }}</small>
                                            </td>
                                            <td>
                                                @if($tour->category)
                                                    <span class="badge bg-light text-dark">{{ $tour->category->name }}</span>
                                                @else
                                                    <span class="text-muted">Chưa phân loại</span>
                                                @endif
                                            </td>
                                            <td class="fw-bold text-success">
                                                {{ number_format($tour->price, 0, ',', '.') }}đ
                                            </td>
                                            <td>
                                                <i class="fas fa-clock text-muted"></i> {{ $tour->duration_days }} ngày
                                            </td>
                                            <td>
                                                <span class="badge 
                                                    @if($tour->status === 'active') bg-success
                                                    @elseif($tour->status === 'inactive') bg-warning
                                                    @elseif($tour->status === 'draft') bg-secondary
                                                    @else bg-light text-dark
                                                    @endif">
                                                    @switch($tour->status)
                                                        @case('active') Hoạt động @break
                                                        @case('inactive') Tạm dừng @break
                                                        @case('draft') Bản nháp @break
                                                        @default {{ $tour->status }} @break
                                                    @endswitch
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">{{ $tour->bookings->count() }}</span>
                                            </td>
                                            <td>
                                                <small class="text-muted">{{ $tour->created_at->format('d/m/Y') }}</small>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <a href="{{ route('tours.show', $tour) }}" 
                                                       class="btn btn-outline-info" title="Xem">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.tours.edit', $tour) }}" 
                                                       class="btn btn-outline-primary" title="Sửa">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-outline-danger" 
                                                            onclick="deleteTour({{ $tour->id }})" title="Xóa">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div class="text-muted">
                                Hiển thị {{ $tours->firstItem() }} đến {{ $tours->lastItem() }} 
                                trong tổng số {{ $tours->total() }} tours
                            </div>
                            <div>
                                {{ $tours->links() }}
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-map-marked-alt fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">Chưa có tour nào</h5>
                            <p class="text-muted">Hãy thêm tour đầu tiên để bắt đầu</p>
                            <a href="{{ route('admin.tours.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Thêm tour mới
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xác nhận xóa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn xóa tour này không?</p>
                <p class="text-danger small">Hành động này không thể hoàn tác!</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Xóa</button>
            </div>
        </div>
    </div>
@endsection

@section('styles')
<style>
.tour-thumbnail {
    width: 60px;
    height: 40px;
    object-fit: cover;
    border-radius: 4px;
}

.tour-checkbox {
    transform: scale(1.2);
}

.btn-group-sm .btn {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
}

.table th {
    border-top: none;
    font-weight: 600;
    color: #6B7280;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.table td {
    vertical-align: middle;
}

.card-header {
    background: #F9FAFB;
    border-bottom: 1px solid #E5E7EB;
}
</style>
@endsection

@section('scripts')
<script>
let tourToDelete = null;

function selectAll() {
    const checkboxes = document.querySelectorAll('.tour-checkbox');
    const selectAllCheckbox = document.getElementById('selectAll');
    const allChecked = Array.from(checkboxes).every(cb => cb.checked);
    
    checkboxes.forEach(cb => cb.checked = !allChecked);
    selectAllCheckbox.checked = !allChecked;
}

function toggleSelectAll() {
    const selectAllCheckbox = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.tour-checkbox');
    
    checkboxes.forEach(cb => cb.checked = selectAllCheckbox.checked);
}

function deleteTour(tourId) {
    tourToDelete = tourId;
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}

function bulkDelete() {
    const selectedTours = Array.from(document.querySelectorAll('.tour-checkbox:checked'))
                               .map(cb => cb.value);
    
    if (selectedTours.length === 0) {
        alert('Vui lòng chọn ít nhất một tour để xóa');
        return;
    }
    
    if (confirm(`Bạn có chắc chắn muốn xóa ${selectedTours.length} tour đã chọn?`)) {
        // Implement bulk delete logic here
        console.log('Deleting tours:', selectedTours);
    }
}

document.getElementById('confirmDelete').addEventListener('click', function() {
    if (tourToDelete) {
        // Implement delete logic here
        console.log('Deleting tour:', tourToDelete);
        // You can use fetch API to send DELETE request
        // fetch(`/admin/tours/${tourToDelete}`, { method: 'DELETE' })
        //     .then(response => response.json())
        //     .then(data => {
        //         location.reload();
        //     });
    }
});
</script>
@endsection
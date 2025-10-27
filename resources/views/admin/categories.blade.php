@extends('layouts.admin')

@section('title', 'Quản lý Danh mục - Admin')

@section('breadcrumb')
<li class="breadcrumb-item active">Quản lý Danh mục</li>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2><i class="fas fa-tags text-primary"></i> Quản lý Danh mục</h2>
        <p class="text-muted mb-0">Quản lý các danh mục tour du lịch</p>
    </div>
    <button class="btn btn-admin-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
        <i class="fas fa-plus"></i> Thêm danh mục mới
    </button>
</div>

<!-- Categories Table -->
<div class="row">
    <div class="col-12">
        <div class="admin-card">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">
                    Danh sách danh mục ({{ $categories->total() }} danh mục)
                </h6>
            </div>
            <div class="card-body">
                @if($categories->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Hình ảnh</th>
                                <th>Tên danh mục</th>
                                <th>Mô tả</th>
                                <th>Số tours</th>
                                <th>Trạng thái</th>
                                <th>Ngày tạo</th>
                                <th width="150">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($categories as $category)
                            <tr>
                                <td>
                                    @if ($category->image_url)
                                        <img src="{{ $category->image_url }}" alt="{{ $category->name }}" class="category-thumbnail">
                                    @else
                                        <div class="category-thumbnail bg-light d-flex align-items-center justify-content-center">
                                            <i class="fas fa-image text-muted"></i>
                                        </div>
                                    @endif
                                </td>
                                <td class="fw-bold">{{ $category->name }}</td>
                                <td><small class="text-muted">{{ Str::limit($category->description, 50) }}</small></td>
                                <td><span class="badge bg-info">{{ $category->tours_count }}</span></td>
                                <td>
                                    <span class="badge 
                                        @if ($category->status === 'active') bg-success
                                        @elseif($category->status === 'inactive') bg-warning
                                        @else bg-secondary @endif">
                                        @switch($category->status)
                                            @case('active') Hoạt động @break
                                            @case('inactive') Tạm dừng @break
                                            @default {{ $category->status }}
                                        @endswitch
                                    </span>
                                </td>
                                <td><small class="text-muted">{{ $category->created_at->format('d/m/Y') }}</small></td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <button class="btn btn-outline-primary"
                                            onclick="openEditModal({{ $category->id }}, '{{ addslashes($category->name) }}', '{{ addslashes($category->description) }}', '{{ $category->status }}')"
                                            title="Sửa">
                                            <i class="fas fa-edit"></i>
                                        </button>

                                        <form action="{{ route('admin.categories.destroy', $category->id) }}"
                                              method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger"
                                                onclick="return confirm('Bạn có chắc chắn muốn xóa danh mục này?')"
                                                title="Xóa">
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

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted">
                        Hiển thị {{ $categories->firstItem() }} đến {{ $categories->lastItem() }}
                        trong tổng số {{ $categories->total() }} danh mục
                    </div>
                    <div>
                        {{ $categories->links() }}
                    </div>
                </div>
                @else
                <div class="text-center py-5">
                    <i class="fas fa-tags fa-4x text-muted mb-3"></i>
                    <h5 class="text-muted">Chưa có danh mục nào</h5>
                    <p class="text-muted">Hãy thêm danh mục đầu tiên để bắt đầu</p>
                    <button class="btn btn-admin-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                        <i class="fas fa-plus"></i> Thêm danh mục mới
                    </button>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Add Category Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.categories.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Thêm danh mục mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Tên danh mục <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Mô tả</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Trạng thái</label>
                        <select class="form-select" id="status" name="status">
                            <option value="active">Hoạt động</option>
                            <option value="inactive">Tạm dừng</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-admin-primary">Thêm danh mục</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Category Modal -->
<div class="modal fade" id="editCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editCategoryForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Chỉnh sửa danh mục</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tên danh mục</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mô tả</label>
                        <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Trạng thái</label>
                        <select class="form-select" id="edit_status" name="status">
                            <option value="active">Hoạt động</option>
                            <option value="inactive">Tạm dừng</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-admin-primary">Cập nhật</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .category-thumbnail {
        width: 60px;
        height: 40px;
        object-fit: cover;
        border-radius: 4px;
    }
</style>
@endsection

@section('scripts')
<script>
    function openEditModal(id, name, description, status) {
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_description').value = description;
        document.getElementById('edit_status').value = status;

        // Tạo URL route update động bằng JS
        const url = `{{ route('admin.categories.update', ':id') }}`.replace(':id', id);
        document.getElementById('editCategoryForm').action = url;

        new bootstrap.Modal(document.getElementById('editCategoryModal')).show();
    }
</script>
@endsection

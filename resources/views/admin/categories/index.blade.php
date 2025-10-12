@extends('layouts.admin')

@section('title', 'Quản lý Danh mục - Admin')

@section('breadcrumb')
<li class="breadcrumb-item active">Quản lý Danh mục</li>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2><i class="fas fa-tags text-primary"></i> Quản lý danh mục</h2>
        <p class="text-muted mb-0">Quản lý các danh mục tour du lịch</p>
    </div>
    <a href="{{ route('admin.categories.create') }}" class="btn btn-admin-primary">
        <i class="fas fa-plus"></i> Tạo danh mục mới
    </a>
</div>

<div class="row">
    <div class="col-12">
        <div class="admin-card">
            <div class="card-body">
                @if($categories->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th><i class="fas fa-tag me-2"></i>Tên danh mục</th>
                                    <th><i class="fas fa-align-left me-2"></i>Mô tả</th>
                                    <th><i class="fas fa-map-marked-alt me-2"></i>Số tour</th>
                                    <th><i class="fas fa-toggle-on me-2"></i>Trạng thái</th>
                                    <th class="text-end"><i class="fas fa-cogs me-2"></i>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($categories as $category)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                    <i class="fas fa-tag text-primary"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h6 class="mb-0">{{ $category->name }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-muted">{{ $category->description ?: 'Không có mô tả' }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $category->tours_count }} tour</span>
                                    </td>
                                    <td>
                                        @if($category->status === 'active')
                                            <span class="badge bg-success">
                                                <i class="fas fa-check-circle me-1"></i>Hoạt động
                                            </span>
                                        @else
                                            <span class="badge bg-danger">
                                                <i class="fas fa-times-circle me-1"></i>Không hoạt động
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.categories.edit', $category) }}" 
                                               class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i> Sửa
                                            </a>
                                            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="btn btn-sm btn-danger"
                                                        onclick="return confirm('Bạn có chắc chắn muốn xóa danh mục \'{{ $category->name }}\'? Hành động này không thể hoàn tác.')">
                                                    <i class="fas fa-trash"></i> Xóa
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
                    @if($categories->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $categories->links() }}
                        </div>
                    @endif
                @else
                    <!-- Empty State -->
                    <div class="text-center py-5">
                        <div class="mb-4">
                            <i class="fas fa-tags text-muted" style="font-size: 4rem;"></i>
                        </div>
                        <h4 class="text-muted mb-3">Chưa có danh mục nào</h4>
                        <p class="text-muted mb-4">Bắt đầu bằng cách tạo danh mục đầu tiên của bạn.</p>
                        <a href="{{ route('admin.categories.create') }}" class="btn btn-admin-primary">
                            <i class="fas fa-plus me-2"></i>Tạo danh mục đầu tiên
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

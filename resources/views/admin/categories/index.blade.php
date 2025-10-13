@extends('layouts.admin')

@section('title', 'Quản lý Danh mục - Admin')

@section('breadcrumb')
<li class="breadcrumb-item">
    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
</li>
<li class="breadcrumb-item active">Quản lý Danh mục</li>
@endsection

@section('content')
<!-- Page Header -->
<div class="fade-in">
    <div class="d-flex justify-content-between align-items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 mb-2">
                <i class="fas fa-tags text-indigo-600 mr-3"></i>
                Quản lý Danh mục
            </h1>
            <p class="text-gray-600">Quản lý các danh mục tour trong hệ thống</p>
        </div>
        <div class="flex gap-3">
            <button class="btn btn-secondary">
                <i class="fas fa-download mr-2"></i>
                Xuất Excel
            </button>
            <button class="btn btn-secondary">
                <i class="fas fa-filter mr-2"></i>
                Lọc nâng cao
            </button>
            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                <i class="fas fa-plus mr-2"></i>
                Thêm danh mục
            </a>
        </div>
    </div>

    <!-- Success Alert -->
    @if(session('success'))
        <div class="alert alert-success fade-in">
            <i class="fas fa-check-circle mr-3"></i>
            {{ session('success') }}
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="stats-grid mb-6">
        <div class="stat-card">
            <div class="stat-icon stat-icon-primary">
                <i class="fas fa-tags"></i>
            </div>
            <div class="stat-value">{{ $categories->total() }}</div>
            <div class="stat-label">Tổng danh mục</div>
            <div class="stat-change positive">
                <i class="fas fa-arrow-up"></i>
                <span>+2 tháng này</span>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon stat-icon-success">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-value">{{ $categories->where('status', 'active')->count() }}</div>
            <div class="stat-label">Đang hoạt động</div>
            <div class="stat-change positive">
                <i class="fas fa-arrow-up"></i>
                <span>100% khả dụng</span>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon stat-icon-info">
                <i class="fas fa-map-marked-alt"></i>
            </div>
            <div class="stat-value">{{ $categories->sum('tours_count') }}</div>
            <div class="stat-label">Tổng tour</div>
            <div class="stat-change positive">
                <i class="fas fa-arrow-up"></i>
                <span>Trong tất cả danh mục</span>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon stat-icon-warning">
                <i class="fas fa-star"></i>
            </div>
            <div class="stat-value">{{ $categories->where('tours_count', '>=', 10)->count() }}</div>
            <div class="stat-label">Danh mục phổ biến</div>
            <div class="stat-change positive">
                <i class="fas fa-arrow-up"></i>
                <span>Có nhiều tour</span>
            </div>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="card mb-6">
        <div class="card-header">
            <h3 class="text-lg font-semibold text-gray-900">
                <i class="fas fa-search text-indigo-600 mr-2"></i>
                Tìm kiếm và lọc
            </h3>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.categories') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="form-group">
                    <label for="search" class="form-label">Tìm kiếm</label>
                    <div class="relative">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <input type="text" class="form-control pl-10" id="search" name="search" 
                               value="{{ request('search') }}" placeholder="Tên danh mục, mô tả...">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="status" class="form-label">Trạng thái</label>
                    <select class="form-control" id="status" name="status">
                        <option value="">Tất cả trạng thái</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Đang hoạt động</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Không hoạt động</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="sort" class="form-label">Sắp xếp</label>
                    <select class="form-control" id="sort" name="sort">
                        <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Tên A-Z</option>
                        <option value="tours" {{ request('sort') == 'tours' ? 'selected' : '' }}>Nhiều tour nhất</option>
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Mới nhất</option>
                        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Cũ nhất</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="per_page" class="form-label">Hiển thị</label>
                    <select class="form-control" id="per_page" name="per_page">
                        <option value="10" {{ request('per_page') == '10' ? 'selected' : '' }}>10</option>
                        <option value="25" {{ request('per_page') == '25' ? 'selected' : '' }}>25</option>
                        <option value="50" {{ request('per_page') == '50' ? 'selected' : '' }}>50</option>
                    </select>
                </div>
                
                <div class="col-span-full flex gap-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search mr-2"></i>
                        Tìm kiếm
                    </button>
                    <a href="{{ route('admin.categories') }}" class="btn btn-secondary">
                        <i class="fas fa-times mr-2"></i>
                        Xóa bộ lọc
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Categories Table -->
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="text-lg font-semibold text-gray-900">
                    <i class="fas fa-list text-indigo-600 mr-2"></i>
                    Danh sách danh mục ({{ $categories->total() }} danh mục)
                </h3>
                <div class="flex gap-2">
                    <button class="btn btn-secondary btn-sm">
                        <i class="fas fa-download mr-1"></i>
                        Xuất báo cáo
                    </button>
                    <button class="btn btn-secondary btn-sm">
                        <i class="fas fa-print mr-1"></i>
                        In danh sách
                    </button>
                </div>
            </div>
        </div>
        
        <div class="card-body p-0">
            @if($categories->count() > 0)
                <div class="overflow-x-auto">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Danh mục</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mô tả</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Số tour</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày tạo</th>
                                <th class="px-6 py-4 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($categories as $category)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center mr-4">
                                                <i class="fas fa-tag text-indigo-600"></i>
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">{{ $category->name }}</div>
                                                <div class="text-sm text-gray-500">ID: #{{ $category->id }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900 max-w-xs">
                                            {{ Str::limit($category->description, 50) }}
                                            @if(strlen($category->description) > 50)
                                                <span class="text-gray-500 text-xs">...Xem thêm</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                                <i class="fas fa-map-marked-alt text-blue-600 text-sm"></i>
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">{{ $category->tours_count }}</div>
                                                <div class="text-xs text-gray-500">tour</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($category->status === 'active')
                                            <span class="badge badge-success">
                                                <i class="fas fa-check-circle mr-1"></i>
                                                Hoạt động
                                            </span>
                                        @else
                                            <span class="badge badge-warning">
                                                <i class="fas fa-pause-circle mr-1"></i>
                                                Không hoạt động
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900">
                                            <i class="fas fa-calendar mr-1 text-gray-400"></i>
                                            {{ $category->created_at->format('d/m/Y') }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            {{ $category->created_at->diffForHumans() }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex justify-center gap-2">
                                            <a href="{{ route('admin.categories.show', $category) }}" 
                                               class="btn btn-secondary btn-sm" 
                                               title="Xem chi tiết">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.categories.edit', $category) }}" 
                                               class="btn btn-primary btn-sm" 
                                               title="Chỉnh sửa">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button class="btn btn-info btn-sm" 
                                                    title="Xem tours">
                                                <i class="fas fa-list"></i>
                                            </button>
                                            <form action="{{ route('admin.categories.destroy', $category) }}" 
                                                  method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-danger btn-sm" 
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
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-700">
                            Hiển thị {{ $categories->firstItem() }} - {{ $categories->lastItem() }} 
                            trong tổng số {{ $categories->total() }} danh mục
                        </div>
                        <div>
                            {{ $categories->links() }}
                        </div>
                    </div>
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-12">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-tags text-3xl text-gray-400"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Chưa có danh mục nào</h3>
                    <p class="text-gray-500 mb-6">Tạo danh mục đầu tiên để bắt đầu quản lý tours</p>
                    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus mr-2"></i>
                        Tạo danh mục đầu tiên
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .mb-6 { margin-bottom: 1.5rem; }
    .text-2xl { font-size: 1.5rem; }
    .text-lg { font-size: 1.125rem; }
    .text-sm { font-size: 0.875rem; }
    .text-xs { font-size: 0.75rem; }
    .font-bold { font-weight: 700; }
    .font-semibold { font-weight: 600; }
    .font-medium { font-weight: 500; }
    .text-gray-900 { color: var(--gray-900); }
    .text-gray-600 { color: var(--gray-600); }
    .text-gray-500 { color: var(--gray-500); }
    .text-indigo-600 { color: var(--primary-600); }
    .mr-1 { margin-right: 0.25rem; }
    .mr-2 { margin-right: 0.5rem; }
    .mr-3 { margin-right: 0.75rem; }
    .mr-4 { margin-right: 1rem; }
    .mb-2 { margin-bottom: 0.5rem; }
    .mb-4 { margin-bottom: 1rem; }
    .mb-6 { margin-bottom: 1.5rem; }
    .px-6 { padding-left: 1.5rem; padding-right: 1.5rem; }
    .py-4 { padding-top: 1rem; padding-bottom: 1rem; }
    .py-12 { padding-top: 3rem; padding-bottom: 3rem; }
    .w-12 { width: 3rem; }
    .h-12 { height: 3rem; }
    .w-24 { width: 6rem; }
    .h-24 { height: 6rem; }
    .w-8 { width: 2rem; }
    .h-8 { height: 2rem; }
    .max-w-xs { max-width: 20rem; }
    .bg-indigo-100 { background-color: var(--primary-100); }
    .bg-blue-100 { background-color: var(--info-50); }
    .bg-gray-100 { background-color: var(--gray-100); }
    .bg-gray-50 { background-color: var(--gray-50); }
    .rounded-lg { border-radius: var(--radius); }
    .rounded-full { border-radius: 9999px; }
    .flex { display: flex; }
    .items-center { align-items: center; }
    .justify-center { justify-content: center; }
    .justify-between { justify-content: space-between; }
    .text-center { text-align: center; }
    .text-left { text-align: left; }
    .uppercase { text-transform: uppercase; }
    .tracking-wider { letter-spacing: 0.05em; }
    .divide-y > * + * { border-top: 1px solid var(--gray-200); }
    .divide-gray-200 > * + * { border-color: var(--gray-200); }
    .hover\:bg-gray-50:hover { background-color: var(--gray-50); }
    .transition-colors { transition: background-color 0.3s ease; }
    .overflow-x-auto { overflow-x: auto; }
    .grid { display: grid; }
    .grid-cols-1 { grid-template-columns: repeat(1, minmax(0, 1fr)); }
    .gap-2 { gap: 0.5rem; }
    .gap-3 { gap: 0.75rem; }
    .gap-4 { gap: 1rem; }
    .col-span-full { grid-column: 1 / -1; }
    .relative { position: relative; }
    .absolute { position: absolute; }
    .left-3 { left: 0.75rem; }
    .top-1\/2 { top: 50%; }
    .transform { transform: translateY(-50%); }
    .-translate-y-1\/2 { transform: translateY(-50%); }
    .pl-10 { padding-left: 2.5rem; }
    .mx-auto { margin-left: auto; margin-right: auto; }
    .text-3xl { font-size: 1.875rem; }
    .text-gray-400 { color: var(--gray-400); }
    
    @media (min-width: 768px) {
        .md\:grid-cols-4 { grid-template-columns: repeat(4, minmax(0, 1fr)); }
    }
</style>
@endsection

@section('scripts')
<script>
    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
        // Add any JavaScript functionality here
    });
</script>
@endsection
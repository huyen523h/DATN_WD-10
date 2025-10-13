@extends('layouts.admin')

@section('title', 'Quản lý Tours - Admin')

@section('breadcrumb')
<li class="breadcrumb-item">
    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
</li>
<li class="breadcrumb-item active">Quản lý Tours</li>
@endsection

@section('content')
<!-- Page Header -->
<div class="fade-in">
    <div class="d-flex justify-content-between align-items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 mb-2">
                <i class="fas fa-map-marked-alt text-indigo-600 mr-3"></i>
                Quản lý Tours
            </h1>
            <p class="text-gray-600">Quản lý tất cả các tour du lịch trong hệ thống</p>
        </div>
        <a href="{{ route('admin.tours.create') }}" class="btn btn-primary btn-lg">
            <i class="fas fa-plus mr-2"></i>
            Thêm tour mới
        </a>
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
            <form method="GET" action="{{ route('admin.tours') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="form-group">
                    <label for="search" class="form-label">Tìm kiếm</label>
                    <div class="relative">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <input type="text" class="form-control pl-10" id="search" name="search" 
                               value="{{ request('search') }}" placeholder="Tên tour, mô tả...">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="category_id" class="form-label">Danh mục</label>
                    <select class="form-control" id="category_id" name="category_id">
                        <option value="">Tất cả danh mục</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" 
                                    {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="status" class="form-label">Trạng thái</label>
                    <select class="form-control" id="status" name="status">
                        <option value="">Tất cả trạng thái</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Hoạt động</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Tạm dừng</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Bản nháp</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label">&nbsp;</label>
                    <div class="flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search mr-1"></i>
                            Tìm
                        </button>
                        <a href="{{ route('admin.tours') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tours Table -->
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="text-lg font-semibold text-gray-900">
                    <i class="fas fa-list text-indigo-600 mr-2"></i>
                    Danh sách tours ({{ $tours->total() }} tours)
                </h3>
                <div class="flex gap-2">
                    <button type="button" class="btn btn-secondary btn-sm" onclick="selectAll()">
                        <i class="fas fa-check-square mr-1"></i>
                        Chọn tất cả
                    </button>
                    <button type="button" class="btn btn-danger btn-sm" onclick="bulkDelete()">
                        <i class="fas fa-trash mr-1"></i>
                        Xóa đã chọn
                    </button>
                    <button class="btn btn-secondary btn-sm">
                        <i class="fas fa-download mr-1"></i>
                        Xuất Excel
                    </button>
                </div>
            </div>
        </div>
        
        <div class="card-body p-0">
            @if($tours->count() > 0)
                <div class="overflow-x-auto">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-12">
                                    <input type="checkbox" id="selectAll" onchange="toggleSelectAll()">
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hình ảnh</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tên tour</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Danh mục</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Giá</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thời gian</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Đặt tour</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày tạo</th>
                                <th class="px-6 py-4 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-32">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($tours as $tour)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <input type="checkbox" class="tour-checkbox" value="{{ $tour->id }}">
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($tour->images->count() > 0)
                                            <img src="{{ $tour->images->first()->image_url }}" 
                                                 alt="{{ $tour->title }}" 
                                                 class="w-16 h-12 object-cover rounded-lg">
                                        @else
                                            <div class="w-16 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                                                <i class="fas fa-image text-gray-400"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="flex-grow-1">
                                                <div class="text-sm font-medium text-gray-900">{{ $tour->title }}</div>
                                                <div class="text-sm text-gray-500 max-w-xs">{{ Str::limit($tour->description, 50) }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($tour->category)
                                            <span class="badge badge-primary">
                                                {{ $tour->category->name }}
                                            </span>
                                        @else
                                            <span class="text-gray-500 text-sm">Chưa phân loại</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-bold text-green-600">
                                            {{ number_format($tour->price, 0, ',', '.') }}đ
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900">
                                            <i class="fas fa-clock mr-1 text-gray-400"></i>
                                            {{ $tour->duration_days }} ngày
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @switch($tour->status)
                                            @case('active')
                                                <span class="badge badge-success">
                                                    <i class="fas fa-circle mr-1" style="font-size: 8px;"></i>
                                                    Hoạt động
                                                </span>
                                                @break
                                            @case('inactive')
                                                <span class="badge badge-warning">
                                                    <i class="fas fa-circle mr-1" style="font-size: 8px;"></i>
                                                    Tạm dừng
                                                </span>
                                                @break
                                            @case('draft')
                                                <span class="badge badge-info">
                                                    <i class="fas fa-circle mr-1" style="font-size: 8px;"></i>
                                                    Bản nháp
                                                </span>
                                                @break
                                            @default
                                                <span class="badge badge-secondary">
                                                    {{ $tour->status }}
                                                </span>
                                        @endswitch
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="badge badge-info">
                                            {{ $tour->bookings->count() }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900">
                                            <i class="fas fa-calendar-alt mr-1 text-gray-400"></i>
                                            {{ $tour->created_at->format('d/m/Y') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex justify-center gap-1">
                                            <a href="{{ route('tours.show', $tour) }}" 
                                               class="btn btn-secondary btn-sm" 
                                               title="Xem">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.tours.edit', $tour) }}" 
                                               class="btn btn-primary btn-sm" 
                                               title="Sửa">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-danger btn-sm" 
                                                    onclick="deleteTour({{ $tour->id }})" 
                                                    title="Xóa">
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
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-700">
                            Hiển thị {{ $tours->firstItem() }} - {{ $tours->lastItem() }} 
                            trong tổng số {{ $tours->total() }} tours
                        </div>
                        <div>
                            {{ $tours->links() }}
                        </div>
                    </div>
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-12">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-map-marked-alt text-3xl text-gray-400"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Chưa có tour nào</h3>
                    <p class="text-gray-500 mb-6">Hãy thêm tour đầu tiên để bắt đầu quản lý</p>
                    <a href="{{ route('admin.tours.create') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-plus mr-2"></i>
                        Thêm tour mới
                    </a>
                </div>
            @endif
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
    .text-gray-400 { color: var(--gray-400); }
    .text-indigo-600 { color: var(--primary-600); }
    .text-green-600 { color: var(--success-600); }
    .mr-1 { margin-right: 0.25rem; }
    .mr-2 { margin-right: 0.5rem; }
    .mr-3 { margin-right: 0.75rem; }
    .mb-2 { margin-bottom: 0.5rem; }
    .mb-4 { margin-bottom: 1rem; }
    .mb-6 { margin-bottom: 1.5rem; }
    .px-6 { padding-left: 1.5rem; padding-right: 1.5rem; }
    .py-4 { padding-top: 1rem; padding-bottom: 1rem; }
    .py-12 { padding-top: 3rem; padding-bottom: 3rem; }
    .w-12 { width: 3rem; }
    .w-16 { width: 4rem; }
    .h-12 { height: 3rem; }
    .w-24 { width: 6rem; }
    .h-24 { height: 6rem; }
    .w-32 { width: 8rem; }
    .max-w-xs { max-width: 20rem; }
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
    .gap-1 { gap: 0.25rem; }
    .gap-2 { gap: 0.5rem; }
    .gap-4 { gap: 1rem; }
    .relative { position: relative; }
    .absolute { position: absolute; }
    .left-3 { left: 0.75rem; }
    .top-1\/2 { top: 50%; }
    .transform { transform: translateY(-50%); }
    .-translate-y-1\/2 { transform: translateY(-50%); }
    .pl-10 { padding-left: 2.5rem; }
    .mx-auto { margin-left: auto; margin-right: auto; }
    .text-3xl { font-size: 1.875rem; }
    .object-cover { object-fit: cover; }
    .flex-grow-1 { flex-grow: 1; }
    
    .tour-checkbox {
        transform: scale(1.2);
    }
    
    @media (min-width: 768px) {
        .md\:grid-cols-4 { grid-template-columns: repeat(4, minmax(0, 1fr)); }
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
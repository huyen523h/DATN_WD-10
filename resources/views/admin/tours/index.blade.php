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

<!-- Modern Table Component -->
<x-admin.table 
    :headers="[
        ['key' => 'id', 'label' => 'ID', 'sortable' => true],
        ['key' => 'title', 'label' => 'Tour', 'sortable' => true, 'component' => 'admin.table.tour-title'],
        ['key' => 'category', 'label' => 'Danh mục', 'sortable' => true],
        ['key' => 'price', 'label' => 'Giá', 'sortable' => true, 'component' => 'admin.table.price'],
        ['key' => 'duration', 'label' => 'Thời gian', 'sortable' => true],
        ['key' => 'status', 'label' => 'Trạng thái', 'sortable' => true, 'component' => 'admin.table.status-badge'],
        ['key' => 'bookings_count', 'label' => 'Đặt tour', 'sortable' => true]
    ]"
    :data="$tours->map(function($tour) {
        return [
            'id' => $tour->id,
            'title' => [
                'title' => $tour->title,
                'description' => Str::limit($tour->description, 50),
                'image' => $tour->images->count() > 0 ? Storage::url($tour->images->first()->image_url) : null
            ],
            'category' => $tour->category->name,
            'price' => [
                'price' => $tour->price,
                'original_price' => $tour->old_price ?? null
            ],
            'duration' => ($tour->duration_days ?? 'N/A') . ' ngày',
            'status' => $tour->status,
            'bookings_count' => $tour->bookings->count()
        ];
    })"
    :actions="[
        ['action' => 'view', 'icon' => 'fas fa-eye', 'class' => 'btn-primary', 'title' => 'Xem chi tiết'],
        ['action' => 'edit', 'icon' => 'fas fa-edit', 'class' => 'btn-warning', 'title' => 'Chỉnh sửa'],
        ['action' => 'delete', 'icon' => 'fas fa-trash', 'class' => 'btn-danger', 'title' => 'Xóa']
    ]"
    :searchable="true"
    :sortable="true"
    :filterable="true"
    :pagination="$tours"
    empty-message="Chưa có tour nào"
    id="tours-table"
>
    <!-- Custom Filters -->
    <x-slot name="filters">
        <div class="filter-grid">
            <div class="filter-group">
                <label class="filter-label">Danh mục</label>
                <select name="category_id" class="filter-select">
                    <option value="">Tất cả danh mục</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="filter-group">
                <label class="filter-label">Trạng thái</label>
                <select name="status" class="filter-select">
                    <option value="">Tất cả trạng thái</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Hoạt động</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Không hoạt động</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Bản nháp</option>
                </select>
            </div>
            
            <div class="filter-group">
                <label class="filter-label">Khoảng giá</label>
                <div class="price-range">
                    <input type="number" name="min_price" class="filter-input" placeholder="Từ" value="{{ request('min_price') }}">
                    <span class="range-separator">-</span>
                    <input type="number" name="max_price" class="filter-input" placeholder="Đến" value="{{ request('max_price') }}">
                </div>
            </div>
            
            <div class="filter-group">
                <label class="filter-label">Thời gian</label>
                <select name="duration" class="filter-select">
                    <option value="">Tất cả</option>
                    <option value="1-3" {{ request('duration') == '1-3' ? 'selected' : '' }}>1-3 ngày</option>
                    <option value="4-7" {{ request('duration') == '4-7' ? 'selected' : '' }}>4-7 ngày</option>
                    <option value="8+" {{ request('duration') == '8+' ? 'selected' : '' }}>8+ ngày</option>
                </select>
            </div>
        </div>
        
        <div class="filter-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search"></i> Áp dụng bộ lọc
            </button>
            <a href="{{ route('admin.tours.index') }}" class="btn btn-secondary">
                <i class="fas fa-times"></i> Xóa bộ lọc
            </a>
        </div>
    </x-slot>
</x-admin.table>


@endsection

@push('styles')
<style>
/* Custom filter styles */
.filter-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-bottom: 1rem;
}

.filter-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.filter-label {
    font-size: 0.875rem;
    font-weight: 600;
    color: #374151;
}

.filter-select,
.filter-input {
    padding: 0.5rem 0.75rem;
    border: 1px solid #d1d5db;
    border-radius: 0.375rem;
    font-size: 0.875rem;
    background: white;
    transition: all 0.3s ease;
}

.filter-select:focus,
.filter-input:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.price-range {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.range-separator {
    color: #6b7280;
    font-weight: 500;
}

.filter-actions {
    display: flex;
    gap: 0.5rem;
    padding-top: 1rem;
    border-top: 1px solid #e5e7eb;
}

/* Tour title component styles */
.tour-title-cell {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.tour-image {
    width: 50px;
    height: 50px;
    border-radius: 0.5rem;
    object-fit: cover;
    background: #f3f4f6;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #9ca3af;
    font-size: 1.25rem;
}

.tour-info {
    flex: 1;
    min-width: 0;
}

.tour-name {
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 0.25rem;
    line-height: 1.4;
}

.tour-description {
    font-size: 0.75rem;
    color: #6b7280;
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .filter-grid {
        grid-template-columns: 1fr;
    }
    
    .price-range {
        flex-direction: column;
        align-items: stretch;
    }
    
    .range-separator {
        text-align: center;
    }
    
    .filter-actions {
        flex-direction: column;
    }
}
</style>
@endpush

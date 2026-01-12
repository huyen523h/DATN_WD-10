@extends('layouts.admin')

@section('title', 'Quản lý HDV')

@push('styles')
<style>
/* Container và Table */
.guides-table-wrapper {
    width: 100%;
    overflow-x: auto;
}

.guides-table {
    width: 100%;
    table-layout: auto;
    border-collapse: separate;
    border-spacing: 0;
    background: white;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.guides-table thead th {
    background: linear-gradient(135deg, #9f7aea 0%, #805ad5 100%);
    color: white;
    font-weight: 600;
    padding: 12px 16px;
    text-align: center;
    font-size: 13px;
    border: none;
    white-space: nowrap;
    position: sticky;
    top: 0;
    z-index: 10;
}

.guides-table tbody tr {
    transition: background-color 0.2s ease;
}

.guides-table tbody tr:nth-child(even) {
    background-color: #f9fafb;
}

.guides-table tbody tr:hover {
    background-color: #f3f4f6;
}

.guides-table tbody td {
    padding: 12px 16px;
    border-bottom: 1px solid #e5e7eb;
    vertical-align: middle;
    font-size: 13px;
    text-align: center;
}

/* Column widths */
.guides-table .col-id { width: 80px; }
.guides-table .col-name { width: 180px; text-align: left; }
.guides-table .col-phone { width: 120px; }
.guides-table .col-group { width: 150px; }
.guides-table .col-rating { width: 100px; }
.guides-table .col-status { width: 250px; min-width: 250px; }
.guides-table .col-actions { width: 120px; }

/* Cell content styling */
.guide-id {
    font-weight: 600;
    color: #4f46e5;
    font-size: 12px;
}

.guide-name {
    font-weight: 600;
    color: #1f2937;
    font-size: 13px;
    text-align: left;
}

.guide-phone {
    font-size: 13px;
    color: #059669;
    font-weight: 500;
}

.guide-group-info {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 4px;
}

.guide-categories-list {
    display: flex;
    flex-wrap: wrap;
    gap: 4px;
    justify-content: center;
    max-width: 150px;
}

.guide-category-badge {
    background: #f3f4f6;
    color: #374151;
    padding: 3px 6px;
    border-radius: 4px;
    font-size: 10px;
    font-weight: 500;
    display: inline-block;
    white-space: nowrap;
    line-height: 1.2;
}

.guide-exp {
    font-size: 11px;
    color: #059669;
    font-weight: 600;
    margin-top: 2px;
}

.guide-rating {
    font-size: 13px;
    color: #fbbf24;
    font-weight: 600;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 4px;
}

.guide-rating .star {
    font-size: 14px;
}

/* Status badges */
.status-badge {
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    display: inline-block;
}

.status-active {
    background: #d1fae5;
    color: #065f46;
}

.status-leave {
    background: #fef3c7;
    color: #92400e;
}

.status-inactive {
    background: #fee2e2;
    color: #991b1b;
}

/* Action buttons */
.actions-group {
    display: flex;
    gap: 6px;
    justify-content: center;
    align-items: center;
}

.action-btn {
    width: 32px;
    height: 32px;
    border-radius: 6px;
    border: 1px solid #e5e7eb;
    background: white;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    transition: all 0.2s ease;
    font-size: 14px;
}

.action-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.action-btn.view {
    color: #10b981;
    border-color: #10b981;
}

.action-btn.view:hover {
    background: #10b981;
    color: white;
}

.action-btn.edit {
    color: #3b82f6;
    border-color: #3b82f6;
}

.action-btn.edit:hover {
    background: #3b82f6;
    color: white;
}

.action-btn.delete {
    color: #ef4444;
    border-color: #ef4444;
}

.action-btn.delete:hover {
    background: #ef4444;
    color: white;
}

.action-btn.more {
    color: #6b7280;
    border-color: #e5e7eb;
}

.action-btn.more:hover {
    background: #f3f4f6;
    color: #374151;
}

/* Empty state */
.empty-state {
    text-align: center;
    padding: 60px 20px;
    color: #6b7280;
}

.empty-state-icon {
    font-size: 48px;
    margin-bottom: 16px;
    opacity: 0.5;
}

.empty-state-text {
    font-size: 16px;
    margin-bottom: 20px;
}

/* Filter section */
.filter-card {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    padding: 20px;
    margin-bottom: 20px;
}

.filter-card .form-label {
    font-weight: 600;
    color: #374151;
    margin-bottom: 8px;
    font-size: 13px;
}

.filter-card .form-control,
.filter-card .form-select {
    border: 1px solid #d1d5db;
    border-radius: 6px;
    padding: 8px 12px;
    font-size: 14px;
    transition: all 0.2s ease;
}

.filter-card .form-control:focus,
.filter-card .form-select:focus {
    border-color: #9f7aea;
    box-shadow: 0 0 0 3px rgba(159, 122, 234, 0.1);
    outline: none;
}
</style>
@endpush

@section('content')
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {!! session('success') !!}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Quản lý hướng dẫn viên</h1>
            <p class="text-muted mb-0">Theo dõi hồ sơ, phân loại và tình trạng hoạt động của HDV</p>
        </div>
        <a href="{{ route('admin.guides.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Thêm HDV
        </a>
    </div>

    <div class="filter-card">
        <form method="GET" action="{{ route('admin.guides.index') }}" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Từ khoá</label>
                <input type="text" name="keyword" class="form-control" value="{{ request('keyword') }}"
                    placeholder="Tên, mã, số điện thoại...">
            </div>
            <div class="col-md-4">
                <label class="form-label">Nhóm HDV</label>
                <select name="category_id" class="form-select">
                    <option value="">-- Tất cả --</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Trạng thái</label>
                <select name="status" class="form-select">
                    <option value="">-- Tất cả --</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Đang hoạt động</option>
                    <option value="on_leave" {{ request('status') === 'on_leave' ? 'selected' : '' }}>Tạm nghỉ</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Ngưng hoạt động</option>
                </select>
            </div>
            <div class="col-md-12 text-end">
                <button type="submit" class="btn btn-primary me-2">
                    <i class="fas fa-search"></i> Lọc
                </button>
                <a href="{{ route('admin.guides.index') }}" class="btn btn-outline-secondary">Đặt lại</a>
            </div>
        </form>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="guides-table-wrapper">
                <table class="guides-table">
                    <thead>
                        <tr>
                            <th class="col-id">ID</th>
                            <th class="col-name">Họ tên</th>
                            <th class="col-phone">SĐT</th>
                            <th class="col-group">Nhóm</th>
                            <th class="col-rating">ExpRating</th>
                            <th class="col-status">StatusAction</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($guides as $guide)
                            <tr>
                                <td class="col-id">
                                    <div class="guide-id">{{ $guide->code ?? 'HDV' . $guide->id }}</div>
                                </td>
                                <td class="col-name">
                                    <div class="guide-name" title="{{ $guide->full_name ?? 'N/A' }}">
                                        {{ $guide->full_name ?? 'N/A' }}
                                    </div>
                                </td>
                                <td class="col-phone">
                                    <div class="guide-phone">
                                        {{ $guide->phone ?? 'N/A' }}
                                    </div>
                                </td>
                                <td class="col-group">
                                    <div class="guide-group-info">
                                        @if($guide->categories->count() > 0)
                                            <div class="guide-categories-list">
                                                @foreach($guide->categories as $category)
                                                    <span class="guide-category-badge" title="{{ $category->name }}">
                                                        {{ $category->name }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="guide-category-badge" style="background: #f9fafb; color: #9ca3af;">N/A</span>
                                        @endif
                                        <span class="guide-exp">{{ $guide->experience_years ?? 0 }}y</span>
                                    </div>
                                </td>
                                <td class="col-rating">
                                    <div class="guide-rating">
                                        <span class="star">⭐</span>
                                        @php
                                            $rating = $guide->rating_average ?? 0;
                                            $ratingCount = $guide->rating_count ?? 0;
                                            if ($ratingCount >= 1000) {
                                                $displayRating = number_format($rating, 2) . 'K';
                                            } else {
                                                $displayRating = number_format($rating, 2);
                                            }
                                        @endphp
                                        <span>{{ $displayRating }}</span>
                                    </div>
                                </td>
                                <td class="col-status">
                                    <div class="d-flex align-items-center justify-content-center gap-2 flex-wrap">
                                        @php
                                            $status = $guide->status ?? 'active';
                                            $statusClass = match($status) {
                                                'active', 'available' => 'status-active',
                                                'on_leave' => 'status-leave',
                                                'inactive' => 'status-inactive',
                                                default => 'status-active'
                                            };
                                            $statusText = match($status) {
                                                'active', 'available' => 'OK',
                                                'on_leave' => 'OFF',
                                                'inactive' => 'NO',
                                                default => 'OK'
                                            };
                                        @endphp
                                        <span class="status-badge {{ $statusClass }}">{{ $statusText }}</span>
                                        <div class="actions-group">
                                            <a href="{{ route('admin.guides.show', $guide) }}" 
                                               class="action-btn view" 
                                               title="Xem chi tiết">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.guides.edit', $guide) }}" 
                                               class="action-btn edit" 
                                               title="Chỉnh sửa">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <div class="dropdown" style="display: inline-block;">
                                                <button class="action-btn more" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Thêm">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li>
                                                        <form action="{{ route('admin.guides.destroy', $guide) }}" 
                                                              method="POST" 
                                                              onsubmit="return confirm('Bạn có chắc muốn xóa HDV này?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item text-danger">
                                                                <i class="fas fa-trash me-2"></i> Xóa
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    <div class="empty-state">
                                        <div class="empty-state-icon">📋</div>
                                        <div class="empty-state-text">Chưa có HDV nào</div>
                                        <a href="{{ route('admin.guides.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus me-1"></i> Thêm HDV
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($guides->hasPages())
        <div class="card-footer bg-white">
            {{ $guides->links() }}
        </div>
        @endif
    </div>
@endsection


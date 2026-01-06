@extends('layouts.admin')

@section('title', 'Quản lý HDV')

@push('styles')
<link href="{{ asset('css/guides-compact.css') }}" rel="stylesheet">
<style>
/* CSS inline để đảm bảo hoạt động */
.guides-compact-wrapper {
    max-width: 900px !important;
    margin: 0 auto !important;
    overflow: hidden !important;
}

.guides-compact-table {
    width: 100% !important;
    table-layout: fixed !important;
    border-collapse: collapse !important;
    background: white !important;
    border-radius: 8px !important;
    overflow: hidden !important;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1) !important;
}

.guides-compact-table thead th {
    background: linear-gradient(135deg, #9f7aea 0%, #805ad5 100%) !important;
    color: white !important;
    font-weight: 600 !important;
    padding: 8px 4px !important;
    text-align: center !important;
    font-size: 11px !important;
    border: none !important;
    white-space: nowrap !important;
}

.guides-compact-table tbody td {
    padding: 6px 4px !important;
    border-bottom: 1px solid #e5e7eb !important;
    vertical-align: middle !important;
    font-size: 11px !important;
    text-align: center !important;
    overflow: hidden !important;
    text-overflow: ellipsis !important;
    white-space: nowrap !important;
}

.guides-compact-table .col-id { width: 50px !important; }
.guides-compact-table .col-name { width: 120px !important; }
.guides-compact-table .col-phone { width: 90px !important; }
.guides-compact-table .col-category { width: 80px !important; }
.guides-compact-table .col-exp { width: 50px !important; }
.guides-compact-table .col-rating { width: 60px !important; }
.guides-compact-table .col-status { width: 70px !important; }
.guides-compact-table .col-actions { width: 80px !important; }

.guide-id { font-weight: 600 !important; color: #4f46e5 !important; font-size: 10px !important; }
.guide-name-compact { font-weight: 600 !important; color: #1f2937 !important; font-size: 11px !important; }
.guide-phone-compact { font-size: 10px !important; color: #059669 !important; }
.guide-category-compact { background: #f3f4f6 !important; color: #374151 !important; padding: 1px 3px !important; border-radius: 2px !important; font-size: 8px !important; }
.guide-exp-compact { font-weight: 600 !important; color: #059669 !important; font-size: 11px !important; }
.guide-rating-compact { font-size: 10px !important; color: #fbbf24 !important; }

.status-compact { padding: 2px 4px !important; border-radius: 8px !important; font-size: 8px !important; font-weight: 600 !important; text-transform: uppercase !important; }
.status-active { background: #d1fae5 !important; color: #065f46 !important; }
.status-leave { background: #fef3c7 !important; color: #92400e !important; }
.status-inactive { background: #fee2e2 !important; color: #991b1b !important; }

.actions-compact { display: flex !important; gap: 1px !important; justify-content: center !important; }
.btn-compact { width: 18px !important; height: 18px !important; border-radius: 2px !important; border: none !important; cursor: pointer !important; font-size: 8px !important; display: inline-flex !important; align-items: center !important; justify-content: center !important; text-decoration: none !important; }
.btn-view-compact { background: #10b981 !important; color: white !important; }
.btn-edit-compact { background: #3b82f6 !important; color: white !important; }
.btn-delete-compact { background: #ef4444 !important; color: white !important; }
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

    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
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
                            <option value="{{ $category->id }}" @selected(request('category_id') == $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Trạng thái</label>
                    <select name="status" class="form-select">
                        <option value="">-- Tất cả --</option>
                        <option value="active" @selected(request('status') === 'active')>Đang hoạt động</option>
                        <option value="on_leave" @selected(request('status') === 'on_leave')>Tạm nghỉ</option>
                        <option value="inactive" @selected(request('status') === 'inactive')>Ngưng hoạt động</option>
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
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="guides-compact-wrapper">
                <table class="guides-compact-table">
                    <thead>
                        <tr>
                            <th class="col-id">ID</th>
                            <th class="col-name">Họ tên</th>
                            <th class="col-phone">SĐT</th>
                            <th class="col-category">Nhóm</th>
                            <th class="col-exp">Exp</th>
                            <th class="col-rating">Rating</th>
                            <th class="col-status">Status</th>
                            <th class="col-actions">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($guides as $guide)
                            <tr>
                                <td class="col-id">
                                    <div class="guide-id">{{ $guide->code ?? 'HDV' . $guide->id }}</div>
                                </td>
                                <td class="col-name">
                                    <div class="guide-name-compact" title="{{ $guide->full_name ?? 'N/A' }}">
                                        {{ Str::limit($guide->full_name ?? 'N/A', 15) }}
                                    </div>
                                </td>
                                <td class="col-phone">
                                    <div class="guide-phone-compact">
                                        {{ $guide->phone ? Str::limit($guide->phone, 10) : 'N/A' }}
                                    </div>
                                </td>
                                <td class="col-category">
                                    @if($guide->categories->count() > 0)
                                        <span class="guide-category-compact" title="{{ $guide->categories->first()->name }}">
                                            {{ Str::limit($guide->categories->first()->name, 8) }}
                                        </span>
                                    @else
                                        <span style="color: #9ca3af; font-size: 8px;">N/A</span>
                                    @endif
                                </td>
                                <td class="col-exp">
                                    <div class="guide-exp-compact">{{ $guide->experience_years ?? 0 }}y</div>
                                </td>
                                <td class="col-rating">
                                    <div class="guide-rating-compact">
                                        ⭐ {{ number_format($guide->rating_average ?? 0, 1) }}
                                    </div>
                                </td>
                                <td class="col-status">
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
                                    <span class="status-compact {{ $statusClass }}">{{ $statusText }}</span>
                                </td>
                                <td class="col-actions">
                                    <div class="actions-compact">
                                        <a href="{{ route('admin.guides.show', $guide) }}" 
                                           class="btn-compact btn-view-compact" 
                                           title="Xem">
                                            👁
                                        </a>
                                        <a href="{{ route('admin.guides.edit', $guide) }}" 
                                           class="btn-compact btn-edit-compact" 
                                           title="Sửa">
                                            ✏
                                        </a>
                                        <form action="{{ route('admin.guides.destroy', $guide) }}" 
                                              method="POST" 
                                              style="display: inline;"
                                              onsubmit="return confirm('Xóa HDV này?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn-compact btn-delete-compact" 
                                                    title="Xóa">
                                                🗑
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8">
                                    <div class="empty-compact">
                                        <div>📋 Chưa có HDV nào</div>
                                        <a href="{{ route('admin.guides.create') }}" class="btn btn-primary btn-sm mt-2">
                                            + Thêm HDV
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            {{ $guides->links() }}
        </div>
    </div>
@endsection


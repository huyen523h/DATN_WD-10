@extends('layouts.admin')

@section('title', 'Quản lý Tours - Admin')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Tours</li>
@endsection

@section('content')
    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Tổng Tours</h6>
                            <h3 class="mb-0 fw-bold">{{ $stats['total_tours'] ?? 0 }}</h3>
                        </div>
                        <div class="bg-white bg-opacity-25 rounded-circle p-3">
                            <i class="fas fa-map-marked-alt fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Tours Hoạt động</h6>
                            <h3 class="mb-0 fw-bold">{{ $stats['active_tours'] ?? 0 }}</h3>
                        </div>
                        <div class="bg-white bg-opacity-25 rounded-circle p-3">
                            <i class="fas fa-check-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Tổng Đặt tour</h6>
                            <h3 class="mb-0 fw-bold">{{ $stats['total_bookings'] ?? 0 }}</h3>
                        </div>
                        <div class="bg-white bg-opacity-25 rounded-circle p-3">
                            <i class="fas fa-calendar-check fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Lịch khởi hành</h6>
                            <h3 class="mb-0 fw-bold">{{ $stats['total_departures'] ?? 0 }}</h3>
                        </div>
                        <div class="bg-white bg-opacity-25 rounded-circle p-3">
                            <i class="fas fa-plane-departure fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Header -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div class="mb-2 mb-md-0">
                    <h4 class="mb-1"><i class="fas fa-map-marked-alt text-primary"></i> Quản lý Tours</h4>
                    <p class="text-muted mb-0 small">Quản lý tất cả các tour du lịch trong hệ thống</p>
                </div>
                <a href="{{ route('admin.tours.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Thêm tour mới
                </a>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center justify-content-between" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-check-circle me-2"></i>
                <span>{{ session('success') }}</span>
            </div>
            @if (session('show_create_departure_cta') && session('tour_id'))
                <a href="{{ route('admin.departures.create') }}?tour_id={{ session('tour_id') }}" class="btn btn-primary btn-sm ms-3">
                    <i class="fas fa-plus"></i> Tạo lịch khởi hành
                </a>
            @endif
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Search and Filter -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.tours.index') }}" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label small fw-bold">Tìm kiếm</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="fas fa-search"></i></span>
                        <input type="text" name="search" class="form-control" placeholder="Tên tour, mô tả..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-bold">Danh mục</label>
                    <select name="category_id" class="form-select">
                        <option value="">Tất cả</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-bold">Trạng thái</label>
                    <select name="status" class="form-select">
                        <option value="">Tất cả</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Hoạt động</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Không hoạt động</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Bản nháp</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-bold">Thời gian</label>
                    <select name="duration" class="form-select">
                        <option value="">Tất cả</option>
                        <option value="1-3" {{ request('duration') == '1-3' ? 'selected' : '' }}>1-3 ngày</option>
                        <option value="4-7" {{ request('duration') == '4-7' ? 'selected' : '' }}>4-7 ngày</option>
                        <option value="8+" {{ request('duration') == '8+' ? 'selected' : '' }}>8+ ngày</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100 me-2">
                        <i class="fas fa-filter"></i> Lọc
                    </button>
                    <a href="{{ route('admin.tours.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-redo"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- <!-- Modern Table Component -->
    <x-admin.table :headers="[
            ['key' => 'id', 'label' => 'ID', 'sortable' => true],
            ['key' => 'title', 'label' => 'Tour', 'sortable' => true, 'component' => 'admin.table.tour-title'],
            ['key' => 'category', 'label' => 'Danh mục', 'sortable' => true],
            ['key' => 'price', 'label' => 'Giá', 'sortable' => true, 'component' => 'admin.table.price'],
            ['key' => 'duration', 'label' => 'Thời gian', 'sortable' => true],
            ['key' => 'status', 'label' => 'Trạng thái', 'sortable' => true, 'component' => 'admin.table.status-badge'],
            ['key' => 'bookings_count', 'label' => 'Đặt tour', 'sortable' => true],
        ]" :data="$tours->map(function ($tour) {
            return [
                'id' => $tour->id,
                'title' => [
                    'title' => $tour->title,
                    'description' => Str::limit($tour->description, 50),
                    'image' => $tour->images->count() > 0 ? Storage::url($tour->images->first()->image_url) : null,
                ],
                'category' => $tour->category->name,
                'price' => [
                    'price' => $tour->price,
                    'original_price' => $tour->old_price ?? null,
                ],
                'duration' => ($tour->duration_days ?? 'N/A') . ' ngày',
                'status' => $tour->status,
                'bookings_count' => $tour->bookings->count(),
            ];
        })" :actions="[
            [
                'custom' => true,
                'route' => 'admin.schedules.index',
                'param' => 'id',
                'icon' => 'fas fa-calendar-alt',
                'class' => 'btn-info',
                'title' => 'Xem lịch trình',
            ],
            ['action' => 'view', 'icon' => 'fas fa-eye', 'class' => 'btn-primary', 'title' => 'Xem chi tiết'],
            ['action' => 'edit', 'icon' => 'fas fa-edit', 'class' => 'btn-warning', 'title' => 'Chỉnh sửa'],
            ['action' => 'delete', 'icon' => 'fas fa-trash', 'class' => 'btn-danger', 'title' => 'Xóa'],
        ]" :searchable="true" :sortable="true"
            :filterable="true" :pagination="$tours" empty-message="Chưa có tour nào" id="tours-table">
            <!-- Custom Filters -->
            <x-slot name="filters">
                <div class="filter-grid">
                    <div class="filter-group">
                        <label class="filter-label">Danh mục</label>
                        <select name="category_id" class="filter-select">
                            <option value="">Tất cả danh mục</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ request('category_id') == $category->id ? 'selected' : '' }}>
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
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Không hoạt động
                            </option>
                            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Bản nháp</option>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label class="filter-label">Khoảng giá</label>
                        <div class="price-range">
                            <input type="number" name="min_price" class="filter-input" placeholder="Từ"
                                value="{{ request('min_price') }}">
                            <span class="range-separator">-</span>
                            <input type="number" name="max_price" class="filter-input" placeholder="Đến"
                                value="{{ request('max_price') }}">
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
    </x-admin.table> --}}






    <!-- Filters -->
    {{-- <div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-filter"></i> Bộ lọc</h5>
    </div>
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Tìm kiếm</label>
                <input type="text" name="search" class="form-control" placeholder="Tìm kiếm tour..."
                    value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Danh mục</label>
                <select name="category_id" class="form-select">
                    <option value="">Tất cả danh mục</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Trạng thái</label>
                <select name="status" class="form-select">
                    <option value="">Tất cả trạng thái</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Hoạt động</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Không hoạt động
                    </option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Bản nháp</option>
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search"></i> Tìm kiếm
                </button>
            </div>
        </form>
    </div>
</div> --}}

<!-- Tours Table -->
<div class="card shadow-sm border-0">
    <div class="card-header bg-white border-bottom">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-list text-primary"></i> Danh sách Tours</h5>
            <span class="badge bg-primary">{{ $tours->total() }} tours</span>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th></th>
                        <th>ID</th>
                        <th>Tour</th>
                        <th>Danh mục</th>
                        <th>Giá</th>
                        <th>Thời gian</th>
                        <th>Trạng thái</th>
                        <th>Đặt tour</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tours as $tour)
                            <tr>
                                <td>{{ $tour->id }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if ($tour->images->count() > 0)
                                            @php $coverImage = $tour->images->where('is_cover', true)->first() ?? $tour->images->first(); @endphp
                                            <img src="{{ Storage::url($coverImage->image_url) }}" alt="{{ $tour->title }}"
                                                class="rounded me-2" style="width: 50px; height: 50px; object-fit: cover;"
                                                onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                            <div class="bg-light rounded me-2 d-flex align-items-center justify-content-center"
                                                style="width: 50px; height: 50px; display: none;">
                                                <i class="fas fa-image text-muted"></i>
                                            </div>
                                        @else
                                            <div class="bg-light rounded me-2 d-flex align-items-center justify-content-center"
                                                style="width: 50px; height: 50px;">
                                                <i class="fas fa-image text-muted"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <strong>{{ $tour->title }}</strong>
                                            <br>
                                            <small class="text-muted">{{ Str::limit($tour->description, 50) }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-primary">{{ $tour->category->name }}</span>
                                </td>
                                <td>
                                    <strong class="text-success">{{ number_format($tour->price) }} VNĐ</strong>
                                </td>
                                <td>{{ $tour->duration_days ?? 'N/A' }} ngày</td>
                                <td>
                                    @switch($tour->status)
                                        @case('active')
                                            <span class="badge bg-success">
                                                <i class="fas fa-check-circle"></i> Hoạt động
                                            </span>
                                        @break

                                        @case('inactive')
                                            <span class="badge bg-secondary">
                                                <i class="fas fa-pause-circle"></i> Không hoạt động
                                            </span>
                                        @break

                                        @case('draft')
                                            <span class="badge bg-warning">
                                                <i class="fas fa-edit"></i> Bản nháp
                                            </span>
                                        @break

                                        @default
                                            <span class="badge bg-info">{{ $tour->status }}</span>
                                    @endswitch
                                </td>
                                <td>
                                    {{-- <span class="badge bg-info">{{ $tour->bookings->count() }}</span> --}}
                                    <span class="badge bg-info">
                                        {{ $tour->completed_bookings_count ?? 0 }}
                                    </span>
                                </td>
                                {{-- <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.schedules.index', $tour->id) }}"
                                        class="btn btn-info btn-sm">
                                        <i class="fas fa-calendar-alt"></i> Xem lịch trình
                                    </a>
                                    <a href="{{ route('admin.tours.manage', $tour) }}" class="btn btn-info btn-sm"
                                        title="Quản lý Tour">
                                        <i class="fas fa-cog"></i>
                                    </a>
                                    <a href="{{ route('admin.tours.show', $tour) }}" class="btn btn-primary btn-sm"
                                        title="Xem chi tiết">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.tours.edit', $tour) }}" class="btn btn-warning btn-sm"
                                        title="Chỉnh sửa">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.tours.destroy', $tour) }}" method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('Bạn có chắc chắn muốn xóa tour này?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Xóa">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td> --}}
                                <td>
                                    <div class="d-flex flex-column gap-1">
                                        <a href="{{ route('admin.tours.manage', $tour) }}" class="btn btn-info btn-sm"
                                            title="Quản lý Tour">
                                            <i class="fas fa-cog"></i> Quản lý
                                        </a>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('admin.schedules.index', $tour->id) }}"
                                                class="btn btn-outline-info" title="Lịch trình">
                                                <i class="fas fa-calendar-alt"></i>
                                            </a>
                                            <a href="{{ route('admin.tours.show', $tour) }}" class="btn btn-outline-primary"
                                                title="Xem chi tiết">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.tours.edit', $tour) }}" class="btn btn-outline-warning"
                                                title="Chỉnh sửa">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @if (($tour->completed_bookings_count ?? 0) == 0)
                                                <form action="{{ route('admin.tours.destroy', $tour) }}" method="POST"
                                                    class="d-inline"
                                                    onsubmit="return confirm('Bạn có chắc chắn muốn xóa tour này?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger" title="Xóa">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <button type="button" class="btn btn-outline-danger" title="Không thể xóa"
                                                    onclick="alert('Tour này đã có người đặt, bạn không thể xóa tour');">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                            </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="fas fa-box-open fa-3x mb-3"></i>
                                            <h5>Chưa có tour nào</h5>
                                            <p>Hãy tạo tour đầu tiên để bắt đầu!</p>
                                            <a href="{{ route('admin.tours.create') }}" class="btn btn-primary">
                                                <i class="fas fa-plus"></i> Thêm tour mới
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if ($tours->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $tours->links() }}
                    </div>
                @endif
            </div>
        </div>


    @endsection

    @push('styles')
        <style>
            /* Stats Cards Animation */
            .card[style*="gradient"] {
                transition: transform 0.3s ease, box-shadow 0.3s ease;
            }
            .card[style*="gradient"]:hover {
                transform: translateY(-5px);
                box-shadow: 0 10px 25px rgba(0,0,0,0.15) !important;
            }

            /* Table Improvements */
            .table thead th {
                background-color: #f8f9fa;
                font-weight: 600;
                text-transform: uppercase;
                font-size: 0.75rem;
                letter-spacing: 0.5px;
                border-bottom: 2px solid #dee2e6;
                padding: 1rem 0.75rem;
            }

            .table tbody tr {
                transition: all 0.2s ease;
            }

            .table tbody tr:hover {
                background-color: #f8f9fa;
                transform: scale(1.01);
                box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            }

            .table td {
                vertical-align: middle;
                padding: 1rem 0.75rem;
            }

            /* Image in table */
            .table img {
                transition: transform 0.3s ease;
            }
            .table tr:hover img {
                transform: scale(1.1);
            }

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






    <!-- Filters -->
    {{-- <div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-filter"></i> Bộ lọc</h5>
    </div>
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Tìm kiếm</label>
                <input type="text" name="search" class="form-control" placeholder="Tìm kiếm tour..."
                    value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Danh mục</label>
                <select name="category_id" class="form-select">
                    <option value="">Tất cả danh mục</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Trạng thái</label>
                <select name="status" class="form-select">
                    <option value="">Tất cả trạng thái</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Hoạt động</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Không hoạt động
                    </option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Bản nháp</option>
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search"></i> Tìm kiếm
                </button>
            </div>
        </form>
    </div>
</div> --}}

    @section('content')

        <!-- Tours Table -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-list"></i> Danh sách Tours</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tour</th>
                                <th>Danh mục</th>
                                <th>Giá</th>
                                <th>Thời gian</th>
                                <th>Trạng thái</th>
                                <th>Đặt tour</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($tours as $tour)
                                <tr>
                                    <td>{{ $tour->id }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if ($tour->images->count() > 0)
                                                @php $coverImage = $tour->images->where('is_cover', true)->first() ?? $tour->images->first(); @endphp
                                                <img src="{{ Storage::url($coverImage->image_url) }}"
                                                    alt="{{ $tour->title }}" class="rounded me-2"
                                                    style="width: 50px; height: 50px; object-fit: cover;"
                                                    onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                                <div class="bg-light rounded me-2 d-flex align-items-center justify-content-center"
                                                    style="width: 50px; height: 50px; display: none;">
                                                    <i class="fas fa-image text-muted"></i>
                                                </div>
                                            @else
                                                <div class="bg-light rounded me-2 d-flex align-items-center justify-content-center"
                                                    style="width: 50px; height: 50px;">
                                                    <i class="fas fa-image text-muted"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <strong>{{ $tour->title }}</strong>
                                                <br>
                                                <small class="text-muted">{{ Str::limit($tour->description, 50) }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">{{ $tour->category->name }}</span>
                                    </td>
                                    <td>
                                        <strong class="text-success">{{ number_format($tour->price) }} VNĐ</strong>
                                    </td>
                                    <td>{{ $tour->duration_days ?? 'N/A' }} ngày</td>
                                    <td>
                                        @switch($tour->status)
                                            @case('active')
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check-circle"></i> Hoạt động
                                                </span>
                                            @break

                                            @case('inactive')
                                                <span class="badge bg-secondary">
                                                    <i class="fas fa-pause-circle"></i> Không hoạt động
                                                </span>
                                            @break

                                            @case('draft')
                                                <span class="badge bg-warning">
                                                    <i class="fas fa-edit"></i> Bản nháp
                                                </span>
                                            @break

                                            @default
                                                <span class="badge bg-info">{{ $tour->status }}</span>
                                        @endswitch
                                    </td>
                                    <td>
                                        {{-- <span class="badge bg-info">{{ $tour->bookings->count() }}</span> --}}
                                        <span class="badge bg-info">
                                            {{ $tour->completed_bookings_count ?? 0 }}
                                        </span>
                                    </td>

                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.schedules.index', $tour->id) }}"
                                                class="btn btn-info btn-sm">
                                                <i class="fas fa-calendar-alt"></i> Xem lịch trình
                                            </a>

                                            <a href="{{ route('admin.tours.show', $tour) }}" class="btn btn-primary btn-sm"
                                                title="Xem chi tiết">
                                                <i class="fas fa-eye"></i>
                                            </a>

                                            <a href="{{ route('admin.tours.edit', $tour) }}" class="btn btn-warning btn-sm"
                                                title="Chỉnh sửa">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            @if (($tour->completed_bookings_count ?? 0) == 0)
                                                {{-- CHƯA CÓ AI ĐẶT -> ĐƯỢC XÓA --}}
                                                <form action="{{ route('admin.tours.destroy', $tour) }}" method="POST"
                                                    class="d-inline"
                                                    onsubmit="return confirm('Bạn có chắc chắn muốn xóa tour này?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" title="Xóa">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @else
                                                {{-- ĐÃ CÓ NGƯỜI ĐẶT -> CHỈ HIỆN ALERT, KHÔNG GỬI REQUEST XOÁ --}}
                                                <button type="button" class="btn btn-danger btn-sm" title="Không thể xóa"
                                                    onclick="alert('Tour này đã có người đặt, bạn không thể xóa tour');">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>

                                </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-5">
                                            <div class="text-muted">
                                                <i class="fas fa-box-open fa-3x mb-3"></i>
                                                <h5>Chưa có tour nào</h5>
                                                <p>Hãy tạo tour đầu tiên để bắt đầu!</p>
                                                <a href="{{ route('admin.tours.create') }}" class="btn btn-primary">
                                                    <i class="fas fa-plus"></i> Thêm tour mới
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if ($tours->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $tours->links() }}
                        </div>
                    @endif
                </div>
            </div>


        @endsection
        @push('styles')
            <style>
                /* Không cho custom CSS phá layout của table bootstrap */
                table.table td,
                table.table th {
                    border-width: 0 !important;
                    border-style: none !important;
                }

                table.table tbody tr {
                    position: static !important;
                    transition: none !important;
                }

                /* Set width cột ID cho rõ ràng */
                table.table th:first-child,
                table.table td:first-child {
                    width: 60px !important;
                    text-align: center !important;
                    white-space: nowrap;
                }

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
        </div>
        </div>

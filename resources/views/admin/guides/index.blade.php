@extends('layouts.admin')

@section('title', 'Quản lý HDV')

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
        <div class="card-body table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Mã</th>
                        <th>Họ tên</th>
                        <th>Liên hệ</th>
                        <th>Nhóm</th>
                        <th>Kinh nghiệm</th>
                        <th>Đánh giá</th>
                        <th>Trạng thái</th>
                        <th class="text-end">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($guides as $guide)
                        <tr>
                            <td>
                                <span class="fw-semibold">{{ $guide->code }}</span>
                                <div class="text-muted">#{{ $guide->id }}</div>
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $guide->full_name }}</div>
                                <small class="text-muted">{{ $guide->primary_language }}</small>
                            </td>
                            <td>
                                <div>{{ $guide->phone }}</div>
                                <small class="text-muted">{{ $guide->email }}</small>
                            </td>
                            <td>
                                @forelse ($guide->categories as $category)
                                    <span class="badge bg-light text-dark me-1">{{ $category->name }}</span>
                                @empty
                                    <span class="text-muted">Chưa phân nhóm</span>
                                @endforelse
                            </td>
                            <td>{{ $guide->experience_years ?? 0 }} năm</td>
                            <td>
                                <span class="badge bg-warning text-dark">
                                    <i class="fas fa-star"></i> {{ number_format($guide->rating_average, 1) }}
                                </span>
                                <small class="text-muted d-block">{{ $guide->rating_count }} lượt</small>
                            </td>
                            <td>
                                @php
                                    $statusMap = [
                                        'active' => ['label' => 'Đang hoạt động', 'class' => 'bg-success'],
                                        'on_leave' => ['label' => 'Tạm nghỉ', 'class' => 'bg-warning text-dark'],
                                        'inactive' => ['label' => 'Ngưng hoạt động', 'class' => 'bg-secondary'],
                                        'available' => ['label' => 'Đang hoạt động', 'class' => 'bg-success'], // Alias for active
                                    ];
                                    $guideStatus = $guide->status ?? 'active';
                                    $status = $statusMap[$guideStatus] ?? $statusMap['active'];
                                @endphp
                                <span class="badge {{ $status['class'] }}">{{ $status['label'] }}</span>
                            </td>
                            <td class="text-end">
                                <div class="btn-group">
                                    <a href="{{ route('admin.guides.show', $guide) }}" class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.guides.edit', $guide) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.guides.destroy', $guide) }}" method="POST"
                                        onsubmit="return confirm('Xác nhận xoá hướng dẫn viên này?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">Chưa có hướng dẫn viên nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $guides->links() }}
        </div>
    </div>
@endsection


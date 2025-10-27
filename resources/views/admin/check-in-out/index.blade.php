@extends('layouts.admin')

@section('title', 'Quản lý Check-in/Check-out')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-clock me-2"></i>Quản lý Check-in/Check-out
            </h1>
            <p class="text-muted mb-0">Theo dõi và quản lý check-in/check-out của khách hàng</p>
        </div>
        <div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
                <i class="fas fa-plus me-2"></i>Thêm mới
            </button>
            <button class="btn btn-success" onclick="refreshData()">
                <i class="fas fa-sync-alt me-2"></i>Làm mới
            </button>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Tổng hôm nay
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="totalToday">
                                {{ $stats['total_today'] }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-day fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Check-in hôm nay
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="checkInsToday">
                                {{ $stats['check_ins_today'] }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-sign-in-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Check-out hôm nay
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="checkOutsToday">
                                {{ $stats['check_outs_today'] }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-sign-out-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Chờ xác nhận
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="pendingCount">
                                {{ $stats['pending_count'] }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-filter me-2"></i>Bộ lọc
            </h6>
        </div>
        <div class="card-body">
            <form id="filterForm" method="GET">
                <div class="row">
                    <div class="col-md-3">
                        <label class="form-label">Loại</label>
                        <select name="type" class="form-select">
                            <option value="">Tất cả</option>
                            <option value="check_in" {{ request('type') == 'check_in' ? 'selected' : '' }}>Check-in</option>
                            <option value="check_out" {{ request('type') == 'check_out' ? 'selected' : '' }}>Check-out</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Trạng thái</label>
                        <select name="status" class="form-select">
                            <option value="">Tất cả</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ xác nhận</option>
                            <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Từ ngày</label>
                        <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Đến ngày</label>
                        <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Tìm kiếm</label>
                        <input type="text" name="search" class="form-control" placeholder="Tên, email, mã booking..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search me-2"></i>Tìm kiếm
                        </button>
                        <a href="{{ route('admin.check-in-out.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>Xóa bộ lọc
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Data Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-list me-2"></i>Danh sách Check-in/Check-out
            </h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Khách hàng</th>
                            <th>Tour</th>
                            <th>Loại</th>
                            <th>Thời gian</th>
                            <th>Địa điểm</th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($checkInOuts as $checkInOut)
                        <tr>
                            <td>{{ $checkInOut->id }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                        {{ substr($checkInOut->user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold">{{ $checkInOut->user->name }}</div>
                                        <small class="text-muted">{{ $checkInOut->user->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <div class="fw-bold">{{ $checkInOut->booking->tour->title }}</div>
                                    <small class="text-muted">Mã: {{ $checkInOut->booking->booking_code }}</small>
                                </div>
                            </td>
                            <td>
                                <span class="badge {{ $checkInOut->isCheckIn() ? 'bg-success' : 'bg-info' }}">
                                    <i class="fas {{ $checkInOut->isCheckIn() ? 'fa-sign-in-alt' : 'fa-sign-out-alt' }} me-1"></i>
                                    {{ $checkInOut->type_label }}
                                </span>
                            </td>
                            <td>
                                <div>{{ $checkInOut->formatted_check_time }}</div>
                                @if($checkInOut->verified_at)
                                <small class="text-muted">Xác nhận: {{ $checkInOut->verified_at->format('d/m/Y H:i') }}</small>
                                @endif
                            </td>
                            <td>
                                <div>{{ $checkInOut->location }}</div>
                                @if($checkInOut->latitude && $checkInOut->longitude)
                                <small class="text-muted">
                                    <i class="fas fa-map-marker-alt me-1"></i>
                                    {{ number_format($checkInOut->latitude, 4) }}, {{ number_format($checkInOut->longitude, 4) }}
                                </small>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-{{ $checkInOut->status_badge }}">
                                    @switch($checkInOut->status)
                                        @case('pending')
                                            <i class="fas fa-clock me-1"></i>Chờ xác nhận
                                            @break
                                        @case('confirmed')
                                            <i class="fas fa-check me-1"></i>Đã xác nhận
                                            @break
                                        @case('cancelled')
                                            <i class="fas fa-times me-1"></i>Đã hủy
                                            @break
                                    @endswitch
                                </span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.check-in-out.show', $checkInOut) }}" class="btn btn-sm btn-info" title="Xem chi tiết">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.check-in-out.edit', $checkInOut) }}" class="btn btn-sm btn-warning" title="Chỉnh sửa">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if($checkInOut->isPending())
                                    <button class="btn btn-sm btn-success" onclick="confirmCheckInOut({{ $checkInOut->id }})" title="Xác nhận">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" onclick="cancelCheckInOut({{ $checkInOut->id }})" title="Hủy">
                                        <i class="fas fa-times"></i>
                                    </button>
                                    @endif
                                    <button class="btn btn-sm btn-danger" onclick="deleteCheckInOut({{ $checkInOut->id }})" title="Xóa">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-inbox fa-3x mb-3"></i>
                                    <p>Không có dữ liệu check-in/check-out</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $checkInOuts->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Thêm Check-in/Check-out mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="createForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">Khách hàng <span class="text-danger">*</span></label>
                            <select name="user_id" class="form-select" required>
                                <option value="">Chọn khách hàng</option>
                                @foreach(\App\Models\User::whereHas('roles', function($q) { $q->where('name', 'customer'); })->get() as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Booking <span class="text-danger">*</span></label>
                            <select name="booking_id" class="form-select" required>
                                <option value="">Chọn booking</option>
                                @foreach(\App\Models\Booking::with('tour')->where('status', 'confirmed')->get() as $booking)
                                <option value="{{ $booking->id }}">{{ $booking->booking_code }} - {{ $booking->tour->title }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label class="form-label">Loại <span class="text-danger">*</span></label>
                            <select name="type" class="form-select" required>
                                <option value="">Chọn loại</option>
                                <option value="check_in">Check-in</option>
                                <option value="check_out">Check-out</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Thời gian <span class="text-danger">*</span></label>
                            <input type="datetime-local" name="check_time" class="form-control" required>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label class="form-label">Vĩ độ</label>
                            <input type="number" name="latitude" class="form-control" step="0.00000001" placeholder="21.0285">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Kinh độ</label>
                            <input type="number" name="longitude" class="form-control" step="0.00000001" placeholder="105.8542">
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <label class="form-label">Địa điểm</label>
                            <input type="text" name="location" class="form-control" placeholder="Nhập địa điểm">
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <label class="form-label">Ghi chú</label>
                            <textarea name="notes" class="form-control" rows="3" placeholder="Nhập ghi chú"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Tạo mới</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// Auto-refresh data every 30 seconds
setInterval(refreshData, 30000);

function refreshData() {
    location.reload();
}

function confirmCheckInOut(id) {
    if (confirm('Bạn có chắc chắn muốn xác nhận check-in/check-out này?')) {
        fetch(`/admin/check-in-out/${id}/confirm`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('success', data.message);
                location.reload();
            } else {
                showAlert('error', data.message);
            }
        })
        .catch(error => {
            showAlert('error', 'Có lỗi xảy ra');
        });
    }
}

function cancelCheckInOut(id) {
    if (confirm('Bạn có chắc chắn muốn hủy check-in/check-out này?')) {
        fetch(`/admin/check-in-out/${id}/cancel`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('success', data.message);
                location.reload();
            } else {
                showAlert('error', data.message);
            }
        })
        .catch(error => {
            showAlert('error', 'Có lỗi xảy ra');
        });
    }
}

function deleteCheckInOut(id) {
    if (confirm('Bạn có chắc chắn muốn xóa check-in/check-out này?')) {
        fetch(`/admin/check-in-out/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('success', data.message);
                location.reload();
            } else {
                showAlert('error', data.message);
            }
        })
        .catch(error => {
            showAlert('error', 'Có lỗi xảy ra');
        });
    }
}

// Create form submission
document.getElementById('createForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const data = Object.fromEntries(formData);
    
    fetch('/admin/check-in-out', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', data.message);
            location.reload();
        } else {
            showAlert('error', data.message);
        }
    })
    .catch(error => {
        showAlert('error', 'Có lỗi xảy ra');
    });
});

function showAlert(type, message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.insertBefore(alertDiv, document.body.firstChild);
    
    setTimeout(() => {
        alertDiv.remove();
    }, 5000);
}
</script>
@endpush

@push('styles')
<style>
.avatar-sm {
    width: 40px;
    height: 40px;
    font-size: 14px;
}

.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}

.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}

.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}

.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}

.card {
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15) !important;
}

.btn-group .btn {
    margin-right: 2px;
}

.table th {
    background-color: #f8f9fc;
    border-color: #e3e6f0;
    color: #5a5c69;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.85rem;
}

.table td {
    vertical-align: middle;
}

.badge {
    font-size: 0.75rem;
    font-weight: 600;
}
</style>
@endpush

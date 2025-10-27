@extends('layouts.admin')

@section('title', 'Chi tiết Check-in/Check-out')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-eye me-2"></i>Chi tiết Check-in/Check-out
            </h1>
            <p class="text-muted mb-0">Thông tin chi tiết về check-in/check-out #{{ $checkInOut->id }}</p>
        </div>
        <div>
            <a href="{{ route('admin.check-in-out.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Quay lại
            </a>
            <a href="{{ route('admin.check-in-out.edit', $checkInOut) }}" class="btn btn-warning">
                <i class="fas fa-edit me-2"></i>Chỉnh sửa
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Main Information -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle me-2"></i>Thông tin cơ bản
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">ID:</label>
                                <p class="text-muted">{{ $checkInOut->id }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Loại:</label>
                                <p>
                                    <span class="badge {{ $checkInOut->isCheckIn() ? 'bg-success' : 'bg-info' }} fs-6">
                                        <i class="fas {{ $checkInOut->isCheckIn() ? 'fa-sign-in-alt' : 'fa-sign-out-alt' }} me-1"></i>
                                        {{ $checkInOut->type_label }}
                                    </span>
                                </p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Thời gian:</label>
                                <p class="text-muted">
                                    <i class="fas fa-clock me-1"></i>{{ $checkInOut->formatted_check_time }}
                                </p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Trạng thái:</label>
                                <p>
                                    <span class="badge bg-{{ $checkInOut->status_badge }} fs-6">
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
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Địa điểm:</label>
                                <p class="text-muted">
                                    <i class="fas fa-map-marker-alt me-1"></i>{{ $checkInOut->location }}
                                </p>
                            </div>
                            @if($checkInOut->latitude && $checkInOut->longitude)
                            <div class="mb-3">
                                <label class="form-label fw-bold">Tọa độ GPS:</label>
                                <p class="text-muted">
                                    <i class="fas fa-globe me-1"></i>
                                    {{ number_format($checkInOut->latitude, 6) }}, {{ number_format($checkInOut->longitude, 6) }}
                                </p>
                            </div>
                            @endif
                            @if($checkInOut->verified_by)
                            <div class="mb-3">
                                <label class="form-label fw-bold">Xác nhận bởi:</label>
                                <p class="text-muted">
                                    <i class="fas fa-user-check me-1"></i>{{ $checkInOut->verified_by }}
                                </p>
                            </div>
                            @endif
                            @if($checkInOut->verified_at)
                            <div class="mb-3">
                                <label class="form-label fw-bold">Thời gian xác nhận:</label>
                                <p class="text-muted">
                                    <i class="fas fa-calendar-check me-1"></i>{{ $checkInOut->verified_at->format('d/m/Y H:i:s') }}
                                </p>
                            </div>
                            @endif
                        </div>
                    </div>
                    @if($checkInOut->notes)
                    <div class="mb-3">
                        <label class="form-label fw-bold">Ghi chú:</label>
                        <div class="bg-light p-3 rounded">
                            <p class="mb-0 text-muted">{{ $checkInOut->notes }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Customer Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-user me-2"></i>Thông tin khách hàng
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-3">
                                <div class="avatar-lg bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3">
                                    {{ substr($checkInOut->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <h5 class="mb-0">{{ $checkInOut->user->name }}</h5>
                                    <p class="text-muted mb-0">{{ $checkInOut->user->email }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-2">
                                <label class="form-label fw-bold">Số điện thoại:</label>
                                <p class="text-muted">{{ $checkInOut->user->phone ?: 'Chưa cập nhật' }}</p>
                            </div>
                            <div class="mb-2">
                                <label class="form-label fw-bold">Địa chỉ:</label>
                                <p class="text-muted">{{ $checkInOut->user->address ?: 'Chưa cập nhật' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tour Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-map-marked-alt me-2"></i>Thông tin tour
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h5 class="mb-2">{{ $checkInOut->booking->tour->title }}</h5>
                            <p class="text-muted mb-3">{{ Str::limit($checkInOut->booking->tour->description, 200) }}</p>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-2">
                                        <label class="form-label fw-bold">Mã booking:</label>
                                        <p class="text-muted">{{ $checkInOut->booking->booking_code }}</p>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label fw-bold">Ngày khởi hành:</label>
                                        <p class="text-muted">{{ \Carbon\Carbon::parse($checkInOut->booking->departure_date)->format('d/m/Y') }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-2">
                                        <label class="form-label fw-bold">Số người:</label>
                                        <p class="text-muted">
                                            {{ $checkInOut->booking->adults }} người lớn
                                            @if($checkInOut->booking->children > 0)
                                            , {{ $checkInOut->booking->children }} trẻ em
                                            @endif
                                            @if($checkInOut->booking->infants > 0)
                                            , {{ $checkInOut->booking->infants }} em bé
                                            @endif
                                        </p>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label fw-bold">Tổng tiền:</label>
                                        <p class="text-muted fw-bold text-success">{{ number_format($checkInOut->booking->total_amount) }} VNĐ</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            @if($checkInOut->booking->tour->images->count() > 0)
                            <img src="{{ asset('storage/' . $checkInOut->booking->tour->images->first()->image_path) }}" 
                                 alt="{{ $checkInOut->booking->tour->title }}" 
                                 class="img-fluid rounded">
                            @else
                            <div class="bg-light d-flex align-items-center justify-content-center rounded" style="height: 200px;">
                                <i class="fas fa-image fa-3x text-muted"></i>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions Sidebar -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-cogs me-2"></i>Thao tác
                    </h6>
                </div>
                <div class="card-body">
                    @if($checkInOut->isPending())
                    <div class="d-grid gap-2 mb-3">
                        <button class="btn btn-success" onclick="confirmCheckInOut({{ $checkInOut->id }})">
                            <i class="fas fa-check me-2"></i>Xác nhận
                        </button>
                        <button class="btn btn-danger" onclick="cancelCheckInOut({{ $checkInOut->id }})">
                            <i class="fas fa-times me-2"></i>Hủy
                        </button>
                    </div>
                    @endif
                    
                    <div class="d-grid gap-2 mb-3">
                        <a href="{{ route('admin.check-in-out.edit', $checkInOut) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-2"></i>Chỉnh sửa
                        </a>
                        <button class="btn btn-danger" onclick="deleteCheckInOut({{ $checkInOut->id }})">
                            <i class="fas fa-trash me-2"></i>Xóa
                        </button>
                    </div>

                    <hr>

                    <div class="text-center">
                        <h6 class="text-muted mb-3">Thông tin bổ sung</h6>
                        <div class="row text-center">
                            <div class="col-6">
                                <div class="border-end">
                                    <h5 class="text-primary mb-0">{{ $checkInOut->created_at->format('d/m') }}</h5>
                                    <small class="text-muted">Tạo lúc</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <h5 class="text-success mb-0">{{ $checkInOut->updated_at->format('d/m') }}</h5>
                                <small class="text-muted">Cập nhật</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Location Map -->
            @if($checkInOut->latitude && $checkInOut->longitude)
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-map me-2"></i>Vị trí
                    </h6>
                </div>
                <div class="card-body">
                    <div id="map" style="height: 300px; width: 100%;"></div>
                    <div class="mt-2 text-center">
                        <a href="https://www.google.com/maps?q={{ $checkInOut->latitude }},{{ $checkInOut->longitude }}" 
                           target="_blank" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-external-link-alt me-1"></i>Mở Google Maps
                        </a>
                    </div>
                </div>
            </div>
            @endif

            <!-- Metadata -->
            @if($checkInOut->metadata)
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-database me-2"></i>Dữ liệu bổ sung
                    </h6>
                </div>
                <div class="card-body">
                    <pre class="bg-light p-3 rounded"><code>{{ json_encode($checkInOut->metadata, JSON_PRETTY_PRINT) }}</code></pre>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
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
                window.location.href = '/admin/check-in-out';
            } else {
                showAlert('error', data.message);
            }
        })
        .catch(error => {
            showAlert('error', 'Có lỗi xảy ra');
        });
    }
}

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

// Initialize map if coordinates exist
@if($checkInOut->latitude && $checkInOut->longitude)
function initMap() {
    const location = { lat: {{ $checkInOut->latitude }}, lng: {{ $checkInOut->longitude }} };
    const map = new google.maps.Map(document.getElementById("map"), {
        zoom: 15,
        center: location,
    });
    const marker = new google.maps.Marker({
        position: location,
        map: map,
        title: "{{ $checkInOut->location }}"
    });
}
@endif
</script>

@if($checkInOut->latitude && $checkInOut->longitude)
<script async defer src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&callback=initMap"></script>
@endif
@endpush

@push('styles')
<style>
.avatar-lg {
    width: 60px;
    height: 60px;
    font-size: 24px;
}

.card {
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15) !important;
}

.badge {
    font-size: 0.875rem;
    font-weight: 600;
}

pre {
    font-size: 0.875rem;
    max-height: 200px;
    overflow-y: auto;
}

#map {
    border-radius: 0.375rem;
}
</style>
@endpush

@extends('layouts.admin')

@section('title', 'Chỉnh sửa Check-in/Check-out')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-edit me-2"></i>Chỉnh sửa Check-in/Check-out
            </h1>
            <p class="text-muted mb-0">Chỉnh sửa thông tin check-in/check-out #{{ $checkInOut->id }}</p>
        </div>
        <div>
            <a href="{{ route('admin.check-in-out.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Quay lại
            </a>
            <a href="{{ route('admin.check-in-out.show', $checkInOut) }}" class="btn btn-info">
                <i class="fas fa-eye me-2"></i>Xem chi tiết
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-edit me-2"></i>Thông tin cơ bản
                    </h6>
                </div>
                <div class="card-body">
                    <form id="editForm">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Loại <span class="text-danger">*</span></label>
                                    <select name="type" class="form-select" required>
                                        <option value="check_in" {{ $checkInOut->type == 'check_in' ? 'selected' : '' }}>Check-in</option>
                                        <option value="check_out" {{ $checkInOut->type == 'check_out' ? 'selected' : '' }}>Check-out</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Trạng thái <span class="text-danger">*</span></label>
                                    <select name="status" class="form-select" required>
                                        <option value="pending" {{ $checkInOut->status == 'pending' ? 'selected' : '' }}>Chờ xác nhận</option>
                                        <option value="confirmed" {{ $checkInOut->status == 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
                                        <option value="cancelled" {{ $checkInOut->status == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Thời gian <span class="text-danger">*</span></label>
                                    <input type="datetime-local" name="check_time" class="form-control" 
                                           value="{{ $checkInOut->check_time->format('Y-m-d\TH:i') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Địa điểm</label>
                                    <input type="text" name="location" class="form-control" 
                                           value="{{ $checkInOut->location }}" placeholder="Nhập địa điểm">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Vĩ độ</label>
                                    <input type="number" name="latitude" class="form-control" 
                                           step="0.00000001" value="{{ $checkInOut->latitude }}" 
                                           placeholder="21.0285">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Kinh độ</label>
                                    <input type="number" name="longitude" class="form-control" 
                                           step="0.00000001" value="{{ $checkInOut->longitude }}" 
                                           placeholder="105.8542">
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Ghi chú</label>
                            <textarea name="notes" class="form-control" rows="4" 
                                      placeholder="Nhập ghi chú">{{ $checkInOut->notes }}</textarea>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.check-in-out.show', $checkInOut) }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Hủy
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Cập nhật
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Current Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle me-2"></i>Thông tin hiện tại
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Khách hàng:</label>
                        <p class="text-muted">{{ $checkInOut->user->name }} ({{ $checkInOut->user->email }})</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tour:</label>
                        <p class="text-muted">{{ $checkInOut->booking->tour->title }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Mã booking:</label>
                        <p class="text-muted">{{ $checkInOut->booking->booking_code }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tạo lúc:</label>
                        <p class="text-muted">{{ $checkInOut->created_at->format('d/m/Y H:i:s') }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Cập nhật lần cuối:</label>
                        <p class="text-muted">{{ $checkInOut->updated_at->format('d/m/Y H:i:s') }}</p>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-bolt me-2"></i>Thao tác nhanh
                    </h6>
                </div>
                <div class="card-body">
                    @if($checkInOut->isPending())
                    <div class="d-grid gap-2 mb-3">
                        <button class="btn btn-success" onclick="confirmCheckInOut({{ $checkInOut->id }})">
                            <i class="fas fa-check me-2"></i>Xác nhận ngay
                        </button>
                        <button class="btn btn-danger" onclick="cancelCheckInOut({{ $checkInOut->id }})">
                            <i class="fas fa-times me-2"></i>Hủy ngay
                        </button>
                    </div>
                    @endif

                    <div class="d-grid gap-2">
                        <button class="btn btn-outline-primary" onclick="getCurrentLocation()">
                            <i class="fas fa-location-arrow me-2"></i>Lấy vị trí hiện tại
                        </button>
                        <button class="btn btn-outline-secondary" onclick="resetForm()">
                            <i class="fas fa-undo me-2"></i>Khôi phục mặc định
                        </button>
                    </div>
                </div>
            </div>

            <!-- Location Map -->
            @if($checkInOut->latitude && $checkInOut->longitude)
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-map me-2"></i>Vị trí hiện tại
                    </h6>
                </div>
                <div class="card-body">
                    <div id="map" style="height: 250px; width: 100%;"></div>
                    <div class="mt-2 text-center">
                        <button class="btn btn-sm btn-outline-primary" onclick="centerMap()">
                            <i class="fas fa-crosshairs me-1"></i>Định vị lại
                        </button>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let map;
let marker;

// Form submission
document.getElementById('editForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const data = Object.fromEntries(formData);
    
    fetch(`/admin/check-in-out/{{ $checkInOut->id }}`, {
        method: 'PUT',
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
            setTimeout(() => {
                window.location.href = '/admin/check-in-out/{{ $checkInOut->id }}';
            }, 1500);
        } else {
            showAlert('error', data.message);
        }
    })
    .catch(error => {
        showAlert('error', 'Có lỗi xảy ra');
    });
});

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

function getCurrentLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function(position) {
                document.querySelector('input[name="latitude"]').value = position.coords.latitude;
                document.querySelector('input[name="longitude"]').value = position.coords.longitude;
                
                // Update map if exists
                if (map) {
                    const newLocation = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude
                    };
                    map.setCenter(newLocation);
                    marker.setPosition(newLocation);
                }
                
                showAlert('success', 'Đã lấy vị trí hiện tại thành công');
            },
            function(error) {
                showAlert('error', 'Không thể lấy vị trí hiện tại: ' + error.message);
            }
        );
    } else {
        showAlert('error', 'Trình duyệt không hỗ trợ định vị');
    }
}

function resetForm() {
    if (confirm('Bạn có chắc chắn muốn khôi phục về giá trị mặc định?')) {
        location.reload();
    }
}

function centerMap() {
    if (map) {
        const lat = parseFloat(document.querySelector('input[name="latitude"]').value);
        const lng = parseFloat(document.querySelector('input[name="longitude"]').value);
        
        if (!isNaN(lat) && !isNaN(lng)) {
            const location = { lat: lat, lng: lng };
            map.setCenter(location);
            marker.setPosition(location);
        }
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

// Initialize map
@if($checkInOut->latitude && $checkInOut->longitude)
function initMap() {
    const location = { lat: {{ $checkInOut->latitude }}, lng: {{ $checkInOut->longitude }} };
    map = new google.maps.Map(document.getElementById("map"), {
        zoom: 15,
        center: location,
    });
    marker = new google.maps.Marker({
        position: location,
        map: map,
        title: "{{ $checkInOut->location }}",
        draggable: true
    });

    // Update coordinates when marker is dragged
    marker.addListener('dragend', function() {
        const position = marker.getPosition();
        document.querySelector('input[name="latitude"]').value = position.lat();
        document.querySelector('input[name="longitude"]').value = position.lng();
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
.card {
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15) !important;
}

#map {
    border-radius: 0.375rem;
}

.form-label {
    font-weight: 600;
    color: #5a5c69;
}

.btn {
    border-radius: 0.375rem;
}

.alert {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 9999;
    min-width: 300px;
}
</style>
@endpush

@extends('layouts.admin')

@section('title', 'Tạo Check-in/Check-out mới')

@section('content')
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-plus me-2"></i>Tạo Check-in/Check-out mới
            </h1>
            <p class="text-muted mb-0">Thêm mới check-in/check-out cho khách hàng</p>
        </div>
        <div>
            <a href="{{ route('admin.check-in-out.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Quay lại
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-plus me-2"></i>Thông tin cơ bản
                    </h6>
                </div>
                <div class="card-body">
                    <form id="createForm" action="{{ route('admin.check-in-out.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Khách hàng <span class="text-danger">*</span></label>
                                    <select name="user_id" class="form-select" required>
                                        <option value="">Chọn khách hàng</option>
                                        @foreach(\App\Models\User::whereHas('roles', function($q) { $q->where('name', 'customer'); })->get() as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Booking <span class="text-danger">*</span></label>
                                    <select name="booking_id" class="form-select" required>
                                        <option value="">Chọn booking</option>
                                        @foreach($bookings as $booking)
                                        <option value="{{ $booking->id }}">{{ $booking->booking_code }} - {{ $booking->tour->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Loại <span class="text-danger">*</span></label>
                                    <select name="type" class="form-select" required>
                                        <option value="">Chọn loại</option>
                                        <option value="check_in">Check-in</option>
                                        <option value="check_out">Check-out</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Thời gian <span class="text-danger">*</span></label>
                                    <input type="datetime-local" name="check_time" class="form-control" 
                                           value="{{ now()->format('Y-m-d\TH:i') }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Vĩ độ</label>
                                    <input type="number" name="latitude" class="form-control" 
                                           step="0.00000001" placeholder="21.0285">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Kinh độ</label>
                                    <input type="number" name="longitude" class="form-control" 
                                           step="0.00000001" placeholder="105.8542">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label class="form-label">Địa điểm</label>
                                    <input type="text" name="location" class="form-control" 
                                           placeholder="Nhập địa điểm">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">&nbsp;</label>
                                    <button type="button" class="btn btn-outline-primary w-100" onclick="getCurrentLocation()">
                                        <i class="fas fa-location-arrow me-2"></i>Lấy vị trí hiện tại
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Ghi chú</label>
                            <textarea name="notes" class="form-control" rows="4" 
                                      placeholder="Nhập ghi chú"></textarea>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.check-in-out.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Hủy
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Tạo mới
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Help Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-question-circle me-2"></i>Hướng dẫn
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="text-primary">Check-in:</h6>
                        <p class="text-muted small">Ghi nhận thời điểm khách hàng bắt đầu tham gia tour</p>
                    </div>
                    <div class="mb-3">
                        <h6 class="text-info">Check-out:</h6>
                        <p class="text-muted small">Ghi nhận thời điểm khách hàng kết thúc tour</p>
                    </div>
                    <div class="mb-3">
                        <h6 class="text-warning">Vị trí GPS:</h6>
                        <p class="text-muted small">Tọa độ GPS giúp xác minh vị trí thực tế của khách hàng</p>
                    </div>
                    <div class="mb-3">
                        <h6 class="text-success">Ghi chú:</h6>
                        <p class="text-muted small">Thông tin bổ sung về check-in/check-out</p>
                    </div>
                </div>
            </div>

            <!-- Location Map -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-map me-2"></i>Bản đồ vị trí
                    </h6>
                </div>
                <div class="card-body">
                    <div id="map" style="height: 300px; width: 100%;"></div>
                    <div class="mt-2 text-center">
                        <button class="btn btn-sm btn-outline-primary" onclick="centerMap()">
                            <i class="fas fa-crosshairs me-1"></i>Định vị lại
                        </button>
                    </div>
                </div>
            </div>

            <!-- Recent Check-ins -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-history me-2"></i>Check-in/out gần đây
                    </h6>
                </div>
                <div class="card-body">
                    @php
                        $recentCheckIns = \App\Models\CheckInOut::with(['user', 'booking.tour'])
                            ->orderBy('created_at', 'desc')
                            ->limit(5)
                            ->get();
                    @endphp
                    
                    @forelse($recentCheckIns as $recent)
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar-sm bg-{{ $recent->isCheckIn() ? 'success' : 'info' }} text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                            <i class="fas {{ $recent->isCheckIn() ? 'fa-sign-in-alt' : 'fa-sign-out-alt' }}"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-bold">{{ $recent->user->name }}</div>
                            <small class="text-muted">{{ $recent->booking->tour->title }}</small>
                        </div>
                        <div class="text-end">
                            <small class="text-muted">{{ $recent->created_at->format('d/m H:i') }}</small>
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-muted">
                        <i class="fas fa-inbox fa-2x mb-2"></i>
                        <p>Chưa có dữ liệu</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
let map;
let marker;

// Form submission - Allow normal form submit
// JavaScript validation can be added here if needed

function getCurrentLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function(position) {
                document.querySelector('input[name="latitude"]').value = position.coords.latitude;
                document.querySelector('input[name="longitude"]').value = position.coords.longitude;
                
                // Update map
                if (map) {
                    const newLocation = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude
                    };
                    map.setCenter(newLocation);
                    if (marker) {
                        marker.setPosition(newLocation);
                    } else {
                        marker = new google.maps.Marker({
                            position: newLocation,
                            map: map,
                            title: "Vị trí hiện tại",
                            draggable: true
                        });
                    }
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

function centerMap() {
    if (map) {
        const lat = parseFloat(document.querySelector('input[name="latitude"]').value);
        const lng = parseFloat(document.querySelector('input[name="longitude"]').value);
        
        if (!isNaN(lat) && !isNaN(lng)) {
            const location = { lat: lat, lng: lng };
            map.setCenter(location);
            if (marker) {
                marker.setPosition(location);
            } else {
                marker = new google.maps.Marker({
                    position: location,
                    map: map,
                    title: "Vị trí",
                    draggable: true
                });
            }
        } else {
            showAlert('error', 'Vui lòng nhập tọa độ GPS hợp lệ');
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
function initMap() {
    // Default to Ho Chi Minh City
    const defaultLocation = { lat: 10.8231, lng: 106.6297 };
    map = new google.maps.Map(document.getElementById("map"), {
        zoom: 10,
        center: defaultLocation,
    });
    
    marker = new google.maps.Marker({
        position: defaultLocation,
        map: map,
        title: "Vị trí mặc định",
        draggable: true
    });

    // Update coordinates when marker is dragged
    marker.addListener('dragend', function() {
        const position = marker.getPosition();
        document.querySelector('input[name="latitude"]').value = position.lat();
        document.querySelector('input[name="longitude"]').value = position.lng();
    });
}

// Auto-fill booking info when user is selected
document.querySelector('select[name="user_id"]').addEventListener('change', function() {
    const userId = this.value;
    if (userId) {
        // Filter bookings by user
        const bookingSelect = document.querySelector('select[name="booking_id"]');
        const options = bookingSelect.querySelectorAll('option');
        
        options.forEach(option => {
            if (option.value) {
                // You can implement filtering logic here
                option.style.display = 'block';
            }
        });
    }
});
</script>

<script async defer src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&callback=initMap"></script>
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

.avatar-sm {
    width: 40px;
    height: 40px;
    font-size: 14px;
}

.text-primary {
    color: #4e73df !important;
}

.text-info {
    color: #36b9cc !important;
}

.text-warning {
    color: #f6c23e !important;
}

.text-success {
    color: #1cc88a !important;
}
</style>
@endpush

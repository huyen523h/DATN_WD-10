@extends('layouts.app')

@section('title', 'Chi tiết đặt tour - Tour365')

@section('content')
    <div class="container py-5">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><i class="fas fa-calendar-check"></i> Chi tiết đặt tour</h2>
                    <a href="{{ route('bookings.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i> Quay lại
                    </a>
                </div>

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="row">
                    <!-- Booking Details -->
                    <div class="col-lg-8">
                        <div class="card mb-4 border-0 shadow-sm">
                            <div class="card-header">
                                <h5><i class="fas fa-info-circle"></i> Thông tin đặt tour</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6>Mã đặt tour</h6>
                                        <p class="text-muted">#{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}</p>

                                        <h6>Trạng thái</h6>
                                        <span
                                            class="badge
                                        @if ($booking->status === 'pending') bg-warning
                                        @elseif($booking->status === 'confirmed') bg-info
                                        @elseif($booking->status === 'paid') bg-success
                                        @elseif($booking->status === 'completed') bg-primary
                                        @elseif($booking->status === 'cancelled') bg-danger
                                        @else bg-secondary @endif fs-6">
                                            @switch($booking->status)
                                                @case('pending')
                                                    Chờ xác nhận
                                                @break

                                                @case('confirmed')
                                                    Đã xác nhận
                                                @break

                                                @case('paid')
                                                    Đã thanh toán
                                                @break

                                                @case('completed')
                                                    Hoàn thành tour
                                                @break

                                                @case('cancelled')
                                                    Đã hủy
                                                @break

                                                @default
                                                    {{ $booking->status }}
                                                @break
                                            @endswitch
                                        </span>
                                    </div>
                                    <div class="col-md-6">
                                        <h6>Ngày đặt</h6>
                                        <p class="text-muted">{{ $booking->created_at->format('d/m/Y H:i') }}</p>

                                        <h6>Tổng tiền</h6>
                                        <p class="h5 text-primary mb-0">
                                            {{ number_format($booking->total_amount, 0, ',', '.') }}đ
                                        </p>
                                    </div>
                                </div>
                            </div>


                            <!-- Tour Information -->
                            <div class="card mb-4 border-0 shadow-sm">
                                <div class="card-header">
                                    <h5><i class="fas fa-map-marked-alt"></i> Thông tin tour</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            @if ($booking->tour->images->count() > 0)
                                                <img src="{{ $booking->tour->images->first()->image_url }}"
                                                    class="img-fluid rounded" alt="{{ $booking->tour->title }}">
                                            @else
                                                <img src="https://via.placeholder.com/300x200/4F46E5/ffffff?text={{ urlencode($booking->tour->title) }}"
                                                    class="img-fluid rounded" alt="{{ $booking->tour->title }}">
                                            @endif
                                        </div>
                                        <div class="col-md-8">
                                            <h5>{{ $booking->tour->title }}</h5>
                                            <p class="text-muted">{{ $booking->tour->description }}</p>
                                            <div class="row">
                                                <div class="col-6">
                                                    <small class="text-muted">
                                                        <i class="fas fa-clock"></i> {{ $booking->tour->duration_days }}
                                                        ngày
                                                    </small>
                                                </div>
                                                <div class="col-6">
                                                    <small class="text-muted">
                                                        <i class="fas fa-calendar"></i>
                                                        {{ \Carbon\Carbon::parse($booking->departure->departure_date)->format('d/m/Y') }}
                                                    </small>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Customer Info -->
                                <div class="card mb-4 border-0 shadow-sm">
                                    <div class="card-header">
                                        <h5><i class="fas fa-users"></i> Thông tin khách hàng</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h6>Số lượng khách</h6>
                                                <ul class="list-unstyled">
                                                    <li><i class="fas fa-user"></i> {{ $booking->adults }} người lớn</li>
                                                    @if ($booking->children > 0)
                                                        <li><i class="fas fa-child"></i> {{ $booking->children }} trẻ em
                                                        </li>
                                                    @endif
                                                    @if ($booking->infants > 0)
                                                        <li><i class="fas fa-baby"></i> {{ $booking->infants }} em bé</li>
                                                    @endif
                                                </ul>
                                            </div>
                                            <div class="col-md-6">
                                                @if ($booking->promotion_code)
                                                    <h6>Mã giảm giá</h6>
                                                    <p class="text-success"><i class="fas fa-tag"></i>
                                                        {{ $booking->promotion_code }}</p>
                                                @endif

                                                @if ($booking->note)
                                                    <h6>Ghi chú</h6>
                                                    <p class="text-muted">{{ $booking->note }}</p>
                                                @endif

                                                </ul>
                                            </div>
                                            <div class="col-md-6">
                                                @if ($booking->promotion_code)
                                                    <h6>Mã giảm giá</h6>
                                                    <p class="text-success">
                                                        <i class="fas fa-tag"></i> {{ $booking->promotion_code }}
                                                    </p>
                                                @endif

                                                @if ($booking->note)
                                                    <h6>Ghi chú</h6>
                                                    <p class="text-muted">{{ $booking->note }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Payment Info -->
                                    @if ($booking->payment->count() > 0)
                                        <div class="card border-0 shadow-sm mb-4">
                                            <div class="card-header">
                                                <h5><i class="fas fa-credit-card"></i> Thông tin thanh toán</h5>
                                            </div>
                                            <div class="card-body">
                                                @foreach ($booking->payment as $payment)
                                                    <div class="row border-bottom pb-3 mb-3">
                                                        <div class="col-md-6">
                                                            <h6>Phương thức thanh toán</h6>
                                                            <p class="text-muted">
                                                                {{ strtoupper($payment->payment_method) }}</p>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <h6>Trạng thái</h6>
                                                            <span
                                                                class="badge 
                                                @if ($payment->status === 'pending') bg-warning
                                                @elseif($payment->status === 'completed') bg-success
                                                @elseif($payment->status === 'failed') bg-danger
                                                @else bg-secondary @endif">
                                                                {{ ucfirst($payment->status) }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Tour Operation Info (HDV, Xe, Tài xế) -->
                                    @if($booking->departure)
                                        <div class="card border-0 shadow-sm mb-4">
                                            <div class="card-header">
                                                <h5><i class="fas fa-info-circle"></i> Thông tin điều hành tour</h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <!-- Hướng dẫn viên -->
                                                    @if($booking->departure->guide)
                                                        <div class="col-md-6 mb-3">
                                                            <h6><i class="fas fa-user-tie text-primary me-2"></i>Hướng dẫn viên</h6>
                                                            <div class="d-flex align-items-center">
                                                                <div class="avatar-circle bg-primary text-white me-3" style="width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold;">
                                                                    {{ strtoupper(substr($booking->departure->guide->name, 0, 2)) }}
                                                                </div>
                                                                <div>
                                                                    <p class="mb-0 fw-bold">{{ $booking->departure->guide->name }}</p>
                                                                    @if($booking->departure->guide->email)
                                                                        <small class="text-muted">
                                                                            <i class="fas fa-envelope"></i> {{ $booking->departure->guide->email }}
                                                                        </small>
                                                                    @endif
                                                                    @if($booking->departure->guide->phone)
                                                                        <br><small class="text-muted">
                                                                            <i class="fas fa-phone"></i> {{ $booking->departure->guide->phone }}
                                                                        </small>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div class="col-md-6 mb-3">
                                                            <h6><i class="fas fa-user-tie text-muted me-2"></i>Hướng dẫn viên</h6>
                                                            <p class="text-muted mb-0">
                                                                <i class="fas fa-info-circle"></i> Chưa được gán
                                                            </p>
                                                        </div>
                                                    @endif

                                                    <!-- Thông tin xe -->
                                                    @if($booking->departure->vehicle_type || $booking->departure->vehicle_details)
                                                        <div class="col-md-6 mb-3">
                                                            <h6><i class="fas fa-bus text-warning me-2"></i>Thông tin xe</h6>
                                                            @if($booking->departure->vehicle_type)
                                                                <p class="mb-1">
                                                                    <span class="badge bg-warning text-dark">
                                                                        <i class="fas fa-bus"></i> Xe {{ $booking->departure->vehicle_type }} chỗ
                                                                    </span>
                                                                </p>
                                                            @endif
                                                            @if($booking->departure->vehicle_details)
                                                                <p class="text-muted mb-1">
                                                                    <small><i class="fas fa-info-circle"></i> {{ $booking->departure->vehicle_details }}</small>
                                                                </p>
                                                            @endif
                                                            @if($booking->departure->driver_contact)
                                                                <p class="text-muted mb-0">
                                                                    <small><i class="fas fa-user"></i> {{ $booking->departure->driver_contact }}</small>
                                                                </p>
                                                            @endif
                                                        </div>
                                                    @else
                                                        <div class="col-md-6 mb-3">
                                                            <h6><i class="fas fa-bus text-muted me-2"></i>Thông tin xe</h6>
                                                            <p class="text-muted mb-0">
                                                                <i class="fas fa-info-circle"></i> Chưa được gán
                                                            </p>
                                                        </div>
                                                    @endif
                                                </div>

                                                <!-- Điểm tập trung -->
                                                @if($booking->departure->meeting_point)
                                                    <div class="row mt-3">
                                                        <div class="col-12">
                                                            <h6><i class="fas fa-map-marker-alt text-danger me-2"></i>Điểm tập trung</h6>
                                                            <p class="text-muted mb-0">
                                                                <i class="fas fa-location-dot"></i> {{ $booking->departure->meeting_point }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                @endif

                                                <!-- Thời gian khởi hành -->
                                                @if($booking->departure->start_time)
                                                    <div class="row mt-3">
                                                        <div class="col-12">
                                                            <h6><i class="fas fa-clock text-info me-2"></i>Thời gian khởi hành</h6>
                                                            <p class="text-muted mb-0">
                                                                <i class="fas fa-calendar-alt"></i> {{ \Carbon\Carbon::parse($booking->departure->departure_date)->format('d/m/Y') }}
                                                                @if($booking->departure->start_time)
                                                                    - {{ \Carbon\Carbon::parse($booking->departure->start_time)->format('H:i') }}
                                                                @endif
                                                            </p>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <!-- Sidebar Actions -->
                                <div class="col-lg-4">
                                    <div class="card border-0 shadow-sm">
                                        <div class="card-header">
                                            <h5><i class="fas fa-cogs"></i> Thao tác</h5>
                                        </div>
                                        <div class="card-body">
                                            @if ($booking->status === 'pending')
                                                <div class="alert alert-warning">
                                                    <i class="fas fa-info-circle"></i> Vui lòng chờ admin xác nhận để thanh
                                                    toán.
                                                </div>
                                            @elseif($booking->status === 'confirmed')
                                                @php
                                                    $canPayResult = $booking->canPay();
                                                    $hasPaidPayment = $booking->payment->where('status', 'completed')->count() > 0;
                                                @endphp
                                                @if ($canPayResult['can_pay'] && !$hasPaidPayment && $booking->status !== 'paid')
                                                    @if (strpos($canPayResult['message'], 'thanh toán lại') !== false)
                                                        <div class="alert alert-warning">
                                                            <i class="fas fa-exclamation-triangle"></i> {{ $canPayResult['message'] }}
                                                        </div>
                                                    @endif
                                                    <div class="d-grid gap-2">
                                                        <form action="{{ route('momo_payment', $booking->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            <input type="hidden" name="total_momo"
                                                                value="{{ optional($booking->payment->first())->amount ?? $booking->total_amount }}">
                                                            <button type="submit" class="btn btn-danger">
                                                                <i class="fas fa-credit-card"></i> Thanh toán qua MOMO
                                                            </button>
                                                        </form>
                                                        <form action="{{ route('vnpay_payment', $booking->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            <button type="submit" class="btn btn-primary">
                                                                <i class="fas fa-wallet"></i> Thanh toán qua VNPay
                                                            </button>
                                                        </form>
                                                        <form action="{{ route('bookings.destroy', $booking->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-outline-danger">
                                                                <i class="fas fa-times"></i> Hủy đặt tour
                                                            </button>
                                                        </form>
                                                    </div>
                                                @elseif($hasPaidPayment || $booking->status === 'paid')
                                                    <div class="alert alert-success">
                                                        <i class="fas fa-check-circle"></i> Đã thanh toán
                                                    </div>
                                                    <div class="d-grid gap-2 mt-3">
                                                        <button type="button" class="btn btn-primary" onclick="generateInvoice({{ $booking->id }}, this)">
                                                            <i class="fas fa-print"></i> In hóa đơn
                                                        </button>
                                                        <button type="button" class="btn btn-success" onclick="downloadInvoice({{ $booking->id }}, this)">
                                                            <i class="fas fa-download"></i> Tải PDF
                                                        </button>
                                                    </div>
                                                @else
                                                    <form action="{{ route('bookings.destroy', $booking->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-outline-danger">
                                                            <i class="fas fa-times"></i> Hủy đặt tour
                                                        </button>
                                                    </form>
                                                @endif
                                            @elseif($booking->status === 'paid')
                                                <div class="alert alert-success">
                                                    <i class="fas fa-check-circle"></i> Đã thanh toán
                                                </div>
                                                <div class="d-grid gap-2 mt-3">
                                                    <button type="button" class="btn btn-primary" onclick="generateInvoice({{ $booking->id }}, this)">
                                                        <i class="fas fa-print"></i> In hóa đơn
                                                    </button>
                                                    <button type="button" class="btn btn-success" onclick="downloadInvoice({{ $booking->id }}, this)">
                                                        <i class="fas fa-download"></i> Tải PDF
                                                    </button>
                                                </div>
                                            @elseif($booking->status === 'completed')
                                                <div class="alert alert-success">
                                                    <i class="fas fa-check-circle"></i> Tour đã hoàn thành
                                                </div>
                                                <div class="d-grid gap-2 mt-3">
                                                    <button type="button" class="btn btn-primary" onclick="generateInvoice({{ $booking->id }}, this)">
                                                        <i class="fas fa-print"></i> In hóa đơn
                                                    </button>
                                                    <button type="button" class="btn btn-success" onclick="downloadInvoice({{ $booking->id }}, this)">
                                                        <i class="fas fa-download"></i> Tải PDF
                                                    </button>
                                                </div>
                                            @elseif($booking->status === 'cancelled')
                                                <div class="alert alert-danger">
                                                    <i class="fas fa-exclamation-triangle"></i> Đặt tour đã bị hủy
                                                </div>
                                            @endif

                                            <hr>

                                            <div class="text-center">
                                                <h6>Liên hệ hỗ trợ</h6>
                                                <p class="text-muted">
                                                    <i class="fas fa-phone"></i> 1900 1234<br>
                                                    <i class="fas fa-envelope"></i> support@tour365.vn
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

{{-- JS xử lý thanh toán --}}
@section('scripts')
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const payBtn = document.getElementById('payNowBtn');
                                const select = document.getElementById('paymentMethod');

                                if (payBtn) {
                                    payBtn.addEventListener('click', function() {
                                        const method = select.value;
                                        const bookingId = {{ $booking->id }};
                                        const url = "{{ url('/payment') }}/" + bookingId + "?method=" + method;
                                        console.log('Redirecting to:', url);
                                        window.location.href = url;
                                    });
                                } else {
                                    console.warn('Không tìm thấy nút thanh toán!');
                                }
                            });

                            @if($booking->status === 'completed')
                            // Generate Invoice (Print)
                            async function generateInvoice(bookingId, buttonElement = null) {
                                let button = null;
                                let originalContent = null;
                                
                                try {
                                    if (buttonElement) {
                                        button = buttonElement;
                                    } else if (typeof event !== 'undefined' && event && event.target) {
                                        button = event.target.closest('button');
                                    } else {
                                        button = document.querySelector(`button[onclick*="generateInvoice(${bookingId}"]`);
                                    }
                                    
                                    if (!button) {
                                        alert('Không tìm thấy nút. Vui lòng thử lại.');
                                        return;
                                    }

                                    originalContent = button.innerHTML;
                                    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang tạo...';
                                    button.disabled = true;

                                    const csrfTokenElement = document.querySelector('meta[name="csrf-token"]');
                                    if (!csrfTokenElement) {
                                        throw new Error('CSRF token not found');
                                    }
                                    const csrfToken = csrfTokenElement.getAttribute('content');

                                    const response = await fetch(`/web/invoices/booking/${bookingId}/pdf`, {
                                        method: 'GET',
                                        headers: {
                                            'Accept': 'application/json',
                                            'X-CSRF-TOKEN': csrfToken
                                        }
                                    });

                                    if (!response.ok) {
                                        const errorText = await response.text();
                                        throw new Error(`HTTP error! status: ${response.status} - ${errorText}`);
                                    }

                                    const data = await response.json();

                                    if (data.success && data.data && data.data.download_url) {
                                        // Open PDF in new tab
                                        const newWindow = window.open(data.data.download_url, '_blank');
                                        
                                        if (newWindow) {
                                            // Show success message
                                            const alertDiv = document.createElement('div');
                                            alertDiv.className = 'alert alert-success alert-dismissible fade show mt-3';
                                            alertDiv.innerHTML = '<strong>Thành công!</strong> PDF hóa đơn đã được tạo. <button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
                                            button.closest('.card-body').appendChild(alertDiv);
                                            setTimeout(() => alertDiv.remove(), 5000);
                                        } else {
                                            alert('Popup bị chặn. Vui lòng cho phép popup và thử lại.');
                                        }
                                    } else {
                                        throw new Error(data.message || 'Không thể tạo PDF');
                                    }
                                } catch (error) {
                                    console.error('Error generating invoice:', error);
                                    alert('Lỗi: ' + error.message);
                                } finally {
                                    if (button && originalContent) {
                                        button.innerHTML = originalContent;
                                        button.disabled = false;
                                    }
                                }
                            }

                            // Download Invoice PDF
                            async function downloadInvoice(bookingId, buttonElement = null) {
                                let button = null;
                                let originalContent = null;
                                
                                try {
                                    if (buttonElement) {
                                        button = buttonElement;
                                    } else if (typeof event !== 'undefined' && event && event.target) {
                                        button = event.target.closest('button');
                                    } else {
                                        button = document.querySelector(`button[onclick*="downloadInvoice(${bookingId}"]`);
                                    }
                                    
                                    if (!button) {
                                        alert('Không tìm thấy nút. Vui lòng thử lại.');
                                        return;
                                    }

                                    originalContent = button.innerHTML;
                                    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang tải...';
                                    button.disabled = true;

                                    const csrfTokenElement = document.querySelector('meta[name="csrf-token"]');
                                    if (!csrfTokenElement) {
                                        throw new Error('CSRF token not found');
                                    }
                                    const csrfToken = csrfTokenElement.getAttribute('content');

                                    // Use download endpoint which returns file with proper headers
                                    const link = document.createElement('a');
                                    link.href = `/web/invoices/booking/${bookingId}/download`;
                                    link.style.display = 'none';
                                    document.body.appendChild(link);
                                    
                                    // Trigger download
                                    link.click();
                                    
                                    // Clean up
                                    setTimeout(() => {
                                        if (link.parentNode) {
                                            document.body.removeChild(link);
                                        }
                                    }, 100);
                                    
                                    // Show success message
                                    const alertDiv = document.createElement('div');
                                    alertDiv.className = 'alert alert-success alert-dismissible fade show mt-3';
                                    alertDiv.innerHTML = '<strong>Thành công!</strong> PDF hóa đơn đã được tải xuống. <button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
                                    button.closest('.card-body').appendChild(alertDiv);
                                    setTimeout(() => alertDiv.remove(), 5000);
                                } catch (error) {
                                    console.error('Error downloading invoice:', error);
                                    alert('Lỗi: ' + error.message);
                                } finally {
                                    if (button && originalContent) {
                                        button.innerHTML = originalContent;
                                        button.disabled = false;
                                    }
                                }
                            }
                            @endif
                        </script>
@endsection

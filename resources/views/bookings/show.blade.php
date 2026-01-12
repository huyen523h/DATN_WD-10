@extends('layouts.app')

@section('title', 'Chi tiết đặt tour - Tour365')

@section('content')
<div class="container py-5">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>
            <i class="fas fa-calendar-check text-primary"></i>
            Chi tiết đặt tour #{{ $booking->id }}
        </h2>
        <a href="{{ route('bookings.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
    </div>

    {{-- ALERT --}}
    @foreach (['success','error'] as $msg)
        @if(session($msg))
            <div class="alert alert-{{ $msg === 'success' ? 'success' : 'danger' }}">
                {{ session($msg) }}
            </div>
        @endif
    @endforeach

    <div class="row">

        {{-- LEFT --}}
        <div class="col-lg-8">

            {{-- TOUR INFO --}}
            <div class="card mb-4 shadow-sm">
                <div class="card-body">
                    <h4 class="fw-bold text-primary">{{ $booking->tour->title }}</h4>
                    <div class="text-muted small">
                        <i class="fas fa-clock"></i> {{ $booking->tour->duration_days }} ngày
                        • <i class="fas fa-map-marker-alt"></i> {{ $booking->tour->category->name }}
                    </div>
                </div>
            </div>

            {{-- PASSENGERS --}}
            <div class="card mb-4 shadow-sm">
                <div class="card-header fw-bold text-primary">
                    <i class="fas fa-users"></i> Danh sách hành khách
                </div>
                <div class="card-body">
                    @if($booking->passengers->count())
                        <table class="table table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Họ tên</th>
                                    <th>Giới tính</th>
                                    <th>Năm sinh</th>
                                    <th>Loại</th>
                                    <th>CCCD / Passport</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($booking->passengers as $i => $p)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td class="fw-bold">{{ $p->full_name }}</td>
                                        <td>{{ $p->gender ?? '-' }}</td>
                                        <td>{{ $p->birth_year ?? '-' }}</td>
                                        <td>
                                            <span class="badge bg-info">
                                                {{ strtoupper($p->passenger_type) }}
                                            </span>
                                        </td>
                                        <td>{{ $p->id_number ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-muted">Chưa có hành khách</p>
                    @endif
                </div>
            </div>

            {{-- CUSTOMER --}}
            <div class="card mb-4 shadow-sm">
                <div class="card-header fw-bold text-primary">
                    <i class="fas fa-user"></i> Thông tin khách hàng
                </div>
                <div class="card-body row">
                    <div class="col-md-6">
                        <p><strong>Họ tên:</strong> {{ $booking->user->name }}</p>
                        <p><strong>Email:</strong> {{ $booking->user->email }}</p>
                        <p><strong>SĐT:</strong> {{ $booking->user->phone }}</p>
                    </div>
                    <div class="col-md-6">
                        <p>
                            <strong>Số lượng:</strong>
                            {{ $booking->adults }} NL,
                            {{ $booking->children }} TE
                        </p>
                        <p><strong>Ghi chú:</strong> {{ $booking->note ?? 'Không' }}</p>
                    </div>
                </div>
            </div>

            {{-- UPLOAD MANIFEST --}}
            <div class="card mb-4 shadow-sm">
                {{-- <div class="card-header fw-bold text-primary">
                    <i class="fas fa-file-upload"></i> Danh sách đoàn
                </div> --}}
                <div class="card-body">
                    {{-- Nút tải file mẫu đã bị gỡ: chức năng tải/upload danh sách đoàn không còn trên giao diện khách hàng --}}

                    {{-- Hiển thị file đã bị ẩn hoàn toàn theo yêu cầu; không hiển thị link tải hoặc thông báo upload --}}
                </div>
            </div>

        </div>

        {{-- RIGHT --}}
        <div class="col-lg-4">

            {{-- GUIDE & VEHICLE (hiển thị cho khách) --}}
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="fw-bold border-bottom pb-2 mb-3">Hướng dẫn viên & Xe</h5>
                    @php $dep = $booking->departure; @endphp

                    @if($dep && ($dep->guide || $dep->backupGuide))
                        <div class="mb-2">
                            <strong>HDV chính:</strong>
                            @if($dep->guide)
                                <div class="fw-bold">{{ $dep->guide->name }}</div>
                                @if($dep->guide->phone)
                                    <div class="small text-muted"><i class="fas fa-phone"></i> {{ $dep->guide->phone }}</div>
                                @endif
                            @else
                                <div class="text-muted">Chưa gán</div>
                            @endif
                        </div>


                        <div class="mb-2">
                            <strong>HDV dự phòng:</strong>
                            @if($dep->backupGuide)
                                <div class="fw-bold">{{ $dep->backupGuide->name }}</div>
                                @if($dep->backupGuide->phone)
                                    <div class="small text-muted"><i class="fas fa-phone"></i> {{ $dep->backupGuide->phone }}</div>
                                @endif
                            @else
                                <div class="text-muted">Chưa gán</div>
                            @endif

                    @if($booking->service_details || $booking->contract_file)
<div class="card mb-4 border-success shadow-sm">
    <div class="card-header bg-success text-white">
        <h6 class="mb-0 fw-bold"><i class="fas fa-file-signature me-2"></i> Cam kết & Hợp đồng</h6>
    </div>
    <div class="card-body">
        @if($booking->service_details)
            <div class="mb-3">
                <strong class="text-success">Dịch vụ bao gồm:</strong>
                <div class="bg-light p-2 rounded mt-1 border border-success border-opacity-25">
                    {!! nl2br(e($booking->service_details)) !!}
                </div>
            </div>
        @endif
        @if($booking->contract_file)
            <div>
                <strong>Ảnh hợp đồng:</strong>
                <a href="{{ Storage::url($booking->contract_file) }}" target="_blank" class="btn btn-sm btn-outline-success fw-bold ms-2">
                    <i class="fas fa-download me-1"></i> Tải về xem
                </a>
            </div>
        @endif
    </div>
</div>
@endif
                </div>
            @else
                <div class="alert alert-warning mb-4">
                    <i class="fas fa-exclamation-triangle"></i> Lịch khởi hành đã bị thay đổi. Vui lòng liên hệ Admin để cập nhật lại.
                </div>
            @endif

            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h6 class="mb-0 text-primary"><i class="fas fa-images me-2"></i> Hình ảnh & Lịch trình</h6>
                </div>
                <div class="card-body">
                    @if($booking->tour->images->count() > 0)
                        <div class="row g-2 mb-3">
                            @foreach($booking->tour->images as $image)
                                <div class="col-md-4">
                                    <img src="{{ $image->image_url }}" class="img-fluid rounded" style="height: 120px; width: 100%; object-fit: cover;">
                                </div>
                            @endforeach
                        </div>
                    @endif
                    @if($booking->tour->schedules->count() > 0)
                        <div class="timeline ms-2 border-start ps-3 border-primary">
                            @foreach($booking->tour->schedules->sortBy('day_number') as $schedule)
                                <div class="mb-3 position-relative">
                                    <span class="position-absolute top-0 start-0 translate-middle bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                          style="width: 20px; height: 20px; font-size: 10px; left: -17px;">{{ $schedule->day_number }}</span>
                                    <h6 class="fw-bold mb-1">Ngày {{ $schedule->day_number }}: {{ $schedule->title }}</h6>
                                    <div class="text-muted small">{{ $schedule->description }}</div>
                                </div>
                            @endforeach

                        </div>
                    @else
                        <p class="text-muted mb-0">Hướng dẫn viên sẽ được cập nhật khi có phân công.</p>
                    @endif

                    @if($dep && $dep->vehicle)
                        <hr>
                        <div>
                            <strong>Xe:</strong>
                            <div class="fw-bold">{{ $dep->vehicle->vehicle_type ?? 'Xe' }} - {{ $dep->vehicle->license_plate ?? '---' }}</div>
                            @if($dep->vehicle->driver_name)
                                <div class="small text-muted"><i class="fas fa-user"></i> Tài xế: {{ $dep->vehicle->driver_name }} @if($dep->vehicle->driver_phone) - {{ $dep->vehicle->driver_phone }} @endif</div>
                            @endif

                            <h5 class="alert-heading h6 fw-bold mb-1">Biên lai thu tiền</h5>
                           <a href="{{ Str::startsWith($booking->receipt_image, '/storage') ? asset($booking->receipt_image) : Storage::url($booking->receipt_image) }}" 
   target="_blank" 
   class="btn btn-sm btn-light text-success fw-bold">
    <i class="fas fa-eye me-1"></i> Xem hóa đơn
</a>

                        </div>
                    @else
                        <p class="text-muted mb-0">Thông tin xe sẽ được cập nhật khi phân công.</p>
                    @endif
                </div>

            {{-- TRƯỜNG HỢP 2: ĐÃ THANH TOÁN --}}
            @elseif ($booking->status === 'paid' || $booking->status === 'completed')
                <div class="alert alert-success text-center mb-0 p-3">
                    <i class="fas fa-check-circle fa-2x mb-2"></i><br>
                    <strong>Đã thanh toán thành công</strong>
                </div>

        {{-- TRƯỜNG HỢP 3: MỚI ĐẶT (PENDING) HOẶC ĐÃ DUYỆT (CONFIRMED) -> THANH TOÁN LUÔN --}}
            {{-- [SỬA LẠI]: Gộp cả 2 trạng thái này để hiện nút thanh toán ngay lập tức --}}
            @elseif (in_array($booking->status, ['pending', 'confirmed']))
                
                <div class="alert alert-warning text-center mb-3 p-2 small border-warning" style="background-color: #fff3cd;">
                    <i class="fas fa-clock text-warning"></i> Đơn hàng đang giữ chỗ. Vui lòng thanh toán ngay để hoàn tất!
                </div>

                {{-- CODE CHECKBOX CŨ CỦA BẠN (GIỮ NGUYÊN) --}}
                <div class="alert alert-light border mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="agreeTermsBooking" required>
                        <label class="form-check-label" for="agreeTermsBooking">
                            Tôi đã đọc và đồng ý với 
                            <a href="{{ route('payment-policy') }}" target="_blank" class="text-primary fw-bold">
                                Chính sách & Điều khoản Tour Đoàn
                                <i class="fas fa-external-link-alt ms-1"></i>
                            </a>
                        </label>
                    </div>
                </div>
                
                <div class="d-grid gap-2">
                    {{-- Form MoMo --}}
                    <form action="{{ route('momo_payment', $booking->id) }}" method="POST" class="d-inline" id="momoForm" onsubmit="return validateTermsBeforePayment(event)">
                        @csrf
                        <button type="submit" class="btn w-100 fw-bold shadow-sm" 
                                style="background: linear-gradient(135deg, #A50064 0%, #FF007F 100%); border: none; color: white; padding: 12px;">
                            <i class="fas fa-mobile-alt me-2"></i>Thanh toán với MoMo
                        </button>
                    </form>
                    
                    {{-- Form VNPay --}}
                    <form action="{{ route('payment.vnpay', $booking->id) }}" method="POST" class="d-inline" id="vnpayForm" onsubmit="return validateTermsBeforePayment(event)">
                        @csrf
                        <button type="submit" class="btn btn-primary w-100 fw-bold shadow-sm" 
                                style="background: linear-gradient(135deg, #005C97 0%, #363795 100%); border: none; padding: 12px;">
                            <i class="fas fa-university me-2"></i>Thanh toán với VNPay
                        </button>
                    </form>
                </div>
                
                <div class="mt-3 text-center">
                    <small class="text-muted fst-italic">Vui lòng thanh toán để hệ thống giữ chỗ cho bạn.</small>
                </div>
            @else
                {{-- Trường hợp khác (Expired...) --}}
                <div class="alert alert-danger text-center">
                    Trạng thái đơn: {{ $booking->status }}
                </div>
            @endif
        </div>
    </div>

            {{-- LOGIC HIỂN THỊ NÚT HỦY --}}
{{-- LOGIC HIỂN THỊ NÚT HỦY TOUR (ĐÃ UPDATE CHECK NGÀY) --}}
<div class="mt-3 pt-3 border-top">

    @php
        // 1. Kiểm tra xem Tour đã khởi hành hay chưa
        $departureDate = \Carbon\Carbon::parse($booking->departure->departure_date);
        $isTourStarted = $departureDate->isPast(); // True nếu ngày đi < ngày hiện tại
    @endphp

    {{-- TRƯỜNG HỢP 1: Tour ĐÃ KHỞI HÀNH hoặc ĐÃ KẾT THÚC --}}
    @if($isTourStarted && $booking->status !== 'cancelled')
        <div class="alert alert-secondary text-center mb-0 p-2 bg-light border-secondary">
            <i class="fas fa-flag-checkered fa-2x mb-2 text-secondary"></i><br>
            <strong class="text-uppercase text-secondary">Tour đã kết thúc / Đã khởi hành</strong>
            <div class="small text-muted mt-1">Không thể hủy hoặc hoàn tiền sau ngày khởi hành.</div>
        </div>

    {{-- TRƯỜNG HỢP 2: Đã thanh toán + CHƯA ĐI -> Hiện nút "Yêu cầu Hoàn tiền" --}}
    @elseif(($booking->status === 'paid' || $booking->status === 'completed') && !$isTourStarted)
        <div class="text-center">
            <small class="d-block text-muted mb-2">Bạn muốn thay đổi kế hoạch?</small>
            <button type="button" class="btn btn-warning w-100 fw-bold text-dark shadow-sm" 
                    data-bs-toggle="modal" data-bs-target="#requestRefundModal">
                <i class="fas fa-headset me-1"></i> Yêu cầu Hủy / Hoàn tiền
            </button>
        </div>

    {{-- TRƯỜNG HỢP 3: Chưa thanh toán + CHƯA ĐI -> Hiện nút "Hủy đơn" --}}
    @elseif(in_array($booking->status, ['pending', 'confirmed']) && !$isTourStarted)
        <button type="button" class="btn btn-outline-danger w-100" 
                data-bs-toggle="modal" data-bs-target="#userCancelModal">
            <i class="fas fa-times-circle me-1"></i> Hủy đơn hàng này
        </button>

    {{-- TRƯỜNG HỢP 4: Đã hủy --}}
    @elseif($booking->status === 'cancelled')
        <div class="alert alert-secondary text-center mb-0 p-2 small">
            <i class="fas fa-ban me-1"></i> Đơn hàng đã hủy
        </div>
        
    {{-- TRƯỜNG HỢP 5: Đang chờ hủy --}}
    @elseif($booking->status === 'cancel_requested')
        <div class="alert alert-warning text-center mb-0 p-2 small">
            <i class="fas fa-clock me-1"></i> Đang chờ xử lý hoàn tiền
        </div>
    @endif
</div>
            
          <div class="card border-0 shadow-sm">
    <div class="card-header bg-white">
        <h6 class="mb-0 fw-bold text-primary"><i class="fas fa-users me-2"></i>Danh sách đoàn</h6>
    </div>
    <div class="card-body">
        {{-- LOGIC CHẶT CHẼ: Phải thanh toán xong mới được nộp danh sách --}}
        @if($booking->status === 'paid' || $booking->status === 'completed')
            
            {{-- Nút tải file mẫu --}}
            <div class="mb-3 p-3 bg-light rounded border">
                <label class="form-label fw-bold mb-2 d-block">
                    <i class="fas fa-file-download text-success"></i> Tải file mẫu danh sách đoàn
                </label>
                <a href="{{ route('bookings.download-manifest-template') }}" 
                   class="btn btn-success btn-sm" target="_blank">
                    <i class="fas fa-download"></i> Tải file mẫu danh sách đoàn
                </a>
                <small class="text-muted d-block mt-2">
                    <i class="fas fa-info-circle"></i> File CSV - Mở bằng Excel hoặc Google Sheets để điền thông tin
                </small>

            </div>

            {{-- PAYMENT --}}
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="fw-bold border-bottom pb-2 mb-3">Thanh toán</h5>

                    @php
                        $dep = $booking->departure;
                        $adultPrice = $dep->price ?? ($booking->tour->price ?? 0);
                        $childPrice = $dep->child_price ?? 0;
                        $adultTotal = ($booking->adults ?? 0) * $adultPrice;
                        $childTotal = ($booking->children ?? 0) * $childPrice;
                        $services = $booking->additional_services ?? [];
                        $servicesTotal = $booking->additional_services_total ?? 0;
                        $subtotal = $adultTotal + $childTotal + $servicesTotal;
                        $discount = max(0, $subtotal - $booking->total_amount);
                    @endphp

                    <div class="mb-3">
                        <div class="d-flex justify-content-between"><div>Ngày khởi hành</div><div class="text-end">{{ $dep?->departure_date?->format('d/m/Y') ?? '-' }}</div></div>
                        <div class="d-flex justify-content-between"><div>Người lớn ({{ $booking->adults ?? 0 }})</div><div class="text-end">{{ number_format($adultPrice) }}đ × {{ $booking->adults ?? 0 }} = <strong>{{ number_format($adultTotal) }}đ</strong></div></div>
                        <div class="d-flex justify-content-between"><div>Trẻ em ({{ $booking->children ?? 0 }})</div><div class="text-end">{{ number_format($childPrice) }}đ × {{ $booking->children ?? 0 }} = <strong>{{ number_format($childTotal) }}đ</strong></div></div>

                        @if(count($services) > 0)
                            <div class="mt-2"><strong>Dịch vụ thêm</strong></div>
                            @foreach($services as $s)
                                <div class="d-flex justify-content-between"><div>{{ $s['label'] ?? ($s['key'] ?? '') }}</div><div class="text-end">{{ number_format($s['amount'] ?? 0) }}đ</div></div>
                            @endforeach
                        @endif

                        <hr>
                        <div class="d-flex justify-content-between"><div>Tạm tính</div><div class="text-end">{{ number_format($subtotal) }}đ</div></div>
                        @if($discount > 0)
                            <div class="d-flex justify-content-between text-danger"><div>Giảm giá</div><div class="text-end">-{{ number_format($discount) }}đ</div></div>
                        @endif
                        <div class="d-flex justify-content-between mt-2"><div class="fw-bold">Tổng phải trả</div><div class="fw-bold text-primary">{{ number_format($booking->total_amount) }}đ</div></div>
                    </div>

                    @if($booking->status === 'confirmed')
                        <div class="form-check mb-3">
                            <input type="checkbox" class="form-check-input" id="agreeTerms">
                            <label for="agreeTerms" class="form-check-label">
                                Đồng ý
                                <a href="{{ route('payment-policy') }}" target="_blank">
                                    Điều khoản thanh toán
                                </a>
                            </label>
                        </div>

                        <form method="POST"
                              action="{{ route('momo_payment', $booking->id) }}"
                              onsubmit="return validateTermsBeforePayment(event)">
                            @csrf
                            <button class="btn btn-danger w-100 mb-2">
                                Thanh toán MoMo
                            </button>
                        </form>

                        <form method="POST"
                              action="{{ route('payment.vnpay', $booking->id) }}"
                              onsubmit="return validateTermsBeforePayment(event)">
                            @csrf
                            <button class="btn btn-primary w-100">
                                Thanh toán VNPay
                            </button>
                        </form>

                    @elseif(in_array($booking->status, ['paid','completed']))
                        <div class="alert alert-success text-center">
                            Đã thanh toán
                        </div>

                    @elseif($booking->status === 'pending')
                        <div class="alert alert-warning text-center">
                            Đang chờ xác nhận
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // 1. Hàm xử lý chọn sao đánh giá
    function setRating(label, bookingId) {
        // Tìm input radio tương ứng để check
        const inputId = label.getAttribute('for');
        const input = document.getElementById(inputId);
        if(input) input.checked = true;

        // Tìm modal hiện tại
        const modal = document.getElementById('reviewModal-' + bookingId);
        if (!modal) return;

        // Reset màu tất cả các sao về xám
        const labels = modal.querySelectorAll('.rating-group label');
        labels.forEach(l => {
            l.classList.remove('text-warning');
            l.classList.add('text-muted');
        });

        // Tô màu vàng cho sao được chọn và các sao phía trước
        let currentLabel = label;
        currentLabel.classList.remove('text-muted');
        currentLabel.classList.add('text-warning');

        let nextSibling = currentLabel.nextElementSibling;
        while(nextSibling) {
            if(nextSibling.tagName === 'LABEL') {
                nextSibling.classList.remove('text-muted');
                nextSibling.classList.add('text-warning');
            }
            nextSibling = nextSibling.nextElementSibling;
        }
    }

    // 2. Validate điều khoản trước khi thanh toán
    // Lưu ý: Trong HTML nút submit phải gọi: onsubmit="return validateTermsBeforePayment(event, {{ $booking->id }})"
    function validateTermsBeforePayment(event, bookingId) {
        // Tìm checkbox theo ID động (ví dụ: agreeTerms-15)
        // Nếu bạn đang dùng ID tĩnh 'agreeTermsBooking' thì sửa dòng dưới thành: 
        // const agreeTerms = document.getElementById('agreeTermsBooking'); 
        // (Nhưng khuyên dùng ID động cho trang danh sách)
        
        const agreeTerms = document.getElementById('agreeTermsBooking'); 

        if (agreeTerms && !agreeTerms.checked) {
            event.preventDefault();
            
            // Tạo thông báo đẹp (Alert Bootstrap)
            const alertDiv = document.createElement('div');
            alertDiv.className = 'alert alert-warning alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3 shadow';
            alertDiv.style.zIndex = '9999';
            alertDiv.innerHTML = `
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>Vui lòng tích chọn "Đồng ý với điều khoản" trước khi thanh toán!</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            document.body.appendChild(alertDiv);
            
            // Scroll tới checkbox để user thấy
            agreeTerms.scrollIntoView({ behavior: 'smooth', block: 'center' });
            agreeTerms.focus();
            
            // Tự tắt sau 3 giây
            setTimeout(() => alertDiv.remove(), 3000);
            
            return false;
        }
        return true;
    }
</script>
@endsection

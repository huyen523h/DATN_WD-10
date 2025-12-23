@extends('layouts.app')

@section('title', 'Chi tiết đặt tour - Tour365')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-calendar-check text-primary"></i> Chi tiết đặt tour #{{ $booking->id }}</h2>
        <a href="{{ route('bookings.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left"></i> Quay lại</a>
    </div>

    {{-- Hiển thị thông báo success/error --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert" id="upload-success-alert" style="border-left: 4px solid #28a745;">
            <div class="d-flex align-items-center">
                <i class="fas fa-check-circle fa-2x me-3"></i>
                <div class="flex-grow-1">
                    <h5 class="alert-heading mb-1">Thành công!</h5>
                    <p class="mb-0">{{ session('success') }}</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
        <script>
            // Tự động scroll đến thông báo và highlight
            document.addEventListener('DOMContentLoaded', function() {
                const alert = document.getElementById('upload-success-alert');
                if (alert) {
                    alert.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    alert.style.animation = 'pulse 2s ease-in-out';
                }
            });
        </script>
        <style>
            @keyframes pulse {
                0%, 100% { transform: scale(1); }
                50% { transform: scale(1.02); }
            }
            @keyframes slideIn {
                from {
                    opacity: 0;
                    transform: translateY(-10px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
        </style>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert" style="border-left: 4px solid #dc3545;">
            <div class="d-flex align-items-center">
                <i class="fas fa-exclamation-circle fa-2x me-3"></i>
                <div class="flex-grow-1">
                    <h5 class="alert-heading mb-1">Lỗi!</h5>
                    <p class="mb-0">{{ session('error') }}</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif

    {{-- HIỂN THỊ THÔNG BÁO HỦY TOUR (Dành cho khách hàng) --}}
@if($booking->status === 'cancelled')
    <div class="alert alert-danger border-danger shadow-sm mb-4">
        <div class="d-flex">
            <div class="me-3">
                <i class="fas fa-exclamation-circle fa-3x text-danger"></i>
            </div>
            <div class="flex-grow-1">
                <h4 class="alert-heading fw-bold">Tour này đã bị hủy!</h4>
                <p class="mb-1">
                    <strong>Lý do hủy:</strong> {{ $booking->cancel_reason ?? 'Không có lý do cụ thể.' }}
                </p>
                
                {{-- Kiểm tra xem có giao dịch hoàn tiền nào không --}}
                @php
                    $refundTransaction = $booking->payment->where('amount', '<', 0)->first();
                @endphp

                @if($refundTransaction)
                    <hr>
                    <div class="bg-white p-3 rounded border border-danger border-opacity-25">
                        <h6 class="text-danger fw-bold"><i class="fas fa-undo"></i> Thông tin hoàn tiền:</h6>
                        <ul class="mb-0 ps-3">
                            <li>Số tiền hoàn lại: <strong class="text-danger">{{ number_format(abs($refundTransaction->amount)) }} VNĐ</strong></li>
                            <li>Thời gian xử lý: {{ $refundTransaction->created_at->format('d/m/Y H:i') }}</li>
                            <li>Ghi chú: {{ $refundTransaction->note }}</li>
                        </ul>
                        
                        {{-- Hiển thị ảnh bằng chứng nếu có --}}
                        @if($refundTransaction->refund_proof)
                            <div class="mt-2">
                                <a href="{{ Storage::url($refundTransaction->refund_proof) }}" target="_blank" class="btn btn-sm btn-outline-danger">
                                    <i class="fas fa-image"></i> Xem biên lai chuyển khoản
                                </a>
                            </div>
                        @endif
                    </div>
                @else
                    <hr>
                    <p class="mb-0 fst-italic">
                        <i class="fas fa-info-circle"></i> Nếu bạn đã thanh toán, nhân viên sẽ liên hệ để xử lý hoàn tiền theo quy định.
                    </p>
                @endif
            </div>
        </div>
    </div>
@endif

    <div class="row">
        <div class="col-lg-8">
            
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <h4 class="fw-bold text-primary mb-2">{{ $booking->tour->title }}</h4>
                            <div class="d-flex gap-3 text-small text-muted">
                                <span><i class="fas fa-clock"></i> {{ $booking->tour->duration_days }} ngày</span>
                                <span><i class="fas fa-map-marker-alt"></i> {{ $booking->tour->category->name }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if($booking->departure)
                <div class="card mb-4 border-info shadow-sm">
                    <div class="card-header bg-info text-white">
                        <h6 class="mb-0"><i class="fas fa-bus-alt me-2"></i> Thông tin Điều hành</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <strong>Hướng dẫn viên:</strong>
                                @if($booking->departure->guide)
                                    <div class="d-flex align-items-center mt-2">
                                        <div class="bg-secondary text-white rounded-circle d-flex justify-content-center align-items-center me-2" style="width: 40px; height: 40px;">
                                            {{ substr($booking->departure->guide->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="fw-bold">{{ $booking->departure->guide->name }}</div>
                                            <div class="small">{{ $booking->departure->guide->phone }}</div>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-muted fst-italic">Đang cập nhật...</span>
                                @endif
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>Phương tiện:</strong><br>
                                <ul class="list-unstyled mt-1">
                                    <li><i class="fas fa-car me-2"></i> {{ $booking->departure->vehicle_details ?? 'Đang cập nhật' }}</li>
                                    <li><i class="fas fa-id-card me-2"></i> {{ $booking->departure->driver_contact ?? 'Đang cập nhật' }}</li>
                                </ul>
                            </div>
                            @if($booking->departure->itinerary_file)
                                <div class="col-12 border-top pt-2">
                                    <a href="{{ Storage::url($booking->departure->itinerary_file) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-download me-1"></i> Tải Lịch trình / Hợp đồng
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
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
                        <p class="text-center text-muted">Chi tiết đang cập nhật...</p>
                    @endif
                </div>
            </div>
            
             <div class="card mb-4 border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h6 class="mb-0 text-primary"><i class="fas fa-user me-2"></i> Thông tin Khách hàng</h6>
                </div>
                <div class="card-body">
                     <div class="row">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Họ tên:</strong> {{ $booking->user->name }}</p>
                            <p class="mb-1"><strong>Email:</strong> {{ $booking->user->email }}</p>
                            <p class="mb-0"><strong>SĐT:</strong> {{ $booking->user->phone }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Số lượng:</strong> {{ $booking->adults }} Lớn, {{ $booking->children }} Trẻ em</p>
                            <p class="mb-0"><strong>Ghi chú:</strong> {{ $booking->note ?? 'Không' }}</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="col-lg-4">
         <div class="card mb-4 border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title text-muted mb-3 border-bottom pb-2">Thông tin thanh toán</h5>
                    
                    <div class="d-flex justify-content-between mb-3">
                        <span>Tổng tiền:</span>
                        <span class="fw-bold text-primary fs-5">{{ number_format($booking->total_amount) }}đ</span>
                    </div>

                    {{-- [CODE MỚI] HIỂN THỊ BILL CHO KHÁCH --}}
@if($booking->receipt_image)
    <div class="alert alert-success mt-3 shadow-sm">
        <div class="d-flex align-items-center">
            <i class="fas fa-receipt fa-2x me-3"></i>
            <div>
                <h5 class="alert-heading h6 fw-bold mb-1">Biên lai thu tiền</h5>
                <p class="mb-1 small">Bạn đã thanh toán bằng tiền mặt. Xem chi tiết phiếu thu tại đây:</p>
                <a href="{{ Storage::url($booking->receipt_image) }}" target="_blank" class="btn btn-sm btn-light text-success fw-bold">
                    <i class="fas fa-eye me-1"></i> Xem hóa đơn của tôi
                </a>
            </div>
        </div>
    </div>
@endif

                    {{-- LOGIC MỚI: Kiểm tra trạng thái chặt chẽ hơn --}}
                    
                    {{-- 1. TRƯỜNG HỢP ĐÃ HỦY: Chặn tuyệt đối nút thanh toán --}}
                    @if ($booking->status === 'cancelled')
                        <div class="alert alert-secondary text-center mb-0 p-3 bg-light border-secondary">
                            <i class="fas fa-ban fa-2x mb-2 text-secondary"></i><br>
                            <strong class="text-uppercase text-secondary">Đơn hàng đã hủy</strong><br>
                            <small class="text-muted">Giao dịch đã đóng. Không thể thanh toán.</small>
                        </div>

                    {{-- 2. TRƯỜNG HỢP ĐÃ THANH TOÁN: Chỉ hiện thông báo xanh --}}
                    @elseif ($booking->status === 'paid' || $booking->status === 'completed')
                        <div class="alert alert-success text-center mb-0 p-3">
                            <i class="fas fa-check-circle fa-2x mb-2"></i><br>
                            <strong>Đã thanh toán thành công</strong>
                        </div>

                    {{-- 3. TRƯỜNG HỢP CÒN LẠI (Pending/Confirmed): Mới hiện nút thanh toán --}}
                    @else
                        <div class="alert alert-warning text-center mb-3 p-2 small">
                            <i class="fas fa-clock"></i> Đơn hàng đang chờ thanh toán
                        </div>
                        
                        <!-- Terms and Conditions Agreement -->
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
                    @endif
                </div>
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
            
            <hr class="my-3">
            
            {{-- Hiển thị file đã upload --}}
            @if($booking->passenger_manifest_file)
                <div class="alert alert-success mb-3 p-3 shadow-sm" id="file-uploaded-info" style="border-left: 4px solid #28a745; animation: slideIn 0.5s ease;">
                    <div class="d-flex align-items-start">
                        <i class="fas fa-check-circle fa-2x me-3 text-success"></i>
                        <div class="flex-grow-1">
                            <h6 class="alert-heading mb-2 fw-bold">
                                <i class="fas fa-file-check me-2"></i>Đã upload file thành công!
                            </h6>
                            <p class="mb-2 small">File danh sách đoàn đã được tải lên hệ thống. Bạn có thể xem hoặc tải về file đã upload.</p>
                            <div class="d-flex gap-2 mb-2">
                                <a href="{{ Storage::url($booking->passenger_manifest_file) }}" target="_blank" class="btn btn-sm btn-outline-success">
                                    <i class="fas fa-download me-1"></i> Xem/Tải về file
                                </a>
                            </div>
                            <small class="d-block text-muted">
                                <i class="fas fa-clock me-1"></i> Upload lúc: {{ \Carbon\Carbon::parse($booking->updated_at)->format('d/m/Y H:i') }}
                            </small>
                        </div>
                    </div>
                </div>
                <hr class="my-3">
                <div class="alert alert-info small mb-2">
                    <i class="fas fa-info-circle me-2"></i>
                    Bạn có thể upload file mới để thay thế file hiện tại.
                </div>
            @else
                <div class="alert alert-info mb-2 p-2">
                    <i class="fas fa-info-circle me-2"></i>
                    <span class="small">Sau khi điền thông tin vào file mẫu, vui lòng upload file đã điền lên hệ thống.</span>
                </div>
            @endif
            
            {{-- Form upload file đã điền --}}
            <form action="{{ route('bookings.upload-manifest', $booking->id) }}" method="POST" enctype="multipart/form-data" id="upload-manifest-form">
                @csrf
                <div class="mb-2">
                    <label class="form-label fw-bold small mb-2 d-block">
                        <i class="fas fa-upload text-primary"></i> {{ $booking->passenger_manifest_file ? 'Upload file mới (thay thế)' : 'Upload file đã điền thông tin' }}
                    </label>
                    <input type="file" name="manifest_file" class="form-control form-control-sm" accept=".csv,.xls,.xlsx" {{ !$booking->passenger_manifest_file ? 'required' : '' }} id="manifest-file-input">
                    <small class="text-muted d-block mt-1">
                        <i class="fas fa-info-circle"></i> Chấp nhận file: CSV, XLS, XLSX (tối đa 5MB)
                    </small>
                </div>
                <button type="submit" class="btn btn-primary btn-sm w-100" id="upload-manifest-btn">
                    <i class="fas fa-cloud-upload-alt me-1"></i> {{ $booking->passenger_manifest_file ? 'Cập nhật file mới' : 'Cập nhật danh sách' }}
                </button>
            </form>
            
            <script>
                // Xử lý sau khi upload thành công
                document.addEventListener('DOMContentLoaded', function() {
                    const form = document.getElementById('upload-manifest-form');
                    const uploadBtn = document.getElementById('upload-manifest-btn');
                    
                    if (form) {
                        form.addEventListener('submit', function(e) {
                            // Hiển thị loading state
                            if (uploadBtn) {
                                uploadBtn.disabled = true;
                                uploadBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Đang upload...';
                            }
                            
                            // Form sẽ submit bình thường, sau khi upload thành công sẽ reload và hiển thị thông báo
                        });
                    }
                    
                    // Nếu có thông báo success, scroll đến đó
                    @if(session('success'))
                        const successAlert = document.getElementById('upload-success-alert');
                        if (successAlert) {
                            setTimeout(() => {
                                successAlert.scrollIntoView({ behavior: 'smooth', block: 'center' });
                            }, 100);
                        }
                    @endif
                });
            </script>

        @elseif($booking->status === 'cancelled')
            <div class="text-center text-muted py-2 small">
                <i class="fas fa-ban"></i> Đơn hàng đã hủy.
            </div>

        @else
            {{-- CHƯA THANH TOÁN --}}
            <div class="text-center text-secondary py-3 bg-light rounded">
                <i class="fas fa-lock fa-2x mb-2 text-muted"></i>
                <div class="small fw-bold">Chức năng đang khóa</div>
                <div style="font-size: 11px;">Vui lòng thanh toán để mở khóa chức năng nộp danh sách đoàn.</div>
            </div>
        @endif
    </div>
</div>
        </div>
    </div>
</div>
@endsection

                {{-- JS xử lý review --}}
                    @section('scripts')
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {


                            // --- (BẮT ĐẦU CODE MỚI - GỬI REVIEW BẰNG AJAX) ---
                            document.querySelectorAll('.form-review-submission').forEach(form => {
                                form.addEventListener('submit', function(e) {
                                    e.preventDefault(); // Ngăn form gửi đi

                                    const modal = this.closest('.modal');
                                    const form = this;
                                    const bookingId = modal.id.split('-')[1];
                                    const errorAlert = document.getElementById(`review-error-${bookingId}`);
                                    const submitButton = form.querySelector('button[type="submit"]');

                                    // Lấy CSRF token (phải có trong <head> của layout)
                                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

                                    submitButton.disabled = true; // Vô hiệu hóa nút
                                    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang gửi...';
                                    errorAlert.classList.add('d-none'); // Ẩn lỗi cũ

                                    fetch(form.action, {
                                            method: 'POST',
                                            headers: {
                                                'X-CSRF-TOKEN': csrfToken,
                                                'Accept': 'application/json',
                                            },
                                            body: new FormData(form)
                                        })
                                        .then(response => {
                                            return response.json().then(data => ({
                                                status: response.status,
                                                body: data
                                            }));
                                        })
                                        .then(result => {
                                            if (result.status === 201) { // 201 Created
                                                alert(
                                                    'Cảm ơn bạn đã đánh giá! Đánh giá của bạn đang chờ duyệt.'
                                                );
                                                location.reload(); // Tải lại trang
                                            } else {
                                                // Hiển thị lỗi (ví dụ: 403, 409)
                                                errorAlert.textContent = result.body.message ||
                                                    'Đã xảy ra lỗi khi gửi đánh giá.';
                                                errorAlert.classList.remove('d-none');
                                                submitButton.disabled = false;
                                                submitButton.innerHTML = 'Gửi đánh giá';
                                            }
                                        })
                                        .catch(error => {
                                            console.error('Lỗi khi gửi đánh giá:', error);
                                            errorAlert.textContent = 'Lỗi hệ thống. Vui lòng thử lại.';
                                            errorAlert.classList.remove('d-none');
                                            submitButton.disabled = false;
                                            submitButton.innerHTML = 'Gửi đánh giá';
                                        });
                                });
                            });
                            // --- (KẾT THÚC CODE MỚI) ---
                        });

                        // Validate terms before payment
                        function validateTermsBeforePayment(event) {
                            const agreeTerms = document.getElementById('agreeTermsBooking');
                            if (!agreeTerms || !agreeTerms.checked) {
                                event.preventDefault();
                                
                                // Show alert
                                const alertDiv = document.createElement('div');
                                alertDiv.className = 'alert alert-warning alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3';
                                alertDiv.style.zIndex = '9999';
                                alertDiv.style.minWidth = '400px';
                                alertDiv.innerHTML = `
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    <strong>Vui lòng đọc và đồng ý với Chính sách & Điều khoản trước khi thanh toán!</strong>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                `;
                                document.body.appendChild(alertDiv);
                                
                                // Scroll to checkbox
                                if (agreeTerms) {
                                    agreeTerms.scrollIntoView({ behavior: 'smooth', block: 'center' });
                                    agreeTerms.focus();
                                }
                                
                                // Remove alert after 5 seconds
                                setTimeout(() => {
                                    alertDiv.remove();
                                }, 5000);
                                
                                return false;
                            }
                            return true;
                        }
                    </script>
                @endsection

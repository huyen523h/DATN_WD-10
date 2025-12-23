@extends('layouts.app')

@section('title', 'Chi tiết đặt tour - Tour365')

@section('content')
<div class="container py-5">

    {{-- [THÊM ĐOẠN NÀY ĐỂ HIỆN LỖI VALIDATE] --}}
    @if ($errors->any())
        <div class="alert alert-danger shadow-sm">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li><i class="fas fa-exclamation-circle me-1"></i> {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    {{-- [THÊM ĐOẠN NÀY ĐỂ HIỆN THÔNG BÁO THÀNH CÔNG/THẤT BẠI] --}}
    @if(session('success'))
        <div class="alert alert-success shadow-sm">
            <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger shadow-sm">
            <i class="fas fa-times-circle me-1"></i> {{ session('error') }}
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-calendar-check text-primary"></i> Chi tiết đặt tour #{{ $booking->id }}</h2>
        <a href="{{ route('bookings.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left"></i> Quay lại</a>
    </div>

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

            {{-- HIỂN THỊ BILL CHO KHÁCH (NẾU CÓ) --}}
            @if($booking->receipt_image)
                <div class="alert alert-success mt-3 shadow-sm">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-receipt fa-2x me-3"></i>
                        <div>
                            <h5 class="alert-heading h6 fw-bold mb-1">Biên lai thu tiền</h5>
                            <a href="{{ Storage::url($booking->receipt_image) }}" target="_blank" class="btn btn-sm btn-light text-success fw-bold">
                                <i class="fas fa-eye me-1"></i> Xem hóa đơn
                            </a>
                        </div>
                    </div>

                      {{-- [CODE MỚI] HIỂN THỊ BILL HOÀN TIỀN CỦA ADMIN --}}
@if($booking->status === 'cancelled' && $booking->refund_proof_image)
    <div class="mt-3 pt-3 border-top border-danger border-opacity-25">
        <h6 class="text-danger fw-bold mb-2">
            <i class="fas fa-file-invoice-dollar"></i> Bằng chứng hoàn tiền từ Admin:
        </h6>
        
        <div class="d-flex align-items-center bg-white p-2 rounded border border-danger border-opacity-10" style="max-width: 400px;">
            {{-- Ảnh thumbnail nhỏ --}}
            <div class="me-3">
                <img src="{{ Storage::url($booking->refund_proof_image) }}" 
                     alt="Refund Bill" 
                     class="rounded shadow-sm"
                     style="width: 80px; height: 80px; object-fit: cover; border: 1px solid #ddd;">
            </div>
            
            {{-- Thông tin & Nút xem to --}}
            <div>
                <div class="small text-muted mb-1">
                    Đã chuyển vào: <strong>{{ $booking->refund_bank }}</strong><br>
                    STK: <strong>{{ $booking->refund_account }}</strong>
                </div>
                <a href="{{ Storage::url($booking->refund_proof_image) }}" target="_blank" class="btn btn-sm btn-outline-danger fw-bold">
                    <i class="fas fa-eye me-1"></i> Xem ảnh gốc
                </a>
            </div>
        </div>
        <div class="form-text text-danger small mt-1">
            * Vui lòng kiểm tra tài khoản ngân hàng của bạn.
        </div>
    </div>
@endif
                </div>
            @endif
            
            {{-- 1. TRƯỜNG HỢP ĐÃ HỦY: Chặn tuyệt đối --}}
            @if ($booking->status === 'cancelled')
                <div class="alert alert-secondary text-center mb-0 p-3 bg-light border-secondary">
                    <i class="fas fa-ban fa-2x mb-2 text-secondary"></i><br>
                    <strong class="text-uppercase text-secondary">Đơn hàng đã hủy</strong><br>
                    <small class="text-muted">Giao dịch đã đóng.</small>
                </div>

            {{-- 2. TRƯỜNG HỢP ĐÃ GỬI YÊU CẦU HỦY: Chờ xử lý --}}
            @elseif ($booking->status === 'cancel_requested')
                <div class="alert alert-warning text-center mb-0 p-3">
                    <i class="fas fa-clock fa-2x mb-2"></i><br>
                    <strong>Đang chờ xử lý hủy</strong><br>
                    <div class="small mt-1">Nhân viên sẽ liên hệ sớm để hoàn tiền.</div>
                </div>

            {{-- 3. TRƯỜNG HỢP ĐÃ THANH TOÁN (PAID): Hiện nút YÊU CẦU --}}
            @elseif ($booking->status === 'paid' || $booking->status === 'completed')
                <div class="alert alert-success text-center mb-3 p-3">
                    <i class="fas fa-check-circle fa-2x mb-2"></i><br>
                    <strong>Đã thanh toán thành công</strong>
                </div>
                
                <div class="d-grid pt-2 border-top">
                    <span class="text-muted small text-center d-block mb-2">Bạn muốn thay đổi kế hoạch?</span>
                    <button type="button" class="btn btn-warning w-100 text-dark fw-bold" 
                            data-bs-toggle="modal" data-bs-target="#requestRefundModal">
                        <i class="fas fa-headset me-1"></i> Yêu cầu Hủy / Hoàn tiền
                    </button>
                </div>

            {{-- [MỚI - SỬA LẠI ĐOẠN NÀY] --}}
            
            {{-- 4. TRƯỜNG HỢP ĐÃ XÁC NHẬN (CONFIRMED): Mới hiện nút Thanh toán --}}
            @elseif ($booking->status === 'confirmed')
                <div class="alert alert-primary text-center mb-3 p-2 small border-primary">
                    <i class="fas fa-check-circle text-primary me-1"></i> 
                    <strong>Tour đã được xác nhận!</strong><br>
                    Vui lòng thanh toán để giữ chỗ.
                </div>
                
                <div class="d-grid gap-2">
                    {{-- Form Thanh toán --}}
                    <form action="{{ route('momo_payment', $booking->id) }}" method="POST">
                        @csrf <button class="btn w-100 fw-bold text-white shadow-sm" style="background: linear-gradient(135deg, #A50064, #FF007F);">Thanh toán MoMo</button>
                    </form>
                    <form action="{{ route('payment.vnpay', $booking->id) }}" method="POST">
                        @csrf <button class="btn btn-primary w-100 fw-bold shadow-sm">Thanh toán VNPay</button>
                    </form>
                    
                    {{-- Nút Hủy ngay --}}
                    <div class="text-center mt-2 pt-2 border-top">
                        <button type="button" class="btn btn-outline-danger w-100 btn-sm" data-bs-toggle="modal" data-bs-target="#userCancelModal">
                            <i class="fas fa-times-circle me-1"></i> Hủy đơn hàng
                        </button>
                    </div>
                </div>

            {{-- 5. TRƯỜNG HỢP MỚI ĐẶT (PENDING): Ẩn nút thanh toán --}}
            @else
                <div class="alert alert-info text-center mb-3 p-3 border-info bg-light">
                    <i class="fas fa-hourglass-half fa-2x mb-2 text-info"></i><br>
                    <strong class="text-info">Đang chờ xác nhận</strong><br>
                    <small class="text-muted">Admin đang kiểm tra tình trạng chỗ trống. Vui lòng quay lại sau.</small>
                </div>

                {{-- Vẫn cho phép hủy yêu cầu --}}
                <div class="d-grid">
                    <button type="button" class="btn btn-outline-danger w-100 btn-sm" data-bs-toggle="modal" data-bs-target="#userCancelModal">
                        <i class="fas fa-times-circle me-1"></i> Hủy yêu cầu đặt tour
                    </button>
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
            
            <form action="{{ route('bookings.upload-manifest', $booking->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-2">
                    @if($booking->passenger_manifest_file)
                        <a href="{{ Storage::url($booking->passenger_manifest_file) }}" target="_blank" class="d-block mb-2 fw-bold text-success text-decoration-none">
                            <i class="fas fa-file-check me-1"></i> Đã gửi danh sách (Tải về)
                        </a>
                    @else
                        <div class="text-muted small mb-2">Vui lòng tải lên danh sách thành viên để làm thủ tục bảo hiểm.</div>
                    @endif
                    
                    <input type="file" name="manifest_file" class="form-control form-control-sm" required>
                </div>
                <button class="btn btn-primary btn-sm w-100">
                    <i class="fas fa-cloud-upload-alt me-1"></i> Cập nhật danh sách
                </button>
            </form>

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

{{-- [MỚI] MODAL HỦY TOUR CHO USER --}}
<div class="modal fade" id="userCancelModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('bookings.cancel', $booking->id) }}" method="POST">
                @csrf
                
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-exclamation-triangle me-2"></i>Xác nhận Hủy Tour
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="alert alert-warning small border-warning">
                        <i class="fas fa-info-circle"></i> 
                        Bạn có chắc chắn muốn hủy đơn hàng <strong>#{{ $booking->id }}</strong> không? <br>
                        Hành động này sẽ giải phóng chỗ đã đặt và không thể hoàn tác.
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Vui lòng cho biết lý do hủy <span class="text-danger">*</span></label>
                        <textarea name="cancel_reason" class="form-control" rows="3" required 
                                  placeholder="VD: Bận việc đột xuất, Đổi ngày đi..."></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-check"></i> Xác nhận Hủy
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- [MỚI] MODAL YÊU CẦU HỦY (Dành cho đơn ĐÃ THANH TOÁN) --}}
<div class="modal fade" id="requestRefundModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('bookings.cancel', $booking->id) }}" method="POST">
                @csrf
                {{-- Input ẩn để Controller biết đây là yêu cầu hoàn tiền --}}
                <input type="hidden" name="is_refund_request" value="1">

                <div class="modal-header bg-warning">
                    <h5 class="modal-title text-dark">
                        <i class="fas fa-money-bill-wave me-2"></i>Hỗ trợ Hủy & Hoàn tiền
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="alert alert-info small border-info bg-light text-dark">
                        <i class="fas fa-phone-alt me-1"></i> 
                        Đơn hàng đã thanh toán. Để được hỗ trợ thủ tục hoàn tiền nhanh nhất, vui lòng điền thông tin bên dưới và liên hệ <strong>Hotline: 0987.654.321</strong> sau khi gửi yêu cầu.
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Lý do hủy tour <span class="text-danger">*</span></label>
                        <textarea name="cancel_reason" class="form-control" rows="2" required placeholder="VD: Bận việc đột xuất..."></textarea>
                    </div>

                    <hr>
                    <h6 class="text-primary fw-bold mb-3"><i class="fas fa-university me-1"></i> Thông tin nhận tiền hoàn (Bắt buộc)</h6>
                    
                    <div class="mb-2">
                        <label class="small fw-bold">Tên Ngân hàng <span class="text-danger">*</span></label>
                        <input type="text" name="refund_bank" class="form-control form-control-sm" placeholder="VD: Vietcombank, Techcombank..." required>
                    </div>
                    
                    <div class="row">
                        <div class="col-6 mb-2">
                            <label class="small fw-bold">Số tài khoản <span class="text-danger">*</span></label>
                            <input type="text" name="refund_account" class="form-control form-control-sm" required>
                        </div>
                        <div class="col-6 mb-2">
                            <label class="small fw-bold">Chủ tài khoản <span class="text-danger">*</span></label>
                            <input type="text" name="refund_holder" class="form-control form-control-sm" placeholder="VIET HOA KHONG DAU" required>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-warning fw-bold text-dark">
                        <i class="fas fa-paper-plane me-1"></i> Gửi yêu cầu
                    </button>
                </div>
            </form>
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
                    </script>
                @endsection

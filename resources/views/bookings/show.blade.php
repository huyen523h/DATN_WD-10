@extends('layouts.app')

@section('title', 'Chi tiết đặt tour - Tour365')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-calendar-check text-primary"></i> Chi tiết đặt tour #{{ $booking->id }}</h2>
        <a href="{{ route('bookings.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left"></i> Quay lại</a>
    </div>

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
                    <div class="d-flex justify-content-between mb-3">
                        <span>Tổng tiền:</span>
                        <span class="fw-bold text-primary fs-5">{{ number_format($booking->total_amount) }}đ</span>
                    </div>
                    @if ($booking->status === 'paid' || $booking->status === 'completed')
                        <div class="alert alert-success text-center mb-0 p-2"><i class="fas fa-check-circle"></i> Đã thanh toán</div>
                    @else
                         <div class="alert alert-warning text-center mb-0 p-2">Chưa thanh toán</div>
                    @endif
                </div>
            </div>
            
             <div class="card border-0 shadow-sm">
                 <div class="card-header bg-white"><h6 class="mb-0">Danh sách đoàn</h6></div>
                 <div class="card-body">
                    <form action="{{ route('bookings.upload-manifest', $booking->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-2">
                             @if($booking->passenger_manifest_file)
                                <a href="{{ Storage::url($booking->passenger_manifest_file) }}" target="_blank" class="d-block mb-2 fw-bold text-success">
                                    <i class="fas fa-file-download"></i> Xem danh sách đã gửi
                                </a>
                            @endif
                            <input type="file" name="manifest_file" class="form-control form-control-sm" required>
                        </div>
                        <button class="btn btn-primary btn-sm w-100">Cập nhật</button>
                    </form>
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
                            // --- (CODE CŨ CỦA BẠN - GIỮ NGUYÊN) ---
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
                            // --- (HẾT CODE CŨ CỦA BẠN) ---


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

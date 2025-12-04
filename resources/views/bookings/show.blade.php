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
                                        @elseif($booking->status === 'confirmed') bg-success
                                        @elseif($booking->status === 'cancelled') bg-danger
                                        @else bg-secondary @endif fs-6">
                                            @switch($booking->status)
                                                @case('pending')
                                                    Chờ xác nhận
                                                @break

                                                @case('confirmed')
                                                    Đã xác nhận
                                                @break

                                                {{-- THÊM CASE MỚI NÀY --}}
                                                @case('paid')
                                                    Đã thanh toán
                                                @break

                                                @case('cancelled')
                                                    Đã hủy
                                                @break

                                                {{-- CASE 'completed' ĐÃ BỊ XÓA --}}

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
                                    <h5><i class="fas fa-map-marked-alt"></i> Thông tin khách hàng</h5>
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

                                    @if($booking->departure && ($booking->departure->guide || $booking->departure->vehicle_details))
    <div class="card mb-4 border-info">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0"><i class="fas fa-bus-alt me-2"></i> Thông tin Khởi hành</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <h6 class="fw-bold text-primary"><i class="fas fa-user-tie me-2"></i> Hướng dẫn viên</h6>
                    @if($booking->departure->guide)
                        <div class="d-flex align-items-center mt-2">
                            <div class="bg-secondary text-white rounded-circle d-flex justify-content-center align-items-center me-3" style="width: 50px; height: 50px; font-size: 20px;">
                                {{ substr($booking->departure->guide->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="mb-0 fw-bold">{{ $booking->departure->guide->name }}</p>
                                <p class="mb-0 text-muted small">{{ $booking->departure->guide->phone ?? 'Đang cập nhật SĐT' }}</p>
                                <p class="mb-0 text-muted small">{{ $booking->departure->guide->email }}</p>
                            </div>
                        </div>
                    @else
                        <p class="text-muted fst-italic">Đang sắp xếp HDV...</p>
                    @endif
                </div>

                <div class="col-md-6 mb-3">
                    <h6 class="fw-bold text-primary"><i class="fas fa-shuttle-van me-2"></i> Phương tiện di chuyển</h6>
                    <ul class="list-unstyled mt-2">
                        <li class="mb-2">
                            <strong>Xe:</strong> {{ $booking->departure->vehicle_details ?? 'Đang cập nhật' }}
                        </li>
                        <li class="mb-2">
                            <strong>SĐT Tài xế:</strong> {{ $booking->departure->driver_contact ?? 'Đang cập nhật' }}
                        </li>
                    </ul>
                </div>
                
                @if($booking->departure->itinerary_file)
                <div class="col-12 mt-2 border-top pt-3">
                    <h6 class="fw-bold text-primary"><i class="fas fa-file-alt me-2"></i> Tài liệu chuyến đi</h6>
                    <a href="{{ Storage::url($booking->departure->itinerary_file) }}" target="_blank" class="btn btn-outline-primary btn-sm mt-2">
                        <i class="fas fa-download me-1"></i> Tải xuống Lịch trình chi tiết / Hợp đồng
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endif

                                    <!-- Payment Info -->
                                    @if ($booking->payment->count() > 0)
                                        <div class="card border-0 shadow-sm">
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
                                                    <i class="fas fa-info-circle"></i>
                                                    Vui lòng chờ admin xác nhận đơn hàng để thanh toán.
                                                </div>
                                                <form action="{{ route('bookings.destroy', $booking->id) }}" method="POST"
                                                    onsubmit="return confirm('Bạn có chắc chắn muốn hủy đặt tour này?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger w-100">
                                                        <i class="fas fa-times"></i> Hủy đặt tour
                                                    </button>
                                                </form>
                                            @elseif ($booking->status === 'confirmed')
                                                <div class="alert alert-info">
                                                    <i class="fas fa-info-circle"></i>
                                                    Đơn hàng của bạn đã được xác nhận. Vui lòng thanh toán.
                                                </div>
                                                <div class="d-grid gap-2">
                                                    <form action="{{ route('momo_payment', $booking->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        <input type="hidden" name="total_momo"
                                                            value="{{ optional($booking->payment->first())->amount ?? $booking->total_amount }}">
                                                        <button type="submit" class="btn btn-danger w-100">
                                                            <i class="fas fa-credit-card"></i> Thanh toán qua MOMO
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('bookings.destroy', $booking->id) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('Bạn có chắc chắn muốn hủy đặt tour này?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-outline-danger w-100">
                                                            <i class="fas fa-times"></i> Hủy đặt tour
                                                        </button>
                                                    </form>
                                                </div>
                                            @elseif ($booking->status === 'paid')
                                                <div class="alert alert-success">
                                                    <i class="fas fa-check-circle"></i>
                                                    Bạn đã thanh toán thành công đơn hàng này.
                                                </div>

                                                @php
                                                    $isCompleted = $booking->isCompleted();
                                                @endphp

                                                @if ($isCompleted && !$booking->review)
                                                    <button type="button" class="btn btn-success w-100"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#reviewModal-{{ $booking->id }}">
                                                        <i class="fas fa-star"></i> Viết đánh giá
                                                    </button>
                                                @elseif ($isCompleted && $booking->review)
                                                    <span class="btn btn-outline-success w-100 disabled">
                                                        <i class="fas fa-check"></i> Đã đánh giá
                                                    </span>
                                                @endif
                                            @elseif ($booking->status === 'cancelled')
                                                <div class="alert alert-danger">
                                                    <i class="fas fa-exclamation-triangle"></i>
                                                    Đặt tour này đã bị hủy.
                                                </div>
                                            @elseif($booking->status === 'completed')
                                                <div class="alert alert-success">
                                                    <i class="fas fa-check-circle"></i>
                                                    Tour đã hoàn thành (Trạng thái cũ).

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

                    

                    @if ($booking->isCompleted() && !$booking->review)
                        <div class="modal fade" id="reviewModal-{{ $booking->id }}" tabindex="-1"
                            aria-labelledby="reviewModalLabel-{{ $booking->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('bookings.reviews.store', $booking->id) }}" method="POST"
                                        class="form-review-submission">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="reviewModalLabel-{{ $booking->id }}">Đánh giá
                                                tour: {{ $booking->tour->title }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label d-block mb-2">Bạn thấy chuyến đi này thế
                                                    nào?</label>
                                                <div class="rating-stars">
                                                    <input type="radio" name="rating"
                                                        id="rating-5-{{ $booking->id }}" value="5" required>
                                                    <label for="rating-5-{{ $booking->id }}">★</label>

                                                    <input type="radio" name="rating"
                                                        id="rating-4-{{ $booking->id }}" value="4">
                                                    <label for="rating-4-{{ $booking->id }}">★</label>

                                                    <input type="radio" name="rating"
                                                        id="rating-3-{{ $booking->id }}" value="3">
                                                    <label for="rating-3-{{ $booking->id }}">★</label>

                                                    <input type="radio" name="rating"
                                                        id="rating-2-{{ $booking->id }}" value="2">
                                                    <label for="rating-2-{{ $booking->id }}">★</label>

                                                    <input type="radio" name="rating"
                                                        id="rating-1-{{ $booking->id }}" value="1">
                                                    <label for="rating-1-{{ $booking->id }}">★</label>
                                                </div>

                                                <style>
                                                    .rating-stars {
                                                        display: inline-flex;
                                                        flex-direction: row-reverse;
                                                        /* để tô sao từ trái sang phải */
                                                        gap: 4px;
                                                        align-items: center;
                                                    }

                                                    .rating-stars input[type="radio"] {
                                                        display: none;
                                                        /* ẩn radio gốc */
                                                    }

                                                    .rating-stars label {
                                                        font-size: 26px;
                                                        line-height: 1;
                                                        cursor: pointer;
                                                        color: #e2e8f0;
                                                        /* xám nhạt */
                                                        transition: color 0.2s ease, transform 0.15s ease;
                                                    }

                                                    /* Hover: tô vàng các sao và phóng to nhẹ */
                                                    .rating-stars label:hover,
                                                    .rating-stars label:hover~label {
                                                        color: #ffc107;
                                                        transform: scale(1.08);
                                                    }

                                                    /* Khi chọn: giữ màu vàng cho các sao đã chọn */
                                                    .rating-stars input[type="radio"]:checked~label {
                                                        color: #ffc107;
                                                    }

                                                    /* Hỗ trợ focus bằng bàn phím */
                                                    .rating-stars input[type="radio"]:focus-visible+label {
                                                        outline: 2px solid #0d6efd;
                                                        outline-offset: 2px;
                                                    }
                                                </style>
                                            </div>
                                            <div class="mb-3">
                                                <label for="comment-{{ $booking->id }}" class="form-label">Viết bình
                                                    luận của bạn</label>
                                                <textarea name="comment" id="comment-{{ $booking->id }}" class="form-control" rows="4" required></textarea>
                                            </div>
                                            <div class="alert alert-danger d-none" role="alert"
                                                id="review-error-{{ $booking->id }}"></div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Hủy</button>
                                            <button type="submit" class="btn btn-primary">Gửi đánh giá</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
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

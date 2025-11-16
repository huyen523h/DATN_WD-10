@extends('layouts.app')

@section('title', 'Đặt tour của tôi - Tour365')

@section('content')
    <div class="container py-5">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><i class="fas fa-calendar-check"></i> Đặt tour của tôi</h2>
                    <a href="{{ route('tours.index') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Đặt tour mới
                    </a>
                </div>

                @if ($bookings->count() > 0)
                    <div class="row">
                        @foreach ($bookings as $booking)
                            <div class="col-lg-6 mb-4">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                @if ($booking->tour->images->count() > 0)
                                                    <img src="{{ $booking->tour->images->first()->image_url }}"
                                                        class="img-fluid rounded" alt="{{ $booking->tour->title }}"
                                                        style="height: 150px; object-fit: cover; width: 100%;">
                                                @else
                                                    <img src="https://via.placeholder.com/300x150/4F46E5/ffffff?text={{ urlencode($booking->tour->title) }}"
                                                        class="img-fluid rounded" alt="{{ $booking->tour->title }}"
                                                        style="height: 150px; object-fit: cover; width: 100%;">
                                                @endif
                                            </div>
                                            <div class="col-md-8">
                                                <h5 class="card-title">{{ $booking->tour->title }}</h5>
                                                <p class="text-muted mb-2">
                                                    <i class="fas fa-calendar"></i>
                                                    {{ $booking->departure?->departure_date
                                                        ? \Carbon\Carbon::parse($booking->departure->departure_date)->format('d/m/Y')
                                                        : 'Chưa có ngày khởi hành' }}
                                                </p>
                                                <p class="text-muted mb-2">
                                                    <i class="fas fa-users"></i>
                                                    {{ $booking->adults }} người lớn
                                                    @if ($booking->children > 0)
                                                        , {{ $booking->children }} trẻ em
                                                    @endif
                                                    @if ($booking->infants > 0)
                                                        , {{ $booking->infants }} em bé
                                                    @endif
                                                </p>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <span
                                                            class="badge 
                                                        @if ($booking->status === 'pending') bg-warning
                                                        @elseif($booking->status === 'confirmed') bg-success
                                                        @elseif($booking->status === 'cancelled') bg-danger
                                                        @else bg-secondary @endif">
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

                                                                @case('cancelled')
                                                                    Đã hủy
                                                                @break

                                                                @default
                                                                    {{ $booking->status }}
                                                                @break
                                                            @endswitch
                                                        </span>
                                                    </div>
                                                    <div class="text-end">
                                                        <div class="h5 text-primary mb-0">
                                                            {{ number_format($booking->total_amount, 0, ',', '.') }}đ
                                                        </div>
                                                        <small class="text-muted">Tổng cộng</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted">
                                                Đặt lúc: {{ $booking->created_at->format('d/m/Y H:i') }}
                                            </small>

                                            <div class="d-flex gap-2">
                                                <a href="{{ route('bookings.show', $booking) }}"
                                                    class="btn btn-outline-primary btn-sm">
                                                    <i class="fas fa-eye"></i> Xem chi tiết
                                                </a>

                                                @if ($booking->status == 'confirmed')
                                                    <form action="{{ route('momo_payment', $booking->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        <input type="hidden" name="total_momo"
                                                            value="{{ optional($booking->payment->first())->amount ?? $booking->total_amount }}">
                                                        <button type="submit" class="btn btn-danger btn-sm">
                                                            <i class="fas fa-credit-card"></i> Thanh toán MOMO
                                                        </button>
                                                    </form>
                                                @endif

                                                @php
                                                    // Gọi hàm isCompleted() chúng ta vừa tạo trong Model
                                                    $isCompleted = $booking->isCompleted();
                                                @endphp

                                                {{-- 1. Nếu đã hoàn thành VÀ chưa có review --}}
                                                @if ($isCompleted && !$booking->review)
                                                    <button type="button" class="btn btn-success btn-sm"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#reviewModal-{{ $booking->id }}">
                                                        <i class="fas fa-star"></i> Viết đánh giá
                                                    </button>

                                                    {{-- 2. Nếu đã hoàn thành VÀ đã có review --}}
                                                @elseif ($isCompleted && $booking->review)
                                                    <span class="btn btn-outline-success btn-sm disabled">
                                                        <i class="fas fa-check"></i> Đã đánh giá
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="row">
                        <div class="col-12">
                            <nav aria-label="Bookings pagination">
                                {{ $bookings->links() }}
                            </nav>
                        </div>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-calendar-times fa-4x text-muted mb-4"></i>
                        <h4>Chưa có đặt tour nào</h4>
                        <p class="text-muted mb-4">Hãy khám phá và đặt tour đầu tiên của bạn</p>
                        <a href="{{ route('tours.index') }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-search"></i> Xem tours
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @foreach ($bookings as $booking)
        @if ($booking->isCompleted() && !$booking->review)
            <div class="modal fade" id="reviewModal-{{ $booking->id }}" tabindex="-1"
                aria-labelledby="reviewModalLabel-{{ $booking->id }}" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="{{ route('bookings.reviews.store', $booking->id) }}" method="POST"
                            class="form-review-submission">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title" id="reviewModalLabel-{{ $booking->id }}">Đánh giá tour:
                                    {{ $booking->tour->title }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label d-block mb-2">Bạn thấy chuyến đi này thế nào?</label>
                                    <div class="rating-stars">
                                        <input type="radio" name="rating" id="rating-5-{{ $booking->id }}"
                                            value="5" required>
                                        <label for="rating-5-{{ $booking->id }}">★</label>

                                        <input type="radio" name="rating" id="rating-4-{{ $booking->id }}"
                                            value="4">
                                        <label for="rating-4-{{ $booking->id }}">★</label>

                                        <input type="radio" name="rating" id="rating-3-{{ $booking->id }}"
                                            value="3">
                                        <label for="rating-3-{{ $booking->id }}">★</label>

                                        <input type="radio" name="rating" id="rating-2-{{ $booking->id }}"
                                            value="2">
                                        <label for="rating-2-{{ $booking->id }}">★</label>

                                        <input type="radio" name="rating" id="rating-1-{{ $booking->id }}"
                                            value="1">
                                        <label for="rating-1-{{ $booking->id }}">★</label>
                                    </div>

                                    {{-- Style CHỈ dùng cho cụm sao này --}}
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
                                    <label for="comment-{{ $booking->id }}" class="form-label">Viết bình luận của
                                        bạn</label>
                                    <textarea name="comment" id="comment-{{ $booking->id }}" class="form-control" rows="4" required></textarea>
                                </div>
                                <div class="alert alert-danger d-none" role="alert"
                                    id="review-error-{{ $booking->id }}"></div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                <button type="submit" class="btn btn-primary">Gửi đánh giá</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    @endforeach
@endsection
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

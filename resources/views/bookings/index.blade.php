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
                                                {{-- <small class="text-danger">DEBUG STATUS: {{ $booking->status }}</small> --}}
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
        {{-- BADGE HIỂN THỊ TRẠNG THÁI --}}
        @php
            $statusBadges = [
                'pending'   => ['class' => 'bg-warning text-dark', 'icon' => 'fa-clock', 'text' => 'Chờ thanh toán'],
                'paid'      => ['class' => 'bg-success', 'icon' => 'fa-check-circle', 'text' => 'Đã thanh toán'],
                'confirmed' => ['class' => 'bg-info text-dark', 'icon' => 'fa-check', 'text' => 'Đã xác nhận'],
                'cancelled' => ['class' => 'bg-danger', 'icon' => 'fa-times-circle', 'text' => 'Đã hủy'],
                'completed' => ['class' => 'bg-primary', 'icon' => 'fa-flag-checkered', 'text' => 'Hoàn thành'],
            ];
            $currentStatus = $statusBadges[$booking->status] ?? ['class' => 'bg-secondary', 'icon' => 'fa-info-circle', 'text' => ucfirst($booking->status)];
        @endphp

        <span class="badge {{ $currentStatus['class'] }} shadow-sm">
            <i class="fas {{ $currentStatus['icon'] }} me-1"></i> {{ $currentStatus['text'] }}
        </span>
    </div>
    
    {{-- PHẦN GIÁ TIỀN (GIỮ NGUYÊN) --}}
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
                                        <div class="d-flex justify-content-between flex-wrap">
                                            <small class="text-muted mb-2">
                                                Đặt lúc: {{ $booking->created_at->format('d/m/Y H:i') }}
                                            </small>
                                            <div class="d-flex gap-2 flex-wrap">
                                                <a href="{{ route('bookings.show', $booking) }}"
                                                    class="btn btn-outline-primary btn-sm">
                                                    <i class="fas fa-eye"></i> Xem chi tiết
                                                </a>

 {{-- [THÊM] Nút Đánh giá (Hiện cạnh nút Xem chi tiết) --}}
@if ($booking->status === 'completed')
    @if (!$booking->review)
        <button type="button"
                class="btn btn-warning btn-sm ms-2"
                data-bs-toggle="modal"
                data-bs-target="#reviewModal-{{ $booking->id }}">
            <i class="fas fa-star"></i> Đánh giá
        </button>
    @else
        <button class="btn btn-success btn-sm ms-2" disabled>
            <i class="fas fa-check"></i> Đã đánh giá
        </button>
    @endif
@endif

                        @if(in_array($booking->status, ['pending', 'confirmed']))
                                                <a href="{{ route('bookings.show', $booking) }}" 
                                                  class="btn btn-danger btn-sm shadow-sm fw-bold">
                                                  <i class="fas fa-credit-card me-1"></i> Thanh toán ngay
                                              </a> 
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
@endsection

@foreach ($bookings as $booking)
    @if ($booking->status === 'completed' && !$booking->review)
    <div class="modal fade" id="reviewModal-{{ $booking->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('bookings.reviews.store', $booking->id) }}" method="POST">
                @csrf
                <div class="modal-header bg-warning">
                    <h5 class="modal-title text-dark">
                        <i class="fas fa-star me-2"></i>Đánh giá: {{ Str::limit($booking->tour->title, 30) }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <p class="mb-2">Bạn chấm mấy sao cho chuyến đi này?</p>
                        
                        {{-- Rating Stars (Lưu ý: ID phải kèm booking->id để không bị trùng) --}}
                        <div class="rating-group d-flex flex-row-reverse justify-content-center gap-2">
                            @for($i = 5; $i >= 1; $i--)
                                <input type="radio" name="rating" id="star{{$i}}-{{$booking->id}}" value="{{$i}}" class="d-none peer" {{ $i==5 ? 'checked' : '' }}>
                                <label for="star{{$i}}-{{$booking->id}}" class="fas fa-star fa-2x text-muted cursor-pointer hover:text-warning peer-checked:text-warning" 
                                       onclick="setRating(this, {{$booking->id}})"></label>
                            @endfor
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Nhận xét chi tiết <span class="text-danger">*</span></label>
                        <textarea name="comment" class="form-control" rows="3" placeholder="Chia sẻ cảm nhận của bạn..." required></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Gửi đánh giá</button>
                </div>
            </form>
        </div>
    </div>
  </div>
@endif
@endforeach
@section('scripts')
<script>

    // Hàm xử lý đổi màu sao đơn giản
    function setRating(label, bookingId) {
        // Tìm input radio tương ứng
        const inputId = label.getAttribute('for');
        const input = document.getElementById(inputId);
        if(input) input.checked = true;

        // Tìm tất cả các sao trong modal hiện tại
        const modal = document.getElementById('reviewModal-' + bookingId);
        const labels = modal.querySelectorAll('.rating-group label');
        
        // Reset hết về màu xám
        labels.forEach(l => {
            l.classList.remove('text-warning');
            l.classList.add('text-muted');
        });
        
        let currentLabel = label;
        // Tô màu chính nó
        currentLabel.classList.remove('text-muted');
        currentLabel.classList.add('text-warning');

        // Tô màu các sao bên phải (thực ra là các sao nhỏ hơn vì flex-row-reverse)
        let nextSibling = currentLabel.nextElementSibling;
        while(nextSibling) {
            if(nextSibling.tagName === 'LABEL') {
                nextSibling.classList.remove('text-muted');
                nextSibling.classList.add('text-warning');
            }
            nextSibling = nextSibling.nextElementSibling;
        }
    }
    // Validate terms before payment for booking list page
    function validateTermsBeforePayment(event, bookingId) {
        const agreeTerms = document.getElementById('agreeTerms' + bookingId);
        if (!agreeTerms || !agreeTerms.checked) {
            event.preventDefault();
            
            // Show alert
            const alertDiv = document.createElement('div');
            alertDiv.className = 'alert alert-warning alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3';
            alertDiv.style.zIndex = '9999';
            alertDiv.style.minWidth = '400px';
            alertDiv.style.maxWidth = '90%';
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

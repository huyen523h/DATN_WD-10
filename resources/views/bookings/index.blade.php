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

                                                                @case('cancelled')
                                                                    Đã hủy
                                                                @break

                                                                @case('completed')
                                                                    Hoàn thành
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
                                        <div class="d-flex justify-content-between flex-wrap">
                                            <small class="text-muted mb-2">
                                                Đặt lúc: {{ $booking->created_at->format('d/m/Y H:i') }}
                                            </small>
                                            <div class="d-flex gap-2 flex-wrap">
                                                <a href="{{ route('bookings.show', $booking) }}"
                                                    class="btn btn-outline-primary btn-sm">
                                                    <i class="fas fa-eye"></i> Xem chi tiết
                                                </a>
                                                @if($booking->status !== 'cancelled' && $booking->status !== 'paid' && $booking->status !== 'completed')
                                                    <!-- Terms checkbox for this booking -->
                                                    <div class="form-check d-inline-flex align-items-center ms-2">
                                                        <input class="form-check-input" type="checkbox" 
                                                               id="agreeTerms{{ $booking->id }}" 
                                                               style="margin-top: 0;">
                                                        <label class="form-check-label small" 
                                                               for="agreeTerms{{ $booking->id }}"
                                                               style="white-space: nowrap;">
                                                            <a href="{{ route('payment-policy') }}" 
                                                               target="_blank" 
                                                               class="text-primary fw-bold">
                                                                Đã đọc điều khoản
                                                                <i class="fas fa-external-link-alt ms-1" style="font-size: 0.7rem;"></i>
                                                            </a>
                                                        </label>
                                                    </div>
                                                    <form action="{{ route('momo_payment', $booking->id) }}" 
                                                          method="POST" 
                                                          class="d-inline ms-1"
                                                          id="momoForm{{ $booking->id }}"
                                                          onsubmit="return validateTermsBeforePayment(event, {{ $booking->id }})">
                                                        @csrf
                                                        <button type="submit" class="btn btn-danger btn-sm">
                                                            <i class="fas fa-wallet"></i> Thanh toán qua MOMO
                                                        </button>
                                                    </form>
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

@section('scripts')
<script>
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

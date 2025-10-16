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
                                    <span class="badge
                                        @if($booking->status === 'pending') bg-warning
                                        @elseif($booking->status === 'confirmed') bg-success
                                        @elseif($booking->status === 'cancelled') bg-danger
                                        @else bg-secondary
                                        @endif fs-6">
                                        @switch($booking->status)
                                            @case('pending') Chờ xác nhận @break
                                            @case('confirmed') Đã xác nhận @break
                                            @case('cancelled') Đã hủy @break
                                            @case('completed') Hoàn thành @break
                                            @default {{ $booking->status }} @break
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
                    </div>

                    <!-- Tour Information -->
                    <div class="card mb-4 border-0 shadow-sm">
                        <div class="card-header">
                            <h5><i class="fas fa-map-marked-alt"></i> Thông tin tour</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    @if($booking->tour->images->count() > 0)
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
                                                <i class="fas fa-clock"></i> {{ $booking->tour->duration_days }} ngày
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
                    </div>

                    <!-- Passenger Information -->
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
                                        @if($booking->children > 0)
                                            <li><i class="fas fa-child"></i> {{ $booking->children }} trẻ em</li>
                                        @endif
                                        @if($booking->infants > 0)
                                            <li><i class="fas fa-baby"></i> {{ $booking->infants }} em bé</li>
                                        @endif
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    @if($booking->promotion_code)
                                        <h6>Mã giảm giá</h6>
                                        <p class="text-success">
                                            <i class="fas fa-tag"></i> {{ $booking->promotion_code }}
                                        </p>
                                    @endif

                                    @if($booking->note)
                                        <h6>Ghi chú</h6>
                                        <p class="text-muted">{{ $booking->note }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Information -->
                    @if($booking->payments->count() > 0)
                        <div class="card border-0 shadow-sm">
                            <div class="card-header">
                                <h5><i class="fas fa-credit-card"></i> Thông tin thanh toán</h5>
                            </div>
                            <div class="card-body">
                                @foreach($booking->payments as $payment)
                                    <div class="row border-bottom pb-3 mb-3">
                                        <div class="col-md-6">
                                            <h6>Phương thức thanh toán</h6>
                                            <p class="text-muted">
                                                @switch($payment->payment_method)
                                                    @case('cash') Tiền mặt @break
                                                    @case('bank_transfer') Chuyển khoản @break
                                                    @case('credit_card') Thẻ tín dụng @break
                                                    @case('momo') MoMo @break
                                                    @case('zalopay') ZaloPay @break
                                                    @default {{ $payment->payment_method }} @break
                                                @endswitch
                                            </p>
                                        </div>
                                        <div class="col-md-6">
                                            <h6>Trạng thái</h6>
                                            <span class="badge
                                                @if($payment->status === 'pending') bg-warning
                                                @elseif($payment->status === 'completed') bg-success
                                                @elseif($payment->status === 'failed') bg-danger
                                                @elseif($payment->status === 'refunded') bg-info
                                                @else bg-secondary
                                                @endif">
                                                @switch($payment->status)
                                                    @case('pending') Chờ thanh toán @break
                                                    @case('completed') Đã thanh toán @break
                                                    @case('failed') Thất bại @break
                                                    @case('refunded') Đã hoàn tiền @break
                                                    @default {{ $payment->status }} @break
                                                @endswitch
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Actions Sidebar -->
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header">
                            <h5><i class="fas fa-cogs"></i> Thao tác</h5>
                        </div>
                        <div class="card-body">
                            @if($booking->status === 'pending')
                                <div class="d-grid gap-2">
                                    <button class="btn btn-success" disabled>
                                        <i class="fas fa-credit-card"></i> Thanh toán
                                    </button>
                                    <button class="btn btn-outline-danger" disabled>
                                        <i class="fas fa-times"></i> Hủy đặt tour
                                    </button>
                                </div>
                                <div class="mt-3">
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle"></i>
                                        Vui lòng chờ xác nhận từ admin để thanh toán
                                    </small>
                                </div>
                            @elseif($booking->status === 'confirmed')
                                <div class="d-grid gap-2">
                                    <a href="{{ route('payments.checkout', $booking) }}" class="btn btn-primary">
                                        <i class="fas fa-credit-card"></i> Thanh toán ngay
                                    </a>
                                    <button class="btn btn-outline-danger">
                                        <i class="fas fa-times"></i> Hủy đặt tour
                                    </button>
                                </div>
                            @elseif($booking->status === 'cancelled')
                                <div class="alert alert-danger">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    Đặt tour đã bị hủy
                                </div>
                            @elseif($booking->status === 'completed')
                                <div class="alert alert-success">
                                    <i class="fas fa-check-circle"></i>
                                    Tour đã hoàn thành
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
@endsection

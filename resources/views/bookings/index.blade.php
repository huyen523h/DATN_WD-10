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

            @if($bookings->count() > 0)
                <div class="row">
                    @foreach($bookings as $booking)
                        <div class="col-lg-6 mb-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            @if($booking->tour->images->count() > 0)
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
                                                {{ \Carbon\Carbon::parse($booking->departure->departure_date)->format('d/m/Y') }}
                                            </p>
                                            <p class="text-muted mb-2">
                                                <i class="fas fa-users"></i> 
                                                {{ $booking->adults }} người lớn
                                                @if($booking->children > 0), {{ $booking->children }} trẻ em @endif
                                                @if($booking->infants > 0), {{ $booking->infants }} em bé @endif
                                            </p>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <span class="badge 
                                                        @if($booking->status === 'pending') bg-warning
                                                        @elseif($booking->status === 'confirmed') bg-success
                                                        @elseif($booking->status === 'cancelled') bg-danger
                                                        @else bg-secondary
                                                        @endif">
                                                        @switch($booking->status)
                                                            @case('pending') Chờ xác nhận @break
                                                            @case('confirmed') Đã xác nhận @break
                                                            @case('cancelled') Đã hủy @break
                                                            @case('completed') Hoàn thành @break
                                                            @default {{ $booking->status }} @break
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
                                    <div class="d-flex justify-content-between">
                                        <small class="text-muted">
                                            Đặt lúc: {{ $booking->created_at->format('d/m/Y H:i') }}
                                        </small>
                                        <a href="{{ route('bookings.show', $booking) }}" class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-eye"></i> Xem chi tiết
                                        </a>
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

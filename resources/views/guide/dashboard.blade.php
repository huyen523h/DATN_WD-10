@extends('layouts.guide')

@section('title', 'Tổng quan - Hướng dẫn viên')
@section('page-title', 'Tổng quan')

@section('content')
    @php
        $totalDepartures = $upcoming->count();
        $todayDepartures = $upcoming->filter(function($dep) {
            return $dep->departure_date && $dep->departure_date->isToday();
        })->count();
        $upcomingCount = $upcoming->filter(function($dep) {
            return $dep->departure_date && $dep->departure_date->isFuture();
        })->count();
        $totalGuests = $upcoming->sum(function($dep) {
            return $dep->bookings->sum(function($booking) {
                return $booking->adults + $booking->children + $booking->infants;
            });
        });
    @endphp

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="guide-stat-card">
                <div class="guide-stat-icon primary">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="guide-stat-value">{{ $totalDepartures }}</div>
                <p class="guide-stat-label">Tổng lịch khởi hành</p>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="guide-stat-card">
                <div class="guide-stat-icon warning">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="guide-stat-value">{{ $todayDepartures }}</div>
                <p class="guide-stat-label">Hôm nay</p>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="guide-stat-card">
                <div class="guide-stat-icon success">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <div class="guide-stat-value">{{ $upcomingCount }}</div>
                <p class="guide-stat-label">Sắp tới</p>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="guide-stat-card">
                <div class="guide-stat-icon primary">
                    <i class="fas fa-users"></i>
                </div>
                <div class="guide-stat-value">{{ $totalGuests }}</div>
                <p class="guide-stat-label">Tổng khách</p>
            </div>
        </div>
    </div>

    <!-- Upcoming Departures -->
    <div class="guide-card">
        <div class="guide-card-header">
            <i class="fas fa-calendar-alt me-2"></i>
            Lịch tour sắp tới
        </div>
        <div class="guide-card-body">
            @if($upcoming->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-calendar-times fa-4x text-muted mb-3"></i>
                    <h5 class="text-muted">Chưa có lịch khởi hành nào</h5>
                    <p class="text-muted">Bạn sẽ nhận được thông báo khi được phân công tour mới.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table guide-table">
                        <thead>
                            <tr>
                                <th>Ngày khởi hành</th>
                                <th>Tour</th>
                                <th>Giờ bắt đầu</th>
                                <th>Số khách</th>
                                <th>Trạng thái</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($upcoming as $departure)
                                <tr>
                                    <td>
                                        <div class="fw-bold">{{ $departure->departure_date?->format('d/m/Y') }}</div>
                                        <small class="text-muted">
                                            {{ $departure->departure_date?->diffForHumans() }}
                                        </small>
                                    </td>
                                    <td>
                                        <div class="fw-bold">{{ $departure->tour?->title }}</div>
                                        @if($departure->meeting_point)
                                            <small class="text-muted">
                                                <i class="fas fa-map-marker-alt"></i> {{ $departure->meeting_point }}
                                            </small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($departure->start_time)
                                            <span class="badge bg-light text-dark">
                                                <i class="fas fa-clock"></i> {{ $departure->start_time->format('H:i') }}
                                            </span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">
                                            <i class="fas fa-users"></i> 
                                            {{ $departure->bookings->sum(function($booking) { return $booking->adults + $booking->children + $booking->infants; }) }} khách
                                        </span>
                                    </td>
                                    <td>
                                        @if($departure->departure_date && $departure->departure_date->isToday())
                                            <span class="guide-badge warning">Hôm nay</span>
                                        @elseif($departure->departure_date && $departure->departure_date->isFuture())
                                            <span class="guide-badge upcoming">Sắp tới</span>
                                        @else
                                            <span class="guide-badge completed">Đã qua</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('guide.departures.show', $departure) }}" 
                                           class="btn btn-guide-primary btn-sm">
                                            <i class="fas fa-eye"></i> Xem chi tiết
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3 text-center">
                    <a href="{{ route('guide.departures') }}" class="btn btn-outline-primary">
                        <i class="fas fa-list"></i> Xem tất cả lịch khởi hành
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection

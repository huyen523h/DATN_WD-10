@extends('layouts.guide')

@section('title', 'Lịch khởi hành - Hướng dẫn viên')
@section('page-title', 'Lịch khởi hành')

@section('content')
    <!-- Filter Card -->
    <div class="guide-card mb-4">
        <div class="guide-card-body">
            <form method="get" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-bold">
                        <i class="fas fa-calendar-alt me-2"></i>Từ ngày
                    </label>
                    <input type="date" name="from" value="{{ request('from') }}" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">
                        <i class="fas fa-calendar-check me-2"></i>Đến ngày
                    </label>
                    <input type="date" name="to" value="{{ request('to') }}" class="form-control">
                </div>
                <div class="col-md-4 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-guide-primary flex-fill">
                        <i class="fas fa-filter"></i> Lọc
                    </button>
                    <a href="{{ route('guide.departures') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-redo"></i> Đặt lại
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Departures List -->
    <div class="guide-card">
        <div class="guide-card-header">
            <i class="fas fa-list me-2"></i>
            Danh sách lịch khởi hành
            <span class="badge bg-primary ms-2">{{ $departures->total() }}</span>
        </div>
        <div class="guide-card-body">
            @if($departures->isEmpty())
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
                                <th>Điểm hẹn</th>
                                <th>Số khách</th>
                                <th>Trạng thái</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($departures as $departure)
                                <tr>
                                    <td>
                                        <div class="fw-bold">{{ $departure->departure_date?->format('d/m/Y') }}</div>
                                        <small class="text-muted">
                                            {{ $departure->departure_date?->format('l') }}
                                        </small>
                                    </td>
                                    <td>
                                        <div class="fw-bold">{{ $departure->tour?->title }}</div>
                                        @if($departure->tour?->duration)
                                            <small class="text-muted">
                                                <i class="fas fa-clock"></i> {{ $departure->tour->duration }} ngày
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
                                        @if($departure->meeting_point)
                                            <small>
                                                <i class="fas fa-map-marker-alt text-primary"></i>
                                                {{ Str::limit($departure->meeting_point, 30) }}
                                            </small>
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
                                        @elseif($departure->departure_date && $departure->departure_date->isPast())
                                            <span class="guide-badge completed">Đã hoàn thành</span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('guide.departures.show', $departure) }}" 
                                           class="btn btn-guide-primary btn-sm">
                                            <i class="fas fa-eye"></i> Chi tiết
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $departures->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection

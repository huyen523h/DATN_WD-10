@extends('layouts.guide')

@section('title', 'Chi tiết lịch khởi hành - Hướng dẫn viên')
@section('page-title', 'Chi tiết lịch khởi hành')

@section('content')
    <div class="mb-3">
        <a href="{{ route('guide.departures') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
    </div>

    <div class="row">
        <!-- Left Column: Tour Info -->
        <div class="col-lg-8 mb-4">
            <!-- Tour Overview -->
            <div class="guide-card mb-4">
                <div class="guide-card-header">
                    <i class="fas fa-info-circle me-2"></i>
                    Thông tin tour
                </div>
                <div class="guide-card-body">
                    <h3 class="mb-3">{{ $departure->tour?->title }}</h3>
                    
                    @if($departure->tour?->images->count() > 0)
                        <img src="{{ $departure->tour->images->first()->image_url }}" 
                             alt="{{ $departure->tour->title }}"
                             class="img-fluid rounded mb-3" 
                             style="max-height: 300px; width: 100%; object-fit: cover;">
                    @endif

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <div class="guide-stat-icon primary me-3" style="width: 50px; height: 50px;">
                                    <i class="fas fa-calendar"></i>
                                </div>
                                <div>
                                    <div class="text-muted small">Ngày khởi hành</div>
                                    <div class="fw-bold">{{ $departure->departure_date?->format('d/m/Y') }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <div class="guide-stat-icon primary me-3" style="width: 50px; height: 50px;">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div>
                                    <div class="text-muted small">Giờ bắt đầu</div>
                                    <div class="fw-bold">
                                        {{ $departure->start_time ? $departure->start_time->format('H:i') : '—' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if($departure->meeting_point)
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <div class="guide-stat-icon primary me-3" style="width: 50px; height: 50px;">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <div>
                                    <div class="text-muted small">Điểm hẹn</div>
                                    <div class="fw-bold">{{ $departure->meeting_point }}</div>
                                </div>
                            </div>
                        </div>
                        @endif
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <div class="guide-stat-icon success me-3" style="width: 50px; height: 50px;">
                                    <i class="fas fa-users"></i>
                                </div>
                                <div>
                                    <div class="text-muted small">Số khách</div>
                                    <div class="fw-bold">{{ $departure->bookings->sum(function($booking) { return $booking->adults + $booking->children + $booking->infants; }) }} khách</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($departure->tour?->description)
                        <div class="mt-3">
                            <h5 class="mb-2">Mô tả tour</h5>
                            <p class="text-muted">{{ $departure->tour->description }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Itinerary File -->
            @if($departure->itinerary_file)
                <div class="guide-card mb-4">
                    <div class="guide-card-header">
                        <i class="fas fa-file-alt me-2"></i>
                        Lịch trình / Hợp đồng
                    </div>
                    <div class="guide-card-body">
                        <a href="{{ Storage::disk('public')->url($departure->itinerary_file) }}" 
                           target="_blank"
                           class="btn btn-guide-primary">
                            <i class="fas fa-download"></i> Tải file lịch trình
                        </a>
                    </div>
                </div>
            @endif
        </div>

        <!-- Right Column: Guest List -->
        <div class="col-lg-4 mb-4">
            <div class="guide-card">
                <div class="guide-card-header">
                    <i class="fas fa-users me-2"></i>
                    Danh sách khách
                    <span class="badge bg-primary ms-2">{{ $departure->bookings->count() }}</span>
                </div>
                <div class="guide-card-body">
                    @if($departure->bookings->isEmpty())
                        <div class="text-center py-4">
                            <i class="fas fa-user-slash fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Chưa có khách đặt tour</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Khách hàng</th>
                                        <th>SĐT</th>
                                        <th>Check-in/out</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($departure->bookings as $booking)
                                        <tr>
                                            <td>{{ $booking->id }}</td>
                                            <td>
                                                <div class="fw-bold">{{ $booking->user?->name ?? 'N/A' }}</div>
                                                <small class="text-muted">{{ $booking->user?->email ?? '' }}</small>
                                            </td>
                                            <td>
                                                @if($booking->user?->phone)
                                                    <a href="tel:{{ $booking->user->phone }}" class="text-decoration-none">
                                                        <i class="fas fa-phone"></i> {{ $booking->user->phone }}
                                                    </a>
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column gap-1">
                                                    <!-- Check-in Status -->
                                                    @if($booking->check_in)
                                                        <span class="badge bg-success">
                                                            <i class="fas fa-sign-in-alt"></i> Check-in: {{ $booking->check_in->check_time->format('H:i') }}
                                                        </span>
                                                    @else
                                                        <form action="{{ route('guide.departures.check-in-out', $departure) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <input type="hidden" name="booking_id" value="{{ $booking->id }}">
                                                            <input type="hidden" name="type" value="check_in">
                                                            <button type="submit" class="btn btn-sm btn-outline-success" onclick="return confirm('Xác nhận check-in cho {{ $booking->user?->name }}?')">
                                                                <i class="fas fa-sign-in-alt"></i> Check-in
                                                            </button>
                                                        </form>
                                                    @endif
                                                    
                                                    <!-- Check-out Status -->
                                                    @if($booking->check_out)
                                                        <span class="badge bg-info">
                                                            <i class="fas fa-sign-out-alt"></i> Check-out: {{ $booking->check_out->check_time->format('H:i') }}
                                                        </span>
                                                    @elseif($booking->check_in)
                                                        <form action="{{ route('guide.departures.check-in-out', $departure) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <input type="hidden" name="booking_id" value="{{ $booking->id }}">
                                                            <input type="hidden" name="type" value="check_out">
                                                            <button type="submit" class="btn btn-sm btn-outline-info" onclick="return confirm('Xác nhận check-out cho {{ $booking->user?->name }}?')">
                                                                <i class="fas fa-sign-out-alt"></i> Check-out
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    @if($booking->check_in && $booking->check_in->status === 'pending')
                                                        <form action="{{ route('guide.check-in-out.confirm', $booking->check_in) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-success" title="Xác nhận">
                                                                <i class="fas fa-check"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                    @if($booking->check_out && $booking->check_out->status === 'pending')
                                                        <form action="{{ route('guide.check-in-out.confirm', $booking->check_out) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-success" title="Xác nhận">
                                                                <i class="fas fa-check"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Guest Summary -->
                        <div class="mt-3 p-3 bg-light rounded">
                            <div class="row g-2 text-center">
                                <div class="col-4">
                                    <div class="fw-bold text-primary">{{ $departure->bookings->sum('adults') }}</div>
                                    <small class="text-muted">Người lớn</small>
                                </div>
                                <div class="col-4">
                                    <div class="fw-bold text-primary">{{ $departure->bookings->sum('children') }}</div>
                                    <small class="text-muted">Trẻ em</small>
                                </div>
                                <div class="col-4">
                                    <div class="fw-bold text-primary">{{ $departure->bookings->sum('infants') }}</div>
                                    <small class="text-muted">Em bé</small>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="guide-card mt-4">
                <div class="guide-card-header">
                    <i class="fas fa-bolt me-2"></i>
                    Thao tác nhanh
                </div>
                <div class="guide-card-body">
                    <div class="d-grid gap-2">
                        @if($departure->bookings->isNotEmpty())
                            <button class="btn btn-outline-primary" onclick="window.print()">
                                <i class="fas fa-print"></i> In danh sách khách
                            </button>
                        @endif
                        <a href="tel:" class="btn btn-outline-success">
                            <i class="fas fa-phone"></i> Liên hệ hỗ trợ
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
<style>
    @media print {
        .guide-sidebar,
        .guide-header,
        .guide-card-header,
        .btn,
        .guide-card:last-child {
            display: none !important;
        }
        .guide-main {
            margin-left: 0 !important;
        }
    }
</style>
@endsection

@extends('layouts.app')

@section('title', 'Chi tiết tour - Hướng dẫn viên')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-primary">
            <i class="fas fa-calendar-alt me-2"></i>Chi tiết tour
        </h2>
        <a href="{{ route('guide.dashboard') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">{{ $departure->tour->title }}</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Ngày khởi hành:</strong> {{ $departure->departure_date->format('d/m/Y') }}</p>
                    <p><strong>Tổng ghế:</strong> {{ $departure->seats_total }}</p>
                    <p><strong>Ghế trống:</strong> {{ $departure->seats_available }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Giá người lớn:</strong> {{ number_format($departure->price, 0, ',', '.') }}đ</p>
                    <p><strong>Giá trẻ em:</strong> {{ number_format($departure->child_price, 0, ',', '.') }}đ</p>
                    <p><strong>Giá em bé:</strong> {{ number_format($departure->infant_price, 0, ',', '.') }}đ</p>
                </div>
            </div>
            
            @if($departure->vehicle_type)
                <div class="alert alert-info mt-3">
                    <strong>Thông tin xe:</strong> Xe {{ $departure->vehicle_type }} chỗ
                    @if($departure->vehicle_details)
                        <br>{{ $departure->vehicle_details }}
                    @endif
                    @if($departure->driver_contact)
                        <br><strong>Liên hệ tài xế:</strong> {{ $departure->driver_contact }}
                    @endif
                </div>
            @endif
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="mb-3"><i class="fas fa-bolt me-2"></i>Thao tác nhanh</h5>
                    <div class="row">
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('guide.departures.customers', $departure->id) }}" class="btn btn-outline-info w-100">
                                <i class="fas fa-users me-2"></i>Danh sách khách
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('guide.tour-logs.index', $departure->id) }}" class="btn btn-outline-success w-100">
                                <i class="fas fa-book me-2"></i>Nhật ký tour
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('guide.check-ins.index', $departure->id) }}" class="btn btn-outline-warning w-100">
                                <i class="fas fa-check-circle me-2"></i>Check-in
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('guide.special-requests.index', $departure->id) }}" class="btn btn-outline-primary w-100">
                                <i class="fas fa-clipboard-list me-2"></i>Yêu cầu đặc biệt
                            </a>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('guide.feedback.create', $departure->id) }}" class="btn btn-outline-secondary w-100">
                                <i class="fas fa-comment-dots me-2"></i>Gửi phản hồi
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-light">
            <h5 class="mb-0"><i class="fas fa-users me-2"></i>Danh sách khách hàng</h5>
        </div>
        <div class="card-body">
            @if($departure->bookings->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Khách hàng</th>
                                <th>Số lượng</th>
                                <th>Tổng tiền</th>
                                <th>Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($departure->bookings as $booking)
                                <tr>
                                    <td>
                                        <strong>{{ $booking->user->name }}</strong><br>
                                        <small class="text-muted">{{ $booking->user->email }}</small><br>
                                        <small class="text-muted">{{ $booking->user->phone }}</small>
                                    </td>
                                    <td>
                                        {{ $booking->adults }} người lớn
                                        @if($booking->children > 0)
                                            <br>{{ $booking->children }} trẻ em
                                        @endif
                                        @if($booking->infants > 0)
                                            <br>{{ $booking->infants }} em bé
                                        @endif
                                    </td>
                                    <td>{{ number_format($booking->total_amount, 0, ',', '.') }}đ</td>
                                    <td>
                                        @if($booking->status == 'paid' || $booking->status == 'completed')
                                            <span class="badge bg-success">Đã thanh toán</span>
                                        @elseif($booking->status == 'confirmed')
                                            <span class="badge bg-info">Đã xác nhận</span>
                                        @else
                                            <span class="badge bg-warning">Chờ xác nhận</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-muted text-center py-3">Chưa có khách hàng nào</p>
            @endif
        </div>
    </div>
</div>
@endsection


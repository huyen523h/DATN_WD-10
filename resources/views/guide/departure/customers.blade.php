@extends('layouts.app')

@section('title', 'Danh sách khách hàng - Hướng dẫn viên')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-primary">
                <i class="fas fa-users me-2"></i>Danh sách khách hàng
            </h2>
            <p class="text-muted mb-0">{{ $departure->tour->title }} - {{ $departure->departure_date->format('d/m/Y') }}</p>
        </div>
        <a href="{{ route('guide.departures.show', $departure->id) }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
    </div>

    <div class="alert alert-info d-flex align-items-start gap-2">
        <i class="fas fa-info-circle mt-1"></i>
        <div>
            <strong>Yêu cầu check-in:</strong> Mỗi booking cần cung cấp <u>danh sách đầy đủ họ tên hành khách</u>.
            Vui lòng tải lên/nhận file danh sách đoàn để điểm danh tại điểm đón.
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            @if($bookings->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Khách hàng</th>
                                <th>Liên hệ</th>
                                <th>Số lượng</th>
                                <th>Danh sách hành khách</th>
                                <th>Tổng tiền</th>
                                <th>Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bookings as $index => $booking)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <strong>{{ $booking->user->name }}</strong>
                                    </td>
                                    <td>
                                        <small><i class="fas fa-envelope"></i> {{ $booking->user->email }}</small><br>
                                        <small><i class="fas fa-phone"></i> {{ $booking->user->phone ?? 'N/A' }}</small>
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
                                    <td>
                                        @if($booking->passenger_manifest_file)
                                            <a href="{{ asset('storage/' . $booking->passenger_manifest_file) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-file-download"></i> Tải danh sách
                                            </a>
                                        @else
                                            <span class="badge bg-warning text-dark">Chưa có danh sách</span>
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
                <div class="text-center py-5">
                    <i class="fas fa-users fa-4x text-muted mb-3"></i>
                    <p class="text-muted">Chưa có khách hàng nào</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection


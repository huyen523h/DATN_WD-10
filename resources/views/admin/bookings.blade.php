@extends('layouts.admin')

@section('title', 'Quản lý Đặt tour - Tour365')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-calendar-check"></i> Quản lý Đặt tour</h2>
                <div class="btn-group">
                    <button class="btn btn-outline-primary" onclick="filterBookings('all')">
                        <i class="fas fa-list"></i> Tất cả
                    </button>
                    <button class="btn btn-outline-warning" onclick="filterBookings('pending')">
                        <i class="fas fa-clock"></i> Chờ xác nhận
                    </button>
                    <button class="btn btn-outline-success" onclick="filterBookings('confirmed')">
                        <i class="fas fa-check"></i> Đã xác nhận
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bookings Table -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-list"></i> Danh sách đặt tour
                    </h6>
                </div>
                <div class="card-body">
                    @if($bookings->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Mã đặt tour</th>
                                        <th>Khách hàng</th>
                                        <th>Tour</th>
                                        <th>Ngày khởi hành</th>
                                        <th>Số khách</th>
                                        <th>Tổng tiền</th>
                                        <th>Trạng thái</th>
                                        <th>Ngày đặt</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($bookings as $booking)
                                        <tr data-status="{{ $booking->status }}">
                                            <td>
                                                <strong>#{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}</strong>
                                            </td>
                                            <td>
                                                <div>
                                                    <strong>{{ $booking->user->name }}</strong><br>
                                                    <small class="text-muted">{{ $booking->user->email }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <strong>{{ $booking->tour->title }}</strong><br>
                                                    <small class="text-muted">{{ $booking->tour->duration_days }} ngày</small>
                                                </div>
                                            </td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($booking->departure->departure_date)->format('d/m/Y') }}
                                            </td>
                                            <td>
                                                <div>
                                                    {{ $booking->adults }} người lớn
                                                    @if($booking->children > 0)<br>{{ $booking->children }} trẻ em@endif
                                                    @if($booking->infants > 0)<br>{{ $booking->infants }} em bé@endif
                                                </div>
                                            </td>
                                            <td>
                                                <strong class="text-primary">
                                                    {{ number_format($booking->total_amount, 0, ',', '.') }}đ
                                                </strong>
                                            </td>
                                            <td>
                                                <span class="badge 
                                                    @if($booking->status === 'pending') bg-warning
                                                    @elseif($booking->status === 'confirmed') bg-success
                                                    @elseif($booking->status === 'cancelled') bg-danger
                                                    @elseif($booking->status === 'completed') bg-info
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
                                            </td>
                                            <td>{{ $booking->created_at->format('d/m/Y H:i') }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.bookings.show', $booking) }}" 
                                                       class="btn btn-sm btn-outline-info" title="Xem chi tiết">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    @if($booking->status === 'pending')
                                                        <button class="btn btn-sm btn-outline-success" 
                                                                onclick="updateBookingStatus({{ $booking->id }}, 'confirmed')" 
                                                                title="Xác nhận">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                        <button class="btn btn-sm btn-outline-danger" 
                                                                onclick="updateBookingStatus({{ $booking->id }}, 'cancelled')" 
                                                                title="Hủy">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    @elseif($booking->status === 'confirmed')
                                                        <button class="btn btn-sm btn-outline-info" 
                                                                onclick="updateBookingStatus({{ $booking->id }}, 'completed')" 
                                                                title="Hoàn thành">
                                                            <i class="fas fa-flag-checkered"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center">
                            {{ $bookings->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-times fa-4x text-muted mb-4"></i>
                            <h4>Chưa có đặt tour nào</h4>
                            <p class="text-muted">Khách hàng chưa đặt tour nào</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Update Status Modal -->
<div class="modal fade" id="updateStatusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cập nhật trạng thái đặt tour</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn cập nhật trạng thái đặt tour này?</p>
                <div id="statusInfo"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-primary" id="confirmUpdate">Xác nhận</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
let currentBookingId = null;
let currentStatus = null;

function filterBookings(status) {
    const rows = document.querySelectorAll('tbody tr');
    rows.forEach(row => {
        if (status === 'all' || row.dataset.status === status) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

function updateBookingStatus(bookingId, status) {
    currentBookingId = bookingId;
    currentStatus = status;
    
    const statusText = {
        'confirmed': 'Đã xác nhận',
        'cancelled': 'Đã hủy',
        'completed': 'Hoàn thành'
    };
    
    document.getElementById('statusInfo').innerHTML = `
        <div class="alert alert-info">
            <strong>Mã đặt tour:</strong> #${String(bookingId).padStart(6, '0')}<br>
            <strong>Trạng thái mới:</strong> ${statusText[status]}
        </div>
    `;
    
    new bootstrap.Modal(document.getElementById('updateStatusModal')).show();
}

document.getElementById('confirmUpdate').addEventListener('click', function() {
    if (currentBookingId && currentStatus) {
        // Here you would typically make an AJAX request to update the status
        // For now, we'll just show a success message
        alert(`Đã cập nhật trạng thái đặt tour #${String(currentBookingId).padStart(6, '0')} thành ${currentStatus}`);
        
        // Close modal
        bootstrap.Modal.getInstance(document.getElementById('updateStatusModal')).hide();
        
        // Reload page to show updated status
        location.reload();
    }
});
</script>
@endsection
@extends('layouts.admin')

@section('title', 'Danh sách khách hàng tổng hợp - Admin')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.bookings') }}">Quản lý Đặt Tour</a></li>
    <li class="breadcrumb-item active">Danh sách khách hàng</li>
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold text-dark mb-2">
                    <i class="fas fa-users text-primary me-2"></i>
                    Danh sách khách hàng tổng hợp
                </h2>
                <p class="text-muted mb-0">
                    Tour: <strong>{{ $tour->title }}</strong> | 
                    Ngày khởi hành: <strong>{{ \Carbon\Carbon::parse($departureDate)->format('d/m/Y') }}</strong>
                </p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.bookings', ['tour_id' => $tour->id, 'date_range' => $departureDate . ' - ' . $departureDate]) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Quay lại
                </a>
                <button onclick="window.print()" class="btn btn-primary">
                    <i class="fas fa-print me-1"></i> In danh sách
                </button>
            </div>
        </div>

        <!-- Thông tin đoàn -->
        @if($departure)
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <small class="text-muted d-block">Hướng dẫn viên</small>
                            <strong>{{ $departure->guide->name ?? 'Chưa gán' }}</strong>
                        </div>
                        <div class="col-md-3">
                            <small class="text-muted d-block">Xe</small>
                            <strong>
                                @if($departure->vehicle)
                                    {{ $departure->vehicle->license_plate ?? 'N/A' }} - {{ $departure->vehicle->capacity ?? $departure->vehicle_type ?? 'N/A' }} chỗ
                                @else
                                    {{ $departure->vehicle_type ?? 'Chưa gán' }}
                                @endif
                            </strong>
                        </div>
                        <div class="col-md-3">
                            <small class="text-muted d-block">Tổng số booking</small>
                            <strong>{{ $bookings->count() }} booking</strong>
                        </div>
                        <div class="col-md-2">
                            <small class="text-muted d-block">Tổng số khách</small>
                            <strong class="text-primary">{{ $totalGuests }} khách</strong>
                        </div>
                        <div class="col-md-2">
                            <small class="text-muted d-block">Đã check-in</small>
                            <strong class="text-success">{{ $totalCheckedIn ?? 0 }}/{{ $totalGuests }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Bảng danh sách khách hàng -->
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 60px;" class="text-center">STT</th>
                                <th>Họ tên</th>
                                <th style="width: 100px;" class="text-center">Loại</th>
                                <th style="width: 100px;">Năm sinh</th>
                                <th style="width: 150px;">Thuộc booking</th>
                                <th style="width: 130px;">Liên hệ</th>
                                <th style="width: 120px;" class="text-center">Trạng thái</th>
                                <th>Ghi chú</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($customerList as $customer)
                                <tr>
                                    <td class="text-center fw-bold">{{ $customer['stt'] }}</td>
                                    <td class="fw-semibold">
                                        {{ $customer['name'] }}
                                        <div class="small text-muted">
                                            CCCD: {{ $customer['id_number'] }}
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        @if($customer['passenger_type'] === 'adult')
                                            <span class="badge bg-info">Người lớn</span>
                                        @elseif($customer['passenger_type'] === 'child')
                                            <span class="badge bg-success">Trẻ em</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Em bé</span>
                                        @endif
                                    </td>
                                    <td>{{ $customer['birth_year'] }}</td>
                                    <td>
                                        <a href="{{ route('admin.bookings.show', $customer['booking_id']) }}" class="text-primary fw-bold" target="_blank">
                                            Booking #{{ $customer['booking_id'] }}
                                        </a>
                                        <div class="small text-muted">
                                            {{ $customer['booking_user_name'] }}
                                        </div>
                                    </td>
                                    <td>
                                        <i class="fas fa-phone me-1"></i>
                                        {{ $customer['phone'] }}
                                    </td>
                                    <td class="text-center">
                                        @if($customer['checked_in'] && $customer['check_in'])
                                            @if($customer['check_in']->status === 'checked_in')
                                                <span class="badge bg-success check-in-badge" 
                                                      data-bs-toggle="modal" 
                                                      data-bs-target="#checkInModal"
                                                      data-check-in-id="{{ $customer['check_in']->id }}"
                                                      data-passenger-name="{{ $customer['name'] }}"
                                                      data-check-in-time="{{ $customer['check_in']->check_in_time ? $customer['check_in']->check_in_time->format('d/m/Y H:i') : 'N/A' }}"
                                                      data-check-in-location="{{ $customer['check_in']->check_in_location ?? 'N/A' }}"
                                                      data-check-in-status="{{ $customer['check_in']->status ?? 'N/A' }}"
                                                      data-check-in-notes="{{ $customer['check_in']->notes ?? 'N/A' }}"
                                                      data-checked-by="{{ $customer['check_in']->checkedBy->name ?? 'N/A' }}"
                                                      style="cursor: pointer;">
                                                    <i class="fas fa-check-circle me-1"></i> Đã check-in
                                                </span>
                                            @elseif($customer['check_in']->status === 'absent')
                                                <span class="badge bg-danger">
                                                    <i class="fas fa-times-circle me-1"></i> Vắng
                                                </span>
                                            @else
                                                <span class="badge bg-secondary">
                                                    <i class="fas fa-clock me-1"></i> Chưa điểm danh
                                                </span>
                                            @endif
                                        @elseif($customer['checked_in'] && $customer['checked_in_at'])
                                            <span class="badge bg-success">
                                                <i class="fas fa-check-circle me-1"></i> Đã check-in
                                                <br>
                                                <small>{{ \Carbon\Carbon::parse($customer['checked_in_at'])->format('d/m/Y H:i') }}</small>
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">
                                                <i class="fas fa-clock me-1"></i> Chưa điểm danh
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $customer['notes'] }}</small>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">Chưa có khách hàng nào trong danh sách</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <td colspan="2" class="text-end fw-bold">Tổng cộng:</td>
                                <td class="text-center">
                                    <span class="badge bg-primary">{{ $totalAdults }}L</span>
                                    <span class="badge bg-warning text-dark">{{ $totalChildren }}T</span>
                                    <span class="badge bg-info">{{ $totalInfants }}E</span>
                                </td>
                                <td colspan="5" class="fw-bold text-primary">
                                    {{ $totalGuests }} khách ({{ $totalAdults }} người lớn, {{ $totalChildren }} trẻ em, {{ $totalInfants }} em bé)
                                </td>
                                <td colspan="2"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        @media print {
            .btn, .breadcrumb, .card:first-child {
                display: none !important;
            }
            .card {
                border: none !important;
                box-shadow: none !important;
            }
            .table {
                font-size: 12px;
            }
        }

        .badge {
            font-weight: 600;
            padding: 4px 8px;
        }

        .table th {
            font-weight: 600;
            background-color: #f8f9fa;
        }

        .table tbody tr:hover {
            background-color: #f8f9fa;
        }
        
        .check-in-badge {
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .check-in-badge:hover {
            transform: scale(1.05);
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }
    </style>
@endsection

<!-- Modal Check-in Details -->
<div class="modal fade" id="checkInModal" tabindex="-1" aria-labelledby="checkInModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="checkInModalLabel">
                    <i class="fas fa-check-circle me-2"></i> Chi tiết Check-in
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-bold text-muted">Hành khách:</label>
                    <p class="mb-0" id="modalPassengerName">—</p>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold text-muted">Thời gian check-in:</label>
                    <p class="mb-0" id="modalCheckInTime">—</p>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold text-muted">Địa điểm:</label>
                    <p class="mb-0" id="modalCheckInLocation">—</p>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold text-muted">Trạng thái:</label>
                    <p class="mb-0">
                        <span class="badge bg-success" id="modalCheckInStatus">—</span>
                    </p>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold text-muted">Người thực hiện:</label>
                    <p class="mb-0" id="modalCheckedBy">—</p>
                </div>
                <div class="mb-0">
                    <label class="form-label fw-bold text-muted">Ghi chú:</label>
                    <p class="mb-0 text-muted" id="modalCheckInNotes">—</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkInModal = document.getElementById('checkInModal');
    if (checkInModal) {
        checkInModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const checkInId = button.getAttribute('data-check-in-id');
            const passengerName = button.getAttribute('data-passenger-name');
            const checkInTime = button.getAttribute('data-check-in-time');
            const checkInLocation = button.getAttribute('data-check-in-location');
            const checkInStatus = button.getAttribute('data-check-in-status');
            const checkInNotes = button.getAttribute('data-check-in-notes');
            const checkedBy = button.getAttribute('data-checked-by');
            
            document.getElementById('modalPassengerName').textContent = passengerName || '—';
            document.getElementById('modalCheckInTime').textContent = checkInTime || '—';
            document.getElementById('modalCheckInLocation').textContent = checkInLocation || '—';
            document.getElementById('modalCheckInStatus').textContent = checkInStatus || '—';
            document.getElementById('modalCheckInNotes').textContent = checkInNotes || '—';
            document.getElementById('modalCheckedBy').textContent = checkedBy || '—';
        });
    }
});
</script>
@endsection


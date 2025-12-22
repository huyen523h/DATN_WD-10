@extends('layouts.app')

@section('title', 'Check-in khách hàng')

@section('content')
{{-- Nếu layouts.app đã có meta csrf-token thì đoạn meta dưới không cần,
   nhưng để chắc chắn bạn có thể giữ lại --}}
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('guide.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('guide.departures.show', $departure->id) }}">{{ $departure->tour->title }}</a></li>
                    <li class="breadcrumb-item active">Check-in</li>
                </ol>
            </nav>
            <h2 class="fw-bold text-primary">
                <i class="fas fa-check-circle me-2"></i>Check-in khách hàng
            </h2>
            <p class="text-muted">
                Tour: <strong>{{ $departure->tour->title }}</strong> |
                Ngày: <strong>{{ $departure->departure_date->format('d/m/Y') }}</strong>
            </p>
        </div>
    </div>

    {{-- Box hiển thị lỗi validation/CSRF/server --}}
    <div id="errorBox" class="alert alert-danger d-none"></div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <form id="checkInForm" class="mb-4">
                @csrf
                <div class="row">
                    <div class="col-md-3">
                        <label for="booking_id" class="form-label">Khách hàng <span class="text-danger">*</span></label>
                        <select class="form-select" id="booking_id" name="booking_id" required>
                            <option value="">Chọn khách hàng</option>
                            @foreach($bookings as $booking)
                                <option value="{{ $booking->id }}">
                                    {{ $booking->user->name }} ({{ $booking->adults + $booking->children + $booking->infants }} người)
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label for="check_in_time" class="form-label">Thời gian <span class="text-danger">*</span></label>
                        <input type="datetime-local" class="form-control" id="check_in_time" name="check_in_time"
                               value="{{ now()->format('Y-m-d\TH:i') }}" required>
                    </div>

                    <div class="col-md-2">
                        <label for="check_in_location" class="form-label">Địa điểm</label>
                        <input type="text" class="form-control" id="check_in_location" name="check_in_location"
                               placeholder="VD: Sân bay Nội Bài">
                    </div>

                    <div class="col-md-2">
                        <label for="status" class="form-label">Trạng thái <span class="text-danger">*</span></label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="checked_in">Đã check-in</option>
                            <option value="checked_out">Đã check-out</option>
                            <option value="absent">Vắng mặt</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">&nbsp;</label>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-check me-2"></i>Ghi nhận check-in
                        </button>
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-md-12">
                        <label for="notes" class="form-label">Ghi chú</label>
                        <textarea class="form-control" id="notes" name="notes" rows="2"
                                  placeholder="Ghi chú thêm về check-in..."></textarea>
                    </div>
                </div>
            </form>

            <hr>

            <h5 class="mb-3">Lịch sử check-in</h5>
            @if($bookings->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Khách hàng</th>
                                <th>Số lượng</th>
                                <th>Thời gian check-in</th>
                                <th>Địa điểm</th>
                                <th>Trạng thái</th>
                                <th>Ghi chú</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bookings as $booking)
                                @php
                                    // Controller đã orderByDesc(check_in_time) nên first() là mới nhất
                                    $latestCheckIn = $booking->checkIns->first();
                                @endphp
                                <tr>
                                    <td>
                                        <strong>{{ $booking->user->name }}</strong><br>
                                        <small class="text-muted">{{ $booking->user->phone }}</small>
                                    </td>
                                    <td>{{ $booking->adults + $booking->children + $booking->infants }} người</td>
                                    <td>
                                        @if($latestCheckIn)
                                            {{ $latestCheckIn->check_in_time->format('d/m/Y H:i') }}
                                        @else
                                            <span class="text-muted">Chưa check-in</span>
                                        @endif
                                    </td>
                                    <td>{{ $latestCheckIn->check_in_location ?? '-' }}</td>
                                    <td>
                                        @if($latestCheckIn)
                                            @if($latestCheckIn->status == 'checked_in')
                                                <span class="badge bg-success">Đã check-in</span>
                                            @elseif($latestCheckIn->status == 'checked_out')
                                                <span class="badge bg-info">Đã check-out</span>
                                            @else
                                                <span class="badge bg-danger">Vắng mặt</span>
                                            @endif
                                        @else
                                            <span class="badge bg-secondary">Chưa check-in</span>
                                        @endif
                                    </td>
                                    <td><small>{{ $latestCheckIn->notes ?? '-' }}</small></td>
                                    <td>
                                        @if($latestCheckIn)
                                            <button class="btn btn-sm btn-warning" onclick="editCheckIn({{ $latestCheckIn->id }})">
                                                <i class="fas fa-edit"></i>
                                            </button>
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

<!-- Modal Edit Check-in -->
<div class="modal fade" id="editCheckInModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Sửa check-in</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editCheckInForm">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="edit_check_in_id" name="check_in_id">

                    <div class="mb-3">
                        <label for="edit_check_in_time" class="form-label">Thời gian</label>
                        <input type="datetime-local" class="form-control" id="edit_check_in_time" name="check_in_time" required>
                    </div>

                    <div class="mb-3">
                        <label for="edit_check_in_location" class="form-label">Địa điểm</label>
                        <input type="text" class="form-control" id="edit_check_in_location" name="check_in_location">
                    </div>

                    <div class="mb-3">
                        <label for="edit_status" class="form-label">Trạng thái</label>
                        <select class="form-select" id="edit_status" name="status" required>
                            <option value="checked_in">Đã check-in</option>
                            <option value="checked_out">Đã check-out</option>
                            <option value="absent">Vắng mặt</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="edit_notes" class="form-label">Ghi chú</label>
                        <textarea class="form-control" id="edit_notes" name="notes" rows="3"></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
const csrf = document.querySelector('meta[name="csrf-token"]')?.content
    || document.querySelector('input[name="_token"]')?.value;

const errorBox = document.getElementById('errorBox');

function showError(msg) {
    errorBox.textContent = msg;
    errorBox.classList.remove('d-none');
}

function clearError() {
    errorBox.textContent = '';
    errorBox.classList.add('d-none');
}

document.getElementById('checkInForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    clearError();

    const formData = new FormData(this);

    const res = await fetch('{{ route("guide.check-ins.store", $departure->id) }}', {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': csrf,
        },
        body: formData
    });

    // Validation error
    if (res.status === 422) {
        const data = await res.json();
        const msgs = Object.values(data.errors || {}).flat().join(' | ');
        showError(msgs || 'Dữ liệu không hợp lệ.');
        return;
    }

    // CSRF error (thường 419)
    if (res.status === 419) {
        showError('Phiên làm việc hết hạn (CSRF 419). F5 trang rồi thử lại.');
        return;
    }

    if (!res.ok) {
        const text = await res.text();
        console.error('HTTP', res.status, text);
        showError(`Lỗi server (${res.status}). Mở Console để xem chi tiết.`);
        return;
    }

    const data = await res.json();
    if (data.success) location.reload();
    else showError(data.message || 'Vui lòng thử lại.');
});

async function editCheckIn(checkInId) {
    clearError();

    const res = await fetch(`/guide/departures/{{ $departure->id }}/check-ins/${checkInId}`, {
        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
    });

    if (!res.ok) {
        showError(`Không tải được dữ liệu (${res.status}).`);
        return;
    }

    const data = await res.json();

    document.getElementById('edit_check_in_id').value = data.id;
    document.getElementById('edit_check_in_time').value = data.check_in_time;
    document.getElementById('edit_check_in_location').value = data.check_in_location || '';
    document.getElementById('edit_status').value = data.status;
    document.getElementById('edit_notes').value = data.notes || '';

    new bootstrap.Modal(document.getElementById('editCheckInModal')).show();
}

document.getElementById('editCheckInForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    clearError();

    const checkInId = document.getElementById('edit_check_in_id').value;
    const formData = new FormData(this);
    formData.append('_method', 'PUT');

    const res = await fetch(`/guide/departures/{{ $departure->id }}/check-ins/${checkInId}`, {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': csrf,
        },
        body: formData
    });

    if (res.status === 422) {
        const data = await res.json();
        const msgs = Object.values(data.errors || {}).flat().join(' | ');
        showError(msgs || 'Dữ liệu không hợp lệ.');
        return;
    }

    if (res.status === 419) {
        showError('Phiên làm việc hết hạn (CSRF 419). F5 trang rồi thử lại.');
        return;
    }

    if (!res.ok) {
        const text = await res.text();
        console.error('HTTP', res.status, text);
        showError(`Lỗi server (${res.status}). Mở Console để xem chi tiết.`);
        return;
    }

    const data = await res.json();
    if (data.success) location.reload();
    else showError(data.message || 'Vui lòng thử lại.');
});
</script>
@endsection

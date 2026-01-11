@extends('layouts.app')

@section('title', 'Chi tiết đặt tour - Tour365')

@section('content')
<div class="container py-5">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>
            <i class="fas fa-calendar-check text-primary"></i>
            Chi tiết đặt tour #{{ $booking->id }}
        </h2>
        <a href="{{ route('bookings.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
    </div>

    {{-- ALERT --}}
    @foreach (['success','error'] as $msg)
        @if(session($msg))
            <div class="alert alert-{{ $msg === 'success' ? 'success' : 'danger' }}">
                {{ session($msg) }}
            </div>
        @endif
    @endforeach

    <div class="row">

        {{-- LEFT --}}
        <div class="col-lg-8">

            {{-- TOUR INFO --}}
            <div class="card mb-4 shadow-sm">
                <div class="card-body">
                    <h4 class="fw-bold text-primary">{{ $booking->tour->title }}</h4>
                    <div class="text-muted small">
                        <i class="fas fa-clock"></i> {{ $booking->tour->duration_days }} ngày
                        • <i class="fas fa-map-marker-alt"></i> {{ $booking->tour->category->name }}
                    </div>
                </div>
            </div>

            {{-- PASSENGERS --}}
            <div class="card mb-4 shadow-sm">
                <div class="card-header fw-bold text-primary">
                    <i class="fas fa-users"></i> Danh sách hành khách
                </div>
                <div class="card-body">
                    @if($booking->passengers->count())
                        <table class="table table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Họ tên</th>
                                    <th>Giới tính</th>
                                    <th>Năm sinh</th>
                                    <th>Loại</th>
                                    <th>CCCD / Passport</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($booking->passengers as $i => $p)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td class="fw-bold">{{ $p->full_name }}</td>
                                        <td>{{ $p->gender ?? '-' }}</td>
                                        <td>{{ $p->birth_year ?? '-' }}</td>
                                        <td>
                                            <span class="badge bg-info">
                                                {{ strtoupper($p->passenger_type) }}
                                            </span>
                                        </td>
                                        <td>{{ $p->id_number ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-muted">Chưa có hành khách</p>
                    @endif
                </div>
            </div>

            {{-- CUSTOMER --}}
            <div class="card mb-4 shadow-sm">
                <div class="card-header fw-bold text-primary">
                    <i class="fas fa-user"></i> Thông tin khách hàng
                </div>
                <div class="card-body row">
                    <div class="col-md-6">
                        <p><strong>Họ tên:</strong> {{ $booking->user->name }}</p>
                        <p><strong>Email:</strong> {{ $booking->user->email }}</p>
                        <p><strong>SĐT:</strong> {{ $booking->user->phone }}</p>
                    </div>
                    <div class="col-md-6">
                        <p>
                            <strong>Số lượng:</strong>
                            {{ $booking->adults }} NL,
                            {{ $booking->children }} TE
                        </p>
                        <p><strong>Ghi chú:</strong> {{ $booking->note ?? 'Không' }}</p>
                    </div>
                </div>
            </div>

            {{-- UPLOAD MANIFEST --}}
            <div class="card mb-4 shadow-sm">
                <div class="card-header fw-bold text-primary">
                    <i class="fas fa-file-upload"></i> Danh sách đoàn
                </div>
                <div class="card-body">
                    @if(in_array($booking->status, ['paid','completed']))
                        <a href="{{ route('bookings.download-manifest-template') }}"
                           class="btn btn-success btn-sm mb-3">
                            <i class="fas fa-download"></i> Tải file mẫu
                        </a>

                        <form method="POST"
                              action="{{ route('bookings.upload-manifest', $booking->id) }}"
                              enctype="multipart/form-data">
                            @csrf
                            <input type="file" name="manifest_file" class="form-control mb-2" required>
                            <button class="btn btn-primary btn-sm w-100">
                                <i class="fas fa-upload"></i> Upload danh sách
                            </button>
                        </form>
                    @else
                        <div class="alert alert-secondary text-center">
                            Thanh toán để mở chức năng upload danh sách đoàn
                        </div>
                    @endif
                </div>
            </div>

        </div>

        {{-- RIGHT --}}
        <div class="col-lg-4">

            {{-- PAYMENT --}}
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="fw-bold border-bottom pb-2 mb-3">Thanh toán</h5>

                    <div class="d-flex justify-content-between mb-3">
                        <span>Tổng tiền:</span>
                        <span class="fw-bold text-primary fs-5">
                            {{ number_format($booking->total_amount) }}đ
                        </span>
                    </div>

                    @if($booking->status === 'confirmed')
                        <div class="form-check mb-3">
                            <input type="checkbox" class="form-check-input" id="agreeTerms">
                            <label for="agreeTerms" class="form-check-label">
                                Đồng ý
                                <a href="{{ route('payment-policy') }}" target="_blank">
                                    Điều khoản thanh toán
                                </a>
                            </label>
                        </div>

                        <form method="POST"
                              action="{{ route('momo_payment', $booking->id) }}"
                              onsubmit="return validateTermsBeforePayment(event)">
                            @csrf
                            <button class="btn btn-danger w-100 mb-2">
                                Thanh toán MoMo
                            </button>
                        </form>

                        <form method="POST"
                              action="{{ route('payment.vnpay', $booking->id) }}"
                              onsubmit="return validateTermsBeforePayment(event)">
                            @csrf
                            <button class="btn btn-primary w-100">
                                Thanh toán VNPay
                            </button>
                        </form>

                    @elseif(in_array($booking->status, ['paid','completed']))
                        <div class="alert alert-success text-center">
                            Đã thanh toán
                        </div>

                    @elseif($booking->status === 'pending')
                        <div class="alert alert-warning text-center">
                            Đang chờ xác nhận
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function validateTermsBeforePayment(event) {
    const cb = document.getElementById('agreeTerms');
    if (cb && !cb.checked) {
        event.preventDefault();
        alert('Vui lòng đồng ý điều khoản trước khi thanh toán');
        return false;
    }
    return true;
}
</script>
@endsection

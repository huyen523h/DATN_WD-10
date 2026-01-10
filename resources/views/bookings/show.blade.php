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
        @if (session('success'))
            <div class="alert alert-success shadow-sm">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger shadow-sm">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            </div>
        @endif

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

                {{-- DEPARTURE --}}
                @if ($booking->departure)
                    <div class="card mb-4 border-info shadow-sm">
                        <div class="card-header bg-info text-white fw-bold">
                            <i class="fas fa-bus"></i> Thông tin điều hành
                        </div>
                        <div class="card-body row">
                            <div class="col-md-6">
                                <strong>Hướng dẫn viên:</strong><br>
                                {{ $booking->departure->guide->name ?? 'Đang cập nhật' }}<br>
                                <small>{{ $booking->departure->guide->phone ?? '' }}</small>
                            </div>
                            <div class="col-md-6">
                                <strong>Phương tiện:</strong><br>
                                {{ $booking->departure->vehicle_details ?? 'Đang cập nhật' }}
                            </div>
                        </div>
                    </div>
                @endif

                {{-- PASSENGERS --}}
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-white fw-bold text-primary">
                        <i class="fas fa-users"></i> Danh sách hành khách
                    </div>
                    <div class="card-body">

                        @if ($booking->passengers->count())
                            <div class="table-responsive">
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
                                        @foreach ($booking->passengers as $i => $p)
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
                            </div>
                        @else
                            <div class="text-muted text-center">
                                <i class="fas fa-info-circle"></i> Chưa có hành khách
                            </div>
                        @endif

                    </div>
                </div>

                {{-- CUSTOMER --}}
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-white fw-bold text-primary">
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
                                {{ $booking->adults }} người lớn,
                                {{ $booking->children }} trẻ em,
                                {{ $booking->infants }} em bé
                            </p>
                            <p><strong>Ghi chú:</strong> {{ $booking->note ?? 'Không' }}</p>
                        </div>
                    </div>
                </div>

            </div>

            {{-- RIGHT --}}
            <div class="col-lg-4">

                {{-- PAYMENT --}}
                <div class="card mb-4 border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title text-muted mb-3 border-bottom pb-2">Thông tin thanh toán</h5>

                        <div class="d-flex justify-content-between mb-3">
                            <span>Tổng tiền:</span>
                            <span class="fw-bold text-primary fs-5">{{ number_format($booking->total_amount) }}đ</span>
                        </div>

                        {{-- HIỂN THỊ BILL NẾU CÓ --}}
                        @if ($booking->receipt_image)
                            <div class="alert alert-success mt-3 shadow-sm">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-receipt fa-2x me-3"></i>
                                    <div>
                                        <h5 class="alert-heading h6 fw-bold mb-1">Biên lai thu tiền</h5>
                                        <a href="{{ Storage::url($booking->receipt_image) }}" target="_blank"
                                            class="btn btn-sm btn-light text-success fw-bold">
                                            <i class="fas fa-eye me-1"></i> Xem hóa đơn
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- LOGIC KIỂM TRA TRẠNG THÁI --}}

                        {{-- TRƯỜNG HỢP 1: ĐÃ HỦY --}}
                        @if ($booking->status === 'cancelled' || $booking->status === 'cancel_requested')
                            <div class="alert alert-secondary text-center mb-0 p-3 bg-light border-secondary">
                                @if ($booking->status === 'cancelled')
                                    <i class="fas fa-ban fa-2x mb-2 text-secondary"></i><br>
                                    <strong class="text-uppercase text-secondary">Đơn hàng đã hủy</strong>
                                @else
                                    <i class="fas fa-hourglass-half fa-2x mb-2 text-warning"></i><br>
                                    <strong class="text-uppercase text-warning">Đang chờ xử lý hủy</strong>
                                @endif
                            </div>

                            {{-- TRƯỜNG HỢP 2: ĐÃ THANH TOÁN --}}
                        @elseif ($booking->status === 'paid' || $booking->status === 'completed')
                            <div class="alert alert-success text-center mb-0 p-3">
                                <i class="fas fa-check-circle fa-2x mb-2"></i><br>
                                <strong>Đã thanh toán thành công</strong>
                            </div>

                            {{-- TRƯỜNG HỢP 3: MỚI ĐẶT (PENDING) -> CHỈ HIỆN THÔNG BÁO CHỜ --}}
                        @elseif ($booking->status === 'pending')
                            <div class="alert alert-warning text-center mb-3 p-3 border-warning"
                                style="background-color: #fff3cd;">
                                <i class="fas fa-user-clock fa-2x mb-2 text-warning"></i>
                                <h6 class="fw-bold text-dark">Đang chờ xác nhận</h6>
                                <p class="small mb-0 text-muted">
                                    Admin đang kiểm tra tình trạng chỗ trống. Vui lòng quay lại sau khi đơn hàng chuyển sang
                                    trạng thái
                                    <span class="badge bg-success">Đã xác nhận</span>.
                                </p>
                            </div>
                            {{-- Nút giả bị vô hiệu hóa để khách biết chưa thanh toán được --}}
                            <button class="btn btn-secondary w-100" disabled>
                                <i class="fas fa-lock me-2"></i>Chưa mở cổng thanh toán
                            </button>

                            {{-- TRƯỜNG HỢP 4: ĐÃ XÁC NHẬN (CONFIRMED) -> HIỆN CHECKBOX & THANH TOÁN --}}
                        @elseif ($booking->status === 'confirmed')
                            <div class="alert alert-info text-center mb-3 p-2 small border-info bg-info bg-opacity-10">
                                <i class="fas fa-check-circle text-info"></i> Admin đã xác nhận chỗ! Mời bạn thanh toán.
                            </div>

                            {{-- CODE CHECKBOX CŨ CỦA BẠN ĐÂY --}}
                            <div class="alert alert-light border mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="agreeTermsBooking" required>
                                    <label class="form-check-label" for="agreeTermsBooking">
                                        Tôi đã đọc và đồng ý với
                                        <a href="{{ route('payment-policy') }}" target="_blank"
                                            class="text-primary fw-bold">
                                            Chính sách & Điều khoản Tour Đoàn
                                            <i class="fas fa-external-link-alt ms-1"></i>
                                        </a>
                                    </label>
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                {{-- Form MoMo --}}
                                <form action="{{ route('momo_payment', $booking->id) }}" method="POST" class="d-inline"
                                    id="momoForm" onsubmit="return validateTermsBeforePayment(event)">
                                    @csrf
                                    <button type="submit" class="btn w-100 fw-bold shadow-sm"
                                        style="background: linear-gradient(135deg, #A50064 0%, #FF007F 100%); border: none; color: white; padding: 12px;">
                                        <i class="fas fa-mobile-alt me-2"></i>Thanh toán với MoMo
                                    </button>
                                </form>

                                {{-- Form VNPay --}}
                                <form action="{{ route('payment.vnpay', $booking->id) }}" method="POST" class="d-inline"
                                    id="vnpayForm" onsubmit="return validateTermsBeforePayment(event)">
                                    @csrf
                                    <button type="submit" class="btn btn-primary w-100 fw-bold shadow-sm"
                                        style="background: linear-gradient(135deg, #005C97 0%, #363795 100%); border: none; padding: 12px;">
                                        <i class="fas fa-university me-2"></i>Thanh toán với VNPay
                                    </button>
                                </form>
                            </div>

                            <div class="mt-3 text-center">
                                <small class="text-muted fst-italic">Vui lòng thanh toán để hệ thống giữ chỗ cho
                                    bạn.</small>
                            </div>
                        @else
                            {{-- Trường hợp khác (Expired...) --}}
                            <div class="alert alert-danger text-center">
                                Trạng thái đơn: {{ $booking->status }}
                            </div>
                        @endif
                    </div>
                </div>

            @endsection

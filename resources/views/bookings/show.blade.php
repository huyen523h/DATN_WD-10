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
                {{-- <div class="card-header fw-bold text-primary">
                    <i class="fas fa-file-upload"></i> Danh sách đoàn
                </div> --}}
                <div class="card-body">
                    {{-- Nút tải file mẫu đã bị gỡ: chức năng tải/upload danh sách đoàn không còn trên giao diện khách hàng --}}

                    {{-- Hiển thị file đã bị ẩn hoàn toàn theo yêu cầu; không hiển thị link tải hoặc thông báo upload --}}
                </div>
            </div>

        </div>

        {{-- RIGHT --}}
        <div class="col-lg-4">

            {{-- GUIDE & VEHICLE (hiển thị cho khách) --}}
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="fw-bold border-bottom pb-2 mb-3">Hướng dẫn viên & Xe</h5>
                    @php $dep = $booking->departure; @endphp

                    @if($dep && ($dep->guide || $dep->backupGuide))
                        <div class="mb-2">
                            <strong>HDV chính:</strong>
                            @if($dep->guide)
                                <div class="fw-bold">{{ $dep->guide->name }}</div>
                                @if($dep->guide->phone)
                                    <div class="small text-muted"><i class="fas fa-phone"></i> {{ $dep->guide->phone }}</div>
                                @endif
                            @else
                                <div class="text-muted">Chưa gán</div>
                            @endif
                        </div>

                        <div class="mb-2">
                            <strong>HDV dự phòng:</strong>
                            @if($dep->backupGuide)
                                <div class="fw-bold">{{ $dep->backupGuide->name }}</div>
                                @if($dep->backupGuide->phone)
                                    <div class="small text-muted"><i class="fas fa-phone"></i> {{ $dep->backupGuide->phone }}</div>
                                @endif
                            @else
                                <div class="text-muted">Chưa gán</div>
                            @endif
                        </div>
                    @else
                        <p class="text-muted mb-0">Hướng dẫn viên sẽ được cập nhật khi có phân công.</p>
                    @endif

                    @if($dep && $dep->vehicle)
                        <hr>
                        <div>
                            <strong>Xe:</strong>
                            <div class="fw-bold">{{ $dep->vehicle->vehicle_type ?? 'Xe' }} - {{ $dep->vehicle->license_plate ?? '---' }}</div>
                            @if($dep->vehicle->driver_name)
                                <div class="small text-muted"><i class="fas fa-user"></i> Tài xế: {{ $dep->vehicle->driver_name }} @if($dep->vehicle->driver_phone) - {{ $dep->vehicle->driver_phone }} @endif</div>
                            @endif
                        </div>
                    @else
                        <p class="text-muted mb-0">Thông tin xe sẽ được cập nhật khi phân công.</p>
                    @endif
                </div>
            </div>

            {{-- PAYMENT --}}
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="fw-bold border-bottom pb-2 mb-3">Thanh toán</h5>

                    @php
                        $dep = $booking->departure;
                        $adultPrice = $dep->price ?? ($booking->tour->price ?? 0);
                        $childPrice = $dep->child_price ?? 0;
                        $adultTotal = ($booking->adults ?? 0) * $adultPrice;
                        $childTotal = ($booking->children ?? 0) * $childPrice;
                        $services = $booking->additional_services ?? [];
                        $servicesTotal = $booking->additional_services_total ?? 0;
                        $subtotal = $adultTotal + $childTotal + $servicesTotal;
                        $discount = max(0, $subtotal - $booking->total_amount);
                    @endphp

                    <div class="mb-3">
                        <div class="d-flex justify-content-between"><div>Ngày khởi hành</div><div class="text-end">{{ $dep?->departure_date?->format('d/m/Y') ?? '-' }}</div></div>
                        <div class="d-flex justify-content-between"><div>Người lớn ({{ $booking->adults ?? 0 }})</div><div class="text-end">{{ number_format($adultPrice) }}đ × {{ $booking->adults ?? 0 }} = <strong>{{ number_format($adultTotal) }}đ</strong></div></div>
                        <div class="d-flex justify-content-between"><div>Trẻ em ({{ $booking->children ?? 0 }})</div><div class="text-end">{{ number_format($childPrice) }}đ × {{ $booking->children ?? 0 }} = <strong>{{ number_format($childTotal) }}đ</strong></div></div>

                        @if(count($services) > 0)
                            <div class="mt-2"><strong>Dịch vụ thêm</strong></div>
                            @foreach($services as $s)
                                <div class="d-flex justify-content-between"><div>{{ $s['label'] ?? ($s['key'] ?? '') }}</div><div class="text-end">{{ number_format($s['amount'] ?? 0) }}đ</div></div>
                            @endforeach
                        @endif

                        <hr>
                        <div class="d-flex justify-content-between"><div>Tạm tính</div><div class="text-end">{{ number_format($subtotal) }}đ</div></div>
                        @if($discount > 0)
                            <div class="d-flex justify-content-between text-danger"><div>Giảm giá</div><div class="text-end">-{{ number_format($discount) }}đ</div></div>
                        @endif
                        <div class="d-flex justify-content-between mt-2"><div class="fw-bold">Tổng phải trả</div><div class="fw-bold text-primary">{{ number_format($booking->total_amount) }}đ</div></div>
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

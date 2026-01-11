@extends('layouts.app')

@section('title', 'Đặt tour - ' . $tour->title)

@section('content')
<div class="container py-5">
    <div class="row">

        {{-- LEFT --}}
        <div class="col-lg-8">

            {{-- TOUR INFO --}}
            <div class="card mb-4">
                <div class="card-body row">
                    <div class="col-md-4">
                        @if ($tour->images->count())
                            <img src="{{ $tour->images->first()->image_url }}" class="img-fluid rounded">
                        @else
                            <img src="https://via.placeholder.com/300x200" class="img-fluid rounded">
                        @endif
                    </div>
                    <div class="col-md-8">
                        <h4>{{ $tour->title }}</h4>
                        <p class="text-muted">{{ Str::limit($tour->description, 150) }}</p>
                        <strong class="text-primary">
                            {{ number_format($tour->price, 0, ',', '.') }}đ/người
                        </strong>
                    </div>
                </div>
            </div>

            {{-- BOOKING FORM --}}
            <form method="POST" action="{{ route('bookings.store') }}" id="bookingForm">
                @csrf
                <input type="hidden" name="tour_id" value="{{ $tour->id }}">

                {{-- DEPARTURE --}}
                <div class="mb-4">
                    <label class="form-label fw-bold">Chọn ngày khởi hành *</label>
                    <select class="form-select" id="departure_id" name="departure_id" required>
                        <option value="">-- Chọn --</option>
                        @foreach ($departures as $departure)
                            <option value="{{ $departure->id }}"
                                data-price="{{ $departure->price }}"
                                data-child-price="{{ $departure->child_price }}"
                                data-seats="{{ $departure->seats_available }}">
                                {{ \Carbon\Carbon::parse($departure->departure_date)->format('d/m/Y') }}
                                ({{ $departure->seats_available }}/{{ $departure->seats_total }} chỗ)
                            </option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback d-block" id="seatsError" style="display:none;"></div>
                </div>

                {{-- PASSENGER COUNT --}}
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label>Người lớn *</label>
                        <input type="number" class="form-control" id="adults" name="adults" value="1" min="1">
                        <small class="text-muted">Người lớn ≥ 12 tuổi</small>
                    </div>
                    <div class="col-md-6">
                        <label>Trẻ em</label>
                        <input type="number" class="form-control" id="children" name="children" value="0" min="0">
                        <div class="invalid-feedback d-block" id="childrenError" style="display:none;"></div>
                    </div>
                </div>

                {{-- =========================
                     THÔNG TIN HÀNH KHÁCH
                     ========================= --}}
                <div class="card mb-4">
                    <div class="card-header bg-white fw-bold">
                        <i class="fas fa-users"></i> Thông tin hành khách
                        <small class="text-muted d-block mt-1">
                            Nhập đúng thông tin theo giấy tờ tùy thân
                        </small>
                    </div>
                    <div class="card-body" id="passengerForms">
                        <div class="text-muted small">
                            Vui lòng chọn số lượng người để nhập thông tin hành khách
                        </div>
                    </div>
                </div>

                {{-- PROMOTION --}}
                @if ($promotions->count())
                <div class="mb-4">
                    <label class="form-label">Mã giảm giá</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="promotion_code" name="promotion_code">
                        <button class="btn btn-outline-secondary" type="button">Áp dụng</button>
                    </div>
                    <div class="form-text">
                        @foreach ($promotions as $promotion)
                            <span class="badge bg-light text-dark">{{ $promotion->code }}</span>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- ADDITIONAL SERVICES --}}
                <div class="mb-4">
                    <label class="form-label">Dịch vụ thêm</label>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox"
                                       name="additional_services[]" value="insurance" data-price="50000">
                                <label class="form-check-label">
                                    <strong>Bảo hiểm du lịch</strong>
                                    <small class="d-block text-muted">+50.000đ/người</small>
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox"
                                       name="additional_services[]" value="airport_pickup" data-price="200000">
                                <label class="form-check-label">
                                    <strong>Đón sân bay</strong>
                                    <small class="d-block text-muted">+200.000đ/chuyến</small>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox"
                                       name="additional_services[]" value="single_room" data-price="300000">
                                <label class="form-check-label">
                                    <strong>Phòng đơn</strong>
                                    <small class="d-block text-muted">+300.000đ/đêm</small>
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox"
                                       name="additional_services[]" value="guide_tip" data-price="100000">
                                <label class="form-check-label">
                                    <strong>Tip hướng dẫn viên</strong>
                                    <small class="d-block text-muted">+100.000đ/tour</small>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- NOTE --}}
                <div class="mb-4">
                    <label>Ghi chú</label>
                    <textarea class="form-control" name="note" rows="3"
                              placeholder="Yêu cầu đặc biệt, dị ứng thức ăn..."></textarea>
                </div>

                <button type="submit" class="btn btn-primary btn-lg w-100">
                    <i class="fas fa-calendar-check"></i> Đặt tour
                </button>
            </form>
        </div>

        {{-- RIGHT --}}
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header fw-bold">
                    <i class="fas fa-calculator"></i> Tóm tắt đặt tour
                </div>
                <div class="card-body" id="bookingSummary">
                    <div class="text-muted text-center py-4">
                        Chọn ngày khởi hành và số lượng người
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@section('scripts')
<script>
const adultsInput = document.getElementById('adults');
const childrenInput = document.getElementById('children');
const passengerForms = document.getElementById('passengerForms');
const bookingForm = document.getElementById('bookingForm');

/* ===== RENDER THÔNG TIN HÀNH KHÁCH ===== */
function passengerForm(type, index) {
    const label = type === 'adult' ? 'Người lớn'
        : type === 'child' ? 'Trẻ em' : '';

    return `
    <div class="card mb-3">
        <div class="card-header fw-bold bg-light">${label} #${index}</div>
        <div class="card-body row g-2">

            <input type="hidden"
                name="passengers[${type}][${index}][passenger_type]"
                value="${type}">

            <div class="col-md-4">
                <label>Họ tên *</label>
                <input class="form-control"
                    name="passengers[${type}][${index}][full_name]" required>
            </div>

            <div class="col-md-2">
                <label>Giới tính</label>
                <select class="form-select"
                    name="passengers[${type}][${index}][gender]">
                    <option value="">--</option>
                    <option value="male">Nam</option>
                    <option value="female">Nữ</option>
                </select>
            </div>

            <div class="col-md-2">
                <label>Năm sinh</label>
                <input type="number" class="form-control"
                    name="passengers[${type}][${index}][birth_year]"
                    placeholder="YYYY">
            </div>

            <div class="col-md-4">
                <label>CCCD / Passport</label>
                <input class="form-control"
                    name="passengers[${type}][${index}][id_number]">
            </div>
        </div>
    </div>`;
}

function renderPassengers() {
    let html = '';
    for (let i = 1; i <= adultsInput.value; i++) html += passengerForm('adult', i);
    for (let i = 1; i <= childrenInput.value; i++) html += passengerForm('child', i);
    passengerForms.innerHTML = html || '<div class="text-muted">Chưa có hành khách</div>';
}

[adultsInput, childrenInput].forEach(el => {
    el.addEventListener('input', renderPassengers);
});
renderPassengers();

/* ===== VALIDATE THÔNG TIN HÀNH KHÁCH ===== */
bookingForm.addEventListener('submit', function(e) {
    const cards = document.querySelectorAll('#passengerForms .card');
    let errors = [];

    cards.forEach((card, idx) => {
        const name = card.querySelector('input[name$="[full_name]"]').value.trim();
        if (!name) errors.push(`Hành khách #${idx+1}: chưa nhập họ tên`);
    });

    if (errors.length) {
        e.preventDefault();
        alert(errors.join('\n'));
    }
});
</script>
@endsection

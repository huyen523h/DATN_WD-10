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
                            <img src="{{ image_url($tour->images->first()->image_url, '300x200') }}" class="img-fluid rounded">
                        @else
                            <img src="{{ placeholder_url('300x200') }}" class="img-fluid rounded">
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
                <div id="formErrors" class="alert alert-danger" style="display:none;"></div>

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
                    <div class="col-12 mb-2">
                        <div class="alert alert-info py-2 mb-0">
                            <strong>Quy định:</strong> Một người lớn tối đa kèm 2 trẻ em. Trẻ em phải đi cùng ít nhất một người lớn. Người từ 12 tuổi trở lên tính giá người lớn.
                        </div>
                    </div>
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

                <button type="submit" id="submitBookingBtn" class="btn btn-primary btn-lg w-100">
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
const departureSelect = document.getElementById('departure_id');
const bookingSummary = document.getElementById('bookingSummary');
const formErrorsEl = document.getElementById('formErrors');

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

/* ===== BOOKING SUMMARY ===== */
function formatCurrency(n) {
    return new Intl.NumberFormat('vi-VN').format(n) + 'đ';
}

function updateSummary() {
    const adults = Math.max(0, parseInt(adultsInput.value) || 0);
    const children = Math.max(0, parseInt(childrenInput.value) || 0);
    const selected = departureSelect.options[departureSelect.selectedIndex];

    if (!selected || !selected.value) {
        bookingSummary.innerHTML = '<div class="text-muted text-center py-4">Chọn ngày khởi hành và số lượng người</div>';
        return;
    }

    const depText = selected.textContent.trim();
    const adultPrice = parseFloat(selected.dataset.price || 0);
    const childPrice = parseFloat(selected.dataset.childPrice || 0);

    // Additional services
    const serviceEls = document.querySelectorAll('input[name="additional_services[]"]:checked');
    let servicesHtml = '';
    let additionalTotal = 0;
    serviceEls.forEach(el => {
        const price = parseFloat(el.dataset.price || 0);
        let label = el.closest('.form-check') ? el.closest('.form-check').querySelector('.form-check-label').innerText.trim() : el.value;
        // Determine multiplicity: insurance => per person, others => per booking
        if (el.value === 'insurance') {
            additionalTotal += price * (adults + children);
            servicesHtml += `<div>${label}: ${formatCurrency(price)} × ${adults + children} = <strong>${formatCurrency(price * (adults + children))}</strong></div>`;
        } else {
            additionalTotal += price;
            servicesHtml += `<div>${label}: ${formatCurrency(price)}</div>`;
        }
    });

    const adultTotal = adultPrice * adults;
    const childTotal = childPrice * children;
    const subtotal = adultTotal + childTotal + additionalTotal;

    bookingSummary.innerHTML = `
        <div>
            <div class="mb-2"><strong>Ngày khởi hành:</strong><div>${depText}</div></div>
            <div class="mb-2"><strong>Số khách:</strong><div>Người lớn: ${adults}, Trẻ em: ${children}</div></div>
            <div class="mb-2"><strong>Giá:</strong>
                <div>Người lớn: ${formatCurrency(adultPrice)} × ${adults} = <strong>${formatCurrency(adultTotal)}</strong></div>
                <div>Trẻ em: ${formatCurrency(childPrice)} × ${children} = <strong>${formatCurrency(childTotal)}</strong></div>
            </div>
            ${ servicesHtml ? `<div class="mb-2"><strong>Dịch vụ thêm:</strong>${servicesHtml}</div>` : '' }
            <hr>
            <div class="d-flex justify-content-between"><div><strong>Tổng tạm tính</strong></div><div><strong>${formatCurrency(subtotal)}</strong></div></div>
        </div>
    `;
}

// Update when departure or passenger counts or services change
departureSelect.addEventListener('change', updateSummary);
[adultsInput, childrenInput].forEach(el => el.addEventListener('input', updateSummary));
document.querySelectorAll('input[name="additional_services[]"]').forEach(el => el.addEventListener('change', updateSummary));

// Initial summary render
updateSummary();

/* ===== REALTIME BUSINESS-RULE VALIDATION FOR COUNTS ===== */
const submitBtn = document.getElementById('submitBookingBtn');
const childrenErrorEl = document.getElementById('childrenError');

function validateCountsRealtime() {
    const adults = Math.max(0, parseInt(adultsInput.value) || 0);
    const children = Math.max(0, parseInt(childrenInput.value) || 0);

    let messages = [];

    if (adults === 0 && children > 0) {
        messages.push('Trẻ em phải đi cùng ít nhất một người lớn.');
    }

    if (children > adults * 2) {
        messages.push(`Tối đa ${adults * 2} trẻ em cho ${adults} người lớn. Hiện có ${children} trẻ em.`);
    }

    if (messages.length) {
        childrenErrorEl.style.display = 'block';
        childrenErrorEl.innerText = messages.join(' ');
        childrenInput.classList.add('is-invalid');
        submitBtn.disabled = true;
    } else {
        childrenErrorEl.style.display = 'none';
        childrenErrorEl.innerText = '';
        childrenInput.classList.remove('is-invalid');
        submitBtn.disabled = false;
    }
}

// run realtime validation on input changes
[adultsInput, childrenInput].forEach(el => el.addEventListener('input', function(){
    renderPassengers();
    updateSummary();
    validateCountsRealtime();
}));

// initial validation
validateCountsRealtime();

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
        showFormErrors(errors);
        return false;
    }
});

// Additional validation: check birth_year vs passenger_type and enforce business rules
function validatePassengerBusinessRules() {
    const cards = document.querySelectorAll('#passengerForms .card');
    const mismatches = [];
    let effectiveAdults = 0;
    let effectiveChildren = 0;
    const currentYear = new Date().getFullYear();

    cards.forEach(card => {
        const typeField = card.querySelector('input[name$="[passenger_type]"]');
        const birthField = card.querySelector('input[name$="[birth_year]"]');
        const nameField = card.querySelector('input[name$="[full_name]"]');
        const ptype = typeField ? typeField.value : 'adult';
        const birth = birthField ? parseInt(birthField.value) : null;
        const age = birth ? (currentYear - birth) : null;

        if (age !== null && !isNaN(age)) {
            if (age >= 12) {
                effectiveAdults++;
                if (ptype === 'child') mismatches.push(`${nameField.value || 'Hành khách'}: ${age} tuổi — phải tính giá người lớn.`);
            } else {
                effectiveChildren++;
                if (ptype === 'adult' && age < 12) mismatches.push(`${nameField.value || 'Hành khách'}: ${age} tuổi — nên khai là Trẻ em.`);
            }
        } else {
            // no birth info: fall back to declared type
            if (ptype === 'adult') effectiveAdults++; else effectiveChildren++;
        }
    });

    // Enforce child/adult ratio
    if (effectiveAdults === 0 && effectiveChildren > 0) {
        mismatches.push('Trẻ em phải đi cùng ít nhất một người lớn.');
    }
    if (effectiveChildren > effectiveAdults * 2) {
        mismatches.push(`Mỗi người lớn chỉ kèm tối đa 2 trẻ em. Hiện có ${effectiveChildren} trẻ em và ${effectiveAdults} người lớn.`);
    }

    return mismatches;
}

bookingForm.addEventListener('submit', function(e){
    const biz = validatePassengerBusinessRules();
    if (biz.length) {
        e.preventDefault();
        showFormErrors(biz);
        return false;
    }
    return true;
});

function showFormErrors(messages) {
    if (!formErrorsEl) return alert(messages.join('\n'));
    formErrorsEl.style.display = 'block';
    formErrorsEl.innerHTML = '<ul style="margin:0;padding-left:18px;">' + messages.map(m => '<li>' + m + '</li>').join('') + '</ul>';
    formErrorsEl.scrollIntoView({ behavior: 'smooth', block: 'center' });
}

function clearFormErrors() {
    if (!formErrorsEl) return;
    formErrorsEl.style.display = 'none';
    formErrorsEl.innerHTML = '';
}
</script>
@endsection

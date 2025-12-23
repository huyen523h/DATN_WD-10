@extends('layouts.app')

@section('title', 'Đặt tour - ' . $tour->title)

@section('content')
    <div class="container py-5">
        <div class="row">
            <div class="col-lg-8">
                <!-- Tour Info -->
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                @if ($tour->images->count() > 0)
                                    <img src="{{ $tour->images->first()->image_url }}" class="img-fluid rounded"
                                        alt="{{ $tour->title }}">
                                @else
                                    <img src="https://via.placeholder.com/300x200/4F46E5/ffffff?text={{ urlencode($tour->title) }}"
                                        class="img-fluid rounded" alt="{{ $tour->title }}">
                                @endif
                            </div>
                            <div class="col-md-8">
                                <h4>{{ $tour->title }}</h4>
                                <p class="text-muted">{{ Str::limit($tour->description, 200) }}</p>
                                <div class="row">
                                    <div class="col-6">
                                        <small class="text-muted">
                                            <i class="fas fa-clock"></i> {{ $tour->duration_days }} ngày
                                        </small>
                                    </div>
                                    <div class="col-6">
                                        <div class="h5 text-primary mb-0">
                                            {{ number_format($tour->price, 0, ',', '.') }}đ/người
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Booking Form -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white">
                        <h5><i class="fas fa-calendar-plus"></i> Thông tin đặt tour</h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info mb-4">
                            <div class="fw-bold mb-2"><i class="fas fa-info-circle me-1"></i>Quy tắc hành khách</div>
                            <ul class="mb-0 ps-3">
                                <li>Mỗi người lớn có thể đi kèm tối đa <strong>2 trẻ em (2–11 tuổi)</strong> và <strong>1 em bé (&lt; 2 tuổi)</strong>.</li>
                                <li>Trẻ em/em bé <strong>bắt buộc</strong> phải đi cùng ít nhất 1 người lớn.</li>
                                <li>Người lớn <strong>&gt; 11 tuổi</strong></li>
                            </ul>
                        </div>

                        <form method="POST" action="{{ route('bookings.store') }}" id="bookingForm">
                            @csrf
                            <input type="hidden" name="tour_id" value="{{ $tour->id }}">

                            <!-- Departure Selection -->
                            <div class="mb-4">
                                <label for="departure_id" class="form-label">Chọn ngày khởi hành <span
                                        class="text-danger">*</span></label>
                                <select class="form-select @error('departure_id') is-invalid @enderror" id="departure_id"
                                    name="departure_id" required>
                                    <option value="">-- Chọn ngày khởi hành --</option>
                                    @foreach ($departures as $departure)
                                        <option value="{{ $departure->id }}" data-price="{{ $departure->price }}"
                                            data-child-price="{{ $departure->child_price }}"
                                            data-infant-price="{{ $departure->infant_price }}"
                                            data-seats="{{ $departure->seats_available }}"
                                            {{ old('departure_id') == $departure->id ? 'selected' : '' }}>
                                            {{ \Carbon\Carbon::parse($departure->departure_date)->format('d/m/Y') }}
                                            ({{ $departure->seats_available }}/{{ $departure->seats_total }} chỗ trống)
                                        </option>
                                    @endforeach
                                </select>
                                @error('departure_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="invalid-feedback d-block" id="seatsError" style="display:none;"></div>
                            </div>

                            <!-- Passenger Count -->
                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <label for="adults" class="form-label">Người lớn <span
                                            class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('adults') is-invalid @enderror"
                                        id="adults" name="adults" value="{{ old('adults', 1) }}" min="1"
                                        required>
                                    @error('adults')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Người lớn ≥ 12 tuổi</small>
                                </div>
                                <div class="col-md-4">
                                    <label for="children" class="form-label">Trẻ em</label>
                                    <input type="number" class="form-control @error('children') is-invalid @enderror"
                                        id="children" name="children" value="{{ old('children', 0) }}" min="0">
                                    @error('children')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="invalid-feedback d-block" id="childrenError" style="display:none;"></div>
                                </div>
                                <div class="col-md-4">
                                    <label for="infants" class="form-label">Em bé</label>
                                    <input type="number" class="form-control @error('infants') is-invalid @enderror"
                                        id="infants" name="infants" value="{{ old('infants', 0) }}" min="0">
                                    @error('infants')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="invalid-feedback d-block" id="infantsError" style="display:none;"></div>
                                </div>
                            </div>

                            <!-- Promotion Code -->
                            @if ($promotions->count() > 0)
                                <div class="mb-4">
    <label class="form-label fw-bold text-warning">Mã giảm giá</label>
    <div class="input-group">
        {{-- Sửa id="promotion_code" thành id="couponCode" --}}
        <input type="text" class="form-control text-uppercase" id="couponCode" name="promotion_code" placeholder="Nhập mã (VD: VIP100K)">
        
        {{-- Sửa id="applyPromotion" thành id="btnApplyCoupon" --}}
        <button class="btn btn-dark" type="button" id="btnApplyCoupon">Áp dụng</button>
    </div>
    <div id="couponMessage" class="small mt-1 fw-bold"></div>

    {{-- Input ẩn bắt buộc --}}
    <input type="hidden" name="promotion_id" id="appliedPromotionId">
    <input type="hidden" name="discount_amount" id="appliedDiscountAmount" value="0">

    @if ($promotions->count() > 0)
        <div class="form-text mt-2">
            Mã có sẵn:
            @foreach ($promotions as $promotion)
                <span class="badge bg-light text-dark border" style="cursor:pointer" onclick="document.getElementById('couponCode').value='{{ $promotion->code }}'">
                    {{ $promotion->code }}
                </span>
            @endforeach
        </div>
    @endif
</div>
                            @endif

                            <!-- Additional Services -->
                            <div class="mb-4">
                                <label class="form-label">Dịch vụ thêm</label>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" id="insurance"
                                                name="additional_services[]" value="insurance" data-price="50000">
                                            <label class="form-check-label" for="insurance">
                                                <strong>Bảo hiểm du lịch</strong>
                                                <small class="text-muted d-block">+50,000đ/người</small>
                                            </label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" id="airport_pickup"
                                                name="additional_services[]" value="airport_pickup" data-price="200000">
                                            <label class="form-check-label" for="airport_pickup">
                                                <strong>Đón sân bay</strong>
                                                <small class="text-muted d-block">+200,000đ/chuyến</small>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" id="single_room"
                                                name="additional_services[]" value="single_room" data-price="300000">
                                            <label class="form-check-label" for="single_room">
                                                <strong>Phòng đơn</strong>
                                                <small class="text-muted d-block">+300,000đ/đêm</small>
                                            </label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" id="guide_tip"
                                                name="additional_services[]" value="guide_tip" data-price="100000">
                                            <label class="form-check-label" for="guide_tip">
                                                <strong>Tip hướng dẫn viên</strong>
                                                <small class="text-muted d-block">+100,000đ/tour</small>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Note -->
                            <div class="mb-4">
                                <label for="note" class="form-label">Ghi chú</label>
                                <textarea class="form-control @error('note') is-invalid @enderror" id="note" name="note" rows="3"
                                    placeholder="Yêu cầu đặc biệt, dị ứng thức ăn...">{{ old('note') }}</textarea>
                                @error('note')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-calendar-check"></i> Đặt tour
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Booking Summary -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-calculator"></i> Tóm tắt đặt tour</h5>
                    </div>
                    <div class="card-body">
                        <div id="bookingSummary">
                            <div class="text-center text-muted py-4">
                                <i class="fas fa-info-circle fa-2x mb-2"></i>
                                <p>Vui lòng chọn ngày khởi hành để xem tóm tắt</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let serverDiscountAmount = 0;
            const departureSelect = document.getElementById('departure_id');
            const adultsInput = document.getElementById('adults');
            const childrenInput = document.getElementById('children');
            const infantsInput = document.getElementById('infants');
            const bookingForm = document.getElementById('bookingForm');
            // const promotionInput = document.getElementById('promotion_code');
            const promotionInput = document.getElementById('couponCode');
            const bookingSummary = document.getElementById('bookingSummary');
            const childrenError = document.getElementById('childrenError');
            const infantsError = document.getElementById('infantsError');
            const seatsError = document.getElementById('seatsError');

            //Hàm chuẩn hóa số
            function parseNumber(value) {
                if (value === null || value === undefined) return 0;
                const str = String(value).replace(/[^\d.-]/g, '');
                return str === '' ? 0 : parseFloat(str);
            }

            function formatVND(n) {
                return new Intl.NumberFormat('vi-VN').format(Math.round(n)) + 'đ';
            }

            //Lấy giá từ option được chọn
            function readPrices() {
                const opt = departureSelect.options[departureSelect.selectedIndex];
                if (!opt) return {
                    price: 0,
                    child: 0,
                    infant: 0
                };
                return {
                    price: parseNumber(opt.dataset.price),
                    child: parseNumber(opt.dataset.childPrice || opt.dataset.child_price),
                    infant: parseNumber(opt.dataset.infantPrice || opt.dataset.infant_price)
                };
            }

            function updateBookingSummary() {
                const departureId = departureSelect.value;
                const adults = parseInt(adultsInput.value) || 0;
                const children = parseInt(childrenInput.value) || 0;
                const infants = parseInt(infantsInput.value) || 0;
                const childLimit = adults * 2;
                const infantLimit = adults * 1;
                const opt = departureSelect.options[departureSelect.selectedIndex];
                const seatsAvailable = opt ? parseNumber(opt.dataset.seats) : 0;
                const seatPassengers = adults + children; // em bé không trừ chỗ
                const totalPassengers = seatPassengers + infants;
                let childMsg = '';
                let infantMsg = '';
                let seatsMsg = '';

                if (!departureId || adults === 0) {
                    bookingSummary.innerHTML = `
                <div class="text-center text-muted py-4">
                    <i class="fas fa-info-circle fa-2x mb-2"></i>
                    <p>Vui lòng chọn ngày khởi hành và số lượng người</p>
                </div>`;
                    return;
                }

                const {
                    price: tourPrice,
                    child: childPrice,
                    infant: infantPrice
                } = readPrices();

                //Tính giá cơ bản
                const adultTotal = tourPrice * adults;
                const childTotal = childPrice * children;
                const infantTotal = infantPrice * infants;

                //Dịch vụ thêm
                let additionalTotal = 0;
                let additionalList = '';
                const selectedServices = document.querySelectorAll('input[name="additional_services[]"]:checked');

                selectedServices.forEach(service => {
                    const price = parseNumber(service.dataset.price);
                    const label = service.nextElementSibling.querySelector('strong')?.textContent || service
                        .value;
                    additionalTotal += price;
                    additionalList += `
                <div class="d-flex justify-content-between small">
                    <span>${label}</span>
                    <span>+${formatVND(price)}</span>
                </div>`;
                });

                //Tính tổng
                const subtotal = adultTotal + childTotal + infantTotal + additionalTotal;
                // const discount = promotionInput.value ? subtotal * 0.1 : 0;
                const discount = serverDiscountAmount;
                const total = subtotal - discount;

                //Render giao diện + cảnh báo nếu vi phạm quy tắc
                let warnings = '';
                if (adults <= 0 && (children > 0 || infants > 0)) {
                    warnings += `<div class="text-danger small mt-2"><i class="fas fa-exclamation-triangle"></i> Trẻ em/em bé cần ít nhất 1 người lớn.</div>`;
                }
                if (children > childLimit) {
                    childMsg = `${adults} người lớn chỉ kèm tối đa ${childLimit} trẻ em. Vui lòng tăng số người lớn hoặc giảm số trẻ.`;
                    warnings += `<div class="text-danger small mt-1"><i class="fas fa-exclamation-triangle"></i> ${childMsg}</div>`;
                }
                if (infants > infantLimit) {
                    infantMsg = `${adults} người lớn chỉ kèm tối đa ${infantLimit} em bé.`;
                    warnings += `<div class="text-danger small mt-1"><i class="fas fa-exclamation-triangle"></i> ${infantMsg}</div>`;
                }
                if ((children > 0 || infants > 0) && adults < 1) {
                    childMsg = childMsg || 'Cần ít nhất 1 người lớn đi cùng trẻ em.';
                    infantMsg = infantMsg || 'Cần ít nhất 1 người lớn đi cùng em bé.';
                }
                if (seatsAvailable && seatPassengers > seatsAvailable) {
                    seatsMsg = `Số ghế cần (${seatPassengers}) vượt quá số chỗ trống (${seatsAvailable}). Vui lòng giảm người lớn/trẻ em hoặc chọn ngày khác.`;
                    warnings += `<div class="text-danger small mt-1"><i class="fas fa-exclamation-triangle"></i> ${seatsMsg}</div>`;
                }

                // Hiển thị lỗi ngay tại ô nhập
                if (childrenError) {
                    if (childMsg) {
                        childrenError.style.display = 'block';
                        childrenError.textContent = childMsg;
                        childrenInput.classList.add('is-invalid');
                    } else {
                        childrenError.style.display = 'none';
                        childrenError.textContent = '';
                        childrenInput.classList.remove('is-invalid');
                    }
                }
                if (seatsError) {
                    if (seatsMsg) {
                        seatsError.style.display = 'block';
                        seatsError.textContent = seatsMsg;
                        departureSelect.classList.add('is-invalid');
                    } else {
                        seatsError.style.display = 'none';
                        seatsError.textContent = '';
                        departureSelect.classList.remove('is-invalid');
                    }
                }
                if (infantsError) {
                    if (infantMsg) {
                        infantsError.style.display = 'block';
                        infantsError.textContent = infantMsg;
                        infantsInput.classList.add('is-invalid');
                    } else {
                        infantsError.style.display = 'none';
                        infantsError.textContent = '';
                        infantsInput.classList.remove('is-invalid');
                    }
                }

                //Render giao diện
                bookingSummary.innerHTML = `
            <div class="mb-3">
                <h6 class="text-primary mb-3">Chi tiết đặt tour</h6>

                ${adults > 0 ? `
                    <div class="d-flex justify-content-between">
                        <span>Người lớn (${adults} x ${formatVND(tourPrice)})</span>
                        <span>${formatVND(adultTotal)}</span>
                    </div>` : ''}

                ${children > 0 ? `
                    <div class="d-flex justify-content-between">
                        <span>Trẻ em (${children} x ${formatVND(childPrice)})</span>
                        <span>${formatVND(childTotal)}</span>
                    </div>` : ''}

                ${infants > 0 ? `
                    <div class="d-flex justify-content-between">
                        <span>Em bé (${infants} x ${formatVND(infantPrice)})</span>
                        <span>${formatVND(infantTotal)}</span>
                    </div>` : ''}

                ${additionalList}

                ${discount > 0 ? `
                   <div class="d-flex justify-content-between text-success fw-bold border-top mt-2 pt-2">
                        <span><i class="fas fa-tag"></i> Voucher giảm:</span>
                        <span>-${formatVND(discount)}</span>
                    </div>` : ''}

                <hr>
                <div class="d-flex justify-content-between">
                    <strong>Tổng cộng:</strong>
                   
                    <strong class="text-primary" id="finalTotalDisplay">${formatVND(total)}</strong>
                </div>

                <div class="mt-3">
                    <small class="text-muted">
                        <i class="fas fa-info-circle"></i>
                        Giá đã bao gồm thuế và phí dịch vụ
                    </small>
                </div>
                ${warnings ? `<div class="mt-2">${warnings}</div>` : ''}
            </div>
        `;
            }

            //Gắn sự kiện
            [departureSelect, adultsInput, childrenInput, infantsInput, promotionInput].forEach(el => {
                if (el) el.addEventListener('input', updateBookingSummary);
                if (el && el.tagName === 'SELECT') el.addEventListener('change', updateBookingSummary);
            });

            document.querySelectorAll('input[name="additional_services[]"]').forEach(cb => {
                cb.addEventListener('change', updateBookingSummary);
            });

            updateBookingSummary();

            // Kiểm tra quy tắc trước khi submit
            if (bookingForm) {
                bookingForm.addEventListener('submit', function(e) {
                    const adults = parseInt(adultsInput.value) || 0;
                    const children = parseInt(childrenInput.value) || 0;
                    const infants = parseInt(infantsInput.value) || 0;
                    const opt = departureSelect.options[departureSelect.selectedIndex];
                    const seatsAvailable = opt ? parseNumber(opt.dataset.seats) : 0;
                    const seatPassengers = adults + children;
                    const totalPassengers = seatPassengers + infants;
                    const childLimit = adults * 2;
                    const infantLimit = adults * 1;
                    const errors = [];

                    if (adults < 1) {
                        errors.push('Cần ít nhất 1 người lớn.');
                    }
                    if (children > childLimit) {
                        errors.push(`${adults} người lớn chỉ có thể đi kèm tối đa ${childLimit} trẻ em. Vui lòng tăng số người lớn hoặc giảm số trẻ.`);
                    }
                    if (infants > infantLimit) {
                        errors.push(`${adults} người lớn chỉ có thể đi kèm tối đa ${infantLimit} em bé. Vui lòng tăng số người lớn hoặc giảm số em bé.`);
                    }
                    if ((children > 0 || infants > 0) && adults < 1) {
                        errors.push('Trẻ em/em bé bắt buộc phải đi cùng ít nhất 1 người lớn.');
                    }
                    if (seatsAvailable && seatPassengers > seatsAvailable) {
                        errors.push(`Số ghế cần (${seatPassengers}) vượt quá số chỗ trống (${seatsAvailable}). Vui lòng giảm người lớn/trẻ em hoặc chọn ngày khác.`);
                    }

                    if (errors.length > 0) {
                        e.preventDefault();
                        alert(errors.join('\\n'));
                    }
                });
            }

            $('#btnApplyCoupon').click(function() {
                var code = $('#couponCode').val().trim();
                
                // Lấy tổng tiền hiện tại từ giao diện (bỏ chữ đ và dấu chấm)
                var currentTotalText = $('#finalTotalDisplay').text().replace(/[^\d]/g, '');
                var currentTotal = parseFloat(currentTotalText) || 0;
                
                // Cộng ngược lại tiền đã giảm (nếu có) để ra giá gốc
                currentTotal += serverDiscountAmount;
                
                if(code === '') {
                    $('#couponMessage').html('<span class="text-danger">Vui lòng nhập mã!</span>');
                    return;
                }

                var btn = $(this);
                var originalText = btn.text();
                btn.html('...').prop('disabled', true);
                $('#couponMessage').html('');

                $.ajax({
                    url: "{{ route('check.coupon') }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        code: code,
                        total_amount: currentTotal
                    },
                    success: function(response) {
                        btn.html(originalText).prop('disabled', false);

                        if(response.success) {
                            // 1. Thành công -> Cập nhật biến
                            $('#couponMessage').html('<span class="text-success"><i class="fas fa-check"></i> ' + response.message + '</span>');
                            serverDiscountAmount = response.discount_amount;
                            
                            // 2. Điền input ẩn
                            $('#appliedPromotionId').val(response.promotion_id);
                            $('#appliedDiscountAmount').val(response.discount_amount);
                            
                            // 3. Gọi hàm updateBookingSummary của bạn để tính lại tiền
                            updateBookingSummary();
                            
                            // 4. Khóa ô nhập
                            $('#couponCode').prop('readonly', true);
                            btn.text('Đã dùng').prop('disabled', true).removeClass('btn-dark').addClass('btn-success');

                        } else {
                            // Thất bại
                            $('#couponMessage').html('<span class="text-danger">' + response.message + '</span>');
                            serverDiscountAmount = 0;
                            updateBookingSummary(); // Tính lại về giá gốc
                        }
                    },
                    error: function() {
                        btn.html(originalText).prop('disabled', false);
                        $('#couponMessage').html('<span class="text-danger">Lỗi kết nối!</span>');
                    }
                });
            });
        });

    </script>
@endsection

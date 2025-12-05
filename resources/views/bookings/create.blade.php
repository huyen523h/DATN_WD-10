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
                        <form method="POST" action="{{ route('bookings.store') }}">
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
                            </div>

                            <!-- Passenger Count -->
                            <div class="mb-4">
                                <label class="form-label fw-bold">Số lượng khách <span class="text-danger">*</span></label>
                                
                                <!-- Info Box -->
                                <div class="alert alert-info mb-3">
                                    <h6 class="alert-heading mb-2">
                                        <i class="fas fa-info-circle"></i> Quy định số lượng khách:
                                    </h6>
                                    <ul class="mb-0 small">
                                        <li><strong>Người lớn:</strong> Trên 9 tuổi</li>
                                        <li><strong>Trẻ em:</strong> Từ 2 đến 9 tuổi</li>
                                        <li><strong>Em bé:</strong> Dưới 2 tuổi</li>
                                    </ul>
                                    <hr class="my-2">
                                    <p class="mb-0 small">
                                        <i class="fas fa-exclamation-triangle text-warning"></i>
                                        <strong>Lưu ý:</strong> Mỗi người lớn có thể đi kèm tối đa <strong>2 trẻ em</strong> và tối đa <strong>1 em bé</strong>.
                                    </p>
                                </div>

                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="adults" class="form-label">Người lớn (>9 tuổi) <span
                                            class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('adults') is-invalid @enderror"
                                        id="adults" name="adults" value="{{ old('adults', 1) }}" min="1"
                                        required>
                                    @error('adults')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="children" class="form-label">Trẻ em (2-9 tuổi)</label>
                                    <input type="number" class="form-control @error('children') is-invalid @enderror"
                                        id="children" name="children" value="{{ old('children', 0) }}" min="0">
                                    @error('children')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                        <small class="text-muted">Tối đa 2 trẻ/người lớn</small>
                                </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="infants" class="form-label">Em bé (<2 tuổi)</label>
                                    <input type="number" class="form-control @error('infants') is-invalid @enderror"
                                        id="infants" name="infants" value="{{ old('infants', 0) }}" min="0">
                                    @error('infants')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                        <small class="text-muted">Tối đa 1 em bé/người lớn</small>
                                    </div>
                                </div>
                                
                                <!-- Error message container -->
                                <div id="passengerError" class="alert alert-danger d-none" role="alert">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    <span id="passengerErrorMessage"></span>
                                </div>
                            </div>

                            <!-- Promotion Code -->
                            @if ($promotions->count() > 0)
                                <div class="mb-4">
                                    <label for="promotion_code" class="form-label">Mã giảm giá</label>
                                    <div class="input-group">
                                        <input type="text"
                                            class="form-control @error('promotion_code') is-invalid @enderror"
                                            id="promotion_code" name="promotion_code" value="{{ old('promotion_code') }}">
                                        <button class="btn btn-outline-secondary" type="button" id="applyPromotion">
                                            Áp dụng
                                        </button>
                                    </div>
                                    @error('promotion_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">
                                        Mã khuyến mãi có sẵn:
                                        @foreach ($promotions as $promotion)
                                            <span class="badge bg-light text-dark">{{ $promotion->code }}</span>
                                        @endforeach
                                    </div>
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
            const departureSelect = document.getElementById('departure_id');
            const adultsInput = document.getElementById('adults');
            const childrenInput = document.getElementById('children');
            const infantsInput = document.getElementById('infants');
            const promotionInput = document.getElementById('promotion_code');
            const bookingSummary = document.getElementById('bookingSummary');

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
                const discount = promotionInput.value ? subtotal * 0.1 : 0;
                const total = subtotal - discount;

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
                    <div class="d-flex justify-content-between text-success">
                        <span>Giảm giá (${promotionInput.value})</span>
                        <span>-${formatVND(discount)}</span>
                    </div>` : ''}

                <hr>
                <div class="d-flex justify-content-between">
                    <strong>Tổng cộng:</strong>
                    <strong class="text-primary">${formatVND(total)}</strong>
                </div>

                <div class="mt-3">
                    <small class="text-muted">
                        <i class="fas fa-info-circle"></i>
                        Giá đã bao gồm thuế và phí dịch vụ
                    </small>
                </div>
            </div>
        `;
            }

            // Validation function for passenger limits
            function validatePassengers() {
                const adults = parseInt(adultsInput.value) || 0;
                const children = parseInt(childrenInput.value) || 0;
                const infants = parseInt(infantsInput.value) || 0;
                const errorDiv = document.getElementById('passengerError');
                const errorMessage = document.getElementById('passengerErrorMessage');
                
                // Clear previous errors
                errorDiv.classList.add('d-none');
                childrenInput.classList.remove('is-invalid');
                infantsInput.classList.remove('is-invalid');
                
                if (adults === 0) {
                    return true; // Will be validated by required attribute
                }
                
                const maxChildren = adults * 2;
                const maxInfants = adults * 1;
                
                let hasError = false;
                let errorMsg = '';
                
                if (children > maxChildren) {
                    hasError = true;
                    errorMsg = `${adults} người lớn chỉ có thể đi kèm tối đa ${maxChildren} trẻ em. Vui lòng tăng số người lớn hoặc giảm số trẻ em.`;
                    childrenInput.classList.add('is-invalid');
                }
                
                if (infants > maxInfants) {
                    hasError = true;
                    if (errorMsg) errorMsg += '<br>';
                    errorMsg += `${adults} người lớn chỉ có thể đi kèm tối đa ${maxInfants} em bé. Vui lòng tăng số người lớn hoặc giảm số em bé.`;
                    infantsInput.classList.add('is-invalid');
                }
                
                if (hasError) {
                    errorMessage.innerHTML = errorMsg;
                    errorDiv.classList.remove('d-none');
                    return false;
                }
                
                return true;
            }
            
            // Validate on form submit
            const bookingForm = document.querySelector('form[method="POST"]');
            if (bookingForm) {
                bookingForm.addEventListener('submit', function(e) {
                    if (!validatePassengers()) {
                        e.preventDefault();
                        e.stopPropagation();
                        // Scroll to error
                        document.getElementById('passengerError').scrollIntoView({ behavior: 'smooth', block: 'center' });
                        return false;
                    }
                });
            }

            //Gắn sự kiện
            [departureSelect, adultsInput, childrenInput, infantsInput, promotionInput].forEach(el => {
                if (el) {
                    el.addEventListener('input', function() {
                        validatePassengers();
                        updateBookingSummary();
                    });
                    if (el.tagName === 'SELECT') {
                        el.addEventListener('change', function() {
                            validatePassengers();
                            updateBookingSummary();
                        });
                    }
                }
            });

            document.querySelectorAll('input[name="additional_services[]"]').forEach(cb => {
                cb.addEventListener('change', updateBookingSummary);
            });

            // Initial validation
            validatePassengers();
            updateBookingSummary();
        });
    </script>
@endsection

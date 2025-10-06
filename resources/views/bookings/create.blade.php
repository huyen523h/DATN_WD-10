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
                            @if($tour->images->count() > 0)
                                <img src="{{ $tour->images->first()->image_url }}" 
                                     class="img-fluid rounded" alt="{{ $tour->title }}">
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
                            <label for="departure_id" class="form-label">Chọn ngày khởi hành <span class="text-danger">*</span></label>
                            <select class="form-select @error('departure_id') is-invalid @enderror" 
                                    id="departure_id" name="departure_id" required>
                                <option value="">-- Chọn ngày khởi hành --</option>
                                @foreach($departures as $departure)
                                    <option value="{{ $departure->id }}" 
                                            data-price="{{ $tour->price }}"
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
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label for="adults" class="form-label">Người lớn <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('adults') is-invalid @enderror" 
                                       id="adults" name="adults" value="{{ old('adults', 1) }}" min="1" required>
                                @error('adults')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="children" class="form-label">Trẻ em</label>
                                <input type="number" class="form-control @error('children') is-invalid @enderror" 
                                       id="children" name="children" value="{{ old('children', 0) }}" min="0">
                                @error('children')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="infants" class="form-label">Em bé</label>
                                <input type="number" class="form-control @error('infants') is-invalid @enderror" 
                                       id="infants" name="infants" value="{{ old('infants', 0) }}" min="0">
                                @error('infants')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Promotion Code -->
                        @if($promotions->count() > 0)
                            <div class="mb-4">
                                <label for="promotion_code" class="form-label">Mã giảm giá</label>
                                <div class="input-group">
                                    <input type="text" class="form-control @error('promotion_code') is-invalid @enderror" 
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
                                    @foreach($promotions as $promotion)
                                        <span class="badge bg-light text-dark">{{ $promotion->code }}</span>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Note -->
                        <div class="mb-4">
                            <label for="note" class="form-label">Ghi chú</label>
                            <textarea class="form-control @error('note') is-invalid @enderror" 
                                      id="note" name="note" rows="3" 
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
            <div class="card sticky-top border-0 shadow-sm">
                <div class="card-header bg-white">
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
    const summaryDiv = document.getElementById('bookingSummary');
    const tourPrice = {{ $tour->price }};

    function updateSummary() {
        const selectedOption = departureSelect.options[departureSelect.selectedIndex];
        const adults = parseInt(adultsInput.value) || 0;
        const children = parseInt(childrenInput.value) || 0;
        const infants = parseInt(infantsInput.value) || 0;
        const totalPassengers = adults + children + infants;

        if (departureSelect.value && totalPassengers > 0) {
            const availableSeats = parseInt(selectedOption.dataset.seats);
            
            if (totalPassengers > availableSeats) {
                summaryDiv.innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle"></i>
                        Không đủ chỗ trống! Chỉ còn ${availableSeats} chỗ.
                    </div>
                `;
                return;
            }

            const totalAmount = tourPrice * totalPassengers;
            
            summaryDiv.innerHTML = `
                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <span>Người lớn (${adults} x ${tourPrice.toLocaleString()}đ):</span>
                        <span>${(tourPrice * adults).toLocaleString()}đ</span>
                    </div>
                    ${children > 0 ? `
                    <div class="d-flex justify-content-between">
                        <span>Trẻ em (${children} x ${tourPrice.toLocaleString()}đ):</span>
                        <span>${(tourPrice * children).toLocaleString()}đ</span>
                    </div>
                    ` : ''}
                    ${infants > 0 ? `
                    <div class="d-flex justify-content-between">
                        <span>Em bé (${infants} x 0đ):</span>
                        <span>0đ</span>
                    </div>
                    ` : ''}
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <strong>Tổng cộng:</strong>
                    <strong class="text-primary">${totalAmount.toLocaleString()}đ</strong>
                </div>
                <div class="mt-3">
                    <small class="text-muted">
                        <i class="fas fa-info-circle"></i>
                        Giá có thể thay đổi tùy theo mã giảm giá
                    </small>
                </div>
            `;
        } else {
            summaryDiv.innerHTML = `
                <div class="text-center text-muted py-4">
                    <i class="fas fa-info-circle fa-2x mb-2"></i>
                    <p>Vui lòng chọn ngày khởi hành và số lượng khách</p>
                </div>
            `;
        }
    }

    departureSelect.addEventListener('change', updateSummary);
    adultsInput.addEventListener('input', updateSummary);
    childrenInput.addEventListener('input', updateSummary);
    infantsInput.addEventListener('input', updateSummary);
});
</script>
@endsection

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
                        <img
                            src="{{ $tour->images->first()->image_url ?? 'https://via.placeholder.com/300x200' }}"
                            class="img-fluid rounded">
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
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5><i class="fas fa-calendar-plus"></i> Thông tin đặt tour</h5>
                </div>
                <div class="card-body">

                    <div class="alert alert-info mb-4">
                        <ul class="mb-0 ps-3">
                            <li>1 người lớn đi kèm tối đa <strong>2 trẻ em</strong></li>
                            <li>Trẻ em phải đi cùng người lớn</li>
                            <li>Người lớn ≥ 12 tuổi</li>
                        </ul>
                    </div>

                    <form method="POST" action="{{ route('bookings.store') }}" id="bookingForm">
                        @csrf
                        <input type="hidden" name="tour_id" value="{{ $tour->id }}">

                        {{-- DEPARTURE --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold">Ngày khởi hành *</label>
                            <select class="form-select" name="departure_id" id="departure_id" required>
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
                            <div class="col-md-4">
                                <label>Người lớn *</label>
                                <input type="number" class="form-control" id="adults" name="adults" value="1" min="1" required>
                            </div>
                            <div class="col-md-4">
                                <label>Trẻ em</label>
                                <input type="number" class="form-control" id="children" name="children" value="0" min="0">
                                <div class="invalid-feedback d-block" id="childrenError" style="display:none;"></div>
                            </div>
                            <div class="col-md-4">
                                <label>Em bé</label>
                                <input type="number" class="form-control" id="infants" name="infants" value="0" min="0">
                            </div>
                        </div>

                        {{-- PASSENGERS --}}
                        <div class="card mb-4">
                            <div class="card-header bg-white fw-bold">
                                <i class="fas fa-users"></i> Thông tin hành khách
                            </div>
                            <div class="card-body" id="passengerForms">
                                <div class="text-muted small">
                                    Chọn số lượng người để nhập thông tin
                                </div>
                            </div>
                        </div>

                        {{-- NOTE --}}
                        <div class="mb-4">
                            <label>Ghi chú</label>
                            <textarea class="form-control" name="note" rows="3"></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100">
                            <i class="fas fa-calendar-check"></i> Đặt tour
                        </button>
                    </form>

                </div>
            </div>
        </div>

        {{-- RIGHT --}}
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header fw-bold">
                    <i class="fas fa-calculator"></i> Tóm tắt
                </div>
                <div class="card-body" id="bookingSummary">
                    <div class="text-muted text-center py-4">
                        Chọn ngày và số lượng người
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const form = document.getElementById('bookingForm');
    const adults = document.getElementById('adults');
    const children = document.getElementById('children');
    const infants = document.getElementById('infants');
    const passengerForms = document.getElementById('passengerForms');
    const childrenError = document.getElementById('childrenError');

    function renderPassengers() {
        passengerForms.innerHTML = '';
        let index = 0;

        function block(type, label) {
            const i = index++;
            return `
            <div class="card mb-2">
                <div class="card-body">
                    <strong>${label} #${i + 1}</strong>
                    <input type="hidden" name="passengers[${type}][${i}][passenger_type]" value="${type}">
                    <input class="form-control mt-2" name="passengers[${type}][${i}][full_name]" placeholder="Họ tên" required>
                    <input class="form-control mt-2" name="passengers[${type}][${i}][birth_year]" placeholder="Năm sinh">
                    <input class="form-control mt-2" name="passengers[${type}][${i}][id_number]" placeholder="CCCD/Passport">
                </div>
            </div>`;
        }

        for (let i = 0; i < adults.value; i++) passengerForms.innerHTML += block('adult', 'Người lớn');
        for (let i = 0; i < children.value; i++) passengerForms.innerHTML += block('child', 'Trẻ em');
        for (let i = 0; i < infants.value; i++) passengerForms.innerHTML += block('infant', 'Em bé');
    }

    function validate(e) {
        if (children.value > adults.value * 2) {
            e.preventDefault();
            childrenError.textContent = 'Số trẻ em vượt quá quy định';
            childrenError.style.display = 'block';
        } else {
            childrenError.style.display = 'none';
        }
    }

    [adults, children, infants].forEach(el => el.addEventListener('input', renderPassengers));
    form.addEventListener('submit', validate);

    renderPassengers();
});
</script>
@endsection

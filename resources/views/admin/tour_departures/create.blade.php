@extends('layouts.admin')
@section('title', 'Thêm ngày khởi hành')

@section('content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    @if(isset($tour))
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.tours.index') }}">Quản lý Tour</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.tours.manage', $tour->id) }}">{{ $tour->title }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.departures.index') }}?tour_id={{ $tour->id }}">Lịch khởi hành</a></li>
            <li class="breadcrumb-item active">Thêm mới</li>
        </ol>
    </nav>

    <!-- Tour Context Info -->
    <div class="card shadow-sm mb-3 border-left border-primary" style="border-left-width: 4px;">
        <div class="card-body py-2">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <strong>{{ $tour->title }}</strong>
                    <span class="text-muted ms-2">| ID: {{ $tour->id }}</span>
                </div>
                <a href="{{ route('admin.tours.manage', $tour->id) }}" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-home"></i> Quản lý Tour
                </a>
            </div>
        </div>
    </div>
    @endif

    <h4 class="mb-4">Thêm ngày khởi hành mới</h4>

    <form action="{{ route('admin.departures.store') }}" method="POST">
        @csrf
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="tour_id" class="form-label">Tour</label>
                <select name="tour_id" class="form-select" required {{ isset($tour) ? 'readonly' : '' }}>
                    <option value="">-- Chọn tour --</option>
                    @foreach($tours as $t)
                    <option value="{{ $t->id }}"
                        {{ (old('tour_id') == $t->id) || (isset($tour) && $tour->id == $t->id) ? 'selected' : '' }}>
                        {{ $t->title }}
                    </option>
                    @endforeach
                </select>
                @if(isset($tour))
                <input type="hidden" name="tour_id" value="{{ $tour->id }}">
                @endif
                @error('tour_id') <div class="text-danger">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
                <label for="departure_date" class="form-label">Ngày khởi hành</label>
                <input type="date" name="departure_date" class="form-control" value="{{ old('departure_date') }}" required>
                @error('departure_date') <div class="text-danger">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-4">
                <label class="form-label">Tổng chỗ</label>
                <input type="number"
                    id="seats_total"
                    name="seats_total"
                    class="form-control"
                    value="{{ old('seats_total') }}"
                    min="1"
                    required>
            </div>

            <div class="col-md-4">
                <label class="form-label">Số ghế tối thiểu để khởi hành</label>
                <input type="number"
                    id="seats_min"
                    name="seats_min"
                    class="form-control"
                    value="{{ old('seats_min') }}"
                    min="1"
                    required>
                <div class="invalid-feedback">
                    Số ghế tối thiểu phải nằm trong khoảng 1 → Tổng chỗ
                </div>
            </div>

        </div>

        <div class="row mb-3">
            <div class="col-md-4">
                <label class="form-label">Giá người lớn</label>
                <input type="number" name="price" class="form-control" value="{{ old('price') }}" step="0.01" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Giá trẻ em</label>
                <input type="number" name="child_price" class="form-control" value="{{ old('child_price') }}" step="0.01">
            </div>
            <div class="col-md-4">
                <label class="form-label">Giá trẻ nhỏ</label>
                <input type="number" name="infant_price" class="form-control" value="{{ old('infant_price') }}" step="0.01">
            </div>
        </div>

        <div class="d-flex gap-2">
            <button class="btn btn-primary"><i class="fas fa-save"></i> Lưu lại</button>
            @if(isset($tour))
            <a href="{{ route('admin.departures.index') }}?tour_id={{ $tour->id }}" class="btn btn-secondary">Hủy</a>
            @else
            <a href="{{ route('admin.departures.index') }}" class="btn btn-secondary">Hủy</a>
            @endif
        </div>
    </form>
</div>
@endsection
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const seatsTotal = document.getElementById('seats_total');
        const seatsMin = document.getElementById('seats_min');

        function validateSeatsMin() {
            const total = parseInt(seatsTotal.value, 10);
            const min = parseInt(seatsMin.value, 10);

            if (isNaN(total) || isNaN(min)) {
                seatsMin.classList.remove('is-invalid');
                return;
            }

            if (min < 1 || min > total) {
                seatsMin.classList.add('is-invalid');
                seatsMin.setCustomValidity('invalid');
            } else {
                seatsMin.classList.remove('is-invalid');
                seatsMin.setCustomValidity('');
            }
        }

        seatsTotal.addEventListener('input', validateSeatsMin);
        seatsMin.addEventListener('input', validateSeatsMin);
    });
</script>
@endpush
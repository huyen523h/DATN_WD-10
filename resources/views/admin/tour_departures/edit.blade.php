@extends('layouts.admin')
@section('title', 'Chỉnh sửa ngày khởi hành')

@section('content')
<div class="container-fluid">
    <h4 class="mb-4">Chỉnh sửa ngày khởi hành</h4>
    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
    </div>
    @endif
    @if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
    </div>
    @endif
    @if (session('warning'))
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <i class="fas fa-info-circle"></i> {{ session('warning') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
    </div>
    @endif
    <form action="{{ route('admin.departures.update', $departure->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="tour_id" class="form-label">Tour</label>
                <select name="tour_id" class="form-select" required>
                    <option value="">-- Chọn tour --</option>
                    @foreach ($tours as $tour)
                    <option value="{{ $tour->id }}" {{ $departure->tour_id == $tour->id ? 'selected' : '' }}>
                        {{ $tour->title }}
                    </option>
                    @endforeach
                </select>
                @error('tour_id')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label for="departure_date" class="form-label">Ngày khởi hành</label>
                <input type="date" name="departure_date" class="form-control"
                    value="{{ old('departure_date', $departure->departure_date) }}" required>
                @error('departure_date')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-4">
                <label class="form-label">Tổng chỗ</label>
                <input type="number"
                    id="seats_total"
                    name="seats_total"
                    class="form-control"
                    value="{{ old('seats_total', $departure->seats_total) }}"
                    min="1"
                    required>
            </div>

            <div class="col-md-4">
                <label class="form-label">Còn lại (tự động)</label>
                <input type="number"
                    class="form-control"
                    value="{{ $departure->seats_available }}"
                    disabled>
            </div>

            <div class="col-md-4">
                <label class="form-label">Số ghế tối thiểu để khởi hành</label>
                <input type="number"
                    id="seats_min"
                    name="seats_min"
                    class="form-control"
                    value="{{ old('seats_min', $departure->seats_min) }}"
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
                <input type="number" name="price" class="form-control" value="{{ old('price', $departure->price) }}"
                    step="0.01" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Giá trẻ em</label>
                <input type="number" name="child_price" class="form-control"
                    value="{{ old('child_price', $departure->child_price) }}" step="0.01">
            </div>
            <div class="col-md-4">
                <label class="form-label">Giá trẻ nhỏ</label>
                <input type="number" name="infant_price" class="form-control"
                    value="{{ old('infant_price', $departure->infant_price) }}" step="0.01">
            </div>
        </div>

        <button class="btn btn-primary"><i class="fas fa-save"></i> Cập nhật</button>
        <a href="{{ route('admin.departures.index') }}" class="btn btn-secondary">Hủy</a>
    </form>
</div>
@endsection
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const seatsTotal = document.getElementById('seats_total');
        const seatsMin = document.getElementById('seats_min');

        if (!seatsTotal || !seatsMin) return;

        function validateSeatsMin() {
            const total = parseInt(seatsTotal.value, 10);
            const min = parseInt(seatsMin.value, 10);

            if (isNaN(total) || isNaN(min)) {
                seatsMin.classList.remove('is-invalid');
                seatsMin.setCustomValidity('');
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

        seatsMin.max = seatsTotal.value || '';

        seatsTotal.addEventListener('input', () => {
            seatsMin.max = seatsTotal.value || '';
            validateSeatsMin();
        });

        seatsMin.addEventListener('input', validateSeatsMin);
    });
</script>
@endpush
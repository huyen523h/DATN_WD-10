@extends('layouts.admin')

@section('title', 'Thêm booking thủ công')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold text-primary"><i class="fas fa-plus-circle"></i> Thêm booking thủ công</h4>
        <a href="{{ route('admin.bookings') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Quay lại danh sách</a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Lỗi:</strong>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.bookings.manual.store') }}" method="POST" class="card shadow-sm border-0">
        @csrf
        <div class="card-body">
            <div class="row g-4">
                <!-- A. Thông tin tour -->
                <div class="col-md-6">
                    <h6 class="fw-bold text-primary mb-3">A. Thông tin tour</h6>
                    <div class="mb-3">
                        <label class="form-label">Tour *</label>
                        <select name="tour_id" id="tour_id" class="form-select" required>
                            <option value="">-- Chọn tour --</option>
                            @foreach($tours as $tour)
                                <option value="{{ $tour->id }}" {{ old('tour_id') == $tour->id ? 'selected' : '' }}>{{ $tour->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Lịch khởi hành *</label>
                        <select name="departure_id" id="departure_id" class="form-select" required>
                            <option value="">-- Chọn lịch khởi hành --</option>
                        </select>
                        <small class="text-muted">Bắt buộc chọn lịch khởi hành vì booking gắn với 1 đợt đi cụ thể.</small>
                    </div>
                </div>

                <!-- B. Thông tin khách hàng -->
                <div class="col-md-6">
                    <h6 class="fw-bold text-primary mb-3">B. Thông tin khách hàng</h6>
                    <div class="mb-3">
                        <label class="form-label">Họ tên *</label>
                        <input type="text" name="customer_name" class="form-control" value="{{ old('customer_name') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Số điện thoại *</label>
                        <input type="text" name="customer_phone" class="form-control" value="{{ old('customer_phone') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="customer_email" class="form-control" value="{{ old('customer_email') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ghi chú</label>
                        <textarea name="note" class="form-control" rows="3">{{ old('note') }}</textarea>
                    </div>
                </div>
            </div>

            <hr>

            <div class="row g-4">
                <!-- C. Thông tin đoàn -->
                <div class="col-md-4">
                    <h6 class="fw-bold text-primary mb-3">C. Thông tin đoàn</h6>
                    <div class="mb-3">
                        <label class="form-label">Số người lớn *</label>
                        <input type="number" name="adults" min="1" class="form-control" value="{{ old('adults', 1) }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Số trẻ em</label>
                        <input type="number" name="children" min="0" class="form-control" value="{{ old('children', 0) }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Số em bé</label>
                        <input type="number" name="infants" min="0" class="form-control" value="{{ old('infants', 0) }}">
                    </div>
                    <small class="text-muted">Dùng để tính tiền và trừ số chỗ còn lại (em bé không trừ chỗ).</small>
                </div>

                <!-- D. Thông tin sale -->
                <div class="col-md-4">
                    <h6 class="fw-bold text-primary mb-3">D. Thông tin sale</h6>
                    <div class="mb-3">
                        <label class="form-label">Sale phụ trách</label>
                        <select name="staff_id" class="form-select">
                            <option value="">-- Chọn sale --</option>
                            @foreach($staffs as $staff)
                                <option value="{{ $staff->id }}" {{ old('staff_id') == $staff->id ? 'selected' : '' }}>{{ $staff->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nguồn booking</label>
                        <select name="source" class="form-select" required>
                            <option value="website" {{ old('source') === 'website' ? 'selected' : '' }}>Website</option>
                            <option value="zalo" {{ old('source') === 'zalo' ? 'selected' : '' }}>Zalo</option>
                            <option value="facebook" {{ old('source') === 'facebook' ? 'selected' : '' }}>Facebook</option>
                            <option value="phone" {{ old('source') === 'phone' ? 'selected' : '' }}>Điện thoại</option>
                        </select>
                    </div>
                </div>

                <!-- E. Thanh toán -->
                <div class="col-md-4">
                    <h6 class="fw-bold text-primary mb-3">E. Thanh toán</h6>
                    <div class="mb-3">
                        <label class="form-label">Trạng thái</label>
                        <select name="payment_status" class="form-select" required>
                            <option value="unpaid" {{ old('payment_status') === 'unpaid' ? 'selected' : '' }}>Chưa thanh toán</option>
                            <option value="deposit" {{ old('payment_status') === 'deposit' ? 'selected' : '' }}>Đặt cọc</option>
                            <option value="paid" {{ old('payment_status') === 'paid' ? 'selected' : '' }}>Đã thanh toán</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Số tiền đã thu</label>
                        <input type="number" name="paid_amount" min="0" step="0.01" class="form-control" value="{{ old('paid_amount', 0) }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phương thức thanh toán</label>
                        <input type="text" name="payment_method" class="form-control" value="{{ old('payment_method') }}" placeholder="Tiền mặt / Chuyển khoản / ...">
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer d-flex justify-content-end gap-2">
            <a href="{{ route('admin.bookings') }}" class="btn btn-secondary"><i class="fas fa-times"></i> Hủy</a>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Lưu booking</button>
        </div>
    </form>
</div>

@section('scripts')
<script>
    const tours = @json($toursForJs);

    function populateDepartures() {
        const tourId = document.getElementById('tour_id').value;
        const select = document.getElementById('departure_id');
        select.innerHTML = '<option value=\"\">-- Chọn lịch khởi hành --</option>';
        if (!tourId) return;
        const tour = tours.find(t => String(t.id) === String(tourId));
        if (!tour) return;
        tour.departures.forEach(d => {
            const opt = document.createElement('option');
            opt.value = d.id;
            opt.textContent = `${d.date || ''} (Còn ${d.seats_available}/${d.seats_total} chỗ)`;
            select.appendChild(opt);
        });
    }
    document.getElementById('tour_id').addEventListener('change', populateDepartures);
    // Preload if old value
    if (document.getElementById('tour_id').value) {
        populateDepartures();
        const oldDep = '{{ old('departure_id') }}';
        if (oldDep) document.getElementById('departure_id').value = oldDep;
    }
</script>
@endsection
@endsection


@php
    /** @var \App\Models\Vehicle $vehicle */
    $isEdit = $vehicle->exists;
@endphp

@if($errors->any())
    <div class="alert alert-danger">
        <strong>Vui lòng kiểm tra lại!</strong>
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ $formAction }}" method="POST">
    @csrf
    @if($isEdit)
        @method('PUT')
    @endif

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Thông tin cơ bản</span>
            <span class="badge bg-primary">{{ $isEdit ? 'Chỉnh sửa' : 'Tạo mới' }}</span>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Biển số xe *</label>
                    <input type="text"
                           name="license_plate"
                           class="form-control"
                           value="{{ old('license_plate', $vehicle->license_plate) }}"
                           required
                           placeholder="VD: 29B-123.45">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Loại xe *</label>
                    @php
                        $vehicleType = old('vehicle_type', $vehicle->vehicle_type);
                    @endphp
                    <select name="vehicle_type" class="form-select" required>
                        <option value="">-- Chọn loại xe --</option>
                        @foreach (['16' => '16 chỗ', '29' => '29 chỗ', '45' => '45 chỗ'] as $value => $label)
                            <option value="{{ $value }}" @selected((string)$vehicleType === (string)$value)>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Hãng xe</label>
                    <input type="text"
                           name="brand"
                           class="form-control"
                           value="{{ old('brand', $vehicle->brand) }}"
                           placeholder="VD: Toyota, Ford">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Năm sản xuất</label>
                    <input type="number"
                           name="year"
                           class="form-control"
                           value="{{ old('year', $vehicle->year) }}"
                           min="1900"
                           max="{{ date('Y') + 1 }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Màu xe</label>
                    <input type="text"
                           name="color"
                           class="form-control"
                           value="{{ old('color', $vehicle->color) }}"
                           placeholder="VD: Trắng, Xanh">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Trạng thái *</label>
                    @php
                        // Xử lý cả integer và string, và cả dữ liệu cũ (available/maintenance/inactive)
                        $rawStatus = old('status', $vehicle->status ?? 1);
                        if (is_string($rawStatus)) {
                            $statusMap = [
                                'available' => '1',
                                'maintenance' => '2',
                                'inactive' => '0',
                                '1' => '1',
                                '2' => '2',
                                '0' => '0',
                            ];
                            $currentStatus = $statusMap[$rawStatus] ?? '1';
                        } else {
                            $currentStatus = (string)(int)$rawStatus;
                        }
                    @endphp
                    <select name="status" class="form-select" required>
                        <option value="1" @selected($currentStatus === '1')>Đang hoạt động</option>
                        <option value="2" @selected($currentStatus === '2')>Đang bảo trì</option>
                        <option value="0" @selected($currentStatus === '0')>Ngưng sử dụng</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            Thông tin Tài xế
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Tên tài xế</label>
                    <input type="text"
                           name="driver_name"
                           class="form-control"
                           value="{{ old('driver_name', $vehicle->driver_name) }}"
                           placeholder="VD: Nguyễn Văn A">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Số điện thoại tài xế</label>
                    <input type="text"
                           name="driver_phone"
                           class="form-control"
                           value="{{ old('driver_phone', $vehicle->driver_phone) }}"
                           placeholder="VD: 0901234567">
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            Ghi chú
        </div>
        <div class="card-body">
            <textarea name="notes"
                      rows="3"
                      class="form-control"
                      placeholder="Ghi chú về xe...">{{ old('notes', $vehicle->notes) }}</textarea>
        </div>
    </div>

    <div class="text-end">
        <a href="{{ route('admin.vehicles.index') }}" class="btn btn-outline-secondary me-2">Huỷ</a>
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save me-1"></i> {{ $isEdit ? 'Cập nhật' : 'Tạo mới' }}
        </button>
    </div>
</form>



@extends('layouts.admin')

@section('title', 'Chi tiết lịch khởi hành')

@section('content')
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold text-primary">
                <i class="fas fa-calendar-day"></i> Chi tiết lịch khởi hành #{{ $departure->id }}
            </h4>
            <a href="{{ route('admin.departures.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại danh sách
            </a>
        </div>

        <!-- Tabs Navigation -->
        <ul class="nav nav-tabs mb-4" id="departureTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="info-tab" data-bs-toggle="tab" data-bs-target="#info" type="button" role="tab">
                    <i class="fas fa-info-circle"></i> Thông tin khởi hành
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="operating-tab" data-bs-toggle="tab" data-bs-target="#operating" type="button" role="tab">
                    <i class="fas fa-cog"></i> Thông tin vận hành
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="management-tab" data-bs-toggle="tab" data-bs-target="#management" type="button" role="tab">
                    <i class="fas fa-clipboard-list"></i> Thông tin điều hành
                </button>
            </li>
        </ul>

        <!-- Tabs Content -->
        <div class="tab-content" id="departureTabsContent">
            <!-- Tab 1: Thông tin khởi hành -->
            <div class="tab-pane fade show active" id="info" role="tabpanel">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-info-circle"></i> Thông tin khởi hành</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered align-middle">
                            <tbody>
                                <tr>
                                    <th style="width: 200px;">ID</th>
                                    <td>{{ $departure->id }}</td>
                                </tr>
                                <tr>
                                    <th>TOUR</th>
                                    <td>{{ $departure->tour->title ?? 'Chưa xác định' }}</td>
                                </tr>
                                <tr>
                                    <th>NGÀY KHỞI HÀNH</th>
                                    <td>{{ \Carbon\Carbon::parse($departure->departure_date)->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <th>TỔNG GHẾ</th>
                                    <td>{{ $departure->seats_total }}</td>
                                </tr>
                                <tr>
                                    <th>GHẾ TRỐNG</th>
                                    <td>
                                        <span class="badge bg-{{ $departure->seats_available > 0 ? 'success' : 'danger' }}">
                                            {{ $departure->seats_available }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>GIÁ NGƯỜI LỚN</th>
                                    <td class="fw-bold text-primary">{{ number_format($departure->price ?? 0, 0, ',', '.') }}₫</td>
                                </tr>
                                <tr>
                                    <th>GIÁ TRẺ EM</th>
                                    <td>{{ number_format($departure->child_price ?? 0, 0, ',', '.') }}₫</td>
                                </tr>
                                <tr>
                                    <th>GIÁ EM BÉ</th>
                                    <td>{{ number_format($departure->infant_price ?? 0, 0, ',', '.') }}₫</td>
                                </tr>
                                <tr>
                                    <th>TRẠNG THÁI</th>
                                    <td>
                                        @if ($departure->status === 'available')
                                            <span class="badge bg-success">Còn chỗ</span>
                                        @elseif ($departure->status === 'contact')
                                            <span class="badge bg-warning text-dark">Liên hệ</span>
                                        @elseif ($departure->status === 'sold_out')
                                            <span class="badge bg-danger">Hết chỗ</span>
                                        @else
                                            <span class="badge bg-secondary">Không xác định</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>NGÀY TẠO</th>
                                    <td>{{ $departure->created_at ? $departure->created_at->format('d/m/Y H:i') : '---' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Tab 2: Thông tin vận hành -->
            <div class="tab-pane fade" id="operating" role="tabpanel">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="fas fa-cog"></i> Thông tin vận hành</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.departures.update_operating', $departure->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="guide_id" class="form-label fw-bold">
                                        <i class="fas fa-user-tie"></i> Hướng dẫn viên chính
                                    </label>
                                    <select 
                                        class="form-select @error('guide_id') is-invalid @enderror" 
                                        id="guide_id" 
                                        name="guide_id">
                                        <option value="">-- Chọn hướng dẫn viên --</option>
                                        @foreach($guides as $guide)
                                            <option value="{{ $guide->id }}" 
                                                {{ old('guide_id', $departure->guide_id) == $guide->id ? 'selected' : '' }}>
                                                {{ $guide->name }}
                                                @if($guide->phone)
                                                    - {{ $guide->phone }}
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('guide_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    @if($departure->guide && $departure->guide->phone)
                                        <small class="text-muted">
                                            <i class="fas fa-phone"></i> Số điện thoại: {{ $departure->guide->phone }}
                                        </small>
                                    @endif
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="backup_guide_id" class="form-label fw-bold">
                                        <i class="fas fa-user-shield"></i> Hướng dẫn viên dự phòng
                                    </label>
                                    <select 
                                        class="form-select @error('backup_guide_id') is-invalid @enderror" 
                                        id="backup_guide_id" 
                                        name="backup_guide_id">
                                        <option value="">-- Chọn hướng dẫn viên dự phòng --</option>
                                        @foreach($guides as $guide)
                                            <option value="{{ $guide->id }}" 
                                                {{ old('backup_guide_id', $departure->backup_guide_id) == $guide->id ? 'selected' : '' }}>
                                                {{ $guide->name }}
                                                @if($guide->phone)
                                                    - {{ $guide->phone }}
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('backup_guide_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="vehicle_id" class="form-label fw-bold">
                                        <i class="fas fa-car"></i> Xe
                                    </label>
                                    <select 
                                        class="form-select @error('vehicle_id') is-invalid @enderror" 
                                        id="vehicle_id" 
                                        name="vehicle_id">
                                        <option value="">-- Chọn xe --</option>
                                        @foreach($vehicles as $vehicle)
                                            <option value="{{ $vehicle->id }}" 
                                                {{ old('vehicle_id', $departure->vehicle_id) == $vehicle->id ? 'selected' : '' }}>
                                                {{ $vehicle->license_plate }}
                                                @if($vehicle->brand)
                                                    - {{ $vehicle->brand }}
                                                @endif
                                                @if($vehicle->vehicle_type)
                                                    ({{ $vehicle->vehicle_type }} chỗ)
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('vehicle_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    @if($departure->vehicle)
                                        <small class="text-muted">
                                            Biển số: <strong>{{ $departure->vehicle->license_plate }}</strong>
                                            @if($departure->vehicle->driver_name)
                                                | Tài xế: {{ $departure->vehicle->driver_name }}
                                                @if($departure->vehicle->driver_phone)
                                                    - {{ $departure->vehicle->driver_phone }}
                                                @endif
                                            @endif
                                        </small>
                                    @endif
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="bus_company" class="form-label fw-bold">
                                        <i class="fas fa-building"></i> Nhà xe
                                    </label>
                                    <input 
                                        type="text" 
                                        class="form-control @error('bus_company') is-invalid @enderror" 
                                        id="bus_company" 
                                        name="bus_company"
                                        value="{{ old('bus_company', $departure->bus_company) }}"
                                        placeholder="Nhập tên nhà xe...">
                                    @error('bus_company')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="assembly_time" class="form-label fw-bold">
                                        <i class="fas fa-clock"></i> Giờ tập trung
                                    </label>
                                    <input 
                                        type="time" 
                                        class="form-control @error('assembly_time') is-invalid @enderror" 
                                        id="assembly_time" 
                                        name="assembly_time"
                                        value="{{ old('assembly_time', $departure->assembly_time ? \Carbon\Carbon::parse($departure->assembly_time)->format('H:i') : ($departure->departure_time ? \Carbon\Carbon::parse($departure->departure_time)->format('H:i') : '')) }}">
                                    @error('assembly_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="pickup_point" class="form-label fw-bold">
                                        <i class="fas fa-map-marker-alt"></i> Điểm đón
                                    </label>
                                    <input 
                                        type="text" 
                                        class="form-control @error('pickup_point') is-invalid @enderror" 
                                        id="pickup_point" 
                                        name="pickup_point"
                                        value="{{ old('pickup_point', $departure->pickup_point ?? $departure->departure_location ?? $departure->meeting_point) }}"
                                        placeholder="Nhập điểm đón...">
                                    @error('pickup_point')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="departure_instructions" class="form-label fw-bold">
                                    <i class="fas fa-info-circle"></i> Hướng dẫn tập trung
                                </label>
                                <textarea 
                                    class="form-control @error('departure_instructions') is-invalid @enderror" 
                                    id="departure_instructions" 
                                    name="departure_instructions" 
                                    rows="3"
                                    placeholder="Nhập hướng dẫn tập trung...">{{ old('departure_instructions', $departure->departure_instructions) }}</textarea>
                                @error('departure_instructions')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.departures.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Hủy
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Lưu thông tin vận hành
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Tab 3: Thông tin điều hành -->
            <div class="tab-pane fade" id="management" role="tabpanel">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0"><i class="fas fa-clipboard-list"></i> Thông tin điều hành</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.departures.update_management', $departure->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            
                            <div class="mb-4">
                                <label for="management_notes" class="form-label fw-bold">
                                    <i class="fas fa-sticky-note"></i> Ghi chú điều hành
                                </label>
                                <textarea 
                                    class="form-control @error('management_notes') is-invalid @enderror" 
                                    id="management_notes" 
                                    name="management_notes" 
                                    rows="5"
                                    placeholder="Nhập ghi chú điều hành...">{{ old('management_notes', $departure->management_notes) }}</textarea>
                                @error('management_notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="tour_status" class="form-label fw-bold">
                                    <i class="fas fa-flag"></i> Trạng thái tour
                                </label>
                                <select 
                                    class="form-select @error('tour_status') is-invalid @enderror" 
                                    id="tour_status" 
                                    name="tour_status">
                                    <option value="preparing" {{ old('tour_status', $departure->tour_status ?? 'preparing') === 'preparing' ? 'selected' : '' }}>
                                        Chuẩn bị
                                    </option>
                                    <option value="running" {{ old('tour_status', $departure->tour_status ?? 'preparing') === 'running' ? 'selected' : '' }}>
                                        Đang chạy
                                    </option>
                                    <option value="completed" {{ old('tour_status', $departure->tour_status ?? 'preparing') === 'completed' ? 'selected' : '' }}>
                                        Hoàn thành
                                    </option>
                                    <option value="has_issue" {{ old('tour_status', $departure->tour_status ?? 'preparing') === 'has_issue' ? 'selected' : '' }}>
                                        Có sự cố
                                    </option>
                                </select>
                                @error('tour_status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="guest_list_file" class="form-label fw-bold">
                                    <i class="fas fa-file-pdf"></i> File đính kèm: Danh sách khách (PDF)
                                </label>
                                @if($departure->guest_list_file)
                                    <div class="mb-2">
                                        <a href="{{ asset('storage/' . $departure->guest_list_file) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-download"></i> Xem file hiện tại
                                        </a>
                                        <small class="text-muted d-block mt-1">File: {{ basename($departure->guest_list_file) }}</small>
                                    </div>
                                @endif
                                <input 
                                    type="file" 
                                    class="form-control @error('guest_list_file') is-invalid @enderror" 
                                    id="guest_list_file" 
                                    name="guest_list_file"
                                    accept=".pdf">
                                <small class="form-text text-muted">Chỉ chấp nhận file PDF</small>
                                @error('guest_list_file')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.departures.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Hủy
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Lưu thông tin điều hành
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="card shadow-sm border-0 mt-4">
            <div class="card-body">
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.departures.edit', $departure->id) }}" class="btn btn-warning text-white">
                        <i class="fas fa-edit"></i> Chỉnh sửa thông tin khởi hành
                    </a>
                    <form action="{{ route('admin.departures.destroy', $departure->id) }}" method="POST"
                        onsubmit="return confirm('Bạn có chắc muốn xóa lịch này?')" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash-alt"></i> Xóa
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        .nav-tabs .nav-link {
            color: #495057;
        }
        .nav-tabs .nav-link.active {
            color: #0d6efd;
            font-weight: 600;
        }
        .table th {
            background-color: #f8f9fa;
            font-weight: 600;
        }
    </style>
    @endpush
@endsection

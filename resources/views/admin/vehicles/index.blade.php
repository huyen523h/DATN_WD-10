@extends('layouts.admin')

@section('title', 'Quản lý xe du lịch')

@section('content')
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Quản lý xe du lịch</h1>
            <p class="text-muted mb-0">Theo dõi danh sách xe, tài xế và tình trạng hoạt động</p>
        </div>
        <a href="{{ route('admin.vehicles.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Thêm xe
        </a>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Từ khoá</label>
                    <input type="text" name="keyword" class="form-control" value="{{ request('keyword') }}"
                           placeholder="Mã xe, tên xe, biển số, tài xế...">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Loại xe</label>
                    <select name="vehicle_type" class="form-select">
                        <option value="">-- Tất cả --</option>
                        @foreach (['16' => '16 chỗ', '29' => '29 chỗ', '45' => '45 chỗ'] as $value => $label)
                            <option value="{{ $value }}" @selected(request('vehicle_type') == $value)>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Trạng thái</label>
                    <select name="status" class="form-select">
                        <option value="">-- Tất cả --</option>
                        <option value="1" @selected(request('status') == '1')>Đang hoạt động</option>
                        <option value="2" @selected(request('status') == '2')>Đang bảo trì</option>
                        <option value="0" @selected(request('status') == '0')>Ngưng sử dụng</option>
                    </select>
                </div>
                <div class="col-md-12 text-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-search"></i> Lọc
                    </button>
                    <a href="{{ route('admin.vehicles.index') }}" class="btn btn-outline-secondary">Đặt lại</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body table-responsive">
            <table class="table align-middle">
                <thead>
                <tr>
                    <th>Biển số</th>
                    <th>Thông tin xe</th>
                    <th>Loại xe</th>
                    <th>Năm SX</th>
                    <th>Tài xế</th>
                    <th>Trạng thái</th>
                    <th class="text-end">Hành động</th>
                </tr>
                </thead>
                <tbody>
                @forelse($vehicles as $vehicle)
                    <tr>
                        <td>
                            <span class="fw-semibold">{{ $vehicle->license_plate }}</span>
                            <div class="text-muted">#{{ $vehicle->id }}</div>
                        </td>
                        <td>
                            <div class="fw-semibold">{{ $vehicle->brand ?? 'Chưa rõ hãng' }}</div>
                            <small class="text-muted">
                                {{ $vehicle->color ? $vehicle->color . ' • ' : '' }}
                                {{ $vehicle->notes ? \Illuminate\Support\Str::limit($vehicle->notes, 40) : 'Không có ghi chú' }}
                            </small>
                        </td>
                        <td>
                            @php
                                $typeMap = ['16' => '16 chỗ', '29' => '29 chỗ', '45' => '45 chỗ'];
                            @endphp
                            {{ $typeMap[$vehicle->vehicle_type] ?? $vehicle->vehicle_type }}
                        </td>
                        <td>{{ $vehicle->year ?? '-' }}</td>
                        <td>
                            @if($vehicle->driver_name)
                                <div>{{ $vehicle->driver_name }}</div>
                                <small class="text-muted">{{ $vehicle->driver_phone }}</small>
                            @else
                                <span class="text-muted">Chưa gán tài xế</span>
                            @endif
                        </td>
                        <td>
                            @php
                                $statusMap = [
                                    1 => ['label' => 'Đang hoạt động', 'class' => 'bg-success'],
                                    2 => ['label' => 'Đang bảo trì', 'class' => 'bg-warning text-dark'],
                                    0 => ['label' => 'Ngưng sử dụng', 'class' => 'bg-secondary'],
                                ];
                                // Model đã cast status thành integer, nên chỉ cần check integer
                                $statusValue = (int)$vehicle->status;
                                $status = $statusMap[$statusValue] ?? $statusMap[0]; // Default to inactive nếu không hợp lệ
                            @endphp
                            <span class="badge {{ $status['class'] }}">{{ $status['label'] }}</span>
                        </td>
                        <td class="text-end">
                            <div class="btn-group">
                                <a href="{{ route('admin.vehicles.show', $vehicle) }}"
                                   class="btn btn-sm btn-outline-secondary" title="Xem chi tiết">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.vehicles.edit', $vehicle) }}"
                                   class="btn btn-sm btn-outline-primary" title="Chỉnh sửa">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.vehicles.destroy', $vehicle) }}" method="POST"
                                      onsubmit="return confirm('Xác nhận xoá xe này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Xoá">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">Chưa có xe nào.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $vehicles->links() }}
        </div>
    </div>
@endsection


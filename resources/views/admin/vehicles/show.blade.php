@extends('layouts.admin')

@section('title', 'Chi tiết xe du lịch')

@section('content')
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <div>
            <a href="{{ route('admin.vehicles.index') }}" class="text-decoration-none text-muted">
                <i class="fas fa-arrow-left me-1"></i> Quay lại danh sách
            </a>
            <h1 class="h3 mt-2">Biển số: {{ $vehicle->license_plate }}</h1>
            <p class="text-muted mb-0">ID: #{{ $vehicle->id }}</p>
        </div>
        <div class="btn-group">
            <a href="{{ route('admin.vehicles.edit', $vehicle) }}" class="btn btn-primary">
                <i class="fas fa-edit me-1"></i> Chỉnh sửa
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    Thông tin xe
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-4">Biển số</dt>
                        <dd class="col-sm-8">{{ $vehicle->license_plate }}</dd>

                        <dt class="col-sm-4">Loại xe</dt>
                        <dd class="col-sm-8">
                            @php
                                $typeMap = ['16' => '16 chỗ', '29' => '29 chỗ', '45' => '45 chỗ'];
                            @endphp
                            {{ $typeMap[$vehicle->vehicle_type] ?? $vehicle->vehicle_type ?? '-' }}
                        </dd>

                        <dt class="col-sm-4">Hãng xe</dt>
                        <dd class="col-sm-8">{{ $vehicle->brand ?? '-' }}</dd>

                        <dt class="col-sm-4">Năm sản xuất</dt>
                        <dd class="col-sm-8">{{ $vehicle->year ?? '-' }}</dd>

                        <dt class="col-sm-4">Màu xe</dt>
                        <dd class="col-sm-8">{{ $vehicle->color ?? '-' }}</dd>
                    </dl>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Ghi chú</span>
                </div>
                <div class="card-body">
                    <p class="mb-0">
                        {{ $vehicle->notes ?: 'Không có ghi chú.' }}
                    </p>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Lịch khởi hành đã gán xe này</span>
                    <small class="text-muted">Tối đa 50 lịch tương lai và 50 lịch đã qua</small>
                </div>
                <div class="card-body">
                    <h6 class="mb-2">Các lịch sắp diễn ra</h6>
                    @if(isset($futureDepartures) && $futureDepartures->count())
                        <div class="table-responsive mb-3">
                            <table class="table table-sm align-middle mb-0">
                                <thead>
                                <tr>
                                    <th>Ngày khởi hành</th>
                                    <th>Tour</th>
                                    <th>Ghi chú khởi hành</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($futureDepartures as $dep)
                                    <tr>
                                        <td>{{ $dep->departure_date?->format('d/m/Y') }}</td>
                                        <td>{{ $dep->tour?->title ?? 'Tour đã xoá' }}</td>
                                        <td>{{ $dep->departure_location ?? '' }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">Chưa có lịch khởi hành tương lai nào được gán xe này.</p>
                    @endif

                    <hr>

                    <h6 class="mb-2">Lịch khởi hành đã chạy</h6>
                    @if(isset($pastDepartures) && $pastDepartures->count())
                        <div class="table-responsive">
                            <table class="table table-sm align-middle mb-0">
                                <thead>
                                <tr>
                                    <th>Ngày khởi hành</th>
                                    <th>Tour</th>
                                    <th>Ghi chú khởi hành</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($pastDepartures as $dep)
                                    <tr>
                                        <td>{{ $dep->departure_date?->format('d/m/Y') }}</td>
                                        <td>{{ $dep->tour?->title ?? 'Tour đã xoá' }}</td>
                                        <td>{{ $dep->departure_location ?? '' }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted mb-0">Chưa có lịch khởi hành đã chạy nào được gán xe này.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    Trạng thái & Tài xế
                </div>
                <div class="card-body">
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

                    <div class="mb-3">
                        <h6 class="text-muted mb-1">Trạng thái</h6>
                        <span class="badge {{ $status['class'] }}">{{ $status['label'] }}</span>
                    </div>

                    <div class="mb-3">
                        <h6 class="text-muted mb-1">Tên tài xế</h6>
                        <p class="mb-0">{{ $vehicle->driver_name ?? 'Chưa gán' }}</p>
                    </div>

                    <div class="mb-0">
                        <h6 class="text-muted mb-1">Số điện thoại tài xế</h6>
                        <p class="mb-0">{{ $vehicle->driver_phone ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection



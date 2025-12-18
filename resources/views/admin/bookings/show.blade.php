@extends('layouts.admin')

@section('title', 'Chi tiết đặt tour')

@section('content')
    <div class="container-fluid">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0 text-gray-800">Chi tiết đặt tour</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.bookings') }}">Đặt tour</a></li>
                        <li class="breadcrumb-item active">#{{ $booking->id }}</li>
                    </ol>
                </nav>
            </div>
            <div>
                <a href="{{ route('admin.bookings') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
            </div>
        </div>

        <div class="row">
            <!-- Booking Details -->
            <div class="col-lg-8">
                <!-- Tour Information -->
                <div class="card shadow mb-4">
                   <div class="card-header py-3 d-flex justify-content-between align-items-center">
    <h6 class="m-0 font-weight-bold text-primary">Thông tin tour</h6>
    {{-- Nút tắt để sang trang Sửa Tour --}}
    <a href="{{ route('admin.tours.edit', $booking->tour_id) }}" target="_blank" class="btn btn-sm btn-warning">
        <i class="fas fa-edit"></i> Cập nhật Lịch trình/Ảnh
    </a>
</div>

                   
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <h4 class="text-primary">{{ $booking->tour->title }}</h4>
                                <p class="text-muted">{{ $booking->tour->description }}</p>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <p><strong>Thời gian:</strong> {{ $booking->tour->duration_days }} ngày</p>
                                        <p><strong>Giá:</strong> {{ number_format($booking->tour->price) }} VNĐ</p>
                                    </div>
                                    <div class="col-sm-6">
                                        <p><strong>Danh mục:</strong> {{ $booking->tour->category->name }}</p>
                                        <p><strong>Trạng thái:</strong>
                                            <span
                                                class="badge badge-{{ $booking->tour->status === 'active' ? 'success' : 'secondary' }}">
                                                {{ $booking->tour->status === 'active' ? 'Hoạt động' : 'Không hoạt động' }}
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 text-center">
                                @if ($booking->tour->images->count() > 0)
                                    <img src="{{ $booking->tour->images->first()->image_url }}"
                                        alt="{{ $booking->tour->title }}" class="img-fluid rounded mb-2"
                                        style="max-height: 150px;">
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Booking Information -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Thông tin đặt tour</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-primary">Thông tin khách hàng</h6>
                                <p><strong>Tên:</strong> {{ $booking->user->name }}</p>
                                <p><strong>Email:</strong> {{ $booking->user->email }}</p>
                                @if ($booking->user->phone)
                                    <p><strong>Điện thoại:</strong> {{ $booking->user->phone }}</p>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-primary">Chi tiết đặt tour</h6>
                                <p><strong>Ngày khởi hành:</strong>
                                    {{ $booking->departure?->departure_date
                                        ? \Carbon\Carbon::parse($booking->departure->departure_date)->format('d/m/Y')
                                        : 'Chưa chọn lịch khởi hành' }}
                                </p>
                                <p><strong>Người lớn:</strong> {{ $booking->adults }}</p>
                                <p><strong>Trẻ em:</strong> {{ $booking->children }}</p>
                                <p><strong>Em bé:</strong> {{ $booking->infants }}</p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Ghi chú:</strong> {{ $booking->notes ?? 'Không có ghi chú' }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Trạng thái:</strong>
                                    <span
                                        class="badge badge-{{ $booking->status === 'confirmed' ? 'success' : ($booking->status === 'pending' ? 'warning' : 'secondary') }} badge-lg">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                 <div class="card shadow mb-4 border-primary">
    <div class="card-header py-3 bg-primary text-white d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold"><i class="fas fa-bus"></i> Thông tin Điều hành & Hậu cần</h6>
    </div>
    <div class="card-body">
        {{-- LOGIC KIỂM TRA: Chỉ cho phép điều hành khi đã THANH TOÁN --}}
        @if($booking->status === 'paid' || $booking->status === 'completed')
        
            {{-- TRƯỜNG HỢP 1: Đã thanh toán -> Hiện Form nhập liệu --}}
            @if($booking->departure)
               <form action="{{ route('admin.departures.update_operating', $booking->departure->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">
                            Hướng dẫn viên (HDV) <span class="text-danger">*</span>
                        </label>
                        <select name="guide_id" class="form-select" required> {{-- Thêm required --}}
                            <option value="">-- Chọn HDV --</option>
                            @foreach($guides as $guide)
                                <option value="{{ $guide->id }}" {{ $booking->departure->guide_id == $guide->id ? 'selected' : '' }}>
                                    {{ $guide->name }} ({{ $guide->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">
                            Chọn xe <span class="text-danger">*</span>
                        </label>
                        <select name="vehicle_id" class="form-select" required>
                            <option value="">-- Chọn xe --</option>
                            @foreach($vehicles as $vehicle)
                                @php
                                    $typeMap = ['16' => '16 chỗ', '29' => '29 chỗ', '45' => '45 chỗ'];
                                    $typeLabel = $typeMap[$vehicle->vehicle_type] ?? ($vehicle->vehicle_type . ' chỗ');
                                    $label = '[' . $typeLabel . '] ' . ($vehicle->brand ?? '') . ' ' . ($vehicle->color ?? '') . ' - ' . $vehicle->license_plate;
                                @endphp
                                <option value="{{ $vehicle->id }}" 
                                    {{ $booking->departure && $booking->departure->vehicle_id == $vehicle->id ? 'selected' : '' }}>
                                    {{ trim($label) }}
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Danh sách lấy từ mục <strong>Quản lý xe</strong>. Chỉ hiển thị các xe đang hoạt động.</small>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">
                            Thông tin xe & Tài xế
                        </label>
                        @if($booking->departure && $booking->departure->vehicle)
                            <div class="border rounded p-3 bg-light">
                                <p class="mb-1"><strong>Biển số:</strong> {{ $booking->departure->vehicle->license_plate }}</p>
                                <p class="mb-1"><strong>Loại xe:</strong> {{ $booking->departure->vehicle->vehicle_type }} chỗ</p>
                                @if($booking->departure->vehicle->brand)
                                    <p class="mb-1"><strong>Hãng:</strong> {{ $booking->departure->vehicle->brand }}</p>
                                @endif
                                @if($booking->departure->vehicle->color)
                                    <p class="mb-1"><strong>Màu:</strong> {{ $booking->departure->vehicle->color }}</p>
                                @endif
                                @if($booking->departure->vehicle->driver_name)
                                    <p class="mb-1"><strong>Tài xế:</strong> {{ $booking->departure->vehicle->driver_name }}</p>
                                @endif
                                @if($booking->departure->vehicle->driver_phone)
                                    <p class="mb-0"><strong>SĐT tài xế:</strong> {{ $booking->departure->vehicle->driver_phone }}</p>
                                @endif
                            </div>
                        @else
                            <div class="text-muted small">Chưa có thông tin xe. Vui lòng chọn xe ở trên.</div>
                        @endif
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">File Lịch trình / Hợp đồng</label>
                      <input type="file" name="itinerary_file" class="form-control" 
       accept=".jpg,.jpeg,.png,.pdf,.doc,.docx,.xls,.xlsx">
                        @if($booking->departure->itinerary_file)
                            <div class="mt-2 text-success small">
                                <i class="fas fa-check-circle"></i> Đã có file. 
                                <a href="{{ Storage::url($booking->departure->itinerary_file) }}" target="_blank" class="fw-bold text-decoration-underline">Xem</a>
                            </div>
                        @else
                            <div class="form-text text-muted">Chưa có file nào được tải lên.</div>
                        @endif
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Lưu & Chốt điều hành
                    </button>
                </div>
            </form>
            @else
                <div class="alert alert-warning">Đơn hàng này chưa được gán vào Lịch khởi hành nào.</div>
            @endif

        @else
            {{-- TRƯỜNG HỢP 2: Chưa thanh toán -> Ẩn form, hiện thông báo --}}
            <div class="text-center py-4">
                <div class="mb-3">
                    <i class="fas fa-lock fa-3x text-secondary opacity-50"></i>
                </div>
                <h6 class="fw-bold text-secondary">Chức năng bị khóa</h6>
                <p class="text-muted mb-0">
                    Khách hàng cần <strong>Thanh toán (Paid)</strong> đơn hàng trước khi bạn có thể cập nhật thông tin Xe và Hướng dẫn viên.
                </p>
            </div>
        @endif
    </div>
</div>

                <!-- Payment Information -->
                @if ($booking->payment->count() > 0)
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Thông tin thanh toán</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Phương thức</th>
                                            <th>Số tiền</th>
                                            <th>Trạng thái</th>
                                            <th>Ngày thanh toán</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($booking->payment as $payment)
                                            <tr>
                                                <td>{{ ucfirst($payment->payment_method) }}</td>
                                                <td>{{ number_format($payment->amount) }} VNĐ</td>
                                                <td>
                                                    <span
                                                        class="badge badge-{{ $payment->status === 'completed' ? 'success' : ($payment->status === 'pending' ? 'warning' : 'danger') }}">
                                                        {{ ucfirst($payment->status) }}
                                                    </span>
                                                </td>
                                                <td>{{ $payment->created_at->format('d/m/Y H:i') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Chat Messages -->
                @if ($booking->chat && $booking->chat->messages->count() > 0)
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Tin nhắn
                                ({{ $booking->chat->messages->count() }})</h6>
                        </div>
                        <div class="card-body">
                            <div class="chat-messages" style="max-height: 300px; overflow-y: auto;">
                                @foreach ($booking->chat->messages as $message)
                                    <div
                                        class="message mb-3 {{ $message->sender_id === auth()->id() ? 'text-end' : 'text-start' }}">
                                        <div
                                            class="d-flex {{ $message->sender_id === auth()->id() ? 'justify-content-end' : 'justify-content-start' }}">
                                            <div class="message-content {{ $message->sender_id === auth()->id() ? 'bg-primary text-white' : 'bg-light' }} p-3 rounded"
                                                style="max-width: 70%;">
                                                <p class="mb-1">{{ $message->content }}</p>
                                                <small
                                                    class="text-muted">{{ $message->created_at->format('d/m/Y H:i') }}</small>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Booking Summary -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Tóm tắt đặt tour</h6>
                    </div>
                    <div class="card-body">
                        <div class="stats-grid">
                            <div class="stat-card">
                                <div class="stat-icon stat-icon-primary">
                                    <i class="fas fa-users"></i>
                                </div>
                                <div class="stat-value">{{ $booking->adults + $booking->children + $booking->infants }}
                                </div>
                                <div class="stat-label">Tổng khách</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-icon stat-icon-info">
                                    <i class="fas fa-tag"></i>
                                </div>
                                <div class="stat-value">{{ number_format($booking->tour->price) }}</div>
                                <div class="stat-label">Giá tour (VNĐ)</div>
                            </div>
                            @if ($booking->promotion)
                                <div class="stat-card">
                                    <div class="stat-icon stat-icon-success">
                                        <i class="fas fa-percentage"></i>
                                    </div>
                                    <div class="stat-value">-{{ number_format($booking->discount_amount) }}</div>
                                    <div class="stat-label">Giảm giá (VNĐ)</div>
                                </div>
                            @endif
                            <div class="stat-card">
                                <div class="stat-icon stat-icon-warning">
                                    <i class="fas fa-calculator"></i>
                                </div>
                                <div class="stat-value">{{ number_format($booking->total_amount) }}</div>
                                <div class="stat-label">Tổng cộng (VNĐ)</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow mb-4 border-warning">
    <div class="card-header py-3 bg-warning text-dark">
        <h6 class="m-0 font-weight-bold"><i class="fas fa-file-excel"></i> Danh sách đoàn (Bảo hiểm)</h6>
    </div>
    <div class="card-body">
        @if($booking->passenger_manifest_file)
            <div class="mb-3">
                <span class="badge bg-success mb-2">Đã có file</span><br>
                <a href="{{ Storage::url($booking->passenger_manifest_file) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-download"></i> Tải về / Xem
                </a>
            </div>
            <hr>
            <p class="small text-muted mb-1">
    <i class="fas fa-info-circle"></i> Nếu file khách gửi bị lỗi/mờ, bạn có thể upload file chuẩn thay thế tại đây:
</p>
        @else
            <div class="alert alert-danger small">Chưa có danh sách đoàn.</div>
            <p class="small text-muted mb-1">Upload hộ khách (nếu khách gửi Zalo):</p>
        @endif

        <form action="{{ route('admin.bookings.upload-manifest', $booking->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="input-group input-group-sm">
                <input type="file" name="manifest_file" class="form-control" required>
                <button class="btn btn-primary" type="submit">Up</button>
            </div>
        </form>
    </div>
</div>

                <!-- Booking Actions -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Hành động</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">

                            @if ($booking->status === 'pending')
                                <form action="{{ route('admin.bookings.confirm', $booking) }}" method="POST"
                                    class="d-inline"
                                    onsubmit="return confirm('Bạn có chắc chắn muốn XÁC NHẬN đơn hàng này?')">
                                    @csrf
                                    <button type="submit" class="btn btn-success w-100">
                                        <i class="fas fa-check"></i> Xác nhận đơn
                                    </button>
                                </form>
                            @endif

                            @if ($booking->status === 'confirmed')
                                <form action="{{ route('admin.bookings.markAsPaid', $booking) }}" method="POST"
                                    class="d-inline"
                                    onsubmit="return confirm('Xác nhận khách đã THANH TOÁN đơn hàng này (thủ công)?')">
                                    @csrf
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-money-check-alt"></i> Đánh dấu Đã thanh toán
                                    </button>
                                </form>
                            @endif

                            @if ($booking->status === 'pending' || $booking->status === 'confirmed')
                                <form action="{{ route('admin.bookings.cancel', $booking) }}" method="POST"
                                    class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn HỦY đơn hàng này?')">
                                    @csrf
                                    <button type="submit" class="btn btn-warning w-100">
                                        <i class="fas fa-times"></i> Hủy đơn
                                    </button>
                                </form>

                                <form action="{{ route('admin.bookings.destroy', $booking) }}" method="POST"
                                    class="d-inline"
                                    onsubmit="return confirm('HÀNH ĐỘNG NGUY HIỂM: Bạn có chắc chắn muốn XÓA VĨNH VIỄN đơn hàng này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger w-100">
                                        <i class="fas fa-trash"></i> Xóa đặt tour
                                    </button>
                                </form>
                            @else
                                <div class="alert alert-info mb-0">
                                    <i class="fas fa-lock"></i>
                                    Đơn hàng đã thanh toán (hoặc đã hủy) không thể bị xóa hoặc sửa đổi.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Booking Info -->
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Thông tin chi tiết</h6>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm">
                            <tr>
                                <td><strong>ID:</strong></td>
                                <td>#{{ $booking->id }}</td>
                            </tr>
                            <tr>
                                <td><strong>Đặt lúc:</strong></td>
                                <td>{{ $booking->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Cập nhật:</strong></td>
                                <td>{{ $booking->updated_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            @if ($booking->staff)
                                <tr>
                                    <td><strong>Nhân viên:</strong></td>
                                    <td>{{ $booking->staff->name }}</td>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

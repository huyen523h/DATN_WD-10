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
    
    {{-- CHỈ HIỆN KHI TOUR CHƯA BỊ HỦY --}}
    @if($booking->status !== 'cancelled')
        <a href="{{ route('admin.tours.edit', $booking->tour_id) }}" target="_blank" class="btn btn-sm btn-warning">
            <i class="fas fa-edit"></i> Cập nhật Lịch trình/Ảnh
        </a>
    @else
        <span class="badge bg-danger">ĐÃ HỦY</span>
    @endif
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
                            Thông tin Xe & Biển số <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="vehicle_details" class="form-control" 
                               value="{{ $booking->departure->vehicle_details }}" 
                               placeholder="VD: 45 chỗ - 29B-12345" required> {{-- Thêm required --}}
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">
                            Liên hệ Tài xế <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="driver_contact" class="form-control" 
                               value="{{ $booking->departure->driver_contact }}" 
                               placeholder="Tên & SĐT Tài xế" required> {{-- Thêm required --}}
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

{{-- [CODE MỚI] HIỂN THỊ ẢNH PHIẾU THU --}}
@if($booking->receipt_image)
    <div class="card shadow mb-4 border-success">
        <div class="card-header py-3 bg-success text-white d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold">
                <i class="fas fa-file-invoice-dollar me-2"></i> Bằng chứng thanh toán (Tiền mặt)
            </h6>

            {{-- [THÊM MỚI] NÚT SỬA ẢNH --}}
            <button type="button" class="btn btn-light btn-sm text-success fw-bold" 
        data-bs-toggle="modal" data-bs-target="#updateImageModal"> {{-- Đổi tên ID ở đây --}}
    <i class="fas fa-edit me-1"></i> Up lại ảnh khác
</button>
        </div>
        <div class="card-body">
            <div class="d-flex align-items-center">
                <div class="me-3">
                    {{-- Hiển thị ảnh thumb nhỏ --}}
                    <img src="{{ Storage::url($booking->receipt_image) }}" alt="Receipt" 
                         class="img-thumbnail" style="height: 80px; width: 80px; object-fit: cover;">
                </div>
                <div>
                    <h6 class="font-weight-bold text-success mb-1">Đã lưu ảnh phiếu thu</h6>
                    <p class="small text-muted mb-2">Ảnh này được upload bởi Admin/Nhân viên khi xác nhận thu tiền.</p>
                    <a href="{{ Storage::url($booking->receipt_image) }}" target="_blank" class="btn btn-sm btn-outline-success">
                        <i class="fas fa-expand"></i> Xem ảnh gốc (Full Size)
                    </a>
                </div>
            </div>
        </div>
    </div>
@endif

                <!-- Payment Information -->
                @if ($booking->payment->count() > 0)
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Thông tin thanh toán</h6>
                        </div>
                        <div class="card-body">
                          <div class="table-responsive">
    <table class="table table-bordered table-hover align-middle">
        <thead class="bg-light">
            <tr>
                <th></th>
                <th>Mã giao dịch</th>
                <th>Phương thức</th>
                <th>Số tiền</th>
                <th>Người thu / Ghi chú</th> {{-- CỘT MỚI --}}
                <th>Trạng thái</th>
                <th>Thời gian</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($booking->payment as $payment)
                <tr>
                    {{-- 1. Mã giao dịch --}}
                    <td class="font-monospace small">
                        {{ $payment->transaction_code ?? 'N/A' }}
                    </td>

                    {{-- 2. Phương thức --}}
                    <td>
                        @if($payment->payment_method == 'cash')
                            <span class="badge bg-secondary"><i class="fas fa-money-bill"></i> Tiền mặt</span>
                        @elseif($payment->payment_method == 'momo')
                            <span class="badge" style="background-color: #a50064"><i class="fas fa-wallet"></i> MoMo</span>
                        @else
                            {{ ucfirst($payment->payment_method) }}
                        @endif
                    </td>

                    {{-- 3. Số tiền --}}
                    <td class="fw-bold text-success">
                        {{ number_format($payment->amount) }} VNĐ
                    </td>

                    {{-- 4. Người thu & Ghi chú (QUAN TRỌNG NHẤT) --}}
                    <td>
                        @if($payment->payment_method == 'cash')
                            <div class="d-flex align-items-center mb-1">
                                <i class="fas fa-user-check text-primary me-2"></i>
                                <strong class="text-dark">{{ $payment->user->name ?? 'Không rõ' }}</strong>
                            </div>
                            @if($payment->note)
                                <div class="small text-muted fst-italic border-start border-3 ps-2">
                                    "{{ $payment->note }}"
                                </div>
                            @endif
                        @else
                            <span class="text-muted small">Thanh toán online tự động</span>
                        @endif
                    </td>

                    {{-- 5. Trạng thái --}}
                    <td>
                        <span class="badge badge-{{ $payment->status === 'completed' ? 'success' : ($payment->status === 'pending' ? 'warning' : 'danger') }}">
                            {{ ucfirst($payment->status) }}
                        </span>
                    </td>

                    {{-- 6. Thời gian --}}
                    <td class="small text-muted">
                        {{ $payment->created_at->timezone('Asia/Ho_Chi_Minh')->format('d/m/Y H:i') }}
                    </td>
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

               {{-- LOGIC: Ẩn hoàn toàn nếu đã hủy VÀ chưa từng có file nào --}}
{{-- Nếu đã hủy nhưng có file cũ thì vẫn hiện để đối soát --}}
@if($booking->status !== 'cancelled' || $booking->passenger_manifest_file)
    <div class="card shadow mb-4 border-warning">
        <div class="card-header py-3 bg-warning text-dark d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold"><i class="fas fa-file-excel"></i> Danh sách đoàn (Bảo hiểm)</h6>
            @if($booking->status === 'cancelled')
                <span class="badge bg-danger text-white">Đã chốt (Hủy)</span>
            @endif
        </div>
        <div class="card-body">
            
            {{-- PHẦN HIỂN THỊ FILE ĐÃ CÓ --}}
            @if($booking->passenger_manifest_file)
                <div class="mb-3">
                    <span class="badge bg-success mb-2">Đã có file lưu trữ</span><br>
                    <a href="{{ Storage::url($booking->passenger_manifest_file) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-download"></i> Tải về / Xem danh sách cũ
                    </a>
                </div>
                <hr>
            @endif

           @if($booking->status !== 'cancelled')
                
                {{-- LOGIC MỚI: Chỉ cho upload khi đã PAID --}}
                @if($booking->status === 'paid' || $booking->status === 'completed')
                    
                    @if(!$booking->passenger_manifest_file)
                        <div class="alert alert-warning small border-warning">
                            <i class="fas fa-exclamation-circle"></i> Chưa có danh sách. Vui lòng upload để mua bảo hiểm.
                        </div>
                    @endif
                    
                    <p class="small text-muted mb-1">Upload hộ khách (nếu khách gửi Zalo):</p>
                    <form action="{{ route('admin.bookings.upload-manifest', $booking->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="input-group input-group-sm">
                            <input type="file" name="manifest_file" class="form-control" required>
                            <button class="btn btn-primary" type="submit">Up</button>
                        </div>
                    </form>

                @else
                    {{-- Nếu chưa thanh toán: KHÓA FORM và hiện nhắc nhở --}}
                    <div class="alert alert-secondary small mb-0 text-center">
                        <i class="fas fa-lock"></i> Chức năng bị khóa.<br>
                        <strong>Yêu cầu xác nhận "Thu tiền" trước khi cập nhật danh sách.</strong>
                    </div>
                @endif

            @else
                {{-- Đã hủy --}}
                <div class="text-center text-muted small fst-italic">
                    <i class="fas fa-lock"></i> Đơn đã hủy. Không thể cập nhật danh sách.
                </div>
            @endif
        </div>
    </div>
@endif

                <!-- Booking Actions -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Hành động</h6>
                    </div>
                    <div class="card-body">
    <div class="d-grid gap-2">

        {{-- 1. Nút Xác nhận đơn (Chỉ hiện khi Pending) --}}
        @if ($booking->status === 'pending')
            <form action="{{ route('admin.bookings.confirm', $booking) }}" method="POST" class="d-inline"
                  onsubmit="return confirm('Bạn có chắc chắn muốn XÁC NHẬN đơn hàng này?')">
                @csrf
                <button type="submit" class="btn btn-success w-100">
                    <i class="fas fa-check"></i> Xác nhận đơn
                </button>
            </form>
        @endif

        {{-- 2. Nút Thu tiền (Chỉ hiện khi Confirmed) --}}
        @if ($booking->status === 'confirmed')
            <button type="button" class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#paymentModal">
                <i class="fas fa-money-bill-wave me-2"></i> Xác nhận Thu tiền mặt
            </button>
        @endif

        {{-- 3. Nút Hủy đơn (Hiện cho MỌI TRẠNG THÁI trừ khi đã Hủy hoặc Hoàn thành) --}}
        {{-- ĐÂY LÀ PHẦN QUAN TRỌNG BẠN ĐANG THIẾU --}}
        @if ($booking->status !== 'cancelled' && $booking->status !== 'completed')
            <button type="button" class="btn btn-warning w-100" data-bs-toggle="modal" data-bs-target="#cancelModal">
                <i class="fas fa-times-circle"></i> Hủy đơn hàng
            </button>
        @endif

        {{-- 4. Nút Xóa đơn (Chỉ cho phép xóa khi chưa thanh toán để an toàn dữ liệu) --}}
      @if ($booking->status === 'pending' && $booking->payment->count() == 0)
    <form action="{{ route('admin.bookings.destroy', $booking) }}" method="POST" class="d-inline"
          onsubmit="return confirm('HÀNH ĐỘNG NGUY HIỂM: Bạn có chắc chắn muốn XÁC NHẬN XÓA VĨNH VIỄN?')">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger w-100 mt-2">
            <i class="fas fa-trash"></i> Xóa đơn rác
        </button>
    </form>
@else
    {{-- Với các trạng thái khác (Paid, Cancelled, Confirmed, Completed...) --}}
    <div class="alert alert-light border text-center small mt-2 mb-0 p-2 bg-light text-muted">
        <i class="fas fa-archive"></i> 
        @if($booking->status === 'cancelled')
            Đơn hủy được lưu trữ để đối soát.
        @else
            Đơn hàng có dữ liệu quan trọng không thể xóa.
        @endif
    </div>
@endif

    </div>
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

    {{-- popup minh bạch sổ sách --}}
  <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            {{-- QUAN TRỌNG: Phải có enctype="multipart/form-data" mới upload được ảnh --}}
            <form action="{{ route('admin.bookings.markAsPaid', $booking->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="paymentModalLabel">
                        <i class="fas fa-cash-register me-2"></i> Xác nhận Thu tiền mặt
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    {{-- Thông tin người thu --}}
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">NGƯỜI THU TIỀN:</label>
                        <input type="text" class="form-control bg-light" value="{{ Auth::user()->name }} ({{ Auth::user()->email }})" readonly>
                    </div>

                    {{-- Số tiền --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold">Số tiền thực thu (VNĐ) <span class="text-danger">*</span></label>
                        <input type="number" name="amount" class="form-control form-control-lg fw-bold text-success bg-light" 
                               value="{{ $booking->total_amount }}" readonly> 
                        <div class="form-text text-primary">
                            <i class="fas fa-info-circle"></i> Hệ thống yêu cầu thanh toán 100% giá trị đơn hàng.
                        </div>
                    </div>

                    {{-- [MỚI] UPLOAD ẢNH PHIẾU THU --}}
                    <div class="mb-3 p-3 bg-light border rounded border-primary">
                        <label class="form-label fw-bold text-primary">
                            <i class="fas fa-camera"></i> Ảnh hóa đơn/Phiếu thu <span class="text-danger">*</span>
                        </label>
                        {{-- required: Bắt buộc phải chọn ảnh --}}
                        {{-- accept="image/*": Chỉ cho chọn file ảnh --}}
                        <input type="file" name="receipt_image" class="form-control" required accept="image/*">
                        
                        <div class="form-text small mt-2">
                            <i class="fas fa-exclamation-circle text-warning"></i> 
                            Bắt buộc chụp ảnh phiếu thu có chữ ký khách hàng để làm bằng chứng đối soát.
                            <br>
                            <em>(Nếu đơn này đã có ảnh cũ, việc upload mới sẽ ghi đè ảnh cũ).</em>
                        </div>
                    </div>

                    {{-- Ghi chú --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold">Ghi chú giao dịch</label>
                        <textarea name="note" class="form-control" rows="2" placeholder="Ví dụ: Khách đưa tiền mặt tại văn phòng, tiền chẵn..."></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy bỏ</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-check"></i> Xác nhận & Lưu ảnh
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="updateImageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.bookings.updateReceipt', $booking->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-image me-2"></i> Cập nhật ảnh phiếu thu
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="alert alert-info small">
                        <i class="fas fa-info-circle"></i> Hành động này chỉ thay thế hình ảnh chứng từ. Thông tin số tiền và giao dịch sẽ giữ nguyên.
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Chọn ảnh mới thay thế <span class="text-danger">*</span></label>
                        <input type="file" name="receipt_image" class="form-control" required accept="image/*">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Lưu ảnh mới
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- HIỂN THỊ ẢNH PHIẾU THU (NẾU CÓ) --}}
{{-- @if($booking->receipt_image)
    <div class="alert alert-success d-flex align-items-center shadow-sm mt-3">
        <div class="me-3">
            <i class="fas fa-file-invoice-dollar fa-3x text-success"></i>
        </div>
        <div>
            <h6 class="fw-bold mb-1">Đã có biên lai thu tiền mặt</h6>
            <p class="mb-1 small">Ảnh bằng chứng đã được lưu trữ trên hệ thống.</p>
            <a href="{{ Storage::url($booking->receipt_image) }}" target="_blank" class="btn btn-sm btn-success">
                <i class="fas fa-eye me-1"></i> Xem ảnh hóa đơn gốc
            </a>
        </div>
    </div>
@endif --}}

{{-- Lý do hủy tour phía ad --}}
<div class="modal fade" id="cancelModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('admin.bookings.cancel', $booking->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-exclamation-triangle me-2"></i> Xác nhận Hủy Đơn Hàng
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    
                    {{-- 1. Nhập lý do hủy --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold">Lý do hủy tour <span class="text-danger">*</span></label>
                        <textarea name="cancel_reason" id="cancelReason" class="form-control" rows="2" required 
                                  placeholder="Nhập lý do chi tiết..."></textarea>
                    </div>

                    {{-- 2. LOGIC TỰ ĐỘNG TÍNH TIỀN --}}
                    @if($booking->status === 'paid')
                        @php 
                            $refundInfo = $booking->getRefundInfo(); 
                        @endphp

                        <div class="card bg-light border-danger mb-3">
                            <div class="card-header bg-white text-danger fw-bold">
                                <i class="fas fa-calculator"></i> Tính toán hoàn tiền (Đơn đã thanh toán: {{ number_format($booking->total_amount) }}đ)

                            </div>
                            <div class="card-body">
                                
                                {{-- SELECT BOX: LỖI THUỘC VỀ AI --}}   
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-dark">Nguyên nhân hủy / Lỗi thuộc về ai?</label>
                                    <select class="form-select border-danger text-danger fw-bold" id="faultSelector">
                                        <option value="customer" selected>🔴 Do Khách hàng (Áp dụng chính sách phạt)</option>
                                        <option value="company">🟢 Do Công ty / Thiên tai (Hoàn 100%)</option>
                                    </select>
                                </div>

                                {{-- Hiển thị chính sách tương ứng (Sẽ đổi chữ bằng JS) --}}
                                <div class="alert alert-warning d-flex align-items-center" id="policyAlert">
                                    <i class="fas fa-lightbulb fa-2x me-3 text-warning"></i>
                                    <div>
                                        <strong>Chính sách áp dụng:</strong> <br>
                                        <span id="policyText">{{ $refundInfo['policy'] }}</span>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">Số tiền hoàn lại khách (VNĐ)</label>
                                        
                                        {{-- INPUT SỐ TIỀN: Có các data-attribute để JS lấy giá trị --}}
                                        <input type="number" name="refund_amount" id="refundAmountInput" 
                                               class="form-control fw-bold text-danger" 
                                               value="{{ $refundInfo['amount'] }}" 
                                               data-policy-amount="{{ $refundInfo['amount'] }}"
                                               data-full-amount="{{ $booking->total_amount }}"
                                               required>
                                        
                                        <div class="form-text">Hệ thống tự động điền, nhưng bạn vẫn có thể sửa tay.</div>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">Ảnh bằng chứng chuyển khoản <span class="text-danger">*</span></label>
                                        <input type="file" name="refund_proof_file" class="form-control" required accept="image/*">
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> Đơn chưa thanh toán. Hệ thống sẽ hủy và nhả chỗ ngay lập tức.
                        </div>
                    @endif

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" required id="confirmCancel">
                        <label class="form-check-label text-danger fw-bold" for="confirmCancel">
                            Tôi xác nhận muốn hủy đơn và hoàn tiền theo mức trên.
                        </label>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-ban"></i> Xác nhận Hủy
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Lấy các element cần thiết
        const faultSelector = document.getElementById('faultSelector');
        const refundInput = document.getElementById('refundAmountInput');
        const policyText = document.getElementById('policyText');
        const cancelReason = document.getElementById('cancelReason');

        // Chỉ chạy nếu tồn tại selector (tức là đơn đã thanh toán)
        if (faultSelector && refundInput) {
            
            // Lấy giá trị tiền gốc từ data-attribute đã cài sẵn trong HTML
            const policyAmount = parseFloat(refundInput.getAttribute('data-policy-amount'));
            const fullAmount = parseFloat(refundInput.getAttribute('data-full-amount'));
            const originalPolicyText = policyText.innerText; // Lưu lại text chính sách gốc

            // Bắt sự kiện khi Admin thay đổi lựa chọn "Lỗi thuộc về ai"
            faultSelector.addEventListener('change', function() {
                if (this.value === 'company') {
                    // TRƯỜNG HỢP 1: Lỗi do công ty -> Hoàn 100%
                    refundInput.value = fullAmount; // Điền full tiền
                    policyText.innerText = "Lỗi do phía công ty hoặc bất khả kháng. Hoàn trả 100% số tiền khách đã đóng.";
                    
                    // (Tùy chọn) Tự động gợi ý lý do
                    if(cancelReason.value === '') {
                        cancelReason.value = "Hủy do lỗi vận hành/thiên tai. Hoàn tiền 100%.";
                    }
                } else {
                    // TRƯỜNG HỢP 2: Lỗi do khách -> Quay về mức phạt theo quy định
                    refundInput.value = policyAmount; // Điền tiền theo chính sách
                    policyText.innerText = originalPolicyText; // Trả lại text chính sách cũ
                }
            });
        }
    });
</script>
@endsection
@endsection

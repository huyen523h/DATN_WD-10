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
            <div class="d-flex gap-2">
                <button type="button" 
                        class="btn btn-primary" 
                        onclick="generateInvoice({{ $booking->id }})"
                        title="In hóa đơn">
                    <i class="fas fa-print"></i> In hóa đơn
                </button>
                <button type="button" 
                        class="btn btn-success" 
                        onclick="downloadInvoice({{ $booking->id }})"
                        title="Tải PDF">
                    <i class="fas fa-download"></i> Tải PDF
                </button>
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
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Thông tin tour</h6>
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

                <!-- Booking Actions -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Hành động</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            @if ($booking->status === 'pending')
                                <form action="{{ route('admin.bookings.update', $booking) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="confirmed">
                                    <button type="submit" class="btn btn-success w-100">
                                        <i class="fas fa-check"></i> Xác nhận đặt tour
                                    </button>
                                </form>
                            @endif

                            @if ($booking->status === 'confirmed')
                                <form action="{{ route('admin.bookings.update', $booking) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="completed">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-flag-checkered"></i> Hoàn thành
                                    </button>
                                </form>
                            @endif

                            @if ($booking->status !== 'cancelled')
                                <form action="{{ route('admin.bookings.update', $booking) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="cancelled">
                                    <button type="submit" class="btn btn-warning w-100"
                                        onclick="return confirm('Bạn có chắc chắn muốn hủy đặt tour này?')">
                                        <i class="fas fa-times"></i> Hủy đặt tour
                                    </button>
                                </form>
                            @endif

                            <form action="{{ route('admin.bookings.destroy', $booking) }}" method="POST"
                                class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa đặt tour này?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger w-100">
                                    <i class="fas fa-trash"></i> Xóa đặt tour
                                </button>
                            </form>
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

@push('scripts')
<script>
// Generate Invoice PDF
async function generateInvoice(bookingId, buttonElement = null) {
    let button = null;
    let originalContent = null;
    
    try {
        button = buttonElement || event.target.closest('button');
        originalContent = button.innerHTML;
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang tạo...';
        button.disabled = true;

        // Debug route first
        const debugResponse = await fetch(`/debug-invoice-simple/${bookingId}`);
        const debugData = await debugResponse.json();

        if (!debugData.success) {
            throw new Error('Debug failed: ' + debugData.message);
        }

        // Generate invoice
        const response = await fetch(`/web/invoices/booking/${bookingId}/pdf`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });

        if (response.ok) {
            const data = await response.json();
            if (data.success && data.data && data.data.download_url) {
                const newWindow = window.open(data.data.download_url, '_blank');
                if (newWindow) {
                    showAlert('success', 'PDF hóa đơn đã được tạo thành công!');
                } else {
                    showAlert('warning', 'Popup bị chặn. Vui lòng cho phép popup và thử lại.');
                }
            }
        }
    } catch (error) {
        console.error('Error generating invoice:', error);
        showAlert('danger', 'Lỗi: ' + error.message);
    } finally {
        if (button && originalContent) {
            button.innerHTML = originalContent;
            button.disabled = false;
        }
    }
}

// Download Invoice PDF
async function downloadInvoice(bookingId) {
    let button = null;
    let originalContent = null;
    
    try {
        button = event.target.closest('button');
        originalContent = button.innerHTML;
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang tải...';
        button.disabled = true;

        const response = await fetch(`/web/invoices/booking/${bookingId}/pdf`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });

        if (response.ok) {
            const data = await response.json();
            if (data.success) {
                const link = document.createElement('a');
                link.href = data.data.download_url;
                link.download = data.data.file_name;
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                showAlert('success', 'PDF hóa đơn đã được tải xuống!');
            }
        }
    } catch (error) {
        showAlert('danger', 'Lỗi: ' + error.message);
    } finally {
        if (button && originalContent) {
            button.innerHTML = originalContent;
            button.disabled = false;
        }
    }
}

// Show alert message
function showAlert(type, message) {
    const alertContainer = document.createElement('div');
    alertContainer.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    alertContainer.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    alertContainer.innerHTML = `
        <div class="d-flex align-items-center">
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>
            <span>${message}</span>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(alertContainer);
    
    setTimeout(() => {
        if (alertContainer.parentNode) {
            alertContainer.remove();
        }
    }, 5000);
}
</script>
@endpush

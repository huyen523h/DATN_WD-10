@extends('layouts.app')

@section('content')
<div class="container text-center mt-5">
    <h2>Thông tin thanh toán MoMo</h2>

    <div class="card shadow-sm p-4 mt-4 mx-auto" style="max-width: 500px;">
        <p><strong>Mã đơn hàng:</strong> {{ $data['orderId'] ?? 'N/A' }}</p>
        <p><strong>Số tiền:</strong> {{ number_format($data['amount'] ?? 0, 0, ',', '.') }} VND</p>
        <p><strong>Mã giao dịch MoMo:</strong> {{ $data['transId'] ?? 'N/A' }}</p>
        <p><strong>Mã phản hồi:</strong> {{ $data['resultCode'] ?? 'N/A' }}</p>
        <p><strong>Thông điệp:</strong> {{ $data['message'] ?? 'Không có thông tin' }}</p>

        @if(isset($data['resultCode']) && $data['resultCode'] == 0)
            <h3 class="text-success mt-4">Thanh toán thành công</h3>
            
            @if($booking)
                <div class="d-grid gap-2 mt-4">
                    <button type="button" class="btn btn-primary" onclick="generateInvoice({{ $booking->id }}, this)">
                        <i class="fas fa-print"></i> In hóa đơn
                    </button>
                    <button type="button" class="btn btn-success" onclick="downloadInvoice({{ $booking->id }}, this)">
                        <i class="fas fa-download"></i> Tải PDF
                    </button>
                </div>
            @endif
        @else
            <h3 class="text-danger mt-4">Thanh toán thất bại</h3>
        @endif

        <a href="/" class="btn btn-outline-primary mt-4">Quay lại trang chủ</a>
        @if($booking ?? null)
        <a href="{{ route('bookings.show', $booking->id) }}" class="btn btn-outline-secondary mt-2">Xem chi tiết đặt tour</a>
        @endif
    </div>
</div>

@if($booking ?? null)
<script>
// Generate Invoice (Print)
async function generateInvoice(bookingId, buttonElement = null) {
    let button = null;
    let originalContent = null;
    
    try {
        if (buttonElement) {
            button = buttonElement;
        } else if (typeof event !== 'undefined' && event && event.target) {
            button = event.target.closest('button');
        } else {
            button = document.querySelector(`button[onclick*="generateInvoice(${bookingId}"]`);
        }
        
        if (!button) {
            alert('Không tìm thấy nút. Vui lòng thử lại.');
            return;
        }

        originalContent = button.innerHTML;
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang tạo...';
        button.disabled = true;

        const csrfTokenElement = document.querySelector('meta[name="csrf-token"]');
        if (!csrfTokenElement) {
            throw new Error('CSRF token not found');
        }
        const csrfToken = csrfTokenElement.getAttribute('content');

        const response = await fetch(`/web/invoices/booking/${bookingId}/pdf`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        });

        if (!response.ok) {
            const errorText = await response.text();
            throw new Error(`HTTP error! status: ${response.status} - ${errorText}`);
        }

        const data = await response.json();

        if (data.success && data.data && data.data.download_url) {
            // Open PDF in new tab
            const newWindow = window.open(data.data.download_url, '_blank');
            
            if (newWindow) {
                // Show success message
                const alertDiv = document.createElement('div');
                alertDiv.className = 'alert alert-success alert-dismissible fade show mt-3';
                alertDiv.innerHTML = '<strong>Thành công!</strong> PDF hóa đơn đã được tạo. <button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
                button.parentElement.appendChild(alertDiv);
                setTimeout(() => alertDiv.remove(), 5000);
            } else {
                alert('Popup bị chặn. Vui lòng cho phép popup và thử lại.');
            }
        } else {
            throw new Error(data.message || 'Không thể tạo PDF');
        }
    } catch (error) {
        console.error('Error generating invoice:', error);
        alert('Lỗi: ' + error.message);
    } finally {
        if (button && originalContent) {
            button.innerHTML = originalContent;
            button.disabled = false;
        }
    }
}

// Download Invoice PDF
async function downloadInvoice(bookingId, buttonElement = null) {
    let button = null;
    let originalContent = null;
    
    try {
        if (buttonElement) {
            button = buttonElement;
        } else if (typeof event !== 'undefined' && event && event.target) {
            button = event.target.closest('button');
        } else {
            button = document.querySelector(`button[onclick*="downloadInvoice(${bookingId}"]`);
        }
        
        if (!button) {
            alert('Không tìm thấy nút. Vui lòng thử lại.');
            return;
        }

        originalContent = button.innerHTML;
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang tải...';
        button.disabled = true;

        const csrfTokenElement = document.querySelector('meta[name="csrf-token"]');
        if (!csrfTokenElement) {
            throw new Error('CSRF token not found');
        }
        const csrfToken = csrfTokenElement.getAttribute('content');

        // Use download endpoint which returns file with proper headers
        const link = document.createElement('a');
        link.href = `/web/invoices/booking/${bookingId}/download`;
        link.style.display = 'none';
        document.body.appendChild(link);
        
        // Trigger download
        link.click();
        
        // Clean up
        setTimeout(() => {
            if (link.parentNode) {
                document.body.removeChild(link);
            }
        }, 100);
        
        // Show success message
        const alertDiv = document.createElement('div');
        alertDiv.className = 'alert alert-success alert-dismissible fade show mt-3';
        alertDiv.innerHTML = '<strong>Thành công!</strong> PDF hóa đơn đã được tải xuống. <button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
        button.parentElement.appendChild(alertDiv);
        setTimeout(() => alertDiv.remove(), 5000);
        
        // Restore button
        if (button && originalContent) {
            button.innerHTML = originalContent;
            button.disabled = false;
        }
    } catch (error) {
        console.error('Error downloading invoice:', error);
        alert('Lỗi: ' + error.message);
        if (button && originalContent) {
            button.innerHTML = originalContent;
            button.disabled = false;
        }
    }
}
</script>
@endif

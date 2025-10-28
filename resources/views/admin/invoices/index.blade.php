@extends('layouts.admin')

@section('title', 'Quản lý Hóa đơn - Admin')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item active">Hóa đơn</li>
@endsection

@section('content')
<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2><i class="fas fa-file-invoice text-primary"></i> Quản lý Hóa đơn</h2>
        <p class="text-muted mb-0">Quản lý tất cả hóa đơn trong hệ thống</p>
    </div>
    <a href="{{ route('admin.invoices.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Tạo hóa đơn mới
    </a>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                            <i class="fas fa-file-invoice text-primary fa-lg"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-1">Tổng hóa đơn</h6>
                        <h4 class="mb-0 fw-bold">{{ $invoices->total() }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-warning bg-opacity-10 rounded-circle p-3">
                            <i class="fas fa-clock text-warning fa-lg"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-1">Chờ thanh toán</h6>
                        <h4 class="mb-0 fw-bold">{{ $invoices->where('payment_status', 'pending')->count() }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-success bg-opacity-10 rounded-circle p-3">
                            <i class="fas fa-check-circle text-success fa-lg"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-1">Đã thanh toán</h6>
                        <h4 class="mb-0 fw-bold">{{ $invoices->where('payment_status', 'paid')->count() }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-info bg-opacity-10 rounded-circle p-3">
                            <i class="fas fa-money-bill-wave text-info fa-lg"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-1">Tổng doanh thu</h6>
                        <h4 class="mb-0 fw-bold">{{ number_format($invoices->where('payment_status', 'paid')->sum('total_amount'), 0, ',', '.') }}đ</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modern Invoices Table -->
<x-admin.table 
    :headers="[
        ['key' => 'invoice_number', 'label' => 'Số hóa đơn', 'sortable' => true],
        ['key' => 'customer', 'label' => 'Khách hàng', 'sortable' => true, 'component' => 'admin.table.avatar'],
        ['key' => 'tour_title', 'label' => 'Tour', 'sortable' => true],
        ['key' => 'total_amount', 'label' => 'Tổng tiền', 'sortable' => true, 'component' => 'admin.table.price'],
        ['key' => 'payment_status', 'label' => 'Trạng thái', 'sortable' => true, 'component' => 'admin.table.status-badge'],
        ['key' => 'invoice_date', 'label' => 'Ngày tạo', 'sortable' => true, 'component' => 'admin.table.date'],
        ['key' => 'due_date', 'label' => 'Hạn thanh toán', 'sortable' => true, 'component' => 'admin.table.date']
    ]"
    :data="$invoices->map(function($invoice) {
        return [
            'id' => $invoice->id,
            'invoice_number' => $invoice->invoice_number,
            'customer' => [
                'name' => $invoice->customer_name,
                'email' => $invoice->customer_email
            ],
            'tour_title' => $invoice->tour_title,
            'total_amount' => [
                'price' => $invoice->total_amount,
                'original_price' => null
            ],
            'payment_status' => $invoice->payment_status,
            'invoice_date' => $invoice->invoice_date,
            'due_date' => $invoice->due_date
        ];
    })"
    :actions="[
        ['action' => 'view', 'icon' => 'fas fa-eye', 'class' => 'btn-primary', 'title' => 'Xem chi tiết'],
        ['action' => 'pdf', 'icon' => 'fas fa-file-pdf', 'class' => 'btn-info', 'title' => 'Xem PDF'],
        ['action' => 'download', 'icon' => 'fas fa-download', 'class' => 'btn-success', 'title' => 'Tải PDF'],
        ['action' => 'email', 'icon' => 'fas fa-envelope', 'class' => 'btn-warning', 'title' => 'Gửi email'],
        ['action' => 'edit', 'icon' => 'fas fa-edit', 'class' => 'btn-secondary', 'title' => 'Chỉnh sửa'],
        ['action' => 'delete', 'icon' => 'fas fa-trash', 'class' => 'btn-danger', 'title' => 'Xóa']
    ]"
    :searchable="true"
    :sortable="true"
    :filterable="true"
    :pagination="$invoices"
    empty-message="Chưa có hóa đơn nào"
    id="invoices-table"
>
    <!-- Custom Filters -->
    <x-slot name="filters">
        <div class="filter-grid">
            <div class="filter-group">
                <label class="filter-label">Trạng thái thanh toán</label>
                <select name="payment_status" class="filter-select">
                    <option value="">Tất cả trạng thái</option>
                    <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Chờ thanh toán</option>
                    <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Đã thanh toán</option>
                    <option value="cancelled" {{ request('payment_status') == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                    <option value="refunded" {{ request('payment_status') == 'refunded' ? 'selected' : '' }}>Đã hoàn tiền</option>
                </select>
            </div>
            
            <div class="filter-group">
                <label class="filter-label">Trạng thái hóa đơn</label>
                <select name="status" class="filter-select">
                    <option value="">Tất cả trạng thái</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Bản nháp</option>
                    <option value="sent" {{ request('status') == 'sent' ? 'selected' : '' }}>Đã gửi</option>
                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Đã thanh toán</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                </select>
            </div>
            
            <div class="filter-group">
                <label class="filter-label">Từ ngày</label>
                <input type="date" name="date_from" class="filter-input" value="{{ request('date_from') }}">
            </div>
            
            <div class="filter-group">
                <label class="filter-label">Đến ngày</label>
                <input type="date" name="date_to" class="filter-input" value="{{ request('date_to') }}">
            </div>
            
            <div class="filter-group">
                <label class="filter-label">Khoảng tiền</label>
                <div class="price-range">
                    <input type="number" name="min_amount" class="filter-input" placeholder="Từ" value="{{ request('min_amount') }}">
                    <span class="range-separator">-</span>
                    <input type="number" name="max_amount" class="filter-input" placeholder="Đến" value="{{ request('max_amount') }}">
                </div>
            </div>
        </div>
        
        <div class="filter-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search"></i> Áp dụng bộ lọc
            </button>
            <a href="{{ route('admin.invoices.index') }}" class="btn btn-secondary">
                <i class="fas fa-times"></i> Xóa bộ lọc
            </a>
        </div>
    </x-slot>
</x-admin.table>

@endsection

@push('styles')
<style>
/* Custom styles for invoice table */
.invoice-number {
    font-family: 'Courier New', monospace;
    font-weight: 600;
    color: #3b82f6;
}

.status-badge {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
    border-radius: 0.375rem;
    font-weight: 600;
    text-transform: uppercase;
}

.badge-success {
    background: #d1fae5;
    color: #065f46;
}

.badge-warning {
    background: #fef3c7;
    color: #92400e;
}

.badge-danger {
    background: #fee2e2;
    color: #991b1b;
}

.badge-info {
    background: #dbeafe;
    color: #1e40af;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle invoice actions
    const actionButtons = document.querySelectorAll('[data-action]');
    
    actionButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const action = this.dataset.action;
            const invoiceId = this.dataset.id;
            
            switch(action) {
                case 'view':
                    window.location.href = `/admin/invoices/${invoiceId}`;
                    break;
                    
                case 'pdf':
                    window.open(`/admin/invoices/${invoiceId}/pdf`, '_blank');
                    break;
                    
                case 'download':
                    window.location.href = `/admin/invoices/${invoiceId}/download`;
                    break;
                    
                case 'email':
                    sendInvoiceEmail(invoiceId);
                    break;
                    
                case 'edit':
                    window.location.href = `/admin/invoices/${invoiceId}/edit`;
                    break;
                    
                case 'delete':
                    if (confirm('Bạn có chắc chắn muốn xóa hóa đơn này?')) {
                        deleteInvoice(invoiceId);
                    }
                    break;
            }
        });
    });
    
    function sendInvoiceEmail(invoiceId) {
        fetch(`/admin/invoices/${invoiceId}/send-email`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification(data.message, 'success');
            } else {
                showNotification(data.message, 'error');
            }
        })
        .catch(error => {
            showNotification('Có lỗi xảy ra khi gửi email!', 'error');
        });
    }
    
    function deleteInvoice(invoiceId) {
        fetch(`/admin/invoices/${invoiceId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification(data.message, 'success');
                location.reload();
            } else {
                showNotification(data.message, 'error');
            }
        })
        .catch(error => {
            showNotification('Có lỗi xảy ra khi xóa hóa đơn!', 'error');
        });
    }
    
    function showNotification(message, type) {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
            <span>${message}</span>
        `;
        
        // Add to page
        document.body.appendChild(notification);
        
        // Show notification
        setTimeout(() => notification.classList.add('show'), 100);
        
        // Remove notification
        setTimeout(() => {
            notification.classList.remove('show');
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }
});
</script>
@endpush

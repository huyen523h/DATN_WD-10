@extends('layouts.admin')

@section('title', 'Quản lý Hóa đơn - Admin')

@section('breadcrumb')
<li class="breadcrumb-item active">Quản lý Hóa đơn</li>
@endsection

@section('content')
<!-- Header Section -->
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="fw-bold text-dark mb-2">
                    <i class="fas fa-file-invoice text-primary me-2"></i>
                    Quản lý Hóa đơn
                </h2>
                <p class="text-muted mb-0">Quản lý tất cả hóa đơn trong hệ thống</p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-primary" onclick="refreshInvoices()">
                    <i class="fas fa-sync-alt me-1"></i>
                    Làm mới
                </button>
                <button class="btn btn-outline-secondary" onclick="exportInvoices()">
                    <i class="fas fa-download me-1"></i>
                    Xuất Excel
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="bg-primary bg-opacity-10 rounded p-3 me-3">
                        <i class="fas fa-file-invoice fa-2x text-primary"></i>
                    </div>
                    <div>
                        <h4 class="mb-0 fw-bold text-dark" id="total-invoices">0</h4>
                        <p class="text-muted mb-0">Tổng hóa đơn</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="bg-success bg-opacity-10 rounded p-3 me-3">
                        <i class="fas fa-check-circle fa-2x text-success"></i>
                    </div>
                    <div>
                        <h4 class="mb-0 fw-bold text-dark" id="paid-invoices">0</h4>
                        <p class="text-muted mb-0">Đã thanh toán</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="bg-warning bg-opacity-10 rounded p-3 me-3">
                        <i class="fas fa-clock fa-2x text-warning"></i>
                    </div>
                    <div>
                        <h4 class="mb-0 fw-bold text-dark" id="pending-invoices">0</h4>
                        <p class="text-muted mb-0">Chờ thanh toán</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="bg-info bg-opacity-10 rounded p-3 me-3">
                        <i class="fas fa-dollar-sign fa-2x text-info"></i>
                    </div>
                    <div>
                        <h4 class="mb-0 fw-bold text-dark" id="total-revenue">0 VNĐ</h4>
                        <p class="text-muted mb-0">Tổng doanh thu</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Trạng thái</label>
                <select class="form-select" id="status-filter">
                    <option value="">Tất cả trạng thái</option>
                    <option value="issued">Đã phát hành</option>
                    <option value="paid">Đã thanh toán</option>
                    <option value="cancelled">Đã hủy</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Từ ngày</label>
                <input type="date" class="form-control" id="date-from">
            </div>
            <div class="col-md-3">
                <label class="form-label">Đến ngày</label>
                <input type="date" class="form-control" id="date-to">
            </div>
            <div class="col-md-3">
                <label class="form-label">Tìm kiếm</label>
                <input type="text" class="form-control" id="search-input" placeholder="Số hóa đơn, tên khách hàng...">
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-12">
                <button class="btn btn-primary" onclick="filterInvoices()">
                    <i class="fas fa-search me-1"></i>
                    Lọc
                </button>
                <button class="btn btn-outline-secondary" onclick="clearFilters()">
                    <i class="fas fa-times me-1"></i>
                    Xóa bộ lọc
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Invoices Table -->
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="border-0 py-3 px-4 fw-semibold text-muted">Số hóa đơn</th>
                        <th class="border-0 py-3 px-4 fw-semibold text-muted">Khách hàng</th>
                        <th class="border-0 py-3 px-4 fw-semibold text-muted">Tour</th>
                        <th class="border-0 py-3 px-4 fw-semibold text-muted">Số tiền</th>
                        <th class="border-0 py-3 px-4 fw-semibold text-muted">Trạng thái</th>
                        <th class="border-0 py-3 px-4 fw-semibold text-muted">Ngày phát hành</th>
                        <th class="border-0 py-3 px-4 fw-semibold text-muted text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody id="invoices-table-body">
                    <!-- Data will be loaded here -->
                </tbody>
            </table>
        </div>
        
        <!-- Loading State -->
        <div id="loading-state" class="text-center py-5" style="display: none;">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-3 text-muted">Đang tải dữ liệu...</p>
        </div>
        
        <!-- Empty State -->
        <div id="empty-state" class="text-center py-5" style="display: none;">
            <div class="mb-4">
                <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center" 
                     style="width: 120px; height: 120px;">
                    <i class="fas fa-file-invoice fa-3x text-muted"></i>
                </div>
            </div>
            <h5 class="text-muted mb-3">Chưa có hóa đơn nào</h5>
            <p class="text-muted">Hãy tạo hóa đơn đầu tiên cho khách hàng</p>
        </div>
    </div>
</div>

<!-- Pagination -->
<div id="pagination-container" class="d-flex justify-content-between align-items-center p-4 border-top">
    <!-- Pagination will be loaded here -->
</div>
@endsection

@section('styles')
<style>
    .card {
        border-radius: 12px;
        transition: all 0.3s ease;
    }
    
    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important;
    }
    
    .table tbody tr:hover {
        background-color: #f8f9fa;
    }
    
    .badge {
        border-radius: 8px;
        font-weight: 500;
    }
    
    .bg-opacity-10 {
        background-color: rgba(var(--bs-primary-rgb), 0.1) !important;
    }
    
    .text-primary {
        color: #0EA5E9 !important;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #0EA5E9 0%, #38BDF8 100%);
        border: none;
        border-radius: 8px;
    }
    
    .btn-primary:hover {
        background: linear-gradient(135deg, #0284C7 0%, #0EA5E9 100%);
        transform: translateY(-1px);
    }
    
    .shadow-sm {
        box-shadow: 0 2px 4px rgba(0,0,0,0.08) !important;
    }
    
    .border-0 {
        border: none !important;
    }
    
    .fw-bold {
        font-weight: 700 !important;
    }
    
    .fw-semibold {
        font-weight: 600 !important;
    }
    
    .btn-group .btn {
        border-radius: 8px;
        margin: 0 2px;
    }
    
    .form-select {
        border-radius: 8px;
        font-size: 0.875rem;
    }
    
    .form-select:focus {
        border-color: #0EA5E9;
        box-shadow: 0 0 0 0.2rem rgba(14, 165, 233, 0.25);
    }
</style>
@endsection

@section('scripts')
<script>
    let currentPage = 1;
    let currentFilters = {};

    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        loadInvoices();
        initializeTooltips();
    });

    // Load invoices
    async function loadInvoices(page = 1) {
        try {
            showLoading();
            
            const params = new URLSearchParams({
                page: page,
                ...currentFilters
            });

            const response = await fetch(`/api/invoices?${params}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            const data = await response.json();

            if (data.success) {
                displayInvoices(data.data);
                updateStats(data.data);
                currentPage = page;
            } else {
                showAlert('danger', 'Lỗi: ' + data.message);
            }
        } catch (error) {
            showAlert('danger', 'Lỗi: ' + error.message);
        } finally {
            hideLoading();
        }
    }

    // Display invoices in table
    function displayInvoices(invoicesData) {
        const tbody = document.getElementById('invoices-table-body');
        
        if (invoicesData.data.length === 0) {
            showEmptyState();
            return;
        }

        hideEmptyState();
        
        tbody.innerHTML = invoicesData.data.map(invoice => `
            <tr class="border-bottom">
                <td class="py-4 px-4">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary bg-opacity-10 rounded p-2 me-3">
                            <i class="fas fa-file-invoice text-primary"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold text-dark">${invoice.invoice_number}</h6>
                            <small class="text-muted">Hóa đơn</small>
                        </div>
                    </div>
                </td>
                <td class="py-4 px-4">
                    <div class="d-flex align-items-center">
                        <div class="bg-info bg-opacity-10 rounded-circle p-2 me-3">
                            <i class="fas fa-user text-info"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold text-dark">${invoice.booking.user.name}</h6>
                            <small class="text-muted">${invoice.booking.user.email}</small>
                        </div>
                    </div>
                </td>
                <td class="py-4 px-4">
                    <div>
                        <h6 class="mb-0 fw-bold text-dark">${invoice.booking.tour.title}</h6>
                        <small class="text-muted">${invoice.booking.tour.location}</small>
                    </div>
                </td>
                <td class="py-4 px-4">
                    <h6 class="mb-0 fw-bold text-success">${formatCurrency(invoice.amount)}</h6>
                </td>
                <td class="py-4 px-4">
                    <span class="badge ${getStatusBadgeClass(invoice.status)}">
                        ${getStatusText(invoice.status)}
                    </span>
                </td>
                <td class="py-4 px-4">
                    <small class="text-muted">
                        <i class="fas fa-calendar me-1"></i>
                        ${formatDate(invoice.issue_date)}
                    </small>
                </td>
                <td class="py-4 px-4 text-center">
                    <div class="btn-group" role="group">
                        <button class="btn btn-outline-info btn-sm" 
                                title="Xem chi tiết"
                                data-bs-toggle="tooltip"
                                onclick="viewInvoice(${invoice.id})">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="btn btn-outline-success btn-sm" 
                                title="In hóa đơn"
                                data-bs-toggle="tooltip"
                                onclick="generateInvoice(${invoice.booking.id})">
                            <i class="fas fa-file-invoice"></i>
                        </button>
                        <button class="btn btn-outline-primary btn-sm" 
                                title="Tải PDF"
                                data-bs-toggle="tooltip"
                                onclick="downloadInvoice(${invoice.booking.id})">
                            <i class="fas fa-download"></i>
                        </button>
                        <button class="btn btn-outline-warning btn-sm" 
                                title="Cập nhật trạng thái"
                                data-bs-toggle="tooltip"
                                onclick="updateInvoiceStatus(${invoice.id})">
                            <i class="fas fa-edit"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `).join('');

        initializeTooltips();
    }

    // Filter invoices
    function filterInvoices() {
        currentFilters = {
            status: document.getElementById('status-filter').value,
            date_from: document.getElementById('date-from').value,
            date_to: document.getElementById('date-to').value,
            search: document.getElementById('search-input').value
        };
        
        loadInvoices(1);
    }

    // Clear filters
    function clearFilters() {
        document.getElementById('status-filter').value = '';
        document.getElementById('date-from').value = '';
        document.getElementById('date-to').value = '';
        document.getElementById('search-input').value = '';
        
        currentFilters = {};
        loadInvoices(1);
    }

    // Generate Invoice PDF
    async function generateInvoice(bookingId) {
        try {
            const button = event.target.closest('button');
            const originalContent = button.innerHTML;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            button.disabled = true;

            const response = await fetch(`/api/invoices/booking/${bookingId}/pdf`, {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            const data = await response.json();

            if (data.success) {
                window.open(data.data.download_url, '_blank');
                showAlert('success', 'PDF hóa đơn đã được tạo thành công!');
            } else {
                showAlert('danger', 'Lỗi: ' + data.message);
            }
        } catch (error) {
            showAlert('danger', 'Lỗi: ' + error.message);
        } finally {
            button.innerHTML = originalContent;
            button.disabled = false;
        }
    }

    // Download Invoice PDF
    async function downloadInvoice(bookingId) {
        try {
            const button = event.target.closest('button');
            const originalContent = button.innerHTML;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            button.disabled = true;

            const response = await fetch(`/api/invoices/booking/${bookingId}/download`, {
                headers: {
                    'Accept': 'application/pdf',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            if (response.ok) {
                const blob = await response.blob();
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = `invoice_${bookingId}.pdf`;
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);
                document.body.removeChild(a);
                
                showAlert('success', 'PDF hóa đơn đã được tải xuống!');
            } else {
                const data = await response.json();
                showAlert('danger', 'Lỗi: ' + data.message);
            }
        } catch (error) {
            showAlert('danger', 'Lỗi: ' + error.message);
        } finally {
            button.innerHTML = originalContent;
            button.disabled = false;
        }
    }

    // Utility functions
    function formatCurrency(amount) {
        return new Intl.NumberFormat('vi-VN', {
            style: 'currency',
            currency: 'VND'
        }).format(amount);
    }

    function formatDate(dateString) {
        return new Date(dateString).toLocaleDateString('vi-VN');
    }

    function getStatusBadgeClass(status) {
        switch(status) {
            case 'issued': return 'bg-warning';
            case 'paid': return 'bg-success';
            case 'cancelled': return 'bg-danger';
            default: return 'bg-secondary';
        }
    }

    function getStatusText(status) {
        switch(status) {
            case 'issued': return 'Đã phát hành';
            case 'paid': return 'Đã thanh toán';
            case 'cancelled': return 'Đã hủy';
            default: return status;
        }
    }

    function showLoading() {
        document.getElementById('loading-state').style.display = 'block';
        document.getElementById('invoices-table-body').style.display = 'none';
        document.getElementById('empty-state').style.display = 'none';
    }

    function hideLoading() {
        document.getElementById('loading-state').style.display = 'none';
        document.getElementById('invoices-table-body').style.display = 'table-row-group';
    }

    function showEmptyState() {
        document.getElementById('empty-state').style.display = 'block';
        document.getElementById('invoices-table-body').style.display = 'none';
    }

    function hideEmptyState() {
        document.getElementById('empty-state').style.display = 'none';
        document.getElementById('invoices-table-body').style.display = 'table-row-group';
    }

    function initializeTooltips() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }

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

    function refreshInvoices() {
        loadInvoices(currentPage);
    }

    function exportInvoices() {
        showAlert('info', 'Tính năng xuất Excel đang được phát triển');
    }

    function viewInvoice(invoiceId) {
        showAlert('info', 'Tính năng xem chi tiết đang được phát triển');
    }

    function updateInvoiceStatus(invoiceId) {
        showAlert('info', 'Tính năng cập nhật trạng thái đang được phát triển');
    }

    function updateStats(data) {
        // Update stats cards
        document.getElementById('total-invoices').textContent = data.total || 0;
        // Add more stats calculations as needed
    }
</script>
@endsection

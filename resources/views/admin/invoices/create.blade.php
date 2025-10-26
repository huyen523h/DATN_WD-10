@extends('layouts.admin')

@section('title', 'Tạo Hóa đơn Mới - Admin')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.invoices.index') }}">Hóa đơn</a></li>
<li class="breadcrumb-item active">Tạo mới</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2><i class="fas fa-file-invoice text-primary"></i> Tạo Hóa đơn Mới</h2>
            <p class="text-muted mb-0">Tạo hóa đơn cho booking đã xác nhận</p>
        </div>
        <a href="{{ route('admin.invoices.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
    </div>

    <form action="{{ route('admin.invoices.store') }}" method="POST" id="invoice-form">
        @csrf
        
        <div class="row">
            <!-- Left Column -->
            <div class="col-lg-8">
                <!-- Booking Selection -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-calendar-check"></i> Chọn Booking</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label for="booking_id" class="form-label">Booking <span class="text-danger">*</span></label>
                            <select name="booking_id" id="booking_id" class="form-select" required>
                                <option value="">-- Chọn booking --</option>
                                @foreach($bookings as $booking)
                                    <option value="{{ $booking->id }}" 
                                            data-customer="{{ $booking->user->name }}"
                                            data-email="{{ $booking->user->email }}"
                                            data-phone="{{ $booking->user->phone ?? '' }}"
                                            data-address="{{ $booking->user->address ?? '' }}"
                                            data-tour="{{ $booking->tour->title }}"
                                            data-departure="{{ $booking->departure->departure_date ?? '' }}"
                                            data-adults="{{ $booking->adults }}"
                                            data-children="{{ $booking->children }}"
                                            data-infants="{{ $booking->infants }}"
                                            data-total="{{ $booking->total_amount }}">
                                        #{{ $booking->id }} - {{ $booking->user->name }} - {{ $booking->tour->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Booking Preview -->
                        <div id="booking-preview" class="booking-preview" style="display: none;">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>Thông tin khách hàng:</h6>
                                    <p><strong>Tên:</strong> <span id="preview-customer"></span></p>
                                    <p><strong>Email:</strong> <span id="preview-email"></span></p>
                                    <p><strong>Điện thoại:</strong> <span id="preview-phone"></span></p>
                                    <p><strong>Địa chỉ:</strong> <span id="preview-address"></span></p>
                                </div>
                                <div class="col-md-6">
                                    <h6>Thông tin tour:</h6>
                                    <p><strong>Tour:</strong> <span id="preview-tour"></span></p>
                                    <p><strong>Ngày khởi hành:</strong> <span id="preview-departure"></span></p>
                                    <p><strong>Số khách:</strong> <span id="preview-guests"></span></p>
                                    <p><strong>Tổng tiền booking:</strong> <span id="preview-total"></span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pricing -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-calculator"></i> Bảng Giá</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="adult_price" class="form-label">Giá người lớn <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" name="adult_price" id="adult_price" class="form-control" 
                                               value="0" min="0" step="1000" required>
                                        <span class="input-group-text">VNĐ</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="child_price" class="form-label">Giá trẻ em <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" name="child_price" id="child_price" class="form-control" 
                                               value="0" min="0" step="1000" required>
                                        <span class="input-group-text">VNĐ</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="infant_price" class="form-label">Giá em bé <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" name="infant_price" id="infant_price" class="form-control" 
                                               value="0" min="0" step="1000" required>
                                        <span class="input-group-text">VNĐ</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="tax_rate" class="form-label">Thuế VAT (%) <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" name="tax_rate" id="tax_rate" class="form-control" 
                                               value="10" min="0" max="100" step="0.01" required>
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="discount_amount" class="form-label">Giảm giá</label>
                                    <div class="input-group">
                                        <input type="number" name="discount_amount" id="discount_amount" class="form-control" 
                                               value="0" min="0" step="1000">
                                        <span class="input-group-text">VNĐ</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Price Summary -->
                        <div class="price-summary">
                            <h6>Bảng tính toán:</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Tạm tính:</strong> <span id="subtotal">0 VNĐ</span></p>
                                    <p><strong>Thuế VAT:</strong> <span id="tax-amount">0 VNĐ</span></p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Giảm giá:</strong> <span id="discount-display">0 VNĐ</span></p>
                                    <p><strong class="text-primary">TỔNG CỘNG:</strong> <span id="total-amount" class="text-primary">0 VNĐ</span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-info-circle"></i> Thông tin bổ sung</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label for="due_date" class="form-label">Hạn thanh toán <span class="text-danger">*</span></label>
                            <input type="date" name="due_date" id="due_date" class="form-control" 
                                   value="{{ now()->addDays(7)->format('Y-m-d') }}" required>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="notes" class="form-label">Ghi chú</label>
                            <textarea name="notes" id="notes" class="form-control" rows="4" 
                                      placeholder="Ghi chú thêm cho hóa đơn..."></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="col-lg-4">
                <!-- Company Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-building"></i> Thông tin công ty</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label for="company_name" class="form-label">Tên công ty <span class="text-danger">*</span></label>
                            <input type="text" name="company_name" id="company_name" class="form-control" 
                                   value="Tour365" required>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="company_address" class="form-label">Địa chỉ</label>
                            <textarea name="company_address" id="company_address" class="form-control" rows="3"
                                      placeholder="Địa chỉ công ty..."></textarea>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="company_phone" class="form-label">Điện thoại</label>
                            <input type="text" name="company_phone" id="company_phone" class="form-control" 
                                   placeholder="Số điện thoại...">
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="company_email" class="form-label">Email</label>
                            <input type="email" name="company_email" id="company_email" class="form-control" 
                                   placeholder="Email công ty...">
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="company_tax_code" class="form-label">Mã số thuế</label>
                            <input type="text" name="company_tax_code" id="company_tax_code" class="form-control" 
                                   placeholder="Mã số thuế...">
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="card">
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save"></i> Tạo hóa đơn
                            </button>
                            <a href="{{ route('admin.invoices.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Hủy
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('styles')
<style>
.booking-preview {
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 0.375rem;
    padding: 1rem;
    margin-top: 1rem;
}

.price-summary {
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 0.375rem;
    padding: 1rem;
    margin-top: 1rem;
}

.price-summary h6 {
    color: #495057;
    margin-bottom: 0.75rem;
    font-weight: 600;
}

.price-summary p {
    margin-bottom: 0.5rem;
    color: #6c757d;
}

.form-label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.5rem;
}

.text-danger {
    color: #dc3545 !important;
}

.card {
    border: 1px solid #dee2e6;
    border-radius: 0.5rem;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
    padding: 0.75rem 1rem;
}

.card-header h5 {
    margin: 0;
    color: #495057;
    font-weight: 600;
}

.card-body {
    padding: 1rem;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const bookingSelect = document.getElementById('booking_id');
    const bookingPreview = document.getElementById('booking-preview');
    
    // Price inputs
    const adultPrice = document.getElementById('adult_price');
    const childPrice = document.getElementById('child_price');
    const infantPrice = document.getElementById('infant_price');
    const taxRate = document.getElementById('tax_rate');
    const discountAmount = document.getElementById('discount_amount');
    
    // Summary elements
    const subtotalEl = document.getElementById('subtotal');
    const taxAmountEl = document.getElementById('tax-amount');
    const discountDisplayEl = document.getElementById('discount-display');
    const totalAmountEl = document.getElementById('total-amount');
    
    // Show booking preview when booking is selected
    bookingSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        
        if (selectedOption.value) {
            // Update preview
            document.getElementById('preview-customer').textContent = selectedOption.dataset.customer;
            document.getElementById('preview-email').textContent = selectedOption.dataset.email;
            document.getElementById('preview-phone').textContent = selectedOption.dataset.phone || 'N/A';
            document.getElementById('preview-address').textContent = selectedOption.dataset.address || 'N/A';
            document.getElementById('preview-tour').textContent = selectedOption.dataset.tour;
            document.getElementById('preview-departure').textContent = selectedOption.dataset.departure;
            
            const adults = parseInt(selectedOption.dataset.adults);
            const children = parseInt(selectedOption.dataset.children);
            const infants = parseInt(selectedOption.dataset.infants);
            
            let guestsText = `${adults} người lớn`;
            if (children > 0) guestsText += `, ${children} trẻ em`;
            if (infants > 0) guestsText += `, ${infants} em bé`;
            
            document.getElementById('preview-guests').textContent = guestsText;
            document.getElementById('preview-total').textContent = formatCurrency(selectedOption.dataset.total);
            
            // Show preview
            bookingPreview.style.display = 'block';
            
            // Set default prices based on booking total
            const bookingTotal = parseFloat(selectedOption.dataset.total);
            const totalGuests = adults + children + infants;
            
            if (totalGuests > 0) {
                const avgPrice = bookingTotal / totalGuests;
                adultPrice.value = Math.round(avgPrice);
                childPrice.value = Math.round(avgPrice * 0.7); // 70% of adult price
                infantPrice.value = Math.round(avgPrice * 0.3); // 30% of adult price
            }
            
            // Calculate totals
            calculateTotals();
        } else {
            bookingPreview.style.display = 'none';
        }
    });
    
    // Calculate totals when prices change
    [adultPrice, childPrice, infantPrice, taxRate, discountAmount].forEach(input => {
        input.addEventListener('input', calculateTotals);
    });
    
    function calculateTotals() {
        const adults = parseInt(bookingSelect.options[bookingSelect.selectedIndex]?.dataset.adults || 0);
        const children = parseInt(bookingSelect.options[bookingSelect.selectedIndex]?.dataset.children || 0);
        const infants = parseInt(bookingSelect.options[bookingSelect.selectedIndex]?.dataset.infants || 0);
        
        const adultPriceVal = parseFloat(adultPrice.value || 0);
        const childPriceVal = parseFloat(childPrice.value || 0);
        const infantPriceVal = parseFloat(infantPrice.value || 0);
        const taxRateVal = parseFloat(taxRate.value || 0);
        const discountAmountVal = parseFloat(discountAmount.value || 0);
        
        const subtotal = (adults * adultPriceVal) + (children * childPriceVal) + (infants * infantPriceVal);
        const taxAmount = subtotal * (taxRateVal / 100);
        const total = subtotal + taxAmount - discountAmountVal;
        
        subtotalEl.textContent = formatCurrency(subtotal);
        taxAmountEl.textContent = formatCurrency(taxAmount);
        discountDisplayEl.textContent = formatCurrency(discountAmountVal);
        totalAmountEl.textContent = formatCurrency(total);
    }
    
    function formatCurrency(amount) {
        return new Intl.NumberFormat('vi-VN', {
            style: 'currency',
            currency: 'VND'
        }).format(amount);
    }
    
    // Form validation
    document.getElementById('invoice-form').addEventListener('submit', function(e) {
        if (!bookingSelect.value) {
            e.preventDefault();
            alert('Vui lòng chọn booking!');
            bookingSelect.focus();
            return;
        }
        
        if (parseFloat(adultPrice.value) <= 0) {
            e.preventDefault();
            alert('Giá người lớn phải lớn hơn 0!');
            adultPrice.focus();
            return;
        }
    });
});
</script>
@endpush

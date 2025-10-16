@extends('layouts.app')

@section('title', 'Thanh toán - Tour365')

@section('content')
    <section>
        <div class="container py-5">
            <div class="row">
                <div class="col-lg-8">
                    <!-- Booking Summary -->
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="fas fa-receipt"></i> Thông tin đặt tour</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <img src="https://picsum.photos/800/600?random=81"
                                         class="img-fluid rounded" alt="Tour Image">
                                </div>
                                <div class="col-md-8">
                                    <h4>Tour Hạ Long 2 ngày 1 đêm</h4>
                                    <p class="text-muted">Khám phá vịnh Hạ Long - Di sản thiên nhiên thế giới</p>
                                    <div class="row">
                                        <div class="col-6">
                                            <small class="text-muted">
                                                <i class="fas fa-calendar"></i> Ngày đi: 25/12/2024
                                            </small>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted">
                                                <i class="fas fa-clock"></i> 2 ngày 1 đêm
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Methods -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white">
                            <h5><i class="fas fa-credit-card"></i> Phương thức thanh toán</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <!-- VNPAY -->
                                <div class="col-md-6 mb-3">
                                    <div class="payment-method-card" data-method="vnpay">
                                        <div class="card h-100 border-2">
                                            <label class="card-body text-center" for="vnpay">
                                                <img src="https://stcd02206177151.cloud.edgevnpay.vn/assets/images/logo-icon/logo-primary.svg"
                                                     alt="VNPAY" class="img-fluid mb-3" style="height: 60px;">
                                                <h6>VNPAY</h6>
                                                <p class="text-muted small">Thanh toán qua ví điện tử VNPAY</p>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="payment_method"
                                                           id="vnpay" value="vnpay" checked>
                                                    <label class="form-check-label" for="vnpay">
                                                        Chọn VNPAY
                                                    </label>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <!-- MoMo -->
                                <div class="col-md-6 mb-3">
                                    <div class="payment-method-card" data-method="momo">
                                        <div class="card h-100 border-2">
                                            <label class="card-body text-center" for="momo">
                                                <img src="https://upload.wikimedia.org/wikipedia/vi/f/fe/MoMo_Logo.png"
                                                     alt="MoMo" class="img-fluid mb-3" style="height: 60px;">
                                                <h6>MoMo</h6>
                                                <p class="text-muted small">Thanh toán qua ví điện tử MoMo</p>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="payment_method"
                                                           id="momo" value="momo">
                                                    <label class="form-check-label" for="momo">
                                                        Chọn MoMo
                                                    </label>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Banking -->
                            <div class="row mt-3">
                                <div class="col-md-6 mb-3">
                                    <div class="payment-method-card" data-method="banking">
                                        <div class="card h-100 border-2">
                                            <label class="card-body text-center" for="banking">
                                                <i class="fas fa-university fa-3x text-primary mb-3"></i>
                                                <h6>Chuyển khoản ngân hàng</h6>
                                                <p class="text-muted small">Chuyển khoản trực tiếp qua ngân hàng</p>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="payment_method"
                                                           id="banking" value="banking">
                                                    <label class="form-check-label" for="banking">
                                                        Chọn chuyển khoản
                                                    </label>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Cash -->
                                <div class="col-md-6 mb-3">
                                    <div class="payment-method-card" data-method="cash">
                                        <div class="card h-100 border-2">
                                            <label class="card-body text-center" for="cash">
                                                <i class="fas fa-money-bill-wave fa-3x text-success mb-3"></i>
                                                <h6>Thanh toán tại văn phòng</h6>
                                                <p class="text-muted small">Thanh toán bằng tiền mặt tại văn phòng</p>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="payment_method"
                                                           id="cash" value="cash">
                                                    <label class="form-check-label" for="cash">
                                                        Thanh toán tại văn phòng
                                                    </label>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Customer Information -->
                    <div class="card border-0 shadow-sm mt-4">
                        <div class="card-header bg-white">
                            <h5><i class="fas fa-user"></i> Thông tin thanh toán</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="customer_name" class="form-label">Họ và tên <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="customer_name" value="Nguyễn Văn A" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="customer_email" class="form-label">Email <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" id="customer_email" value="nguyenvana@email.com" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="customer_phone" class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                                        <input type="tel" class="form-control" id="customer_phone" value="0123456789" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="customer_address" class="form-label">Địa chỉ</label>
                                        <input type="text" class="form-control" id="customer_address" value="Hà Nội, Việt Nam">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Summary -->
                <div class="col-lg-4">
                    <div class="card" style="top: 100px;">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="fas fa-calculator"></i> Tóm tắt thanh toán</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Người lớn (2 x 1,500,000đ)</span>
                                <span>3,000,000đ</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Trẻ em (1 x 750,000đ)</span>
                                <span>750,000đ</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Phí dịch vụ</span>
                                <span>100,000đ</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between mb-3">
                                <strong>Tổng cộng:</strong>
                                <strong class="text-primary">3,850,000đ</strong>
                            </div>

                            <!-- Payment Instructions -->
                            <div id="payment-instructions" class="mt-4">
                                <!-- VNPAY Instructions -->
                                <div id="vnpay-instructions" class="payment-instruction">
                                    <h6 class="text-primary">Hướng dẫn thanh toán VNPAY:</h6>
                                    <ol class="small">
                                        <li>Nhấn "Thanh toán" để chuyển đến VNPAY</li>
                                        <li>Đăng nhập tài khoản VNPAY của bạn</li>
                                        <li>Xác nhận thông tin thanh toán</li>
                                        <li>Hoàn tất thanh toán</li>
                                    </ol>
                                </div>

                                <!-- MoMo Instructions -->
                                <div id="momo-instructions" class="payment-instruction" style="display: none;">
                                    <h6 class="text-primary">Hướng dẫn thanh toán MoMo:</h6>
                                    <ol class="small">
                                        <li>Nhấn "Thanh toán" để mở ứng dụng MoMo</li>
                                        <li>Xác thực bằng vân tay/mật mã</li>
                                        <li>Kiểm tra thông tin giao dịch</li>
                                        <li>Xác nhận thanh toán</li>
                                    </ol>
                                </div>

                                <!-- Banking Instructions -->
                                <div id="banking-instructions" class="payment-instruction" style="display: none;">
                                    <h6 class="text-primary">Thông tin chuyển khoản:</h6>
                                    <div class="bg-light p-3 rounded small">
                                        <p><strong>Ngân hàng:</strong> Vietcombank</p>
                                        <p><strong>STK:</strong> 1234567890</p>
                                        <p><strong>Chủ TK:</strong> Công ty TNHH Tour365</p>
                                        <p><strong>Nội dung:</strong> Tour HL-001 - [SĐT của bạn]</p>
                                    </div>
                                </div>

                                <!-- Cash Instructions -->
                                <div id="cash-instructions" class="payment-instruction" style="display: none;">
                                    <h6 class="text-primary">Thông tin văn phòng:</h6>
                                    <div class="bg-light p-3 rounded small">
                                        <p><strong>Địa chỉ:</strong> 123 Đường ABC, Quận 1, TP.HCM</p>
                                        <p><strong>Giờ làm việc:</strong> 8:00 - 17:00 (T2-T6)</p>
                                        <p><strong>Hotline:</strong> 1900 1234</p>
                                    </div>
                                </div>
                            </div>

                            <div class="d-grid gap-2 mt-4">
                                <button class="btn btn-primary btn-lg" onclick="processPayment()">
                                    <i class="fas fa-credit-card"></i> Thanh toán
                                </button>
                                <button class="btn btn-outline-secondary" onclick="history.back()">
                                    <i class="fas fa-arrow-left"></i> Quay lại
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

<!-- Payment Processing Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center py-5">
                <div class="spinner-border text-primary mb-3" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <h5>Đang xử lý thanh toán...</h5>
                <p class="text-muted">Vui lòng đợi trong giây lát</p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
.payment-method-card .card {
    transition: all 0.3s ease;
    cursor: pointer;
}

.payment-method-card .card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.payment-method-card input[type="radio"]:checked + label {
    font-weight: bold;
}

.payment-method-card input[type="radio"]:checked ~ .card,
.payment-method-card .card:has(input[type="radio"]:checked) {
    border-color: var(--brand-primary) !important;
    background-color: rgba(14, 165, 233, 0.05);
}

.payment-instruction {
    animation: fadeIn 0.3s ease-in-out;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

</style>
@endsection

@section('scripts')
<script>
// Payment method selection
document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
    radio.addEventListener('change', function() {
        // Hide all instructions
        document.querySelectorAll('.payment-instruction').forEach(instruction => {
            instruction.style.display = 'none';
        });

        // Show selected instruction
        const selectedMethod = this.value;
        const instructionElement = document.getElementById(selectedMethod + '-instructions');
        if (instructionElement) {
            instructionElement.style.display = 'block';
        }

        // Update card styles
        document.querySelectorAll('.payment-method-card .card').forEach(card => {
            card.classList.remove('border-primary');
            card.style.backgroundColor = '';
        });

        const selectedCard = this.closest('.payment-method-card').querySelector('.card');
        selectedCard.classList.add('border-primary');
        selectedCard.style.backgroundColor = 'rgba(14, 165, 233, 0.05)';
    });
});

function processPayment() {
    const selectedMethod = document.querySelector('input[name="payment_method"]:checked').value;

    // Show processing modal
    const modal = new bootstrap.Modal(document.getElementById('paymentModal'));
    modal.show();

    // Simulate payment processing
    setTimeout(() => {
        modal.hide();

        // Show success message based on payment method
        let message = '';
        switch(selectedMethod) {
            case 'vnpay':
                message = 'Đang chuyển hướng đến VNPAY...';
                // In real app, redirect to VNPAY gateway
                setTimeout(() => {
                    alert('Thanh toán VNPAY thành công!');
                    window.location.href = '/bookings';
                }, 2000);
                break;
            case 'momo':
                message = 'Đang mở ứng dụng MoMo...';
                setTimeout(() => {
                    alert('Thanh toán MoMo thành công!');
                    window.location.href = '/bookings';
                }, 2000);
                break;
            case 'banking':
                alert('Vui lòng chuyển khoản theo thông tin trên và liên hệ hotline để xác nhận.');
                break;
            case 'cash':
                alert('Vui lòng đến văn phòng để thanh toán trong vòng 24h.');
                break;
        }
    }, 2000);
}

// Initialize default selection
document.addEventListener('DOMContentLoaded', function() {
    const defaultMethod = document.querySelector('input[name="payment_method"]:checked');
    if (defaultMethod) {
        defaultMethod.dispatchEvent(new Event('change'));
    }
});
</script>
@endsection

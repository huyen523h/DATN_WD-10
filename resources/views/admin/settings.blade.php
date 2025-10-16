@extends('layouts.admin')

@section('title', 'Cài đặt hệ thống - Admin')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2><i class="fas fa-cog text-primary"></i> Cài đặt hệ thống</h2>
                    <p class="text-muted mb-0">Quản lý cấu hình và thiết lập hệ thống</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- General Settings -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-globe"></i> Cài đặt chung</h5>
                </div>
                <div class="card-body">
                    <form>
                        <div class="mb-3">
                            <label for="site_name" class="form-label">Tên website</label>
                            <input type="text" class="form-control" id="site_name" value="Tour365">
                        </div>
                        <div class="mb-3">
                            <label for="site_description" class="form-label">Mô tả website</label>
                            <textarea class="form-control" id="site_description" rows="3">Dịch vụ du lịch trọn gói uy tín, chất lượng</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="contact_email" class="form-label">Email liên hệ</label>
                            <input type="email" class="form-control" id="contact_email" value="info@tour365.vn">
                        </div>
                        <div class="mb-3">
                            <label for="contact_phone" class="form-label">Số điện thoại</label>
                            <input type="tel" class="form-control" id="contact_phone" value="1900 1234">
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Lưu cài đặt
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Booking Settings -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-calendar-check"></i> Cài đặt đặt tour</h5>
                </div>
                <div class="card-body">
                    <form>
                        <div class="mb-3">
                            <label for="booking_advance_days" class="form-label">Số ngày đặt trước tối thiểu</label>
                            <input type="number" class="form-control" id="booking_advance_days" value="7" min="1">
                        </div>
                        <div class="mb-3">
                            <label for="cancellation_hours" class="form-label">Số giờ hủy tour trước khởi hành</label>
                            <input type="number" class="form-control" id="cancellation_hours" value="24" min="1">
                        </div>
                        <div class="mb-3">
                            <label for="max_guests_per_booking" class="form-label">Số khách tối đa mỗi đặt tour</label>
                            <input type="number" class="form-control" id="max_guests_per_booking" value="10" min="1">
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="auto_confirm_booking" checked>
                                <label class="form-check-label" for="auto_confirm_booking">
                                    Tự động xác nhận đặt tour
                                </label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Lưu cài đặt
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Payment Settings -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-credit-card"></i> Cài đặt thanh toán</h5>
                </div>
                <div class="card-body">
                    <form>
                        <div class="mb-3">
                            <label class="form-label">Phương thức thanh toán</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="payment_cash" checked>
                                <label class="form-check-label" for="payment_cash">Tiền mặt</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="payment_bank" checked>
                                <label class="form-check-label" for="payment_bank">Chuyển khoản ngân hàng</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="payment_momo">
                                <label class="form-check-label" for="payment_momo">MoMo</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="payment_zalopay">
                                <label class="form-check-label" for="payment_zalopay">ZaloPay</label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="deposit_percentage" class="form-label">Phần trăm đặt cọc (%)</label>
                            <input type="number" class="form-control" id="deposit_percentage" value="30" min="0" max="100">
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Lưu cài đặt
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Notification Settings -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-bell"></i> Cài đặt thông báo</h5>
                </div>
                <div class="card-body">
                    <form>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="email_notifications" checked>
                                <label class="form-check-label" for="email_notifications">
                                    Gửi thông báo qua email
                                </label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="sms_notifications">
                                <label class="form-check-label" for="sms_notifications">
                                    Gửi thông báo qua SMS
                                </label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="admin_email" class="form-label">Email admin nhận thông báo</label>
                            <input type="email" class="form-control" id="admin_email" value="admin@tour365.vn">
                        </div>
                        <div class="mb-3">
                            <label for="notification_hours" class="form-label">Giờ gửi thông báo hàng ngày</label>
                            <input type="time" class="form-control" id="notification_hours" value="09:00">
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Lưu cài đặt
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- System Information -->
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> Thông tin hệ thống</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="info-item">
                                <strong>Phiên bản Laravel:</strong>
                                <span class="text-muted">{{ app()->version() }}</span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-item">
                                <strong>Phiên bản PHP:</strong>
                                <span class="text-muted">{{ PHP_VERSION }}</span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-item">
                                <strong>Môi trường:</strong>
                                <span class="badge bg-info">{{ app()->environment() }}</span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-item">
                                <strong>Debug mode:</strong>
                                <span class="badge {{ config('app.debug') ? 'bg-success' : 'bg-secondary' }}">
                                    {{ config('app.debug') ? 'Bật' : 'Tắt' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
.info-item {
    padding: 0.5rem 0;
    border-bottom: 1px solid #E5E7EB;
}

.info-item:last-child {
    border-bottom: none;
}

.card-header {
    background: #F9FAFB;
    border-bottom: 1px solid #E5E7EB;
}

.form-check-input:checked {
    background-color: #4F46E5;
    border-color: #4F46E5;
}
</style>
@endsection
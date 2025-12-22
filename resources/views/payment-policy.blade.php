@extends('layouts.app')

@section('title', 'Chính sách & Điều khoản Tour Đoàn - Tour365')

@section('content')
<!-- Hero Section -->
<section class="py-5 text-white" style="background: linear-gradient(135deg, #0EA5E9 0%, #38BDF8 100%);">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <h1 class="display-4 fw-bold mb-3">Chính sách & Điều khoản Tour Đoàn</h1>
                <p class="lead">Thông tin chi tiết về chính sách thanh toán và điều khoản đặt tour</p>
            </div>
        </div>
    </div>
</section>

<!-- Policy Content Section -->
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <!-- Chính sách trẻ em -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="policy-icon me-3">
                                <i class="fas fa-child fa-2x text-primary"></i>
                            </div>
                            <h2 class="card-title mb-0 fw-bold">1. Chính sách trẻ em</h2>
                        </div>
                        <div class="policy-content">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="policy-item p-3 bg-light rounded">
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="fas fa-baby text-info me-2"></i>
                                            <strong class="text-primary">Trẻ em dưới 5 tuổi</strong>
                                        </div>
                                        <p class="mb-0 text-muted">Miễn phí (ngồi chung với bố mẹ)</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="policy-item p-3 bg-light rounded">
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="fas fa-child text-success me-2"></i>
                                            <strong class="text-success">Trẻ em từ 5 - 11 tuổi</strong>
                                        </div>
                                        <p class="mb-0 text-muted">Tính 50% giá người lớn</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="policy-item p-3 bg-light rounded">
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="fas fa-user text-warning me-2"></i>
                                            <strong class="text-warning">Trẻ em trên 11 tuổi</strong>
                                        </div>
                                        <p class="mb-0 text-muted">Tính như người lớn</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Chính sách hoàn hủy -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="policy-icon me-3">
                                <i class="fas fa-undo-alt fa-2x text-danger"></i>
                            </div>
                            <h2 class="card-title mb-0 fw-bold">2. Chính sách hoàn hủy</h2>
                        </div>
                        <div class="policy-content">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="policy-item p-4 bg-success bg-opacity-10 border border-success rounded">
                                        <div class="d-flex align-items-center mb-3">
                                            <i class="fas fa-check-circle fa-2x text-success me-2"></i>
                                            <strong class="text-success fs-5">Hủy trước 15 ngày</strong>
                                        </div>
                                        <p class="mb-0 fw-bold text-success">Hoàn 100% tiền cọc</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="policy-item p-4 bg-warning bg-opacity-10 border border-warning rounded">
                                        <div class="d-flex align-items-center mb-3">
                                            <i class="fas fa-exclamation-triangle fa-2x text-warning me-2"></i>
                                            <strong class="text-warning fs-5">Hủy trước 7 ngày</strong>
                                        </div>
                                        <p class="mb-0 fw-bold text-warning">Phạt 50% tiền cọc</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="policy-item p-4 bg-danger bg-opacity-10 border border-danger rounded">
                                        <div class="d-flex align-items-center mb-3">
                                            <i class="fas fa-times-circle fa-2x text-danger me-2"></i>
                                            <strong class="text-danger fs-5">Hủy sau 7 ngày</strong>
                                        </div>
                                        <p class="mb-0 fw-bold text-danger">Không hoàn tiền cọc</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Lưu ý quan trọng -->
                <div class="alert alert-info border-0 shadow-sm" role="alert">
                    <div class="d-flex align-items-start">
                        <i class="fas fa-info-circle fa-2x me-3 mt-1"></i>
                        <div>
                            <h5 class="alert-heading fw-bold mb-2">Lưu ý quan trọng</h5>
                            <p class="mb-0">
                                Đây là chính sách tham khảo. Điều khoản chi tiết sẽ được quy định cụ thể trong 
                                <strong>Hợp đồng Du lịch</strong> khi hai bên ký kết.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Additional Information -->
                <div class="card border-0 shadow-sm mt-4">
                    <div class="card-body p-4">
                        <h3 class="fw-bold mb-3">
                            <i class="fas fa-question-circle text-primary me-2"></i>
                            Thông tin bổ sung
                        </h3>
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                Thời gian tính từ ngày khởi hành của tour
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                Mọi thay đổi về chính sách sẽ được thông báo trước khi đặt tour
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                Vui lòng đọc kỹ hợp đồng trước khi ký kết
                            </li>
                            <li class="mb-0">
                                <i class="fas fa-check text-success me-2"></i>
                                Liên hệ hotline để được tư vấn chi tiết về từng tour cụ thể
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- CTA Section -->
                <div class="text-center mt-5">
                    <a href="{{ route('tours.index') }}" class="btn btn-primary btn-lg me-2">
                        <i class="fas fa-map-marked-alt me-2"></i>
                        Xem các tour hiện có
                    </a>
                    <a href="{{ route('contact') }}" class="btn btn-outline-primary btn-lg">
                        <i class="fas fa-envelope me-2"></i>
                        Liên hệ tư vấn
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('styles')
<style>
.policy-icon {
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(14, 165, 233, 0.1);
    border-radius: 12px;
}

.policy-item {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    height: 100%;
}

.policy-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
}

.card {
    border-radius: 16px;
    transition: transform 0.3s ease;
}

.card:hover {
    transform: translateY(-3px);
}

.alert {
    border-radius: 16px;
}

@media (max-width: 768px) {
    .policy-icon {
        width: 50px;
        height: 50px;
    }
    
    .policy-icon i {
        font-size: 1.5rem !important;
    }
}
</style>
@endsection


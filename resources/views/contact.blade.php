@extends('layouts.app')

@section('title', 'Liên hệ - Tour365')

@section('content')
<!-- Hero Section -->
<section class="py-5 text-white" style="background: linear-gradient(135deg, #0EA5E9 0%, #38BDF8 100%);">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <h1 class="display-4 fw-bold mb-3">Liên hệ với chúng tôi</h1>
                <p class="lead">Chúng tôi luôn sẵn sàng hỗ trợ bạn</p>
            </div>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-envelope"></i> Gửi tin nhắn</h5>
                    </div>
                    <div class="card-body">
                        <form>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Họ và tên <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" id="email" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">Số điện thoại</label>
                                    <input type="tel" class="form-control" id="phone">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="subject" class="form-label">Chủ đề <span class="text-danger">*</span></label>
                                    <select class="form-select" id="subject" required>
                                        <option value="">-- Chọn chủ đề --</option>
                                        <option value="booking">Đặt tour</option>
                                        <option value="support">Hỗ trợ kỹ thuật</option>
                                        <option value="complaint">Khiếu nại</option>
                                        <option value="suggestion">Góp ý</option>
                                        <option value="other">Khác</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="message" class="form-label">Nội dung <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="message" rows="5" required></textarea>
                            </div>
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="agree" required>
                                    <label class="form-check-label" for="agree">
                                        Tôi đồng ý với <a href="#" class="text-decoration-none">Điều khoản sử dụng</a>
                                    </label>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-paper-plane"></i> Gửi tin nhắn
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Contact Info -->
                <div class="card mb-4 border-0 shadow-sm">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-info-circle"></i> Thông tin liên hệ</h5>
                    </div>
                    <div class="card-body">
                        <div class="contact-item mb-3">
                            <div class="d-flex align-items-center">
                                <div class="contact-icon me-3">
                                    <i class="fas fa-map-marker-alt text-primary"></i>
                                </div>
                                <div>
                                    <strong>Địa chỉ</strong><br>
                                    <span class="text-muted">123 Đường ABC, Quận 1, TP.HCM</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="contact-item mb-3">
                            <div class="d-flex align-items-center">
                                <div class="contact-icon me-3">
                                    <i class="fas fa-phone text-primary"></i>
                                </div>
                                <div>
                                    <strong>Điện thoại</strong><br>
                                    <span class="text-muted">1900 1234</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="contact-item mb-3">
                            <div class="d-flex align-items-center">
                                <div class="contact-icon me-3">
                                    <i class="fas fa-envelope text-primary"></i>
                                </div>
                                <div>
                                    <strong>Email</strong><br>
                                    <span class="text-muted">info@tour365.vn</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="contact-item">
                            <div class="d-flex align-items-center">
                                <div class="contact-icon me-3">
                                    <i class="fas fa-clock text-primary"></i>
                                </div>
                                <div>
                                    <strong>Giờ làm việc</strong><br>
                                    <span class="text-muted">8:00 - 22:00 (T2-CN)</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Social Media -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-share-alt"></i> Mạng xã hội</h5>
                    </div>
                    <div class="card-body">
                        <div class="social-links">
                            <a href="#" class="btn btn-outline-primary btn-sm me-2 mb-2">
                                <i class="fab fa-facebook-f"></i> Facebook
                            </a>
                            <a href="#" class="btn btn-outline-info btn-sm me-2 mb-2">
                                <i class="fab fa-twitter"></i> Twitter
                            </a>
                            <a href="#" class="btn btn-outline-danger btn-sm me-2 mb-2">
                                <i class="fab fa-instagram"></i> Instagram
                            </a>
                            <a href="#" class="btn btn-outline-success btn-sm me-2 mb-2">
                                <i class="fab fa-whatsapp"></i> WhatsApp
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="display-5 fw-bold">Câu hỏi thường gặp</h2>
                <p class="lead text-muted">Tìm câu trả lời cho những thắc mắc phổ biến</p>
            </div>
        </div>
        
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="accordion" id="faqAccordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faq1">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1">
                                Làm thế nào để đặt tour?
                            </button>
                        </h2>
                        <div id="collapse1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Bạn có thể đặt tour trực tuyến bằng cách chọn tour yêu thích, điền thông tin và thanh toán. 
                                Hoặc gọi hotline 1900 1234 để được hỗ trợ đặt tour.
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faq2">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2">
                                Có thể hủy tour không?
                            </button>
                        </h2>
                        <div id="collapse2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Có, bạn có thể hủy tour trước ngày khởi hành 7 ngày để được hoàn tiền 100%. 
                                Hủy trong vòng 3-7 ngày được hoàn 70%, dưới 3 ngày được hoàn 50%.
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faq3">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse3">
                                Phương thức thanh toán nào được chấp nhận?
                            </button>
                        </h2>
                        <div id="collapse3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Chúng tôi chấp nhận thanh toán bằng tiền mặt, chuyển khoản ngân hàng, 
                                thẻ tín dụng, MoMo, ZaloPay và các ví điện tử khác.
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faq4">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse4">
                                Tour có bao gồm bảo hiểm không?
                            </button>
                        </h2>
                        <div id="collapse4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Tất cả các tour đều bao gồm bảo hiểm du lịch với mức bồi thường tối đa 100 triệu VNĐ. 
                                Bạn có thể mua thêm bảo hiểm nâng cao nếu cần.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('styles')
<style>
.contact-icon {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(14, 165, 233, 0.15);
    border-radius: 50%;
}

.social-links .btn {
    min-width: 100px;
}

.accordion-button:not(.collapsed) {
    background-color: #f8f9fa;
    color: #0EA5E9;
}

.accordion-button:focus {
    box-shadow: 0 0 0 0.25rem rgba(14, 165, 233, 0.25);
}
</style>
@endsection

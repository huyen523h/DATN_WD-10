@extends('layouts.app')

@section('title', 'Tour365 - Du lịch trọn gói')
@section('description', 'Khám phá thế giới cùng Tour365 - Dịch vụ du lịch trọn gói uy tín, chất lượng với giá cả hợp lý')

@section('content')
<!-- Hero Section -->
<section class="hero-section" style="background: linear-gradient(135deg, #0EA5E9 0%, #38BDF8 100%);">
    <div class="container">
        <div class="row align-items-center min-vh-100">
            <div class="col-lg-6">
                <div class="hero-content">
                    <h1 class="display-3 fw-bold mb-4 text-white">
                        Khám phá thế giới cùng <span class="text-warning">Tour365</span>
                    </h1>
                    <p class="lead mb-4 text-white-50">
                        Trải nghiệm những chuyến du lịch tuyệt vời với dịch vụ chuyên nghiệp, 
                        an toàn và giá cả hợp lý. Từ Việt Nam đến thế giới, chúng tôi đồng hành cùng bạn.
                    </p>
                    <div class="hero-buttons">
                        <a href="{{ route('tours.index') }}" class="btn btn-light text-primary btn-lg me-3" style="color:#0EA5E9 !important;">
                            <i class="fas fa-search"></i> Khám phá tours
                        </a>
                        <a href="{{ route('register') }}" class="btn btn-outline-light btn-lg">
                            <i class="fas fa-user-plus"></i> Đăng ký ngay
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="hero-image slide-up">
                    <img src="https://via.placeholder.com/600x500/667eea/ffffff?text=Travel+with+Tour365" 
                         alt="Travel with Tour365" class="img-fluid rounded-3 shadow-lg" loading="lazy" sizes="(min-width: 992px) 600px, 100vw">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Promotions Banner -->
<section class="py-4" style="background: rgba(14,165,233,.08)">
    <div class="container">
        <div class="d-flex flex-column flex-md-row align-items-center justify-content-between">
            <div class="mb-3 mb-md-0">
                <span class="badge me-2" style="background: linear-gradient(45deg,#0EA5E9,#06B6D4)">Ưu đãi</span>
                <strong>Siêu khuyến mãi mùa hè:</strong>
                <span class="text-muted">Giảm đến 20% cho tour biển. Số lượng có hạn!</span>
            </div>
            <a href="{{ route('promotions.index') }}" class="btn btn-primary">
                <i class="fas fa-gift"></i> Xem ưu đãi
            </a>
        </div>
    </div>
    
</section>
<!-- Testimonials Section -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="row mb-5">
            <div class="col-12 text-center">
                <h2 class="display-6 fw-bold mb-3">Khách hàng nói gì?</h2>
                <p class="lead text-muted">Những trải nghiệm thực tế từ khách của Tour365</p>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm h-100 slide-up">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="me-3" style="width:48px;height:48px;border-radius:50%;background:linear-gradient(135deg,#0EA5E9,#38BDF8);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;">A</div>
                            <div>
                                <strong>Anh Minh</strong>
                                <div class="small text-muted">TP.HCM</div>
                            </div>
                        </div>
                        <div class="mb-2 text-warning">
                            <i class="fas fa-star"></i> <i class="fas fa-star"></i> <i class="fas fa-star"></i> <i class="fas fa-star"></i> <i class="fas fa-star"></i>
                        </div>
                        <p class="text-muted mb-0">Dịch vụ tuyệt vời, lịch trình hợp lý. Gia đình mình rất hài lòng, sẽ quay lại đặt tour lần nữa!</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm h-100 slide-up" style="animation-delay:.08s">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="me-3" style="width:48px;height:48px;border-radius:50%;background:linear-gradient(135deg,#0EA5E9,#06B6D4);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;">B</div>
                            <div>
                                <strong>Chị Bình</strong>
                                <div class="small text-muted">Đà Nẵng</div>
                            </div>
                        </div>
                        <div class="mb-2 text-warning">
                            <i class="fas fa-star"></i> <i class="fas fa-star"></i> <i class="fas fa-star"></i> <i class="fas fa-star"></i> <i class="fas fa-star-half-alt"></i>
                        </div>
                        <p class="text-muted mb-0">Hỗ trợ 24/7 rất chu đáo, giá cả hợp lý. Mình đặc biệt thích tour Phú Quốc.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm h-100 slide-up" style="animation-delay:.16s">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="me-3" style="width:48px;height:48px;border-radius:50%;background:linear-gradient(135deg,#06B6D4,#38BDF8);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;">C</div>
                            <div>
                                <strong>Mr. Cuong</strong>
                                <div class="small text-muted">Hà Nội</div>
                            </div>
                        </div>
                        <div class="mb-2 text-warning">
                            <i class="fas fa-star"></i> <i class="fas fa-star"></i> <i class="fas fa-star"></i> <i class="fas fa-star"></i> <i class="fas fa-star"></i>
                        </div>
                        <p class="text-muted mb-0">Lần đầu đặt online nhưng rất yên tâm. Hướng dẫn viên thân thiện, lịch trình tối ưu.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-3 col-6 mb-4">
                <div class="stat-item">
                    <div class="stat-number text-primary fw-bold display-4">500+</div>
                    <div class="stat-label text-muted">Tours đa dạng</div>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-4">
                <div class="stat-item">
                    <div class="stat-number text-success fw-bold display-4">10K+</div>
                    <div class="stat-label text-muted">Khách hàng hài lòng</div>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-4">
                <div class="stat-item">
                    <div class="stat-number text-warning fw-bold display-4">50+</div>
                    <div class="stat-label text-muted">Điểm đến</div>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-4">
                <div class="stat-item">
                    <div class="stat-number text-info fw-bold display-4">5★</div>
                    <div class="stat-label text-muted">Đánh giá trung bình</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Tours Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row mb-5">
            <div class="col-12 text-center">
                <h2 class="display-5 fw-bold mb-3">Tours nổi bật</h2>
                <p class="lead text-muted">Khám phá những điểm đến tuyệt vời nhất</p>
            </div>
        </div>
        
        <div class="row">
            <div class="col-lg-4 col-md-6 mb-4 slide-up">
                <div class="card tour-card h-100 border-0 shadow-sm">
                    <div class="card-image">
                        <img src="https://via.placeholder.com/400x250/0EA5E9/ffffff?text=Da+Nang+Hoi+An" 
                             class="card-img-top" alt="Đà Nẵng - Hội An" loading="lazy" sizes="(min-width: 992px) 400px, 100vw" style="height: 250px; object-fit: cover;">
                        <div class="card-overlay">
                            <span class="badge bg-warning">Bán chạy</span>
                        </div>
                    </div>
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">Đà Nẵng - Hội An</h5>
                        <p class="card-text text-muted flex-grow-1">
                            Khám phá vẻ đẹp cổ kính của Hội An và sự hiện đại của Đà Nẵng
                        </p>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <small class="text-muted">
                                    <i class="fas fa-clock"></i> 3 ngày 2 đêm
                                </small>
                            </div>
                            <div class="price-badge" style="background: linear-gradient(45deg, #0EA5E9, #06B6D4);">
                                2,500,000đ
                            </div>
                        </div>
                        <a href="{{ route('tours.index') }}" class="btn btn-primary">
                            <i class="fas fa-eye"></i> Xem chi tiết
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4 slide-up">
                <div class="card tour-card h-100 border-0 shadow-sm">
                    <div class="card-image">
                        <img src="https://via.placeholder.com/400x250/06B6D4/ffffff?text=Phu+Quoc" 
                             class="card-img-top" alt="Phú Quốc" loading="lazy" sizes="(min-width: 992px) 400px, 100vw" style="height: 250px; object-fit: cover;">
                        <div class="card-overlay">
                            <span class="badge bg-success">Mới</span>
                        </div>
                    </div>
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">Phú Quốc</h5>
                        <p class="card-text text-muted flex-grow-1">
                            Đảo ngọc xinh đẹp với những bãi biển tuyệt vời
                        </p>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <small class="text-muted">
                                    <i class="fas fa-clock"></i> 4 ngày 3 đêm
                                </small>
                            </div>
                            <div class="price-badge" style="background: linear-gradient(45deg, #0EA5E9, #06B6D4);">
                                3,200,000đ
                            </div>
                        </div>
                        <a href="{{ route('tours.index') }}" class="btn btn-primary">
                            <i class="fas fa-eye"></i> Xem chi tiết
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4 slide-up">
                <div class="card tour-card h-100 border-0 shadow-sm">
                    <div class="card-image">
                        <img src="https://via.placeholder.com/400x250/0284C7/ffffff?text=Sapa" 
                             class="card-img-top" alt="Sapa" loading="lazy" sizes="(min-width: 992px) 400px, 100vw" style="height: 250px; object-fit: cover;">
                        <div class="card-overlay">
                            <span class="badge bg-info">Hot</span>
                        </div>
                    </div>
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">Sapa</h5>
                        <p class="card-text text-muted flex-grow-1">
                            Vùng đất mây mù với ruộng bậc thang tuyệt đẹp
                        </p>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <small class="text-muted">
                                    <i class="fas fa-clock"></i> 2 ngày 1 đêm
                                </small>
                            </div>
                            <div class="price-badge" style="background: linear-gradient(45deg, #0EA5E9, #06B6D4);">
                                1,800,000đ
                            </div>
                        </div>
                        <a href="{{ route('tours.index') }}" class="btn btn-primary">
                            <i class="fas fa-eye"></i> Xem chi tiết
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="text-center mt-4">
            <a href="{{ route('tours.index') }}" class="btn btn-outline-primary btn-lg">
                <i class="fas fa-list"></i> Xem tất cả tours
            </a>
        </div>
    </div>
</section>

<!-- Why Choose Us Section -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="row mb-5">
            <div class="col-12 text-center">
                <h2 class="display-5 fw-bold mb-3">Tại sao chọn Tour365?</h2>
                <p class="lead text-muted">Chúng tôi cam kết mang đến trải nghiệm du lịch tốt nhất</p>
            </div>
        </div>
        
        <div class="row">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="text-center">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-shield-alt fa-3x text-primary"></i>
                    </div>
                    <h5>An toàn tuyệt đối</h5>
                    <p class="text-muted">Đảm bảo an toàn cho khách hàng trong mọi chuyến đi với bảo hiểm toàn diện</p>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="text-center">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-star fa-3x text-warning"></i>
                    </div>
                    <h5>Chất lượng cao</h5>
                    <p class="text-muted">Dịch vụ chuyên nghiệp với đội ngũ hướng dẫn viên giàu kinh nghiệm</p>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="text-center">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-heart fa-3x text-danger"></i>
                    </div>
                    <h5>Giá cả hợp lý</h5>
                    <p class="text-muted">Mức giá cạnh tranh với nhiều ưu đãi hấp dẫn và linh hoạt thanh toán</p>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="text-center">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-headset fa-3x text-success"></i>
                    </div>
                    <h5>Hỗ trợ 24/7</h5>
                    <p class="text-muted">Đội ngũ chăm sóc khách hàng luôn sẵn sàng hỗ trợ bạn mọi lúc</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-5 bg-primary text-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h3 class="fw-bold mb-3">Sẵn sàng cho chuyến du lịch đầu tiên?</h3>
                <p class="lead mb-0">Đăng ký ngay để nhận ưu đãi đặc biệt và cập nhật tour mới nhất</p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="{{ route('register') }}" class="btn btn-warning btn-lg">
                    <i class="fas fa-rocket"></i> Bắt đầu ngay
                </a>
            </div>
        </div>
    </div>
</section>
@endsection

@section('styles')
<style>
.hero-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    position: relative;
    overflow: hidden;
}

.hero-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000"><polygon fill="rgba(255,255,255,0.1)" points="0,1000 1000,0 1000,1000"/></svg>');
    background-size: cover;
}

.hero-content {
    position: relative;
    z-index: 2;
}

.tour-card {
    transition: all 0.3s ease;
    border-radius: 15px;
    overflow: hidden;
}

.tour-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
}

.card-image {
    position: relative;
    overflow: hidden;
}

.card-overlay {
    position: absolute;
    top: 15px;
    right: 15px;
    z-index: 3;
}

.price-badge {
    background: linear-gradient(45deg, #FF6B6B, #4ECDC4);
    color: white;
    padding: 8px 16px;
    border-radius: 20px;
    font-weight: bold;
    font-size: 1.1rem;
}

.feature-icon {
    transition: transform 0.3s ease;
}

.feature-icon:hover {
    transform: scale(1.1);
}

.stat-item {
    padding: 20px;
    border-radius: 10px;
    background: rgba(255,255,255,0.8);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.2);
}

.stat-number {
    font-size: 3rem;
    line-height: 1;
}

.stat-label {
    font-size: 1.1rem;
    font-weight: 500;
}

@media (max-width: 768px) {
    .hero-section {
        padding: 60px 0;
    }
    
    .display-3 {
        font-size: 2.5rem;
    }
    
    .hero-buttons .btn {
        display: block;
        width: 100%;
        margin-bottom: 10px;
    }
}
</style>
@endsection

@extends('layouts.app')

@section('title', 'Về chúng tôi - Tour365')

@section('content')
<!-- Hero Section -->
<section class="py-5 text-white" style="background: linear-gradient(135deg, #0EA5E9 0%, #38BDF8 100%);">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <h1 class="display-4 fw-bold mb-3">Về Tour365</h1>
                <p class="lead">Hành trình đưa bạn khám phá thế giới</p>
            </div>
        </div>
    </div>
</section>

<!-- About Section -->
<section class="py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h2 class="display-5 fw-bold mb-4">Câu chuyện của chúng tôi</h2>
                <p class="lead text-muted mb-4">
                    Tour365 được thành lập với sứ mệnh mang đến những trải nghiệm du lịch tuyệt vời
                    và đáng nhớ cho mọi khách hàng. Chúng tôi tin rằng mỗi chuyến đi đều là một cơ hội
                    để khám phá, học hỏi và tạo ra những kỷ niệm đẹp.
                </p>
                <p class="text-muted mb-4">
                    Với hơn 5 năm kinh nghiệm trong ngành du lịch, chúng tôi đã phục vụ hơn 10,000 khách hàng
                    và tổ chức hơn 500 chuyến tour thành công. Đội ngũ của chúng tôi bao gồm những chuyên gia
                    du lịch giàu kinh nghiệm, hướng dẫn viên chuyên nghiệp và nhân viên chăm sóc khách hàng tận tâm.
                </p>
                <div class="row">
                    <div class="col-6">
                        <div class="stat-item text-center">
                            <div class="stat-number text-primary fw-bold display-4">5+</div>
                            <div class="stat-label text-muted">Năm kinh nghiệm</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat-item text-center">
                            <div class="stat-number text-success fw-bold display-4">10K+</div>
                            <div class="stat-label text-muted">Khách hàng hài lòng</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <img src="https://via.placeholder.com/600x400/0EA5E9/ffffff?text=About+Tour365"
                     alt="About Tour365" class="img-fluid rounded-3 shadow">
            </div>
        </div>
    </div>
</section>

<!-- Mission & Vision -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="mission-icon mb-3">
                            <i class="fas fa-bullseye fa-3x text-primary"></i>
                        </div>
                        <h4 class="card-title">Sứ mệnh</h4>
                        <p class="card-text text-muted">
                            Mang đến những trải nghiệm du lịch chất lượng cao, an toàn và đáng nhớ
                            cho mọi khách hàng, góp phần quảng bá vẻ đẹp của Việt Nam và thế giới.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="mission-icon mb-3">
                            <i class="fas fa-eye fa-3x text-success"></i>
                        </div>
                        <h4 class="card-title">Tầm nhìn</h4>
                        <p class="card-text text-muted">
                            Trở thành công ty du lịch hàng đầu Việt Nam, được khách hàng tin tưởng
                            và yêu mến, góp phần phát triển ngành du lịch bền vững.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="mission-icon mb-3">
                            <i class="fas fa-heart fa-3x text-danger"></i>
                        </div>
                        <h4 class="card-title">Giá trị cốt lõi</h4>
                        <p class="card-text text-muted">
                            Chất lượng, uy tín, tận tâm và sáng tạo. Chúng tôi cam kết mang đến
                            dịch vụ tốt nhất với giá cả hợp lý và sự chăm sóc chu đáo.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Team Section -->
<section class="py-5">
    <div class="container">
        <div class="row mb-5">
            <div class="col-12 text-center">
                <h2 class="display-5 fw-bold mb-3">Đội ngũ của chúng tôi</h2>
                <p class="lead text-muted">Những con người tài năng và tận tâm</p>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card team-card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <div class="team-avatar mb-3">
                            <img src="https://via.placeholder.com/150x150/4F46E5/ffffff?text=CEO"
                                 alt="CEO" class="rounded-circle">
                        </div>
                        <h5 class="card-title">Nguyễn Văn A</h5>
                        <p class="text-primary">CEO & Founder</p>
                        <p class="card-text text-muted small">
                            Hơn 10 năm kinh nghiệm trong ngành du lịch, từng làm việc tại các công ty du lịch lớn.
                        </p>
                        <div class="social-links">
                            <a href="#" class="btn btn-outline-primary btn-sm me-1">
                                <i class="fab fa-linkedin"></i>
                            </a>
                            <a href="#" class="btn btn-outline-info btn-sm me-1">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="#" class="btn btn-outline-danger btn-sm">
                                <i class="fab fa-instagram"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card team-card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <div class="team-avatar mb-3">
                            <img src="https://via.placeholder.com/150x150/059669/ffffff?text=CTO"
                                 alt="CTO" class="rounded-circle">
                        </div>
                        <h5 class="card-title">Trần Thị B</h5>
                        <p class="text-success">CTO</p>
                        <p class="card-text text-muted small">
                            Chuyên gia công nghệ với 8 năm kinh nghiệm phát triển hệ thống du lịch trực tuyến.
                        </p>
                        <div class="social-links">
                            <a href="#" class="btn btn-outline-primary btn-sm me-1">
                                <i class="fab fa-linkedin"></i>
                            </a>
                            <a href="#" class="btn btn-outline-info btn-sm me-1">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="#" class="btn btn-outline-danger btn-sm">
                                <i class="fab fa-instagram"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card team-card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <div class="team-avatar mb-3">
                            <img src="https://via.placeholder.com/150x150/DC2626/ffffff?text=CMO"
                                 alt="CMO" class="rounded-circle">
                        </div>
                        <h5 class="card-title">Lê Văn C</h5>
                        <p class="text-danger">CMO</p>
                        <p class="card-text text-muted small">
                            Chuyên gia marketing với nhiều chiến dịch thành công trong ngành du lịch.
                        </p>
                        <div class="social-links">
                            <a href="#" class="btn btn-outline-primary btn-sm me-1">
                                <i class="fab fa-linkedin"></i>
                            </a>
                            <a href="#" class="btn btn-outline-info btn-sm me-1">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="#" class="btn btn-outline-danger btn-sm">
                                <i class="fab fa-instagram"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card team-card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <div class="team-avatar mb-3">
                            <img src="https://via.placeholder.com/150x150/7C3AED/ffffff?text=CFO"
                                 alt="CFO" class="rounded-circle">
                        </div>
                        <h5 class="card-title">Phạm Thị D</h5>
                        <p class="text-warning">CFO</p>
                        <p class="card-text text-muted small">
                            Chuyên gia tài chính với kinh nghiệm quản lý tài chính tại các công ty lớn.
                        </p>
                        <div class="social-links">
                            <a href="#" class="btn btn-outline-primary btn-sm me-1">
                                <i class="fab fa-linkedin"></i>
                            </a>
                            <a href="#" class="btn btn-outline-info btn-sm me-1">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="#" class="btn btn-outline-danger btn-sm">
                                <i class="fab fa-instagram"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Awards Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row mb-5">
            <div class="col-12 text-center">
                <h2 class="display-5 fw-bold mb-3">Giải thưởng & Chứng nhận</h2>
                <p class="lead text-muted">Những thành tựu chúng tôi đạt được</p>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="award-item text-center">
                    <div class="award-icon mb-3">
                        <i class="fas fa-trophy fa-3x text-warning"></i>
                    </div>
                    <h5>Top 10 Công ty du lịch</h5>
                    <p class="text-muted">Giải thưởng Du lịch Việt Nam 2024</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="award-item text-center">
                    <div class="award-icon mb-3">
                        <i class="fas fa-medal fa-3x text-primary"></i>
                    </div>
                    <h5>Dịch vụ xuất sắc</h5>
                    <p class="text-muted">Chứng nhận ISO 9001:2015</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="award-item text-center">
                    <div class="award-icon mb-3">
                        <i class="fas fa-star fa-3x text-success"></i>
                    </div>
                    <h5>Khách hàng hài lòng</h5>
                    <p class="text-muted">Đánh giá 5 sao từ 95% khách hàng</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="award-item text-center">
                    <div class="award-icon mb-3">
                        <i class="fas fa-shield-alt fa-3x text-info"></i>
                    </div>
                    <h5>An toàn & Bảo mật</h5>
                    <p class="text-muted">Chứng nhận bảo mật thông tin</p>
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
                <h3 class="fw-bold mb-3">Sẵn sàng trải nghiệm cùng chúng tôi?</h3>
                <p class="lead mb-0">Khám phá những chuyến du lịch tuyệt vời ngay hôm nay</p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="{{ route('tours.index') }}" class="btn btn-warning btn-lg">
                    <i class="fas fa-search"></i> Xem tours
                </a>
            </div>
        </div>
    </div>
</section>
@endsection

@section('styles')
<style>
.stat-item {
    padding: 20px;
}

.stat-number {
    font-size: 3rem;
    line-height: 1;
}

.stat-label {
    font-size: 1.1rem;
    font-weight: 500;
}

.team-card {
    transition: transform 0.3s ease;
}

.team-card:hover {
    transform: translateY(-5px);
}

.team-avatar img {
    width: 150px;
    height: 150px;
    object-fit: cover;
}

.award-item {
    padding: 20px;
    border-radius: 10px;
    background: white;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.award-item:hover {
    transform: translateY(-5px);
}

.mission-icon {
    transition: transform 0.3s ease;
}

.mission-icon:hover {
    transform: scale(1.1);
}
</style>
@endsection

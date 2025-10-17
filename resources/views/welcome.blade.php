@extends('layouts.app')

@section('title', 'Tour365 - Du lịch trọn gói')
@section('description', 'Khám phá thế giới cùng Tour365 - Dịch vụ du lịch trọn gói uy tín, chất lượng với giá cả hợp lý')

@php
    $featuredTours = \App\Models\Tour::with(['category', 'images', 'departures'])
        ->where('status', 'active')
        ->orderBy('created_at', 'desc')
        ->limit(6)
        ->get();
    
    $categories = \App\Models\Category::withCount('tours')->get();
    
    $stats = [
        'total_tours' => \App\Models\Tour::count(),
        'total_bookings' => \App\Models\Booking::count(),
        'total_customers' => \App\Models\User::whereHas('roles', function($query) {
            $query->where('name', 'customer');
        })->count(),
        'avg_rating' => 4.8
    ];
@endphp

@section('content')
<!-- Hero Section với Video Background -->
<section class="hero-section">
    <div class="hero-video">
        <video autoplay muted loop playsinline>
            <source src="https://cdn.pixabay.com/vimeo/1234567890/travel-123456.mp4" type="video/mp4">
        </video>
        <div class="hero-overlay"></div>
    </div>
    
    <div class="container">
        <div class="row align-items-center min-vh-100">
            <div class="col-lg-6">
                <div class="hero-content">
                    <div class="hero-badge mb-3">
                        <span class="badge bg-warning text-dark">
                            <i class="fas fa-star"></i> #1 Du lịch trọn gói Việt Nam
                        </span>
                    </div>
                    <h1 class="display-2 fw-bold mb-4 text-white hero-title">
                        Khám phá thế giới cùng <span class="text-warning gradient-text">Tour365</span>
                    </h1>
                    <p class="lead mb-4 text-white-75 hero-description">
                        Trải nghiệm những chuyến du lịch tuyệt vời với dịch vụ chuyên nghiệp, 
                        an toàn và giá cả hợp lý. Từ Việt Nam đến thế giới, chúng tôi đồng hành cùng bạn.
                    </p>
                    
                    <!-- Smart Search Box trong Hero -->
                    <div class="hero-search mb-4">
                        <div class="smart-search-container">
                            <form method="GET" action="{{ route('tours.index') }}" class="smart-search-form">
                                <div class="search-input-wrapper">
                                    <div class="search-icon">
                                        <i class="fas fa-search"></i>
                                    </div>
                                    <input type="text" 
                                           class="smart-search-input" 
                                           name="search" 
                                           placeholder="Bạn muốn đi đâu? (VD: Phú Quốc, Đà Lạt, Nha Trang...)" 
                                           value="{{ request('search') }}"
                                           autocomplete="off"
                                           id="smartSearchInput">
                                    <div class="search-suggestions" id="searchSuggestions"></div>
                                </div>
                                
                                <!-- Advanced Search Toggle -->
                                <button type="button" class="advanced-search-toggle" id="advancedSearchToggle">
                                    <i class="fas fa-sliders-h"></i>
                                    <span>Bộ lọc</span>
                                </button>
                                
                                <!-- Advanced Search Panel -->
                                <div class="advanced-search-panel" id="advancedSearchPanel">
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label class="form-label">Danh mục</label>
                                            <select name="category" class="form-select">
                                                <option value="">Tất cả danh mục</option>
                                                @foreach($categories as $category)
                                                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                                        {{ $category->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Giá từ</label>
                                            <div class="input-group">
                                                <input type="number" name="price_from" class="form-control" 
                                                       placeholder="0" value="{{ request('price_from', 0) }}">
                                                <span class="input-group-text">VNĐ</span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Giá đến</label>
                                            <div class="input-group">
                                                <input type="number" name="price_to" class="form-control" 
                                                       placeholder="10,000,000" value="{{ request('price_to', 10000000) }}">
                                                <span class="input-group-text">VNĐ</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <button type="submit" class="search-submit-btn">
                                    <i class="fas fa-search"></i>
                                    <span>Tìm kiếm</span>
                                </button>
                            </form>
                            
                            <!-- Popular Searches -->
                            <div class="popular-searches">
                                <span class="popular-label">Tìm kiếm phổ biến:</span>
                                <div class="popular-tags">
                                    <a href="{{ route('tours.index', ['search' => 'Phú Quốc']) }}" class="popular-tag">Phú Quốc</a>
                                    <a href="{{ route('tours.index', ['search' => 'Đà Lạt']) }}" class="popular-tag">Đà Lạt</a>
                                    <a href="{{ route('tours.index', ['search' => 'Nha Trang']) }}" class="popular-tag">Nha Trang</a>
                                    <a href="{{ route('tours.index', ['search' => 'Hạ Long']) }}" class="popular-tag">Hạ Long</a>
                                    <a href="{{ route('tours.index', ['search' => 'Sapa']) }}" class="popular-tag">Sapa</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="hero-buttons">
                        <a href="{{ route('tours.index') }}" class="btn btn-warning btn-lg me-3 pulse-btn">
                            <i class="fas fa-compass"></i> Khám phá tours
                        </a>
                        <a href="{{ route('register') }}" class="btn btn-outline-light btn-lg">
                            <i class="fas fa-user-plus"></i> Đăng ký ngay
                        </a>
                    </div>
                    
                    <!-- Trust Indicators -->
                    <div class="hero-trust mt-4">
                        <div class="d-flex align-items-center text-white-50">
                            <div class="me-4">
                                <i class="fas fa-shield-alt text-success me-2"></i>
                                <small>Bảo hiểm toàn diện</small>
                            </div>
                            <div class="me-4">
                                <i class="fas fa-headset text-info me-2"></i>
                                <small>Hỗ trợ 24/7</small>
                            </div>
                            <div>
                                <i class="fas fa-star text-warning me-2"></i>
                                <small>4.8/5 đánh giá</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="hero-visual">
                    <div class="floating-cards">
                        <div class="floating-card card-1">
                            <div class="card-content">
                                <i class="fas fa-plane text-primary"></i>
                                <span>500+ Tours</span>
                            </div>
                        </div>
                        <div class="floating-card card-2">
                            <div class="card-content">
                                <i class="fas fa-users text-success"></i>
                                <span>10K+ Khách hàng</span>
                            </div>
                        </div>
                        <div class="floating-card card-3">
                            <div class="card-content">
                                <i class="fas fa-map-marker-alt text-warning"></i>
                                <span>50+ Điểm đến</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Scroll Indicator -->
    <div class="scroll-indicator">
        <div class="scroll-arrow">
            <i class="fas fa-chevron-down"></i>
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

<!-- Stats Section với Animation -->
<section class="py-5 bg-gradient-primary text-white">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-3 col-6 mb-4">
                <div class="stat-item">
                    <div class="stat-icon mb-3">
                        <i class="fas fa-map-marked-alt fa-2x"></i>
                    </div>
                    <div class="stat-number fw-bold display-4 counter" data-target="{{ $stats['total_tours'] }}">0</div>
                    <div class="stat-label">Tours đa dạng</div>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-4">
                <div class="stat-item">
                    <div class="stat-icon mb-3">
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                    <div class="stat-number fw-bold display-4 counter" data-target="{{ $stats['total_customers'] }}">0</div>
                    <div class="stat-label">Khách hàng hài lòng</div>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-4">
                <div class="stat-item">
                    <div class="stat-icon mb-3">
                        <i class="fas fa-calendar-check fa-2x"></i>
                    </div>
                    <div class="stat-number fw-bold display-4 counter" data-target="{{ $stats['total_bookings'] }}">0</div>
                    <div class="stat-label">Đặt tour thành công</div>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-4">
                <div class="stat-item">
                    <div class="stat-icon mb-3">
                        <i class="fas fa-star fa-2x"></i>
                    </div>
                    <div class="stat-number fw-bold display-4 counter" data-target="{{ $stats['avg_rating'] }}" data-decimal="true">0</div>
                    <div class="stat-label">Đánh giá trung bình</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Blog/News Section -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="row mb-5">
            <div class="col-12 text-center">
                <h2 class="display-5 fw-bold mb-3">Tin tức & Blog</h2>
                <p class="lead text-muted">Cập nhật những thông tin du lịch mới nhất</p>
            </div>
        </div>
        
        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <article class="blog-card">
                    <div class="blog-image">
                        <img src="https://via.placeholder.com/400x250/0EA5E9/ffffff?text=Travel+Tips" 
                             alt="Mẹo du lịch" class="img-fluid" style="height: 200px; object-fit: cover;">
                        <div class="blog-category">
                            <span class="badge bg-primary">Mẹo du lịch</span>
                        </div>
                    </div>
                    <div class="blog-content">
                        <div class="blog-meta">
                            <span class="blog-date">
                                <i class="fas fa-calendar-alt me-1"></i>
                                15/01/2025
                            </span>
                            <span class="blog-author">
                                <i class="fas fa-user me-1"></i>
                                Admin
                            </span>
                        </div>
                        <h5 class="blog-title">10 mẹo du lịch tiết kiệm cho người mới bắt đầu</h5>
                        <p class="blog-excerpt">
                            Khám phá những bí quyết du lịch thông minh giúp bạn tiết kiệm chi phí mà vẫn có trải nghiệm tuyệt vời...
                        </p>
                        <a href="{{ route('blog.show', 'meo-du-lich-tiet-kiem') }}" class="btn btn-outline-primary btn-sm">
                            Đọc thêm <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </article>
            </div>
            
            <div class="col-lg-4 col-md-6">
                <article class="blog-card">
                    <div class="blog-image">
                        <img src="https://via.placeholder.com/400x250/06B6D4/ffffff?text=Destinations" 
                             alt="Điểm đến" class="img-fluid" style="height: 200px; object-fit: cover;">
                        <div class="blog-category">
                            <span class="badge bg-success">Điểm đến</span>
                        </div>
                    </div>
                    <div class="blog-content">
                        <div class="blog-meta">
                            <span class="blog-date">
                                <i class="fas fa-calendar-alt me-1"></i>
                                12/01/2025
                            </span>
                            <span class="blog-author">
                                <i class="fas fa-user me-1"></i>
                                Admin
                            </span>
                        </div>
                        <h5 class="blog-title">Top 5 điểm đến hot nhất mùa hè 2025</h5>
                        <p class="blog-excerpt">
                            Cùng khám phá những điểm đến đang được yêu thích nhất trong mùa hè này với thời tiết lý tưởng...
                        </p>
                        <a href="{{ route('blog.show', 'top-5-diem-den-hot-mua-he-2025') }}" class="btn btn-outline-primary btn-sm">
                            Đọc thêm <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </article>
            </div>
            
            <div class="col-lg-4 col-md-6">
                <article class="blog-card">
                    <div class="blog-image">
                        <img src="https://via.placeholder.com/400x250/0284C7/ffffff?text=Promotions" 
                             alt="Ưu đãi" class="img-fluid" style="height: 200px; object-fit: cover;">
                        <div class="blog-category">
                            <span class="badge bg-warning">Ưu đãi</span>
                        </div>
                    </div>
                    <div class="blog-content">
                        <div class="blog-meta">
                            <span class="blog-date">
                                <i class="fas fa-calendar-alt me-1"></i>
                                10/01/2025
                            </span>
                            <span class="blog-author">
                                <i class="fas fa-user me-1"></i>
                                Admin
                            </span>
                        </div>
                        <h5 class="blog-title">Chương trình khuyến mãi đặc biệt tháng 1</h5>
                        <p class="blog-excerpt">
                            Cơ hội vàng để đặt tour với mức giá ưu đãi lên đến 30% cho tất cả các tour trong tháng 1...
                        </p>
                        <a href="{{ route('blog.show', 'chuong-trinh-khuyen-mai-thang-1') }}" class="btn btn-outline-primary btn-sm">
                            Đọc thêm <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </article>
            </div>
        </div>
        
        <div class="text-center mt-4">
            <a href="{{ route('blog.index') }}" class="btn btn-outline-primary btn-lg">
                <i class="fas fa-newspaper"></i> Xem tất cả bài viết
            </a>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="row mb-5">
            <div class="col-12 text-center">
                <h2 class="display-5 fw-bold mb-3">Khám phá theo danh mục</h2>
                <p class="lead text-muted">Chọn loại hình du lịch phù hợp với sở thích của bạn</p>
            </div>
        </div>
        
        <div class="row g-4">
            @foreach($categories as $category)
            <div class="col-lg-2 col-md-4 col-6">
                <a href="{{ route('tours.index', ['category_id' => $category->id]) }}" class="category-card">
                    <div class="category-icon">
                        @switch($category->name)
                            @case('Du lịch trong nước')
                                <i class="fas fa-home"></i>
                                @break
                            @case('Du lịch nước ngoài')
                                <i class="fas fa-globe-americas"></i>
                                @break
                            @case('Du lịch biển đảo')
                                <i class="fas fa-water"></i>
                                @break
                            @case('Du lịch văn hóa')
                                <i class="fas fa-landmark"></i>
                                @break
                            @case('Du lịch thiên nhiên')
                                <i class="fas fa-tree"></i>
                                @break
                            @case('Du lịch tâm linh')
                                <i class="fas fa-pray"></i>
                                @break
                            @default
                                <i class="fas fa-map-marked-alt"></i>
                        @endswitch
                    </div>
                    <h6 class="category-name">{{ $category->name }}</h6>
                    <small class="category-count">{{ $category->tours_count }} tours</small>
                </a>
            </div>
            @endforeach
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
        
        @if($featuredTours->count() > 0)
        <div class="row g-4">
            @foreach($featuredTours as $index => $tour)
            <div class="col-lg-4 col-md-6 mb-4 slide-up" style="animation-delay: {{ $index * 0.1 }}s">
                <div class="card tour-card h-100 border-0 shadow-sm">
                    <div class="card-image">
                        @if($tour->images->count() > 0)
                            <img src="{{ $tour->images->first()->image_url }}" 
                                 class="card-img-top" alt="{{ $tour->title }}" loading="lazy" 
                                 style="height: 250px; object-fit: cover;">
                        @else
                            <img src="https://via.placeholder.com/400x250/0EA5E9/ffffff?text={{ urlencode($tour->title) }}" 
                                 class="card-img-top" alt="{{ $tour->title }}" loading="lazy" 
                                 style="height: 250px; object-fit: cover;">
                        @endif
                        <div class="card-overlay">
                            @if($index == 0)
                                <span class="badge bg-warning">Bán chạy</span>
                            @elseif($index == 1)
                                <span class="badge bg-success">Mới</span>
                            @elseif($index == 2)
                                <span class="badge bg-info">Hot</span>
                            @else
                                <span class="badge bg-primary">Nổi bật</span>
                            @endif
                        </div>
                        <div class="card-actions">
                            <button class="btn btn-sm btn-light wishlist-btn" data-tour-id="{{ $tour->id }}">
                                <i class="fas fa-heart"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <span class="category-badge">{{ $tour->category->name }}</span>
                            <div class="rating">
                                <i class="fas fa-star text-warning"></i>
                                <span class="ms-1">4.8</span>
                            </div>
                        </div>
                        <h5 class="card-title">{{ $tour->title }}</h5>
                        <p class="card-text text-muted flex-grow-1">
                            {{ Str::limit($tour->short_description ?? $tour->description, 100) }}
                        </p>
                        <div class="tour-meta mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="tour-duration">
                                    <i class="fas fa-clock text-muted me-1"></i>
                                    <small class="text-muted">{{ $tour->duration }}</small>
                                </div>
                                <div class="tour-location">
                                    <i class="fas fa-map-marker-alt text-muted me-1"></i>
                                    <small class="text-muted">{{ $tour->location }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="price-section">
                                @if($tour->discount_price && $tour->discount_price < $tour->price)
                                    <div class="price-original text-muted text-decoration-line-through">
                                        {{ number_format($tour->price) }}đ
                                    </div>
                                    <div class="price-current fw-bold text-primary">
                                        {{ number_format($tour->discount_price) }}đ
                                    </div>
                                @else
                                    <div class="price-current fw-bold text-primary">
                                        {{ number_format($tour->price) }}đ
                                    </div>
                                @endif
                            </div>
                            <div class="availability">
                                @if($tour->departures->where('seats_available', '>', 0)->count() > 0)
                                    <span class="badge bg-success">Còn chỗ</span>
                                @else
                                    <span class="badge bg-danger">Hết chỗ</span>
                                @endif
                            </div>
                        </div>
                        <a href="{{ route('tours.show', $tour) }}" class="btn btn-primary">
                            <i class="fas fa-eye"></i> Xem chi tiết
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-5">
            <div class="empty-state">
                <i class="fas fa-map-marked-alt fa-3x text-muted mb-3"></i>
                <h4 class="text-muted">Chưa có tour nào</h4>
                <p class="text-muted">Chúng tôi đang cập nhật các tour mới nhất</p>
            </div>
        </div>
        @endif
        
        <div class="text-center mt-5">
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

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Counter Animation
    function animateCounters() {
        const counters = document.querySelectorAll('.counter');
        
        counters.forEach(counter => {
            const target = parseInt(counter.getAttribute('data-target'));
            const isDecimal = counter.hasAttribute('data-decimal');
            const duration = 2000; // 2 seconds
            const increment = target / (duration / 16); // 60fps
            let current = 0;
            
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    current = target;
                    clearInterval(timer);
                }
                
                if (isDecimal) {
                    counter.textContent = current.toFixed(1);
                } else {
                    counter.textContent = Math.floor(current);
                }
            }, 16);
        });
    }
    
    // Intersection Observer for animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                if (entry.target.classList.contains('bg-gradient-primary')) {
                    animateCounters();
                }
                entry.target.classList.add('animate-in');
            }
        });
    }, observerOptions);
    
    // Observe stats section
    const statsSection = document.querySelector('.bg-gradient-primary');
    if (statsSection) {
        observer.observe(statsSection);
    }
    
    // Wishlist functionality
    document.querySelectorAll('.wishlist-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const tourId = this.getAttribute('data-tour-id');
            
            // Toggle heart icon
            const icon = this.querySelector('i');
            if (icon.classList.contains('fas')) {
                icon.classList.remove('fas');
                icon.classList.add('far');
                this.style.background = '#ff6b6b';
                this.style.color = 'white';
            } else {
                icon.classList.remove('far');
                icon.classList.add('fas');
                this.style.background = '';
                this.style.color = '';
            }
            
            // Here you would typically make an AJAX call to add/remove from wishlist
            // For now, we'll just show a toast notification
            showToast('Đã thêm vào danh sách yêu thích!', 'success');
        });
    });
    
    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
    
    // Parallax effect for hero section
    window.addEventListener('scroll', () => {
        const scrolled = window.pageYOffset;
        const heroSection = document.querySelector('.hero-section');
        if (heroSection) {
            const rate = scrolled * -0.5;
            heroSection.style.transform = `translateY(${rate}px)`;
        }
    });
    
    // Smart Search functionality
    const smartSearchInput = document.getElementById('smartSearchInput');
    const searchSuggestions = document.getElementById('searchSuggestions');
    const advancedSearchToggle = document.getElementById('advancedSearchToggle');
    const advancedSearchPanel = document.getElementById('advancedSearchPanel');
    
    // Sample suggestions data (in real app, this would come from API)
    const searchSuggestionsData = [
        { text: 'Phú Quốc', category: 'Điểm đến', icon: 'fas fa-map-marker-alt' },
        { text: 'Đà Lạt', category: 'Điểm đến', icon: 'fas fa-mountain' },
        { text: 'Nha Trang', category: 'Điểm đến', icon: 'fas fa-umbrella-beach' },
        { text: 'Hạ Long', category: 'Điểm đến', icon: 'fas fa-water' },
        { text: 'Sapa', category: 'Điểm đến', icon: 'fas fa-mountain' },
        { text: 'Hội An', category: 'Điểm đến', icon: 'fas fa-city' },
        { text: 'Tour biển', category: 'Loại tour', icon: 'fas fa-ship' },
        { text: 'Tour núi', category: 'Loại tour', icon: 'fas fa-mountain' },
        { text: 'Tour văn hóa', category: 'Loại tour', icon: 'fas fa-landmark' },
        { text: 'Tour ẩm thực', category: 'Loại tour', icon: 'fas fa-utensils' }
    ];
    
    // Advanced search toggle
    if (advancedSearchToggle && advancedSearchPanel) {
        advancedSearchToggle.addEventListener('click', function() {
            this.classList.toggle('active');
            advancedSearchPanel.classList.toggle('show');
        });
    }
    
    // Search suggestions
    if (smartSearchInput && searchSuggestions) {
        let debounceTimer;
        
        smartSearchInput.addEventListener('input', function() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                const query = this.value.toLowerCase().trim();
                
                if (query.length < 2) {
                    searchSuggestions.style.display = 'none';
                    return;
                }
                
                const filteredSuggestions = searchSuggestionsData.filter(item => 
                    item.text.toLowerCase().includes(query) || 
                    item.category.toLowerCase().includes(query)
                );
                
                if (filteredSuggestions.length > 0) {
                    displaySuggestions(filteredSuggestions);
                } else {
                    searchSuggestions.style.display = 'none';
                }
            }, 300);
        });
        
        // Hide suggestions when clicking outside
        document.addEventListener('click', function(e) {
            if (!smartSearchInput.contains(e.target) && !searchSuggestions.contains(e.target)) {
                searchSuggestions.style.display = 'none';
            }
        });
        
        // Handle suggestion selection
        searchSuggestions.addEventListener('click', function(e) {
            if (e.target.classList.contains('suggestion-item')) {
                const text = e.target.querySelector('.suggestion-text').textContent;
                smartSearchInput.value = text;
                searchSuggestions.style.display = 'none';
                smartSearchInput.focus();
            }
        });
    }
    
    function displaySuggestions(suggestions) {
        searchSuggestions.innerHTML = '';
        
        suggestions.slice(0, 5).forEach(suggestion => {
            const suggestionItem = document.createElement('div');
            suggestionItem.className = 'suggestion-item';
            suggestionItem.innerHTML = `
                <i class="suggestion-icon ${suggestion.icon}"></i>
                <span class="suggestion-text">${suggestion.text}</span>
                <span class="suggestion-category">${suggestion.category}</span>
            `;
            searchSuggestions.appendChild(suggestionItem);
        });
        
        searchSuggestions.style.display = 'block';
    }
    
    // Search form submission with loading state
    const searchForm = document.querySelector('.smart-search-form');
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('.search-submit-btn');
            const originalText = submitBtn.innerHTML;
            
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang tìm kiếm...';
            submitBtn.disabled = true;
            
            // Re-enable after 2 seconds (in case of slow response)
            setTimeout(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }, 2000);
        });
    }
    
    // Popular tags click tracking
    document.querySelectorAll('.popular-tag').forEach(tag => {
        tag.addEventListener('click', function(e) {
            // Add click animation
            this.style.transform = 'scale(0.95)';
            setTimeout(() => {
                this.style.transform = '';
            }, 150);
        });
    });
    
    // Toast notification function
    function showToast(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `toast-notification toast-${type}`;
        toast.innerHTML = `
            <div class="toast-content">
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'info-circle'}"></i>
                <span>${message}</span>
            </div>
        `;
        
        // Add toast styles
        toast.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: ${type === 'success' ? '#10B981' : '#3B82F6'};
            color: white;
            padding: 15px 20px;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            z-index: 9999;
            transform: translateX(100%);
            transition: transform 0.3s ease;
        `;
        
        document.body.appendChild(toast);
        
        // Animate in
        setTimeout(() => {
            toast.style.transform = 'translateX(0)';
        }, 100);
        
        // Remove after 3 seconds
        setTimeout(() => {
            toast.style.transform = 'translateX(100%)';
            setTimeout(() => {
                document.body.removeChild(toast);
            }, 300);
        }, 3000);
    }
    
    // Add loading animation to buttons
    document.querySelectorAll('.btn').forEach(btn => {
        btn.addEventListener('click', function() {
            if (this.href && !this.href.includes('#')) {
                const originalText = this.innerHTML;
                this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang tải...';
                this.disabled = true;
                
                // Re-enable after 2 seconds (in case of slow loading)
                setTimeout(() => {
                    this.innerHTML = originalText;
                    this.disabled = false;
                }, 2000);
            }
        });
    });
    
    // Add hover effects to cards
    document.querySelectorAll('.tour-card, .category-card, .blog-card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-10px) scale(1.02)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });
    
    // Lazy loading for images
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src || img.src;
                    img.classList.remove('lazy');
                    imageObserver.unobserve(img);
                }
            });
        });
        
        document.querySelectorAll('img[loading="lazy"]').forEach(img => {
            imageObserver.observe(img);
        });
    }
});

// Add CSS for toast notifications
const toastStyles = document.createElement('style');
toastStyles.textContent = `
    .toast-notification {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        font-size: 14px;
        font-weight: 500;
    }
    
    .toast-content {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .toast-content i {
        font-size: 16px;
    }
    
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
        }
        to {
            transform: translateX(0);
        }
    }
    
    @keyframes slideOutRight {
        from {
            transform: translateX(0);
        }
        to {
            transform: translateX(100%);
        }
    }
`;
document.head.appendChild(toastStyles);
</script>
@endsection

@section('styles')
<style>
/* Hero Section với Video Background */
.hero-section {
    position: relative;
    min-height: 100vh;
    display: flex;
    align-items: center;
    overflow: hidden;
    background: linear-gradient(135deg, #0EA5E9 0%, #38BDF8 50%, #06B6D4 100%);
}

.hero-video {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 1;
}

.hero-video video {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.hero-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, rgba(14,165,233,0.8) 0%, rgba(56,189,248,0.8) 50%, rgba(6,182,212,0.8) 100%);
    z-index: 2;
}

.hero-content {
    position: relative;
    z-index: 3;
}

.hero-badge {
    animation: fadeInUp 0.8s ease;
}

.hero-title {
    animation: fadeInUp 0.8s ease 0.2s both;
}

.hero-description {
    animation: fadeInUp 0.8s ease 0.4s both;
}

.hero-search {
    animation: fadeInUp 0.8s ease 0.6s both;
}

.hero-buttons {
    animation: fadeInUp 0.8s ease 0.8s both;
}

.hero-trust {
    animation: fadeInUp 0.8s ease 1s both;
}

.gradient-text {
    background: linear-gradient(45deg, #FFD700, #FFA500);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

/* Smart Search Container */
.smart-search-container {
    position: relative;
    max-width: 900px;
    margin: 0 auto;
}

.smart-search-form {
    background: linear-gradient(145deg, #ffffff, #f8fafc);
    border-radius: 25px;
    padding: 30px;
    box-shadow: 
        0 25px 50px rgba(0,0,0,0.08),
        0 0 0 1px rgba(255,255,255,0.8),
        inset 0 1px 0 rgba(255,255,255,0.9);
    backdrop-filter: blur(20px);
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    border: 1px solid rgba(14,165,233,0.1);
    position: relative;
    overflow: hidden;
}

.smart-search-form::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 2px;
    background: linear-gradient(90deg, #0EA5E9, #38BDF8, #06B6D4, #0EA5E9);
    background-size: 200% 100%;
    animation: gradientShift 3s ease-in-out infinite;
}

@keyframes gradientShift {
    0%, 100% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
}

.smart-search-form:hover {
    transform: translateY(-8px) scale(1.02);
    box-shadow: 
        0 35px 70px rgba(0,0,0,0.12),
        0 0 0 1px rgba(14,165,233,0.2),
        inset 0 1px 0 rgba(255,255,255,0.9);
    border-color: rgba(14,165,233,0.3);
}

/* Search Input Wrapper */
.search-input-wrapper {
    position: relative;
    margin-bottom: 20px;
}

.search-icon {
    position: absolute;
    left: 25px;
    top: 50%;
    transform: translateY(-50%);
    color: #0EA5E9;
    font-size: 1.2rem;
    z-index: 2;
    transition: all 0.3s ease;
}

.smart-search-input {
    width: 100%;
    padding: 22px 25px 22px 65px;
    border: 2px solid rgba(14,165,233,0.1);
    border-radius: 20px;
    font-size: 1.1rem;
    background: linear-gradient(145deg, #ffffff, #f8fafc);
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    outline: none;
    box-shadow: 
        inset 0 2px 4px rgba(0,0,0,0.02),
        0 1px 3px rgba(0,0,0,0.05);
    font-weight: 500;
}

.smart-search-input:focus {
    border-color: #0EA5E9;
    box-shadow: 
        0 0 0 4px rgba(14,165,233,0.1),
        inset 0 2px 4px rgba(0,0,0,0.02),
        0 8px 25px rgba(14,165,233,0.15);
    transform: translateY(-2px);
    background: #ffffff;
}

.smart-search-input:focus + .search-icon {
    color: #0284C7;
    transform: translateY(-50%) scale(1.1);
}

.smart-search-input::placeholder {
    color: #94A3B8;
    font-weight: 400;
    transition: all 0.3s ease;
}

.smart-search-input:focus::placeholder {
    color: #CBD5E1;
    transform: translateX(5px);
}

/* Search Suggestions */
.search-suggestions {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: white;
    border: 1px solid #E5E7EB;
    border-radius: 0 0 15px 15px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    z-index: 1000;
    max-height: 300px;
    overflow-y: auto;
    display: none;
}

.suggestion-item {
    padding: 12px 20px;
    cursor: pointer;
    border-bottom: 1px solid #F3F4F6;
    transition: background 0.2s ease;
    display: flex;
    align-items: center;
    gap: 10px;
}

.suggestion-item:hover {
    background: #F8FAFC;
}

.suggestion-item:last-child {
    border-bottom: none;
}

.suggestion-icon {
    color: #0EA5E9;
    font-size: 0.9rem;
}

.suggestion-text {
    flex: 1;
    font-weight: 500;
}

.suggestion-category {
    color: #6B7280;
    font-size: 0.85rem;
    background: #F3F4F6;
    padding: 2px 8px;
    border-radius: 10px;
}

/* Advanced Search Toggle */
.advanced-search-toggle {
    background: linear-gradient(145deg, #f8fafc, #e2e8f0);
    border: 2px solid rgba(14,165,233,0.1);
    border-radius: 15px;
    padding: 15px 25px;
    color: #475569;
    font-weight: 600;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 20px;
    width: 100%;
    justify-content: center;
    position: relative;
    overflow: hidden;
    box-shadow: 
        inset 0 1px 3px rgba(0,0,0,0.05),
        0 2px 8px rgba(0,0,0,0.05);
}

.advanced-search-toggle::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(14,165,233,0.1), transparent);
    transition: left 0.6s ease;
}

.advanced-search-toggle:hover::before {
    left: 100%;
}

.advanced-search-toggle:hover {
    background: linear-gradient(145deg, #0EA5E9, #0284C7);
    border-color: #0EA5E9;
    color: white;
    transform: translateY(-3px);
    box-shadow: 
        0 8px 25px rgba(14,165,233,0.3),
        inset 0 1px 3px rgba(255,255,255,0.2);
}

.advanced-search-toggle.active {
    background: linear-gradient(145deg, #0EA5E9, #0284C7);
    border-color: #0EA5E9;
    color: white;
    transform: translateY(-2px);
    box-shadow: 
        0 6px 20px rgba(14,165,233,0.25),
        inset 0 1px 3px rgba(255,255,255,0.2);
}

.advanced-search-toggle i {
    transition: transform 0.3s ease;
}

.advanced-search-toggle:hover i,
.advanced-search-toggle.active i {
    transform: rotate(180deg);
}

/* Advanced Search Panel */
.advanced-search-panel {
    background: linear-gradient(145deg, #f8fafc, #e2e8f0);
    border-radius: 20px;
    padding: 25px;
    margin-bottom: 20px;
    display: none;
    animation: slideDown 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    border: 1px solid rgba(14,165,233,0.1);
    box-shadow: 
        inset 0 1px 3px rgba(0,0,0,0.05),
        0 4px 15px rgba(0,0,0,0.05);
}

.advanced-search-panel.show {
    display: block;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-20px) scale(0.95);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

.advanced-search-panel .form-label {
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 10px;
    font-size: 0.95rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.advanced-search-panel .form-select,
.advanced-search-panel .form-control {
    border: 2px solid rgba(14,165,233,0.1);
    border-radius: 12px;
    padding: 14px 18px;
    transition: all 0.3s ease;
    background: linear-gradient(145deg, #ffffff, #f8fafc);
    font-weight: 500;
    box-shadow: inset 0 1px 3px rgba(0,0,0,0.05);
}

.advanced-search-panel .form-select:focus,
.advanced-search-panel .form-control:focus {
    border-color: #0EA5E9;
    box-shadow: 
        0 0 0 3px rgba(14,165,233,0.1),
        inset 0 1px 3px rgba(0,0,0,0.05);
    transform: translateY(-1px);
}

.advanced-search-panel .input-group-text {
    background: linear-gradient(145deg, #f1f5f9, #e2e8f0);
    border: 2px solid rgba(14,165,233,0.1);
    border-left: none;
    color: #64748b;
    font-weight: 600;
}

/* Search Submit Button */
.search-submit-btn {
    background: linear-gradient(135deg, #0EA5E9, #38BDF8, #06B6D4);
    background-size: 200% 200%;
    border: none;
    border-radius: 20px;
    padding: 20px 35px;
    color: white;
    font-weight: 700;
    font-size: 1.2rem;
    width: 100%;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    box-shadow: 
        0 10px 30px rgba(14,165,233,0.3),
        inset 0 1px 0 rgba(255,255,255,0.2);
    position: relative;
    overflow: hidden;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.search-submit-btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: left 0.6s ease;
}

.search-submit-btn:hover::before {
    left: 100%;
}

.search-submit-btn:hover {
    transform: translateY(-4px) scale(1.02);
    box-shadow: 
        0 15px 40px rgba(14,165,233,0.4),
        inset 0 1px 0 rgba(255,255,255,0.3);
    background-position: 100% 0;
}

.search-submit-btn:active {
    transform: translateY(-2px) scale(1.01);
}

.search-submit-btn i {
    transition: transform 0.3s ease;
}

.search-submit-btn:hover i {
    transform: scale(1.1) rotate(5deg);
}

/* Popular Searches */
.popular-searches {
    margin-top: 30px;
    text-align: center;
    padding: 25px;
    background: linear-gradient(145deg, rgba(255,255,255,0.1), rgba(255,255,255,0.05));
    border-radius: 20px;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.2);
}

.popular-label {
    color: rgba(255,255,255,0.9);
    font-size: 1rem;
    font-weight: 600;
    margin-bottom: 15px;
    display: block;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.popular-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    justify-content: center;
    margin-top: 15px;
}

.popular-tag {
    background: linear-gradient(145deg, rgba(255,255,255,0.2), rgba(255,255,255,0.1));
    color: white;
    padding: 10px 18px;
    border-radius: 25px;
    text-decoration: none;
    font-size: 0.9rem;
    font-weight: 600;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    border: 1px solid rgba(255,255,255,0.3);
    position: relative;
    overflow: hidden;
    backdrop-filter: blur(10px);
}

.popular-tag::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: left 0.6s ease;
}

.popular-tag:hover::before {
    left: 100%;
}

.popular-tag:hover {
    background: linear-gradient(145deg, rgba(255,255,255,0.3), rgba(255,255,255,0.2));
    color: white;
    text-decoration: none;
    transform: translateY(-3px) scale(1.05);
    box-shadow: 
        0 8px 25px rgba(0,0,0,0.2),
        inset 0 1px 0 rgba(255,255,255,0.3);
    border-color: rgba(255,255,255,0.5);
}

.popular-tag:active {
    transform: translateY(-1px) scale(1.02);
}

.pulse-btn {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

/* Floating Cards Animation */
.floating-cards {
    position: relative;
    height: 400px;
}

.floating-card {
    position: absolute;
    background: rgba(255,255,255,0.95);
    border-radius: 20px;
    padding: 20px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    backdrop-filter: blur(10px);
    animation: float 6s ease-in-out infinite;
}

.floating-card.card-1 {
    top: 20%;
    left: 10%;
    animation-delay: 0s;
}

.floating-card.card-2 {
    top: 50%;
    right: 20%;
    animation-delay: 2s;
}

.floating-card.card-3 {
    bottom: 20%;
    left: 30%;
    animation-delay: 4s;
}

@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-20px); }
}

.card-content {
    text-align: center;
}

.card-content i {
    font-size: 2rem;
    margin-bottom: 10px;
    display: block;
}

.card-content span {
    font-weight: 600;
    color: #333;
}

/* Scroll Indicator */
.scroll-indicator {
    position: absolute;
    bottom: 30px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 3;
}

.scroll-arrow {
    animation: bounce 2s infinite;
    color: white;
    font-size: 1.5rem;
}

@keyframes bounce {
    0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
    40% { transform: translateY(-10px); }
    60% { transform: translateY(-5px); }
}

/* Category Cards */
.category-card {
    display: block;
    text-decoration: none;
    color: inherit;
    transition: all 0.3s ease;
    padding: 20px;
    border-radius: 15px;
    text-align: center;
    background: white;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
}

.category-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    text-decoration: none;
    color: inherit;
}

.category-icon {
    width: 60px;
    height: 60px;
    margin: 0 auto 15px;
    background: linear-gradient(135deg, #0EA5E9, #38BDF8);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
}

.category-name {
    font-weight: 600;
    margin-bottom: 5px;
}

.category-count {
    color: #6B7280;
}

/* Tour Cards */
.tour-card {
    transition: all 0.3s ease;
    border-radius: 20px;
    overflow: hidden;
    background: white;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
}

.tour-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.15);
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

.card-actions {
    position: absolute;
    top: 15px;
    left: 15px;
    z-index: 3;
}

.wishlist-btn {
    border-radius: 50%;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.wishlist-btn:hover {
    background: #ff6b6b !important;
    color: white !important;
}

.category-badge {
    background: #E3F2FD;
    color: #1976D2;
    padding: 4px 12px;
    border-radius: 15px;
    font-size: 0.85rem;
    font-weight: 500;
}

.rating {
    color: #FFA500;
}

.price-section {
    text-align: left;
}

.price-current {
    font-size: 1.2rem;
}

/* Stats Section */
.bg-gradient-primary {
    background: linear-gradient(135deg, #0EA5E9 0%, #38BDF8 50%, #06B6D4 100%);
    position: relative;
    overflow: hidden;
}

.bg-gradient-primary::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000"><polygon fill="rgba(255,255,255,0.1)" points="0,1000 1000,0 1000,1000"/></svg>');
    background-size: cover;
}

.stat-item {
    position: relative;
    z-index: 2;
    padding: 30px 20px;
    text-align: center;
}

.stat-icon {
    opacity: 0.9;
}

.stat-number {
    font-size: 3.5rem;
    line-height: 1;
    margin: 15px 0;
}

.stat-label {
    font-size: 1.1rem;
    font-weight: 500;
    opacity: 0.9;
}

/* Blog Cards */
.blog-card {
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    transition: all 0.3s ease;
    height: 100%;
}

.blog-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
}

.blog-image {
    position: relative;
    overflow: hidden;
}

.blog-category {
    position: absolute;
    top: 15px;
    left: 15px;
    z-index: 3;
}

.blog-content {
    padding: 20px;
}

.blog-meta {
    display: flex;
    justify-content: space-between;
    margin-bottom: 15px;
    font-size: 0.9rem;
    color: #6B7280;
}

.blog-title {
    font-weight: 600;
    margin-bottom: 10px;
    line-height: 1.4;
}

.blog-excerpt {
    color: #6B7280;
    margin-bottom: 15px;
    line-height: 1.6;
}

/* Animations */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.slide-up {
    animation: fadeInUp 0.6s ease both;
}

/* Counter Animation */
.counter {
    font-variant-numeric: tabular-nums;
}

/* Responsive Design */
@media (max-width: 768px) {
    .hero-section {
        min-height: 80vh;
        padding: 40px 0;
    }
    
    .display-2 {
        font-size: 2.5rem;
    }
    
    .hero-buttons .btn {
        display: block;
        width: 100%;
        margin-bottom: 10px;
    }
    
    .floating-cards {
        display: none;
    }
    
    .hero-trust {
        flex-direction: column;
        gap: 10px;
    }
    
    .hero-trust > div {
        margin: 0 !important;
    }
    
    .stat-number {
        font-size: 2.5rem;
    }
    
    .category-card {
        padding: 15px;
    }
    
    .category-icon {
        width: 50px;
        height: 50px;
        font-size: 1.2rem;
    }
}

@media (max-width: 768px) {
    .smart-search-container {
        max-width: 100%;
        padding: 0 15px;
    }
    
    .smart-search-form {
        padding: 15px;
    }
    
    .smart-search-input {
        font-size: 1rem;
        padding: 15px 15px 15px 45px;
    }
    
    .search-icon {
        left: 15px;
        font-size: 1rem;
    }
    
    .advanced-search-panel .row {
        flex-direction: column;
    }
    
    .advanced-search-panel .col-md-4 {
        margin-bottom: 15px;
    }
    
    .popular-tags {
        justify-content: flex-start;
    }
    
    .popular-tag {
        font-size: 0.8rem;
        padding: 5px 10px;
    }
}

@media (max-width: 576px) {
    .smart-search-form {
        padding: 12px;
        border-radius: 15px;
    }
    
    .smart-search-input {
        padding: 12px 12px 12px 40px;
        font-size: 0.95rem;
    }
    
    .search-icon {
        left: 12px;
        font-size: 0.9rem;
    }
    
    .advanced-search-toggle {
        padding: 10px 15px;
        font-size: 0.9rem;
    }
    
    .search-submit-btn {
        padding: 15px 20px;
        font-size: 1rem;
    }
    
    .popular-searches {
        margin-top: 15px;
    }
    
    .popular-label {
        font-size: 0.8rem;
        display: block;
        margin-bottom: 8px;
    }
    
    .popular-tags {
        gap: 6px;
    }
    
    .popular-tag {
        font-size: 0.75rem;
        padding: 4px 8px;
    }
}

/* Dark mode support */
body.dark .category-card,
body.dark .tour-card,
body.dark .blog-card {
    background: #1F2937;
    color: #E5E7EB;
}

body.dark .category-card:hover,
body.dark .tour-card:hover,
body.dark .blog-card:hover {
    background: #374151;
}

/* Dark mode support for smart search */
body.dark .smart-search-form {
    background: rgba(31, 41, 55, 0.95);
    border-color: rgba(75, 85, 99, 0.3);
}

body.dark .smart-search-input {
    background: #374151;
    border-color: #4B5563;
    color: #E5E7EB;
}

body.dark .smart-search-input:focus {
    border-color: #38BDF8;
    background: #374151;
}

body.dark .smart-search-input::placeholder {
    color: #9CA3AF;
}

body.dark .search-suggestions {
    background: #374151;
    border-color: #4B5563;
}

body.dark .suggestion-item {
    border-color: #4B5563;
    color: #E5E7EB;
}

body.dark .suggestion-item:hover {
    background: #4B5563;
}

body.dark .suggestion-category {
    background: #4B5563;
    color: #9CA3AF;
}

body.dark .advanced-search-toggle {
    background: #374151;
    border-color: #4B5563;
    color: #9CA3AF;
}

body.dark .advanced-search-toggle:hover,
body.dark .advanced-search-toggle.active {
    background: #38BDF8;
    border-color: #38BDF8;
    color: #1F2937;
}

body.dark .advanced-search-panel {
    background: #374151;
}

body.dark .advanced-search-panel .form-label {
    color: #E5E7EB;
}

body.dark .advanced-search-panel .form-select,
body.dark .advanced-search-panel .form-control {
    background: #4B5563;
    border-color: #6B7280;
    color: #E5E7EB;
}

body.dark .advanced-search-panel .form-select:focus,
body.dark .advanced-search-panel .form-control:focus {
    border-color: #38BDF8;
    background: #4B5563;
}

body.dark .advanced-search-panel .input-group-text {
    background: #4B5563;
    border-color: #6B7280;
    color: #9CA3AF;
}

body.dark .floating-card {
    background: rgba(31, 41, 55, 0.95);
    color: #E5E7EB;
}
</style>
@endsection

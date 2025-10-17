@extends('layouts.app')

@section('title', 'Blog du lịch - Tour365')

@section('content')
<!-- Hero Section với Animation -->
<section class="blog-hero">
    <div class="hero-background">
        <div class="hero-pattern"></div>
    </div>
    <div class="container">
        <div class="row align-items-center min-vh-50">
            <div class="col-lg-8 mx-auto text-center">
                <div class="hero-content">
                    <div class="hero-badge mb-3">
                        <span class="badge bg-warning text-dark">
                            <i class="fas fa-newspaper"></i> Blog du lịch
                        </span>
                    </div>
                    <h1 class="display-3 fw-bold mb-4 text-white hero-title">
                        Khám phá thế giới qua <span class="text-warning gradient-text">lăng kính du lịch</span>
                    </h1>
                    <p class="lead mb-4 text-white-75 hero-description">
                        Mẹo, cẩm nang và kinh nghiệm quý báu cho mọi hành trình. 
                        Từ những bí quyết tiết kiệm đến những điểm đến bí ẩn.
                    </p>
                    
                    <!-- Search Box -->
                    <div class="hero-search mb-4">
                        <div class="search-box">
                            <form method="GET" action="{{ route('blog.index') }}">
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text bg-white border-0">
                                        <i class="fas fa-search text-primary"></i>
                                    </span>
                                    <input type="text" class="form-control border-0" name="search" 
                                           placeholder="Tìm kiếm bài viết..." value="{{ request('search') }}">
                                    <button class="btn btn-primary px-4" type="submit">
                                        <i class="fas fa-search"></i> Tìm kiếm
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Stats -->
                    <div class="hero-stats">
                        <div class="row text-center">
                            <div class="col-4">
                                <div class="stat-item">
                                    <div class="stat-number text-warning fw-bold">50+</div>
                                    <div class="stat-label text-white-50">Bài viết</div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="stat-item">
                                    <div class="stat-number text-warning fw-bold">10K+</div>
                                    <div class="stat-label text-white-50">Lượt đọc</div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="stat-item">
                                    <div class="stat-number text-warning fw-bold">5★</div>
                                    <div class="stat-label text-white-50">Đánh giá</div>
                                </div>
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

<!-- Main Content -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <!-- Filter Section -->
                <div class="blog-filters mb-5">
                    <div class="d-flex flex-wrap justify-content-between align-items-center">
                        <div class="filter-title">
                            <h2 class="h3 mb-0">Bài viết mới nhất</h2>
                            <p class="text-muted mb-0">Khám phá những bài viết hữu ích nhất</p>
                        </div>
                        <div class="filter-buttons">
                            <div class="btn-group" role="group">
                                <button class="btn btn-outline-primary filter-btn active" data-filter="all">
                                    <i class="fas fa-th"></i> Tất cả
                                </button>
                                <button class="btn btn-outline-primary filter-btn" data-filter="Mẹo du lịch">
                                    <i class="fas fa-lightbulb"></i> Mẹo
                                </button>
                                <button class="btn btn-outline-primary filter-btn" data-filter="Cẩm nang">
                                    <i class="fas fa-book"></i> Cẩm nang
                                </button>
                                <button class="btn btn-outline-primary filter-btn" data-filter="Điểm đến">
                                    <i class="fas fa-map-marker-alt"></i> Điểm đến
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Blog Posts Grid -->
                <div class="row g-4">
            @php
                $posts = [
                            [
                                'title' => '10 mẹo săn vé máy bay giá rẻ không thể bỏ qua',
                                'tag' => 'Mẹo du lịch',
                                'img' => 'https://via.placeholder.com/600x360/0EA5E9/ffffff?text=Tips',
                                'excerpt' => 'Cách đặt vé, thời điểm vàng, và bí quyết tối ưu chi phí cho mọi chuyến bay. Từ việc chọn thời gian đến việc sử dụng các công cụ so sánh giá...',
                                'author' => 'Admin',
                                'date' => '15/01/2025',
                                'read_time' => '5 phút',
                                'views' => '1.2K'
                            ],
                            [
                                'title' => 'Checklist chuẩn bị hành lý thông minh cho mọi chuyến đi',
                                'tag' => 'Cẩm nang',
                                'img' => 'https://via.placeholder.com/600x360/06B6D4/ffffff?text=Checklist',
                                'excerpt' => 'Mang đủ nhưng gọn nhẹ: danh sách vật dụng cần thiết cho mọi chuyến đi. Từ quần áo đến đồ dùng cá nhân, không bỏ sót gì...',
                                'author' => 'Admin',
                                'date' => '12/01/2025',
                                'read_time' => '7 phút',
                                'views' => '980'
                            ],
                            [
                                'title' => 'Top 5 điểm đến biển đẹp nhất mùa hè 2025',
                                'tag' => 'Điểm đến',
                                'img' => 'https://via.placeholder.com/600x360/0284C7/ffffff?text=Beach',
                                'excerpt' => 'Phú Quốc, Nha Trang, Quy Nhơn và những bãi biển không thể bỏ lỡ. Khám phá vẻ đẹp thiên nhiên và trải nghiệm độc đáo...',
                                'author' => 'Admin',
                                'date' => '10/01/2025',
                                'read_time' => '6 phút',
                                'views' => '1.5K'
                            ],
                            [
                                'title' => 'Hướng dẫn du lịch bụi an toàn cho người mới bắt đầu',
                                'tag' => 'Cẩm nang',
                                'img' => 'https://via.placeholder.com/600x360/10B981/ffffff?text=Backpack',
                                'excerpt' => 'Những điều cần biết khi du lịch bụi lần đầu. Từ việc lập kế hoạch đến cách ứng phó với tình huống bất ngờ...',
                                'author' => 'Admin',
                                'date' => '08/01/2025',
                                'read_time' => '8 phút',
                                'views' => '2.1K'
                            ],
                            [
                                'title' => 'Khám phá ẩm thực đường phố Việt Nam',
                                'tag' => 'Điểm đến',
                                'img' => 'https://via.placeholder.com/600x360/F59E0B/ffffff?text=Food',
                                'excerpt' => 'Hành trình khám phá những món ăn đường phố ngon nhất từ Bắc vào Nam. Từ phở Hà Nội đến bún bò Huế...',
                                'author' => 'Admin',
                                'date' => '05/01/2025',
                                'read_time' => '9 phút',
                                'views' => '1.8K'
                            ],
                            [
                                'title' => 'Cách chụp ảnh du lịch đẹp như nhiếp ảnh gia',
                                'tag' => 'Mẹo du lịch',
                                'img' => 'https://via.placeholder.com/600x360/EF4444/ffffff?text=Photo',
                                'excerpt' => 'Bí quyết chụp ảnh du lịch đẹp với điện thoại. Từ góc chụp đến ánh sáng, tất cả những gì bạn cần biết...',
                                'author' => 'Admin',
                                'date' => '03/01/2025',
                                'read_time' => '6 phút',
                                'views' => '1.3K'
                            ]
                ];
            @endphp

                    @foreach($posts as $index => $post)
                    <div class="col-lg-6 mb-4 slide-up blog-card" data-tag="{{ $post['tag'] }}" style="animation-delay: {{ $index * 0.1 }}s">
                        <article class="blog-card-modern">
                            <div class="blog-image">
                                <img src="{{ $post['img'] }}" alt="{{ $post['title'] }}" loading="lazy">
                                <div class="blog-overlay">
                                    <div class="blog-category">
                                        <span class="badge bg-primary">{{ $post['tag'] }}</span>
                                    </div>
                                    <div class="blog-actions">
                                        <button class="btn btn-sm btn-light wishlist-btn" title="Lưu bài viết">
                                            <i class="fas fa-bookmark"></i>
                                        </button>
                                        <button class="btn btn-sm btn-light share-btn" title="Chia sẻ">
                                            <i class="fas fa-share-alt"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="blog-content">
                                <div class="blog-meta">
                                    <div class="blog-author">
                                        <i class="fas fa-user-circle text-primary me-1"></i>
                                        <span>{{ $post['author'] }}</span>
                                    </div>
                                    <div class="blog-date">
                                        <i class="fas fa-calendar-alt text-muted me-1"></i>
                                        <span>{{ $post['date'] }}</span>
                                    </div>
                                </div>
                                <h3 class="blog-title">
                                    <a href="{{ route('blog.show', Str::slug($post['title'])) }}">{{ $post['title'] }}</a>
                                </h3>
                                <p class="blog-excerpt">{{ $post['excerpt'] }}</p>
                                <div class="blog-footer">
                                    <div class="blog-stats">
                                        <span class="stat-item">
                                            <i class="fas fa-clock text-muted me-1"></i>
                                            {{ $post['read_time'] }}
                                        </span>
                                        <span class="stat-item">
                                            <i class="fas fa-eye text-muted me-1"></i>
                                            {{ $post['views'] }}
                                        </span>
                                    </div>
                                    <a href="{{ route('blog.show', Str::slug($post['title'])) }}" class="btn btn-primary btn-sm">
                                        Đọc tiếp <i class="fas fa-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </article>
                    </div>
                    @endforeach
                </div>

                <!-- Load More -->
                <div class="text-center mt-5">
                    <button class="btn btn-outline-primary btn-lg load-more-btn">
                        <i class="fas fa-plus-circle"></i> Tải thêm bài viết
                    </button>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <div class="blog-sidebar">
                    <!-- Popular Posts -->
                    <div class="sidebar-widget">
                        <h4 class="widget-title">
                            <i class="fas fa-fire text-warning"></i> Bài viết hot
                        </h4>
                        <div class="popular-posts">
                            <div class="popular-post">
                                <div class="post-image">
                                    <img src="https://via.placeholder.com/80x60/0EA5E9/ffffff?text=1" alt="Post 1">
                                </div>
                                <div class="post-content">
                                    <h6><a href="#">10 mẹo săn vé máy bay giá rẻ</a></h6>
                                    <div class="post-meta">
                                        <span><i class="fas fa-eye"></i> 1.2K</span>
                                        <span><i class="fas fa-calendar"></i> 15/01</span>
                                    </div>
                                </div>
                            </div>
                            <div class="popular-post">
                                <div class="post-image">
                                    <img src="https://via.placeholder.com/80x60/06B6D4/ffffff?text=2" alt="Post 2">
                                </div>
                                <div class="post-content">
                                    <h6><a href="#">Checklist hành lý thông minh</a></h6>
                                    <div class="post-meta">
                                        <span><i class="fas fa-eye"></i> 980</span>
                                        <span><i class="fas fa-calendar"></i> 12/01</span>
                                    </div>
                                </div>
                            </div>
                            <div class="popular-post">
                                <div class="post-image">
                                    <img src="https://via.placeholder.com/80x60/0284C7/ffffff?text=3" alt="Post 3">
                                </div>
                                <div class="post-content">
                                    <h6><a href="#">Top 5 điểm đến biển đẹp</a></h6>
                                    <div class="post-meta">
                                        <span><i class="fas fa-eye"></i> 1.5K</span>
                                        <span><i class="fas fa-calendar"></i> 10/01</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Categories -->
                    <div class="sidebar-widget">
                        <h4 class="widget-title">
                            <i class="fas fa-tags text-primary"></i> Chuyên mục
                        </h4>
                        <div class="categories-list">
                            <a href="#" class="category-item">
                                <span class="category-name">Mẹo du lịch</span>
                                <span class="category-count">15</span>
                            </a>
                            <a href="#" class="category-item">
                                <span class="category-name">Cẩm nang</span>
                                <span class="category-count">12</span>
                            </a>
                            <a href="#" class="category-item">
                                <span class="category-name">Điểm đến</span>
                                <span class="category-count">18</span>
                            </a>
                            <a href="#" class="category-item">
                                <span class="category-name">Ẩm thực</span>
                                <span class="category-count">8</span>
                            </a>
                            <a href="#" class="category-item">
                                <span class="category-name">Kinh nghiệm</span>
                                <span class="category-count">10</span>
                            </a>
                        </div>
                    </div>

                    <!-- Newsletter -->
                    <div class="sidebar-widget newsletter-widget">
                        <h4 class="widget-title">
                            <i class="fas fa-envelope text-success"></i> Đăng ký nhận tin
                        </h4>
                        <p class="newsletter-text">Nhận bài viết mới nhất qua email</p>
                        <form class="newsletter-form">
                            <div class="input-group">
                                <input type="email" class="form-control" placeholder="Email của bạn">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fas fa-paper-plane"></i>
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Tags -->
                    <div class="sidebar-widget">
                        <h4 class="widget-title">
                            <i class="fas fa-hashtag text-info"></i> Tags phổ biến
                        </h4>
                        <div class="tags-cloud">
                            <a href="#" class="tag">du lịch</a>
                            <a href="#" class="tag">mẹo</a>
                            <a href="#" class="tag">cẩm nang</a>
                            <a href="#" class="tag">điểm đến</a>
                            <a href="#" class="tag">ẩm thực</a>
                            <a href="#" class="tag">kinh nghiệm</a>
                            <a href="#" class="tag">tiết kiệm</a>
                            <a href="#" class="tag">hành lý</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filter functionality
    const buttons = document.querySelectorAll('.filter-btn');
    const cards = document.querySelectorAll('.blog-card');
    
    buttons.forEach(btn => btn.addEventListener('click', function() {
        buttons.forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        const tag = this.dataset.filter;
        
        cards.forEach((card, index) => {
            const match = tag === 'all' || card.dataset.tag === tag;
            if (match) {
                card.style.display = '';
                card.style.animation = `fadeInUp 0.6s ease ${index * 0.1}s both`;
            } else {
                card.style.display = 'none';
            }
        });
    }));
    
    // Wishlist functionality
    document.querySelectorAll('.wishlist-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const icon = this.querySelector('i');
            if (icon.classList.contains('fas')) {
                icon.classList.remove('fas');
                icon.classList.add('far');
                this.style.color = '#ff6b6b';
            } else {
                icon.classList.remove('far');
                icon.classList.add('fas');
                this.style.color = '#ff6b6b';
            }
            showToast('Đã lưu bài viết!', 'success');
        });
    });
    
    // Share functionality
    document.querySelectorAll('.share-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            if (navigator.share) {
                navigator.share({
                    title: 'Blog du lịch - Tour365',
                    text: 'Khám phá những bài viết du lịch hữu ích',
                    url: window.location.href
                });
            } else {
                // Fallback: copy to clipboard
                navigator.clipboard.writeText(window.location.href);
                showToast('Đã copy link!', 'info');
            }
        });
    });
    
    // Load more functionality
    const loadMoreBtn = document.querySelector('.load-more-btn');
    if (loadMoreBtn) {
        loadMoreBtn.addEventListener('click', function() {
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang tải...';
            this.disabled = true;
            
            // Simulate loading
            setTimeout(() => {
                this.innerHTML = '<i class="fas fa-plus-circle"></i> Tải thêm bài viết';
                this.disabled = false;
                showToast('Đã tải thêm bài viết!', 'success');
            }, 2000);
        });
    }
    
    // Newsletter form
    const newsletterForm = document.querySelector('.newsletter-form');
    if (newsletterForm) {
        newsletterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const email = this.querySelector('input[type="email"]').value;
            if (email) {
                showToast('Đã đăng ký nhận tin thành công!', 'success');
                this.reset();
            }
        });
    }
    
    // Toast notification function
    function showToast(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `toast-notification toast-${type}`;
        toast.innerHTML = `
            <div class="toast-content">
                <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'info' ? 'info-circle' : 'exclamation-circle'}"></i>
                <span>${message}</span>
            </div>
        `;
        
        toast.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: ${type === 'success' ? '#10B981' : type === 'info' ? '#3B82F6' : '#F59E0B'};
            color: white;
            padding: 15px 20px;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            z-index: 9999;
            transform: translateX(100%);
            transition: transform 0.3s ease;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            font-size: 14px;
            font-weight: 500;
        `;
        
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.style.transform = 'translateX(0)';
        }, 100);
        
        setTimeout(() => {
            toast.style.transform = 'translateX(100%)';
            setTimeout(() => {
                if (document.body.contains(toast)) {
                    document.body.removeChild(toast);
                }
            }, 300);
        }, 3000);
    }
    
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
    
    // Search box focus effect
    const searchInput = document.querySelector('.hero-search input');
    if (searchInput) {
        searchInput.addEventListener('focus', function() {
            this.parentElement.parentElement.style.transform = 'scale(1.02)';
        });
        
        searchInput.addEventListener('blur', function() {
            this.parentElement.parentElement.style.transform = 'scale(1)';
        });
    }
});
</script>
@endsection

@section('styles')
<style>
/* Blog Hero Section */
.blog-hero {
    position: relative;
    min-height: 60vh;
    display: flex;
    align-items: center;
    background: linear-gradient(135deg, #0EA5E9 0%, #38BDF8 50%, #06B6D4 100%);
    overflow: hidden;
}

.hero-background {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 1;
}

.hero-pattern {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000"><polygon fill="rgba(255,255,255,0.1)" points="0,1000 1000,0 1000,1000"/></svg>');
    background-size: cover;
    animation: float 20s ease-in-out infinite;
}

@keyframes float {
    0%, 100% { transform: translateY(0px) rotate(0deg); }
    50% { transform: translateY(-20px) rotate(1deg); }
}

.hero-content {
    position: relative;
    z-index: 2;
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

.hero-stats {
    animation: fadeInUp 0.8s ease 0.8s both;
}

.gradient-text {
    background: linear-gradient(45deg, #FFD700, #FFA500);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.search-box {
    background: rgba(255,255,255,0.95);
    border-radius: 50px;
    padding: 8px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    backdrop-filter: blur(10px);
    transition: transform 0.3s ease;
}

.search-box:hover {
    transform: scale(1.02);
}

.stat-item {
    padding: 10px;
}

.stat-number {
    font-size: 2rem;
    line-height: 1;
}

.stat-label {
    font-size: 0.9rem;
    opacity: 0.8;
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

/* Blog Filters */
.blog-filters {
    background: white;
    padding: 30px;
    border-radius: 20px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    margin-bottom: 40px;
}

.filter-title h2 {
    color: #1F2937;
    font-weight: 700;
}

.filter-buttons .btn-group .btn {
    border-radius: 25px;
    margin: 0 5px;
    transition: all 0.3s ease;
}

.filter-buttons .btn-group .btn.active {
    background: #0EA5E9;
    border-color: #0EA5E9;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(14,165,233,0.3);
}

/* Blog Cards */
.blog-card-modern {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    transition: all 0.3s ease;
    height: 100%;
}

.blog-card-modern:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.15);
}

.blog-image {
    position: relative;
    overflow: hidden;
    height: 200px;
}

.blog-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.blog-card-modern:hover .blog-image img {
    transform: scale(1.1);
}

.blog-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(45deg, rgba(0,0,0,0.3), transparent);
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    padding: 15px;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.blog-card-modern:hover .blog-overlay {
    opacity: 1;
}

.blog-category .badge {
    font-size: 0.8rem;
    padding: 6px 12px;
    border-radius: 15px;
}

.blog-actions {
    display: flex;
    gap: 8px;
}

.blog-actions .btn {
    border-radius: 50%;
    width: 35px;
    height: 35px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.blog-actions .btn:hover {
    transform: scale(1.1);
}

.blog-content {
    padding: 25px;
}

.blog-meta {
    display: flex;
    justify-content: space-between;
    margin-bottom: 15px;
    font-size: 0.9rem;
    color: #6B7280;
}

.blog-title {
    margin-bottom: 15px;
    line-height: 1.4;
}

.blog-title a {
    color: #1F2937;
    text-decoration: none;
    font-weight: 600;
    transition: color 0.3s ease;
}

.blog-title a:hover {
    color: #0EA5E9;
}

.blog-excerpt {
    color: #6B7280;
    margin-bottom: 20px;
    line-height: 1.6;
}

.blog-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.blog-stats {
    display: flex;
    gap: 15px;
}

.stat-item {
    font-size: 0.85rem;
    color: #6B7280;
}

/* Sidebar */
.blog-sidebar {
    position: sticky;
    top: 100px;
}

.sidebar-widget {
    background: white;
    border-radius: 15px;
    padding: 25px;
    margin-bottom: 30px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    transition: transform 0.3s ease;
}

.sidebar-widget:hover {
    transform: translateY(-5px);
}

.widget-title {
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 20px;
    color: #1F2937;
    display: flex;
    align-items: center;
    gap: 8px;
}

/* Popular Posts */
.popular-posts {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.popular-post {
    display: flex;
    gap: 12px;
    padding: 10px;
    border-radius: 10px;
    transition: background 0.3s ease;
}

.popular-post:hover {
    background: #F8FAFC;
}

.post-image {
    flex-shrink: 0;
}

.post-image img {
    width: 60px;
    height: 45px;
    object-fit: cover;
    border-radius: 8px;
}

.post-content h6 {
    margin-bottom: 5px;
    line-height: 1.3;
}

.post-content h6 a {
    color: #1F2937;
    text-decoration: none;
    font-size: 0.9rem;
    font-weight: 500;
    transition: color 0.3s ease;
}

.post-content h6 a:hover {
    color: #0EA5E9;
}

.post-meta {
    display: flex;
    gap: 10px;
    font-size: 0.8rem;
    color: #6B7280;
}

/* Categories */
.categories-list {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.category-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 15px;
    border-radius: 10px;
    text-decoration: none;
    color: #1F2937;
    transition: all 0.3s ease;
}

.category-item:hover {
    background: #F8FAFC;
    color: #0EA5E9;
    text-decoration: none;
    transform: translateX(5px);
}

.category-name {
    font-weight: 500;
}

.category-count {
    background: #E5E7EB;
    color: #6B7280;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 0.8rem;
    font-weight: 500;
}

/* Newsletter */
.newsletter-widget {
    background: linear-gradient(135deg, #0EA5E9, #38BDF8);
    color: white;
}

.newsletter-widget .widget-title {
    color: white;
}

.newsletter-text {
    color: rgba(255,255,255,0.9);
    margin-bottom: 20px;
}

.newsletter-form .input-group {
    border-radius: 25px;
    overflow: hidden;
}

.newsletter-form .form-control {
    border: none;
    border-radius: 25px 0 0 25px;
    padding: 12px 20px;
}

.newsletter-form .btn {
    border-radius: 0 25px 25px 0;
    border: none;
    background: rgba(255,255,255,0.2);
    color: white;
    transition: background 0.3s ease;
}

.newsletter-form .btn:hover {
    background: rgba(255,255,255,0.3);
    color: white;
}

/* Tags */
.tags-cloud {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.tag {
    background: #F3F4F6;
    color: #6B7280;
    padding: 6px 12px;
    border-radius: 15px;
    text-decoration: none;
    font-size: 0.85rem;
    transition: all 0.3s ease;
}

.tag:hover {
    background: #0EA5E9;
    color: white;
    text-decoration: none;
    transform: translateY(-2px);
}

/* Load More Button */
.load-more-btn {
    border-radius: 25px;
    padding: 12px 30px;
    transition: all 0.3s ease;
}

.load-more-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(14,165,233,0.3);
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

/* Responsive Design */
@media (max-width: 768px) {
    .blog-hero {
        min-height: 50vh;
        padding: 40px 0;
    }
    
    .display-3 {
        font-size: 2.5rem;
    }
    
    .blog-filters {
        padding: 20px;
    }
    
    .filter-buttons .btn-group {
        flex-direction: column;
        width: 100%;
    }
    
    .filter-buttons .btn-group .btn {
        margin: 2px 0;
        border-radius: 10px;
    }
    
    .blog-sidebar {
        position: static;
        margin-top: 40px;
    }
    
    .hero-search .input-group {
        flex-direction: column;
    }
    
    .hero-search .input-group-text {
        border-radius: 25px 25px 0 0;
    }
    
    .hero-search .form-control {
        border-radius: 0;
    }
    
    .hero-search .btn {
        border-radius: 0 0 25px 25px;
    }
}

@media (max-width: 576px) {
    .blog-content {
        padding: 20px;
    }
    
    .blog-footer {
        flex-direction: column;
        gap: 15px;
        align-items: flex-start;
    }
    
    .blog-stats {
        flex-direction: column;
        gap: 8px;
    }
    
    .popular-post {
        flex-direction: column;
        text-align: center;
    }
    
    .post-image {
        align-self: center;
    }
}

/* Dark mode support */
body.dark .blog-card-modern,
body.dark .sidebar-widget {
    background: #1F2937;
    color: #E5E7EB;
}

body.dark .blog-title a,
body.dark .post-content h6 a,
body.dark .category-item {
    color: #E5E7EB;
}

body.dark .blog-title a:hover,
body.dark .post-content h6 a:hover,
body.dark .category-item:hover {
    color: #38BDF8;
}

body.dark .blog-filters {
    background: #1F2937;
}

body.dark .filter-title h2 {
    color: #E5E7EB;
}

body.dark .popular-post:hover,
body.dark .category-item:hover {
    background: #374151;
}

body.dark .tag {
    background: #374151;
    color: #9CA3AF;
}

body.dark .tag:hover {
    background: #38BDF8;
    color: #1F2937;
}
</style>
@endsection



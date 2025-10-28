@php
    $banners = \App\Models\Banner::currentlyActive()
        ->orderBy('sort_order')
        ->get();
@endphp

@if($banners->count() > 0)
<div class="banner-slider-container">
    <div id="bannerCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
            @foreach($banners as $index => $banner)
                <button type="button" 
                        data-bs-target="#bannerCarousel" 
                        data-bs-slide-to="{{ $index }}" 
                        class="{{ $index === 0 ? 'active' : '' }}"
                        aria-current="{{ $index === 0 ? 'true' : 'false' }}"
                        aria-label="Slide {{ $index + 1 }}"></button>
            @endforeach
        </div>
        
        <div class="carousel-inner">
            @foreach($banners as $index => $banner)
                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                    <div class="banner-slide">
                        <img src="{{ asset($banner->image_url) }}" 
                             class="d-block w-100 banner-image" 
                             alt="{{ $banner->title }}">
                        <div class="banner-overlay"></div>
                        <div class="banner-content">
                            <div class="container">
                                <div class="row align-items-center min-vh-50">
                                    <div class="col-lg-8">
                                        <div class="banner-text">
                                            <h1 class="banner-title">{{ $banner->title }}</h1>
                                            @if($banner->description)
                                                <p class="banner-description">{{ $banner->description }}</p>
                                            @endif
                                            @if($banner->link_url)
                                                <a href="{{ $banner->link_url }}" 
                                                   class="btn btn-warning btn-lg banner-btn"
                                                   onclick="trackBannerClick({{ $banner->id }})">
                                                    Xem chi tiết <i class="fas fa-arrow-right ms-2"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <button class="carousel-control-prev" type="button" data-bs-target="#bannerCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#bannerCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
</div>

<style>
.banner-slider-container {
    position: relative;
    margin-bottom: 2rem;
}

.banner-slide {
    position: relative;
    height: 60vh;
    min-height: 400px;
    overflow: hidden;
}

.banner-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.banner-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, rgba(0,0,0,0.4) 0%, rgba(0,0,0,0.2) 100%);
    z-index: 1;
}

.banner-content {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 2;
    display: flex;
    align-items: center;
}

.banner-text {
    color: white;
}

.banner-title {
    font-size: 3rem;
    font-weight: 700;
    margin-bottom: 1rem;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
}

.banner-description {
    font-size: 1.2rem;
    margin-bottom: 2rem;
    text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
}

.banner-btn {
    border-radius: 50px;
    padding: 12px 30px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
    transition: all 0.3s ease;
}

.banner-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.2);
}

.carousel-indicators {
    bottom: 20px;
}

.carousel-indicators button {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    margin: 0 5px;
}

.carousel-control-prev,
.carousel-control-next {
    width: 50px;
    height: 50px;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(255,255,255,0.2);
    border-radius: 50%;
    backdrop-filter: blur(10px);
}

.carousel-control-prev {
    left: 20px;
}

.carousel-control-next {
    right: 20px;
}

@media (max-width: 768px) {
    .banner-slide {
        height: 50vh;
        min-height: 300px;
    }
    
    .banner-title {
        font-size: 2rem;
    }
    
    .banner-description {
        font-size: 1rem;
    }
}
</style>

<script>
function trackBannerClick(bannerId) {
    fetch(`/api/banners/${bannerId}/track-click`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    }).catch(error => console.log('Banner click tracking failed:', error));
}

// Track banner views when they become visible
document.addEventListener('DOMContentLoaded', function() {
    const carousel = document.getElementById('bannerCarousel');
    if (carousel) {
        carousel.addEventListener('slide.bs.carousel', function (event) {
            const activeSlide = event.target.querySelector('.carousel-item.active');
            const bannerId = activeSlide.querySelector('[onclick*="trackBannerClick"]');
            if (bannerId) {
                const id = bannerId.onclick.toString().match(/\d+/)[0];
                fetch(`/api/banners/${id}/track-view`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                }).catch(error => console.log('Banner view tracking failed:', error));
            }
        });
    }
});
</script>
@endif

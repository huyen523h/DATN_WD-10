@props(['position' => 'middle', 'type' => null])

@php
    $query = \App\Models\Banner::currentlyActive()->byPosition($position);
    
    if ($type) {
        $query->byType($type);
    }
    
    $banners = $query->orderBy('sort_order')->get();
@endphp

@if($banners->count() > 0)
<div class="banner-section banner-{{ $position }}">
    <div class="container">
        @if($position === 'top')
            <div class="banner-top">
                @foreach($banners as $banner)
                    <div class="banner-item">
                        <a href="{{ $banner->link_url ?: '#' }}" 
                           class="banner-link"
                           onclick="trackBannerClick({{ $banner->id }})">
                            <img src="{{ asset($banner->image_url) }}" 
                                 alt="{{ $banner->title }}" 
                                 class="banner-img">
                            <div class="banner-info">
                                <h3 class="banner-title">{{ $banner->title }}</h3>
                                @if($banner->description)
                                    <p class="banner-desc">{{ $banner->description }}</p>
                                @endif
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        @elseif($position === 'middle')
            <div class="banner-middle">
                <div class="row">
                    @foreach($banners as $banner)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="banner-card">
                                <a href="{{ $banner->link_url ?: '#' }}" 
                                   class="banner-link"
                                   onclick="trackBannerClick({{ $banner->id }})">
                                    <div class="banner-card-img">
                                        <img src="{{ asset($banner->image_url) }}" 
                                             alt="{{ $banner->title }}" 
                                             class="img-fluid">
                                        <div class="banner-card-overlay">
                                            <div class="banner-card-content">
                                                <h4 class="banner-card-title">{{ $banner->title }}</h4>
                                                @if($banner->description)
                                                    <p class="banner-card-desc">{{ $banner->description }}</p>
                                                @endif
                                                <span class="banner-card-btn">
                                                    Xem chi tiết <i class="fas fa-arrow-right"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @elseif($position === 'bottom')
            <div class="banner-bottom">
                @foreach($banners as $banner)
                    <div class="banner-bottom-item">
                        <a href="{{ $banner->link_url ?: '#' }}" 
                           class="banner-bottom-link"
                           onclick="trackBannerClick({{ $banner->id }})">
                            <img src="{{ asset($banner->image_url) }}" 
                                 alt="{{ $banner->title }}" 
                                 class="banner-bottom-img">
                            <div class="banner-bottom-content">
                                <h3 class="banner-bottom-title">{{ $banner->title }}</h3>
                                @if($banner->description)
                                    <p class="banner-bottom-desc">{{ $banner->description }}</p>
                                @endif
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        @elseif($position === 'sidebar')
            <div class="banner-sidebar">
                @foreach($banners as $banner)
                    <div class="banner-sidebar-item mb-3">
                        <a href="{{ $banner->link_url ?: '#' }}" 
                           class="banner-sidebar-link"
                           onclick="trackBannerClick({{ $banner->id }})">
                            <img src="{{ asset($banner->image_url) }}" 
                                 alt="{{ $banner->title }}" 
                                 class="img-fluid">
                            <div class="banner-sidebar-content">
                                <h5 class="banner-sidebar-title">{{ $banner->title }}</h5>
                                @if($banner->description)
                                    <p class="banner-sidebar-desc">{{ Str::limit($banner->description, 80) }}</p>
                                @endif
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

<style>
/* Banner Top */
.banner-top {
    display: flex;
    gap: 20px;
    overflow-x: auto;
    padding: 20px 0;
}

.banner-item {
    flex: 0 0 300px;
}

.banner-link {
    display: block;
    text-decoration: none;
    color: inherit;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.banner-link:hover {
    transform: translateY(-5px);
    text-decoration: none;
    color: inherit;
}

.banner-img {
    width: 100%;
    height: 150px;
    object-fit: cover;
}

.banner-info {
    padding: 15px;
    background: white;
}

.banner-title {
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 8px;
    color: #333;
}

.banner-desc {
    font-size: 0.9rem;
    color: #666;
    margin: 0;
}

/* Banner Middle */
.banner-card {
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.banner-card:hover {
    transform: translateY(-8px);
}

.banner-card-img {
    position: relative;
    height: 200px;
    overflow: hidden;
}

.banner-card-img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.banner-card-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0.3) 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.banner-card:hover .banner-card-overlay {
    opacity: 1;
}

.banner-card:hover .banner-card-img img {
    transform: scale(1.1);
}

.banner-card-content {
    text-align: center;
    color: white;
    padding: 20px;
}

.banner-card-title {
    font-size: 1.3rem;
    font-weight: 600;
    margin-bottom: 10px;
}

.banner-card-desc {
    font-size: 0.9rem;
    margin-bottom: 15px;
    opacity: 0.9;
}

.banner-card-btn {
    display: inline-block;
    background: #ffc107;
    color: #000;
    padding: 8px 20px;
    border-radius: 25px;
    font-weight: 600;
    font-size: 0.9rem;
    transition: all 0.3s ease;
}

.banner-card-btn:hover {
    background: #ffb300;
    transform: scale(1.05);
}

/* Banner Bottom */
.banner-bottom {
    display: flex;
    gap: 20px;
    padding: 30px 0;
}

.banner-bottom-item {
    flex: 1;
}

.banner-bottom-link {
    display: flex;
    align-items: center;
    text-decoration: none;
    color: inherit;
    background: white;
    border-radius: 15px;
    padding: 20px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.banner-bottom-link:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    text-decoration: none;
    color: inherit;
}

.banner-bottom-img {
    width: 120px;
    height: 80px;
    object-fit: cover;
    border-radius: 10px;
    margin-right: 20px;
}

.banner-bottom-title {
    font-size: 1.2rem;
    font-weight: 600;
    margin-bottom: 8px;
    color: #333;
}

.banner-bottom-desc {
    font-size: 0.9rem;
    color: #666;
    margin: 0;
}

/* Banner Sidebar */
.banner-sidebar-item {
    background: white;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.banner-sidebar-item:hover {
    transform: translateY(-3px);
}

.banner-sidebar-link {
    display: block;
    text-decoration: none;
    color: inherit;
}

.banner-sidebar-link:hover {
    text-decoration: none;
    color: inherit;
}

.banner-sidebar-content {
    padding: 15px;
}

.banner-sidebar-title {
    font-size: 1rem;
    font-weight: 600;
    margin-bottom: 8px;
    color: #333;
}

.banner-sidebar-desc {
    font-size: 0.85rem;
    color: #666;
    margin: 0;
}

/* Responsive */
@media (max-width: 768px) {
    .banner-top {
        flex-direction: column;
    }
    
    .banner-item {
        flex: 1;
    }
    
    .banner-bottom {
        flex-direction: column;
    }
    
    .banner-bottom-link {
        flex-direction: column;
        text-align: center;
    }
    
    .banner-bottom-img {
        margin-right: 0;
        margin-bottom: 15px;
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
</script>
@endif

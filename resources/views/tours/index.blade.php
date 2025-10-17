@extends('layouts.app')

@section('title', 'Tours - Tour365')
@section('description', 'Khám phá các tour du lịch hấp dẫn với Tour365')

@section('content')
    <!-- Hero Section -->
    <section class="hero-section" style="background: linear-gradient(135deg, #0EA5E9 0%, #38BDF8 100%);">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold mb-4">Khám phá thế giới cùng Tour365</h1>
                    <p class="lead mb-4">Trải nghiệm những chuyến du lịch tuyệt vời với dịch vụ chuyên nghiệp và giá cả hợp
                        lý.</p>
                    <a href="#tours" class="btn btn-light text-primary btn-lg" style="color:#0EA5E9 !important;">
                        <i class="fas fa-search"></i> Xem tours ngay
                    </a>
                </div>
                <div class="col-lg-6">
                    <img src="https://via.placeholder.com/600x400/667eea/ffffff?text=Travel+with+Tour365" alt="Travel"
                        class="img-fluid rounded shadow">
                </div>
            </div>
        </div>
    </section>

    <!-- Smart Search & Filter Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
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
                                <span>Bộ lọc nâng cao</span>
                            </button>
                            
                            <!-- Advanced Search Panel -->
                            <div class="advanced-search-panel" id="advancedSearchPanel">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label">Danh mục</label>
                                        <select name="category_id" class="form-select">
                                            <option value="">Tất cả danh mục</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Giá từ</label>
                                        <div class="input-group">
                                            <input type="number" name="min_price" class="form-control" 
                                                   placeholder="0" value="{{ request('min_price', 0) }}">
                                            <span class="input-group-text">VNĐ</span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Giá đến</label>
                                        <div class="input-group">
                                            <input type="number" name="max_price" class="form-control" 
                                                   placeholder="10,000,000" value="{{ request('max_price', 10000000) }}">
                                            <span class="input-group-text">VNĐ</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <button type="submit" class="search-submit-btn">
                                <i class="fas fa-search"></i>
                                <span>Tìm kiếm tours</span>
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
            </div>
        </div>
    </section>

    <!-- Tours Section -->
    <section id="tours" class="py-5">
        <div class="container">
            <div class="row mb-4">
                <div class="col-12">
                    @if (request('search'))
                        <h2 class="text-center mb-4">
                            Kết quả tìm kiếm cho "{{ request('search') }}"
                        </h2>
                        <p class="text-center text-muted mb-3">
                            Tìm thấy {{ $tours->total() }} tour{{ $tours->total() > 1 ? 's' : '' }}
                        </p>
                        <div class="text-center">
                            <a href="{{ route('tours.index') }}" class="btn btn-outline-primary">
                                <i class="fas fa-times"></i> Xóa tìm kiếm
                            </a>
                        </div>
                    @else
                        <h2 class="text-center mb-4">Tours nổi bật</h2>
                        <p class="text-center text-muted">Khám phá {{ $tours->total() }} tour du lịch hấp dẫn</p>
                    @endif
                </div>
            </div>

            @if ($tours->count() > 0)
                <!-- Quick client-side filters -->
                <div class="d-flex justify-content-end align-items-center mb-3 gap-2">
                    <div class="small text-muted me-2">Lọc nhanh:</div>
                    <button class="btn btn-sm btn-outline-primary" id="filterDeal">Chỉ Deal</button>
                    <button class="btn btn-sm btn-outline-success" id="filterNew">Chỉ New</button>
                    <button class="btn btn-sm btn-outline-secondary" id="filterReset">Reset</button>
                </div>

                <div class="row">
                    @foreach ($tours as $tour)
                        @php
                            $isNew = isset($tour->created_at) && $tour->created_at->gt(now()->subDays(30));
                            $isDeal = $tour->price < 2000000;
                        @endphp
                        <div class="col-lg-4 col-md-6 mb-4 slide-up tour-item" data-new="{{ $isNew ? 1 : 0 }}"
                            data-deal="{{ $isDeal ? 1 : 0 }}">
                            <div class="card tour-card h-100">
                                <div class="position-relative">
                                    @if ($tour->images->count() > 0)
                                        <img src="{{ $tour->images->first()->image_url }}" class="card-img-top"
                                            alt="{{ $tour->title }}" loading="lazy"
                                            sizes="(min-width: 992px) 400px, 100vw"
                                            style="height: 250px; object-fit: cover;">
                                    @else
                                        <img src="https://via.placeholder.com/400x250/4F46E5/ffffff?text={{ urlencode($tour->title) }}"
                                            class="card-img-top" alt="{{ $tour->title }}" loading="lazy"
                                            sizes="(min-width: 992px) 400px, 100vw"
                                            style="height: 250px; object-fit: cover;">
                                    @endif
                                    <div class="position-absolute top-0 start-0 p-2">
                                        @if ($isNew)
                                            <span class="badge bg-success me-1">New</span>
                                        @endif
                                        @php
                                            $hasOld = isset($tour->old_price) && $tour->old_price > $tour->price;
                                            $discount = $hasOld
                                                ? round(100 - ($tour->price / $tour->old_price) * 100)
                                                : null;
                                        @endphp
                                        @if ($isDeal)
                                            <span class="badge"
                                                style="background: linear-gradient(45deg, #0EA5E9, #06B6D4);"
                                                data-bs-toggle="tooltip"
                                                title="Giá dưới 2.000.000đ – Deal tốt">Deal</span>
                                        @endif
                                        @if ($hasOld)
                                            <span class="badge bg-danger ms-1" data-bs-toggle="tooltip"
                                                title="Giảm {{ $discount }}%">-{{ $discount }}%</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="card-body d-flex flex-column">
                                    <div class="mb-2">
                                        @if ($tour->category)
                                            <span class="category-badge">{{ $tour->category->name }}</span>
                                        @endif
                                    </div>

                                    <h5 class="card-title">{{ $tour->title }}</h5>
                                    <p class="card-text text-muted flex-grow-1">
                                        {{ Str::limit($tour->description, 100) }}
                                    </p>

                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div>
                                            <small class="text-muted">
                                                <i class="fas fa-clock"></i> {{ $tour->duration_days }} ngày
                                            </small>
                                        </div>
                                        <div class="text-end">
                                            @if ($hasOld)
                                                <div class="text-muted small">
                                                    <s>{{ number_format($tour->old_price, 0, ',', '.') }}đ</s>
                                                </div>
                                            @endif
                                            <div class="price-badge"
                                                style="background: linear-gradient(45deg, #0EA5E9, #06B6D4);">
                                                {{ number_format($tour->price, 0, ',', '.') }}đ
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between">
                                        <a href="{{ route('tours.show', $tour) }}" class="btn btn-outline-primary">
                                            <i class="fas fa-eye"></i> Xem chi tiết
                                        </a>
                                        @auth
                                            <form action="{{ route('wishlists.store') }}" method="POST" class="d-inline">
                                                @csrf
                                                <input type="hidden" name="tour_id" value="{{ $tour->id }}">
                                                <button type="submit" class="btn btn-outline-danger">
                                                    <i class="fas fa-heart"></i> Yêu thích
                                                </button>
                                            </form>

                                            <a href="{{ route('bookings.create', ['tour_id' => $tour->id]) }}"
                                                class="btn btn-primary">
                                                <i class="fas fa-calendar-plus"></i> Đặt tour
                                            </a>
                                        @else
                                            <a href="{{ route('login') }}" class="btn btn-primary">
                                                <i class="fas fa-sign-in-alt"></i> Đăng nhập để đặt
                                            </a>
                                        @endauth
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="row">
                    <div class="col-12">
                        <nav aria-label="Tours pagination">
                            {{ $tours->links() }}
                        </nav>
                    </div>
                </div>
            @else
                <div class="row">
                    <div class="col-12 text-center">
                        <div class="py-5">
                            <i class="fas fa-search fa-3x text-muted mb-3"></i>
                            <h4>Không tìm thấy tour nào</h4>
                            <p class="text-muted">Hãy thử thay đổi bộ lọc tìm kiếm</p>
                            <a href="{{ route('tours.index') }}" class="btn btn-primary">
                                <i class="fas fa-refresh"></i> Xem tất cả tours
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row text-center">
                <div class="col-md-4 mb-4">
                    <div class="p-4">
                        <i class="fas fa-shield-alt fa-3x text-primary mb-3"></i>
                        <h5>An toàn tuyệt đối</h5>
                        <p class="text-muted">Đảm bảo an toàn cho khách hàng trong mọi chuyến đi</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="p-4">
                        <i class="fas fa-star fa-3x text-warning mb-3"></i>
                        <h5>Chất lượng cao</h5>
                        <p class="text-muted">Dịch vụ chuyên nghiệp với đội ngũ hướng dẫn viên giàu kinh nghiệm</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="p-4">
                        <i class="fas fa-heart fa-3x text-danger mb-3"></i>
                        <h5>Giá cả hợp lý</h5>
                        <p class="text-muted">Mức giá cạnh tranh với nhiều ưu đãi hấp dẫn</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        // Auto-submit form when category changes
        document.getElementById('category_id').addEventListener('change', function() {
            this.form.submit();
        });

        // Client-side quick filters (Deal/New)
        (function() {
            const items = document.querySelectorAll('.tour-item');
            const btnDeal = document.getElementById('filterDeal');
            const btnNew = document.getElementById('filterNew');
            const btnReset = document.getElementById('filterReset');
            if (!btnDeal || !btnNew || !btnReset) return;
            btnDeal.addEventListener('click', function() {
                items.forEach(el => el.style.display = el.dataset.deal === '1' ? '' : 'none');
            });
            btnNew.addEventListener('click', function() {
                items.forEach(el => el.style.display = el.dataset.new === '1' ? '' : 'none');
            });
            btnReset.addEventListener('click', function() {
                items.forEach(el => el.style.display = '');
            });
        })();
    </script>
@endsection

@section('styles')
<style>
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
    color: #64748b;
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
    background: linear-gradient(145deg, rgba(14,165,233,0.1), rgba(14,165,233,0.05));
    color: #0EA5E9;
    padding: 10px 18px;
    border-radius: 25px;
    text-decoration: none;
    font-size: 0.9rem;
    font-weight: 600;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    border: 1px solid rgba(14,165,233,0.2);
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
    background: linear-gradient(90deg, transparent, rgba(14,165,233,0.1), transparent);
    transition: left 0.6s ease;
}

.popular-tag:hover::before {
    left: 100%;
}

.popular-tag:hover {
    background: linear-gradient(145deg, #0EA5E9, #0284C7);
    color: white;
    text-decoration: none;
    transform: translateY(-3px) scale(1.05);
    box-shadow: 
        0 8px 25px rgba(14,165,233,0.3),
        inset 0 1px 0 rgba(255,255,255,0.2);
    border-color: rgba(14,165,233,0.5);
}

.popular-tag:active {
    transform: translateY(-1px) scale(1.02);
}

/* Responsive Design */
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
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Smart Search functionality
    const smartSearchInput = document.getElementById('smartSearchInput');
    const searchSuggestions = document.getElementById('searchSuggestions');
    const advancedSearchToggle = document.getElementById('advancedSearchToggle');
    const advancedSearchPanel = document.getElementById('advancedSearchPanel');
    
    // Sample suggestions data
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
});
</script>
@endsection

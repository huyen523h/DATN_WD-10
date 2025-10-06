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
                <p class="lead mb-4">Trải nghiệm những chuyến du lịch tuyệt vời với dịch vụ chuyên nghiệp và giá cả hợp lý.</p>
                <a href="#tours" class="btn btn-light text-primary btn-lg" style="color:#0EA5E9 !important;">
                    <i class="fas fa-search"></i> Xem tours ngay
                </a>
            </div>
            <div class="col-lg-6">
                <img src="https://via.placeholder.com/600x400/667eea/ffffff?text=Travel+with+Tour365" 
                     alt="Travel" class="img-fluid rounded shadow">
            </div>
        </div>
    </div>
</section>

<!-- Search & Filter Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <form method="GET" action="{{ route('tours.index') }}" class="row g-3">
                    <div class="col-md-4">
                        <label for="search" class="form-label">Tìm kiếm tour</label>
                        <input type="text" class="form-control" id="search" name="search" 
                               value="{{ request('search') }}" placeholder="Nhập tên tour...">
                    </div>
                    <div class="col-md-3">
                        <label for="category_id" class="form-label">Danh mục</label>
                        <select class="form-select" id="category_id" name="category_id">
                            <option value="">Tất cả danh mục</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" 
                                        {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="min_price" class="form-label">Giá từ</label>
                        <input type="number" class="form-control" id="min_price" name="min_price" 
                               value="{{ request('min_price') }}" placeholder="0">
                    </div>
                    <div class="col-md-2">
                        <label for="max_price" class="form-label">Đến</label>
                        <input type="number" class="form-control" id="max_price" name="max_price" 
                               value="{{ request('max_price') }}" placeholder="10000000">
                    </div>
                    <div class="col-md-1">
                        <label class="form-label">&nbsp;</label>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Tours Section -->
<section id="tours" class="py-5">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12">
                @if(request('search'))
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

        @if($tours->count() > 0)
            <!-- Quick client-side filters -->
            <div class="d-flex justify-content-end align-items-center mb-3 gap-2">
                <div class="small text-muted me-2">Lọc nhanh:</div>
                <button class="btn btn-sm btn-outline-primary" id="filterDeal">Chỉ Deal</button>
                <button class="btn btn-sm btn-outline-success" id="filterNew">Chỉ New</button>
                <button class="btn btn-sm btn-outline-secondary" id="filterReset">Reset</button>
            </div>

            <div class="row">
                @foreach($tours as $tour)
                    @php
                        $isNew = isset($tour->created_at) && $tour->created_at->gt(now()->subDays(30));
                        $isDeal = $tour->price < 2000000;
                    @endphp
                    <div class="col-lg-4 col-md-6 mb-4 slide-up tour-item" data-new="{{ $isNew ? 1 : 0 }}" data-deal="{{ $isDeal ? 1 : 0 }}">
                        <div class="card tour-card h-100">
                            <div class="position-relative">
                                @if($tour->images->count() > 0)
                                    <img src="{{ $tour->images->first()->image_url }}" 
                                         class="card-img-top" alt="{{ $tour->title }}" 
                                         loading="lazy" sizes="(min-width: 992px) 400px, 100vw"
                                         style="height: 250px; object-fit: cover;">
                                @else
                                    <img src="https://via.placeholder.com/400x250/4F46E5/ffffff?text={{ urlencode($tour->title) }}" 
                                         class="card-img-top" alt="{{ $tour->title }}" 
                                         loading="lazy" sizes="(min-width: 992px) 400px, 100vw"
                                         style="height: 250px; object-fit: cover;">
                                @endif
                                <div class="position-absolute top-0 start-0 p-2">
                                    @if($isNew)
                                        <span class="badge bg-success me-1">New</span>
                                    @endif
                                    @php
                                        $hasOld = isset($tour->old_price) && $tour->old_price > $tour->price;
                                        $discount = $hasOld ? round(100 - ($tour->price / $tour->old_price * 100)) : null;
                                    @endphp
                                    @if($isDeal)
                                        <span class="badge" style="background: linear-gradient(45deg, #0EA5E9, #06B6D4);" data-bs-toggle="tooltip" title="Giá dưới 2.000.000đ – Deal tốt">Deal</span>
                                    @endif
                                    @if($hasOld)
                                        <span class="badge bg-danger ms-1" data-bs-toggle="tooltip" title="Giảm {{ $discount }}%">-{{ $discount }}%</span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="card-body d-flex flex-column">
                                <div class="mb-2">
                                    @if($tour->category)
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
                                        @if($hasOld)
                                            <div class="text-muted small"><s>{{ number_format($tour->old_price, 0, ',', '.') }}đ</s></div>
                                        @endif
                                        <div class="price-badge" style="background: linear-gradient(45deg, #0EA5E9, #06B6D4);">
                                            {{ number_format($tour->price, 0, ',', '.') }}đ
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('tours.show', $tour) }}" class="btn btn-outline-primary">
                                        <i class="fas fa-eye"></i> Xem chi tiết
                                    </a>
                                    @auth
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
    (function(){
        const items = document.querySelectorAll('.tour-item');
        const btnDeal = document.getElementById('filterDeal');
        const btnNew = document.getElementById('filterNew');
        const btnReset = document.getElementById('filterReset');
        if (!btnDeal || !btnNew || !btnReset) return;
        btnDeal.addEventListener('click', function(){
            items.forEach(el => el.style.display = el.dataset.deal === '1' ? '' : 'none');
        });
        btnNew.addEventListener('click', function(){
            items.forEach(el => el.style.display = el.dataset.new === '1' ? '' : 'none');
        });
        btnReset.addEventListener('click', function(){
            items.forEach(el => el.style.display = '');
        });
    })();
</script>
@endsection

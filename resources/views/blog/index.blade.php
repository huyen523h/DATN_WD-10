@extends('layouts.app')

@section('title', 'Blog du lịch - Tour365')

@section('content')
<section class="py-5" style="background: linear-gradient(135deg, #0EA5E9 0%, #38BDF8 100%);">
    <div class="container text-center text-white">
        <h1 class="display-5 fw-bold mb-2">Blog du lịch</h1>
        <p class="lead mb-0">Mẹo, cẩm nang và kinh nghiệm cho mọi hành trình</p>
    </div>
    
</section>

<section class="py-5">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12 d-flex flex-wrap justify-content-between align-items-center">
                <h2 class="h3 mb-2 mb-md-0">Bài viết mới</h2>
                <div class="d-flex flex-wrap align-items-center gap-2">
                    <span class="small text-muted me-2">Lọc theo:</span>
                    <button class="btn btn-sm btn-outline-primary filter-btn active" data-filter="all">Tất cả</button>
                    <button class="btn btn-sm btn-outline-primary filter-btn" data-filter="Mẹo du lịch">Mẹo du lịch</button>
                    <button class="btn btn-sm btn-outline-primary filter-btn" data-filter="Cẩm nang">Cẩm nang</button>
                    <button class="btn btn-sm btn-outline-primary filter-btn" data-filter="Điểm đến">Điểm đến</button>
                    <a href="#" class="btn btn-outline-primary btn-sm ms-2">Theo dõi RSS</a>
                </div>
            </div>
        </div>

        <div class="row">
            @php
                $posts = [
                    ['title' => '10 mẹo săn vé máy bay giá rẻ', 'tag' => 'Mẹo du lịch', 'img' => 'https://via.placeholder.com/600x360/0EA5E9/ffffff?text=Tips', 'excerpt' => 'Cách đặt vé, thời điểm vàng, và bí quyết tối ưu chi phí...'],
                    ['title' => 'Checklist chuẩn bị hành lý thông minh', 'tag' => 'Cẩm nang', 'img' => 'https://via.placeholder.com/600x360/06B6D4/ffffff?text=Checklist', 'excerpt' => 'Mang đủ nhưng gọn nhẹ: danh sách vật dụng cần thiết cho mọi chuyến đi...'],
                    ['title' => 'Top 5 điểm đến biển đẹp mùa hè', 'tag' => 'Điểm đến', 'img' => 'https://via.placeholder.com/600x360/0284C7/ffffff?text=Beach', 'excerpt' => 'Phú Quốc, Nha Trang, Quy Nhơn và những bãi biển không thể bỏ lỡ...'],
                ];
            @endphp

            @foreach($posts as $post)
            <div class="col-lg-4 col-md-6 mb-4 slide-up blog-card" data-tag="{{ $post['tag'] }}">
                <div class="card border-0 shadow-sm h-100">
                    <img src="{{ $post['img'] }}" alt="{{ $post['title'] }}" class="card-img-top" loading="lazy" style="height:220px;object-fit:cover;">
                    <div class="card-body d-flex flex-column">
                        <div class="mb-2">
                            <span class="badge" style="background: linear-gradient(45deg, #0EA5E9, #06B6D4);">{{ $post['tag'] }}</span>
                        </div>
                        <h5 class="card-title">{{ $post['title'] }}</h5>
                        <p class="text-muted flex-grow-1">{{ $post['excerpt'] }}</p>
                        <a href="{{ route('blog.show', Str::slug($post['title'])) }}" class="btn btn-outline-primary mt-auto">
                            Đọc tiếp
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="text-center">
            <a href="#" class="btn btn-primary"><i class="fas fa-arrow-down"></i> Tải thêm</a>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const buttons = document.querySelectorAll('.filter-btn');
    const cards = document.querySelectorAll('.blog-card');
    buttons.forEach(btn => btn.addEventListener('click', function() {
        buttons.forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        const tag = this.dataset.filter;
        cards.forEach(card => {
            const match = tag === 'all' || card.dataset.tag === tag;
            card.style.display = match ? '' : 'none';
        });
    }));
});
</script>
@endsection



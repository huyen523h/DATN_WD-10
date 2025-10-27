@extends('layouts.app')

@section('title', ucwords(str_replace('-', ' ', $slug)) . ' - Blog - Tour365')

@section('content')
<section class="py-5" style="background: linear-gradient(135deg, #0EA5E9 0%, #38BDF8 100%);">
    <div class="container text-white">
        <ol class="breadcrumb mb-3">
            <li class="breadcrumb-item"><a class="text-white" href="{{ route('blog.index') }}">Blog</a></li>
            <li class="breadcrumb-item active text-white">{{ ucwords(str_replace('-', ' ', $slug)) }}</li>
        </ol>
        <h1 class="display-5 fw-bold">{{ ucwords(str_replace('-', ' ', $slug)) }}</h1>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <article class="prose">
                    <img src="https://via.placeholder.com/900x450/0EA5E9/ffffff?text=Blog+Cover" class="img-fluid rounded-3 shadow mb-4" alt="cover" loading="lazy">
                    <p class="text-muted">Đăng ngày {{ now()->format('d/m/Y') }} • 6 phút đọc • Chuyên mục Mẹo du lịch</p>
                    <h2>Giới thiệu</h2>
                    <p>Đây là bài viết minh hoạ cho trang chi tiết blog. Bạn có thể kết nối dữ liệu thật từ CSDL sau.</p>
                    <h3>Mẹo hữu ích</h3>
                    <ul>
                        <li>Đặt vé sớm để có giá tốt.</li>
                        <li>Chuẩn bị checklist hành lý thông minh.</li>
                        <li>Ưu tiên tour có lịch trình linh hoạt.</li>
                    </ul>
                    <p>Kết luận: luôn chủ động và chuẩn bị kỹ sẽ giúp chuyến đi của bạn trọn vẹn.</p>
                </article>
            </div>
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="mb-3">Bài viết liên quan</h5>
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2"><a href="#" class="text-decoration-none">10 mẹo săn vé máy bay giá rẻ</a></li>
                            <li class="mb-2"><a href="#" class="text-decoration-none">Checklist hành lý thông minh</a></li>
                            <li class="mb-2"><a href="#" class="text-decoration-none">Top điểm đến hè</a></li>
                        </ul>
                    </div>
                </div>
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="mb-3">Tags</h5>
                        <span class="badge bg-light text-dark me-1 mb-1">Mẹo</span>
                        <span class="badge bg-light text-dark me-1 mb-1">Cẩm nang</span>
                        <span class="badge bg-light text-dark me-1 mb-1">Điểm đến</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection



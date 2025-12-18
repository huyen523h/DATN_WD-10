@extends('layouts.app')

@section('title', '500 - Lỗi máy chủ')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 text-center">
            <div class="error-page">
                <div class="error-code mb-4">
                    <h1 class="display-1 fw-bold text-danger">500</h1>
                </div>
                <div class="error-message mb-4">
                    <h2 class="h3 mb-3">Lỗi máy chủ</h2>
                    <p class="lead text-muted">
                        Có lỗi xảy ra trên máy chủ. Vui lòng thử lại sau hoặc liên hệ với chúng tôi.
                    </p>
                </div>
                <div class="error-actions">
                    <a href="{{ route('tours.index') }}" class="btn btn-primary btn-lg me-3">
                        <i class="fas fa-home"></i> Về trang chủ
                    </a>
                    <button onclick="history.back()" class="btn btn-outline-primary btn-lg">
                        <i class="fas fa-arrow-left"></i> Quay lại
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
.error-page {
    padding: 60px 0;
}

.error-code h1 {
    font-size: 8rem;
    line-height: 1;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
}

@media (max-width: 768px) {
    .error-code h1 {
        font-size: 6rem;
    }
}
</style>
@endsection

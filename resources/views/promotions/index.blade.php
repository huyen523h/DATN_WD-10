@extends('layouts.app')

@section('title', 'Ưu đãi & Khuyến mãi - Tour365')

@section('content')
<section class="py-5" style="background: linear-gradient(135deg, #0EA5E9 0%, #38BDF8 100%);">
    <div class="container text-white text-center">
        <h1 class="display-5 fw-bold mb-2">Ưu đãi & Khuyến mãi</h1>
        <p class="lead mb-0">Săn deal du lịch giá tốt mỗi tuần</p>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="row g-4">
            @php
                $promos = [
                    ['title' => 'Giảm 20% tour biển mùa hè', 'badge' => 'Hot', 'color' => '#EF4444', 'desc' => 'Áp dụng cho tour Phú Quốc, Nha Trang, Quy Nhơn.', 'old' => 2500000, 'new' => 2000000],
                    ['title' => 'Combo 4 người tặng 1 vé trẻ em', 'badge' => 'Combo', 'color' => '#06B6D4', 'desc' => 'Áp dụng tất cả tour nội địa tháng này.', 'old' => 3200000, 'new' => 2900000],
                    ['title' => 'Ưu đãi khách hàng mới -10%', 'badge' => 'New', 'color' => '#10B981', 'desc' => 'Nhập mã WELCOME khi đặt tour đầu tiên.', 'old' => 1800000, 'new' => 1620000],
                ];
            @endphp

            @foreach($promos as $p)
            <div class="col-lg-4 col-md-6">
                @php $discount = round(100 - ($p['new'] / $p['old'] * 100)); @endphp
                <div class="card border-0 shadow-sm h-100 slide-up position-relative">
                    <div class="position-absolute top-0 start-0 p-2">
                        <span class="badge bg-danger">-{{ $discount }}%</span>
                    </div>
                    <div class="card-body d-flex flex-column">
                        <span class="badge align-self-start mb-3" style="background: {{ $p['color'] }};">{{ $p['badge'] }}</span>
                        <h5 class="card-title">{{ $p['title'] }}</h5>
                        <p class="text-muted">{{ $p['desc'] }}</p>
                        <div class="d-flex justify-content-between align-items-end flex-wrap gap-2 flex-grow-1">
                            <div>
                                <div class="text-muted small"><s>{{ number_format($p['old'],0,',','.') }}đ</s></div>
                                <div class="price-badge" style="background: linear-gradient(45deg, #0EA5E9, #06B6D4);">{{ number_format($p['new'],0,',','.') }}đ</div>
                            </div>
                            <small class="text-muted">Hạn dùng: {{ now()->addDays(14)->format('d/m/Y') }}</small>
                            <div class="d-flex gap-2">
                                <a href="{{ route('tours.index') }}" class="btn btn-primary btn-sm">Đặt ngay</a>
                                <a href="{{ route('tours.index') }}" class="btn btn-outline-primary btn-sm">Xem tour</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endsection



@extends('layouts.app')

@section('title', $tour->title . ' - Tour365')
@section('description', Str::limit($tour->description, 160))

@section('content')
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="bg-light py-3">
        <div class="container">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('tours.index') }}">Tours</a></li>
                <li class="breadcrumb-item active">{{ $tour->title }}</li>
            </ol>
        </div>
    </nav>

    <!-- Tour Details -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <!-- Tour Images -->
                <div class="col-lg-6 mb-4">
                    @if ($tour->images->count() > 0)
                        <div id="tourCarousel" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                @foreach ($tour->images as $index => $image)
                                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                        <img src="{{ $image->image_url }}" class="d-block w-100 rounded"
                                            alt="{{ $tour->title }}" loading="lazy" sizes="(min-width: 992px) 800px, 100vw"
                                            style="height: 400px; object-fit: cover;">
                                    </div>
                                @endforeach
                            </div>
                            @if ($tour->images->count() > 1)
                                <button class="carousel-control-prev" type="button" data-bs-target="#tourCarousel"
                                    data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon"></span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#tourCarousel"
                                    data-bs-slide="next">
                                    <span class="carousel-control-next-icon"></span>
                                </button>
                            @endif
                        </div>
                    @else
                        <img src="https://via.placeholder.com/600x400/4F46E5/ffffff?text={{ urlencode($tour->title) }}"
                            class="img-fluid rounded" alt="{{ $tour->title }}">
                    @endif
                </div>

                <!-- Tour Info -->
                <div class="col-lg-6">
                    <div class="mb-3">
                        @if ($tour->category)
                            <span class="category-badge">{{ $tour->category->name }}</span>
                        @endif
                    </div>

                    <h1 class="h2 mb-3">{{ $tour->title }}</h1>

                    <div class="row mb-4">
                        <div class="col-6">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-clock text-primary me-2"></i>
                                <span><strong>{{ $tour->duration_days }} ngày</strong></span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-users text-primary me-2"></i>
                                <span><strong>{{ $tour->departures->sum('seats_total') }} chỗ</strong></span>
                            </div>
                        </div>
                    </div>

                    @php
                        $hasOld = isset($tour->old_price) && $tour->old_price > $tour->price;
                        $discount = $hasOld ? round(100 - ($tour->price / $tour->old_price) * 100) : null;
                    @endphp
                    <div class="price-section mb-4 d-flex align-items-center justify-content-between">
                        <div>
                            @if ($hasOld)
                                <div class="text-white-50 mb-1"><s>{{ number_format($tour->old_price, 0, ',', '.') }}đ</s>
                                </div>
                            @endif
                            <div class="price-badge fs-4">
                                {{ number_format($tour->price, 0, ',', '.') }}đ
                                <small class="fs-6">/ người</small>
                            </div>
                        </div>
                        @if ($hasOld)
                            <span class="badge bg-danger">-{{ $discount }}%</span>
                        @endif
                    </div>

                    <div class="mb-4">
                        <h5>Mô tả tour</h5>
                        <p class="text-muted">{{ $tour->description }}</p>
                    </div>

                    @auth
                        <div class="d-grid gap-2">
                            <a href="{{ route('bookings.create', ['tour_id' => $tour->id]) }}" class="btn btn-primary btn-lg">
                                <i class="fas fa-calendar-plus"></i> Đặt tour ngay
                            </a>

                            <form action="{{ route('wishlists.store') }}" method="POST" class="d-inline">
                                @csrf
                                <input type="hidden" name="tour_id" value="{{ $tour->id }}">
                                <button type="submit" class="btn btn-outline-danger mt-2">
                                    <i class="fas fa-heart"></i> Thêm vào yêu thích
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="d-grid gap-2">
                            <a href="{{ route('login') }}" class="btn btn-primary btn-lg">
                                <i class="fas fa-sign-in-alt"></i> Đăng nhập để đặt tour
                            </a>
                        </div>
                    @endauth
                </div>
            </div>

            <!-- Tour Details Tabs -->
            <div class="row mt-5">
                <div class="col-12">
                    <ul class="nav nav-tabs" id="tourTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="schedule-tab" data-bs-toggle="tab"
                                data-bs-target="#schedule" type="button" role="tab">
                                <i class="fas fa-calendar-alt"></i> Lịch trình
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="departures-tab" data-bs-toggle="tab" data-bs-target="#departures"
                                type="button" role="tab">
                                <i class="fas fa-plane-departure"></i> Ngày khởi hành
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews"
                                type="button" role="tab">
                                <i class="fas fa-star"></i> Đánh giá
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content" id="tourTabsContent">
                        <!-- Schedule Tab -->
                        <div class="tab-pane fade show active" id="schedule" role="tabpanel">
                            <div class="p-4">
                                @if ($tour->schedules->count() > 0)
                                    <div class="timeline">
                                        @foreach ($tour->schedules as $schedule)
                                            <div class="timeline-item mb-4">
                                                <div class="row">
                                                    <div class="col-md-2">
                                                        <div class="timeline-marker bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                                                            style="width: 50px; height: 50px;">
                                                            <strong>{{ $schedule->day_number }}</strong>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-10">
                                                        <h5>{{ $schedule->title }}</h5>
                                                        <p class="text-muted">{{ $schedule->description }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-4">
                                        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">Chưa có lịch trình chi tiết</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Departures Tab -->
                        <div class="tab-pane fade" id="departures" role="tabpanel">
                            <div class="p-4">
                                @if ($tour->departures->count() > 0)
                                    <div class="row">
                                        @foreach ($tour->departures as $departure)
                                            <div class="col-md-6 col-lg-4 mb-3">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <h6 class="card-title">
                                                            <i class="fas fa-calendar text-primary"></i>
                                                            {{ \Carbon\Carbon::parse($departure->departure_date)->format('d/m/Y') }}
                                                        </h6>
                                                        <p class="card-text">
                                                            <small class="text-muted">
                                                                <i class="fas fa-users"></i>
                                                                {{ $departure->seats_available }}/{{ $departure->seats_total }}
                                                                chỗ trống
                                                            </small>
                                                        </p>
                                                        @if ($departure->seats_available > 0)
                                                            <span class="badge bg-success">Còn chỗ</span>
                                                        @else
                                                            <span class="badge bg-danger">Hết chỗ</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-4">
                                        <i class="fas fa-plane-slash fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">Chưa có ngày khởi hành</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Reviews Tab -->
                        <div class="tab-pane fade" id="reviews" role="tabpanel">
                            <div class="p-4">
                                @if ($tour->reviews->count() > 0)
                                    @foreach ($tour->reviews as $review)
                                        <div class="review-item border-bottom pb-3 mb-3">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <h6 class="mb-1">{{ $review->user->name }}</h6>
                                                    <div class="rating mb-2">
                                                        @for ($i = 1; $i <= 5; $i++)
                                                            <i
                                                                class="fas fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-muted' }}"></i>
                                                        @endfor
                                                    </div>
                                                </div>
                                                <small
                                                    class="text-muted">{{ $review->created_at->format('d/m/Y') }}</small>
                                            </div>
                                            @if ($review->comment)
                                                <p class="text-muted">{{ $review->comment }}</p>
                                            @endif
                                        </div>
                                    @endforeach
                                @else
                                    <div class="text-center py-4">
                                        <i class="fas fa-star fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">Chưa có đánh giá nào</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('styles')
    <style>
        .timeline-marker {
            font-size: 1.2rem;
        }

        .timeline-item {
            position: relative;
        }

        .timeline-item:not(:last-child)::after {
            content: '';
            position: absolute;
            left: 24px;
            top: 50px;
            width: 2px;
            height: calc(100% + 1rem);
            background: #dee2e6;
        }

        .price-section {
            background: linear-gradient(135deg, #0EA5E9 0%, #38BDF8 100%);
            color: white;
            padding: 1.5rem;
            border-radius: 10px;
        }
    </style>
@endsection

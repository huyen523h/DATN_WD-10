@extends('layouts.app')

@section('title', $tour->title . ' - Tour365')
@section('description', Str::limit($tour->description, 160))

@section('content')
<nav aria-label="breadcrumb" class="bg-light py-3">
    <div class="container">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('tours.index') }}">Tours</a></li>
            <li class="breadcrumb-item active">{{ $tour->title }}</li>
        </ol>
    </div>
</nav>

<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 mb-4">
                @if($tour->images->count() > 0)
                    <div id="tourCarousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            @foreach($tour->images as $index => $image)
                                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                    <img src="{{ $image->image_url }}" class="d-block w-100 rounded" 
                                         alt="{{ $tour->title }}" loading="lazy" sizes="(min-width: 992px) 800px, 100vw"
                                         style="height: 400px; object-fit: cover;">
                                </div>
                            @endforeach
                        </div>
                        @if($tour->images->count() > 1)
                            <button class="carousel-control-prev" type="button" data-bs-target="#tourCarousel" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon"></span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#tourCarousel" data-bs-slide="next">
                                <span class="carousel-control-next-icon"></span>
                            </button>
                        @endif
                    </div>
                @else
                    <img src="https://via.placeholder.com/600x400/4F46E5/ffffff?text={{ urlencode($tour->title) }}" 
                         class="img-fluid rounded" alt="{{ $tour->title }}">
                @endif
            </div>

            <div class="col-lg-6">
                <div class="mb-3">
                    @if($tour->category)
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
                    $discount = $hasOld ? round(100 - ($tour->price / $tour->old_price * 100)) : null;
                @endphp
                <div class="price-section mb-4 d-flex align-items-center justify-content-between">
                    <div>
                        @if($hasOld)
                            <div class="text-white-50 mb-1"><s>{{ number_format($tour->old_price, 0, ',', '.') }}đ</s></div>
                        @endif
                        <div class="price-badge fs-4">
                            {{ number_format($tour->price, 0, ',', '.') }}đ
                            <small class="fs-6">/ người</small>
                        </div>
                    </div>
                    @if($hasOld)
                        <span class="badge bg-danger">-{{ $discount }}%</span>
                    @endif
                </div>

                <div class="mb-4">
                    <h5>Mô tả tour</h5>
                    <p class="text-muted">{{ $tour->description }}</p>
                </div>

                @auth
                    <div class="d-grid gap-2">
                        <a href="{{ route('bookings.create', ['tour_id' => $tour->id]) }}" 
                           class="btn btn-primary btn-lg">
                            <i class="fas fa-calendar-plus"></i> Đặt tour ngay
                        </a>
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
                        <button class="nav-link" id="departures-tab" data-bs-toggle="tab" 
                                data-bs-target="#departures" type="button" role="tab">
                            <i class="fas fa-plane-departure"></i> Ngày khởi hành
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" 
                                data-bs-target="#reviews" type="button" role="tab">
                            <i class="fas fa-star"></i> Đánh giá
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="tourTabsContent">
                    <div class="tab-pane fade show active" id="schedule" role="tabpanel">
                        <div class="p-4">
                            @if($tour->schedules->count() > 0)
                                <div class="timeline">
                                    @foreach($tour->schedules as $schedule)
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

                    <div class="tab-pane fade" id="departures" role="tabpanel">
                        <div class="p-4">
                            @if($tour->departures->count() > 0)
                                <div class="row">
                                    @foreach($tour->departures as $departure)
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
                                                            {{ $departure->seats_available }}/{{ $departure->seats_total }} chỗ trống
                                                        </small>
                                                    </p>
                                                    @if($departure->seats_available > 0)
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

                    <div class="tab-pane fade" id="reviews" role="tabpanel">
                        <div class="p-4">
                            <div id="review-list">
                                <div class="text-center py-4">
                                    <p class="text-muted">Đang tải đánh giá...</p>
                                </div>
                            </div>

                            <hr class="my-4">

                            <div>
                                <h4 class="mb-3">Viết đánh giá của bạn</h4>
                                @auth
                                    <form id="reviewForm">
                                        <div id="reviewErrors" class="alert alert-danger d-none"></div>
                                        <div id="reviewSuccess" class="alert alert-success d-none"></div>
                                        <div class="mb-3">
                                            <label for="rating" class="form-label">Bạn đánh giá bao nhiêu sao?</label>
                                            <select name="rating" id="rating" required class="form-select">
                                                <option value="5">5 sao ★★★★★</option>
                                                <option value="4">4 sao ★★★★☆</option>
                                                <option value="3">3 sao ★★★☆☆</option>
                                                <option value="2">2 sao ★★☆☆☆</option>
                                                <option value="1">1 sao ★☆☆☆☆</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="comment" class="form-label">Bình luận</label>
                                            <textarea id="comment" name="comment" rows="4" class="form-control" placeholder="Chia sẻ cảm nhận của bạn về chuyến đi..."></textarea>
                                        </div>
                                        <button type="submit" id="submitReviewBtn" class="btn btn-primary">
                                            <i class="fas fa-paper-plane"></i> Gửi đánh giá
                                        </button>
                                    </form>
                                @else
                                    <div class="alert alert-info">
                                        Vui lòng <a href="{{ route('login') }}" class="alert-link">đăng nhập</a> để gửi đánh giá của bạn.
                                    </div>
                                @endauth
                            </div>
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

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const tourId = {{ $tour->id }};
    const reviewList = document.getElementById('review-list');
    
    let authToken = null;
    @auth
        authToken = '{{ auth()->user()->createToken('temporary-review-token')->plainTextToken }}';
    @endauth

    function fetchReviews() {
        fetch(`/api/tours/${tourId}/reviews`)
            .then(response => response.json())
            .then(data => {
                reviewList.innerHTML = '';
                if (data.data && data.data.length > 0) {
                    data.data.forEach(review => {
                        let stars = '';
                        for (let i = 1; i <= 5; i++) {
                            stars += `<i class="fas fa-star ${i <= review.rating ? 'text-warning' : 'text-muted'}"></i>`;
                        }
                        const reviewHtml = `
                            <div class="review-item border-bottom pb-3 mb-3">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1">${review.user.name}</h6>
                                        <div class="rating mb-2">${stars}</div>
                                    </div>
                                    <small class="text-muted">${review.created_at_human}</small>
                                </div>
                                ${review.comment ? `<p class="text-muted mb-0">${review.comment}</p>` : ''}
                            </div>
                        `;
                        reviewList.innerHTML += reviewHtml;
                    });
                } else {
                    reviewList.innerHTML = `
                        <div class="text-center py-4">
                            <i class="fas fa-star fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Chưa có đánh giá nào. Hãy là người đầu tiên đánh giá tour này!</p>
                        </div>
                    `;
                }
            })
            .catch(error => {
                console.error('Lỗi khi tải đánh giá:', error);
                reviewList.innerHTML = '<p class="text-danger">Không thể tải được danh sách đánh giá.</p>';
            });
    }
    fetchReviews();

    const reviewForm = document.getElementById('reviewForm');
    if (reviewForm) {
        reviewForm.addEventListener('submit', function(event) {
            event.preventDefault();

            const submitBtn = document.getElementById('submitReviewBtn');
            const reviewErrors = document.getElementById('reviewErrors');
            const reviewSuccess = document.getElementById('reviewSuccess');

            if (!authToken) {
                reviewErrors.classList.remove('d-none');
                reviewErrors.innerText = 'Lỗi xác thực. Vui lòng đăng nhập lại.';
                return;
            }

            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Đang gửi...';
            reviewErrors.classList.add('d-none');
            reviewSuccess.classList.add('d-none');

            const formData = {
                rating: document.getElementById('rating').value,
                comment: document.getElementById('comment').value
            };

            fetch(`/api/tours/${tourId}/reviews`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'Authorization': `Bearer ${authToken}`
                },
                body: JSON.stringify(formData)
            })
            .then(response => response.json().then(data => ({ status: response.status, body: data })))
            .then(({ status, body }) => {
                if (status === 201) {
                    reviewSuccess.classList.remove('d-none');
                    reviewSuccess.innerText = body.message;
                    reviewForm.reset();
                } else {
                    reviewErrors.classList.remove('d-none');
                    if (body.errors) {
                        let errorText = '';
                        for (const key in body.errors) {
                            errorText += body.errors[key][0] + '\n';
                        }
                        reviewErrors.innerText = errorText;
                    } else {
                        reviewErrors.innerText = body.message || 'Đã có lỗi xảy ra.';
                    }
                }
            })
            .catch(error => {
                console.error('Lỗi khi gửi đánh giá:', error);
                reviewErrors.classList.remove('d-none');
                reviewErrors.innerText = 'Lỗi kết nối. Vui lòng thử lại.';
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Gửi đánh giá';
            });
        });
    }
});
</script>
@endsection
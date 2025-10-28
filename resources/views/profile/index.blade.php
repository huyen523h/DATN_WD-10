@extends('layouts.app')

@section('title', 'Thông tin cá nhân - Tour365')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-4">
            <!-- Profile Sidebar -->
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="profile-avatar mb-3">
                        <div class="avatar-circle">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                    </div>
                    <h5 class="card-title">{{ Auth::user()->name }}</h5>
                    <p class="text-muted">{{ Auth::user()->email }}</p>
                    <div class="profile-stats">
                        <div class="row text-center">
                            <div class="col-6">
                                <div class="stat-item">
                                    <div class="stat-number">{{ Auth::user()->bookings->count() }}</div>
                                    <div class="stat-label">Đặt tour</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="stat-item">
                                    <div class="stat-number">{{ Auth::user()->reviews->count() }}</div>
                                    <div class="stat-label">Đánh giá</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <div class="card mt-3 border-0 shadow-sm">
                <div class="list-group list-group-flush">
                    <a href="#profile" class="list-group-item list-group-item-action active" data-bs-toggle="tab">
                        <i class="fas fa-user"></i> Thông tin cá nhân
                    </a>
                    <a href="#bookings" class="list-group-item list-group-item-action" data-bs-toggle="tab">
                        <i class="fas fa-calendar-check"></i> Đặt tour của tôi
                    </a>
                    <a href="#reviews" class="list-group-item list-group-item-action" data-bs-toggle="tab">
                        <i class="fas fa-star"></i> Đánh giá của tôi
                    </a>
                    <a href="#settings" class="list-group-item list-group-item-action" data-bs-toggle="tab">
                        <i class="fas fa-cog"></i> Cài đặt
                    </a>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="tab-content">
                <!-- Profile Tab -->
                <div class="tab-pane fade show active" id="profile">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-user"></i> Thông tin cá nhân</h5>
                        </div>
                        <div class="card-body">
                            <form>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label">Họ và tên</label>
                                        <input type="text" class="form-control" id="name" value="{{ Auth::user()->name }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="email" value="{{ Auth::user()->email }}">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="phone" class="form-label">Số điện thoại</label>
                                        <input type="tel" class="form-control" id="phone" value="{{ Auth::user()->phone ?? '' }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="address" class="form-label">Địa chỉ</label>
                                        <input type="text" class="form-control" id="address" value="{{ Auth::user()->address ?? '' }}">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Mật khẩu mới</label>
                                    <input type="password" class="form-control" id="password" placeholder="Để trống nếu không muốn đổi">
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Cập nhật thông tin
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Bookings Tab -->
                <div class="tab-pane fade" id="bookings">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-calendar-check"></i> Đặt tour của tôi</h5>
                        </div>
                        <div class="card-body">
                            @if(Auth::user()->bookings->count() > 0)
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Tour</th>
                                                <th>Ngày khởi hành</th>
                                                <th>Tổng tiền</th>
                                                <th>Trạng thái</th>
                                                <th>Thao tác</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach(Auth::user()->bookings as $booking)
                                                <tr>
                                                    <td>{{ $booking->tour->title }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($booking->departure->departure_date)->format('d/m/Y') }}</td>
                                                    <td>{{ number_format($booking->total_amount, 0, ',', '.') }}đ</td>
                                                    <td>
                                                        <span class="badge 
                                                            @if($booking->status === 'pending') bg-warning
                                                            @elseif($booking->status === 'confirmed') bg-success
                                                            @elseif($booking->status === 'cancelled') bg-danger
                                                            @else bg-secondary
                                                            @endif">
                                                            @switch($booking->status)
                                                                @case('pending') Chờ xác nhận @break
                                                                @case('confirmed') Đã xác nhận @break
                                                                @case('cancelled') Đã hủy @break
                                                                @case('completed') Hoàn thành @break
                                                                @default {{ $booking->status }} @break
                                                            @endswitch
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('bookings.show', $booking) }}" class="btn btn-sm btn-outline-primary">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">Chưa có đặt tour nào</p>
                                    <a href="{{ route('tours.index') }}" class="btn btn-primary">
                                        <i class="fas fa-search"></i> Xem tours
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Reviews Tab -->
                <div class="tab-pane fade" id="reviews">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-star"></i> Đánh giá của tôi</h5>
                        </div>
                        <div class="card-body">
                            @if(Auth::user()->reviews->count() > 0)
                                @foreach(Auth::user()->reviews as $review)
                                    <div class="review-item border-bottom pb-3 mb-3">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h6>{{ $review->tour->title }}</h6>
                                                <div class="rating mb-2">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <i class="fas fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-muted' }}"></i>
                                                    @endfor
                                                </div>
                                                @if($review->comment)
                                                    <p class="text-muted">{{ $review->comment }}</p>
                                                @endif
                                            </div>
                                            <small class="text-muted">{{ $review->created_at->format('d/m/Y') }}</small>
                                        </div>
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

                <!-- Settings Tab -->
                <div class="tab-pane fade" id="settings">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-cog"></i> Cài đặt tài khoản</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-4">
                                <h6>Thông báo</h6>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="emailNotifications" checked>
                                    <label class="form-check-label" for="emailNotifications">
                                        Nhận thông báo qua email
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="smsNotifications">
                                    <label class="form-check-label" for="smsNotifications">
                                        Nhận thông báo qua SMS
                                    </label>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <h6>Bảo mật</h6>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="twoFactor">
                                    <label class="form-check-label" for="twoFactor">
                                        Xác thực 2 yếu tố (2FA)
                                    </label>
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Lưu cài đặt
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
.avatar-circle {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: linear-gradient(135deg, #0EA5E9 0%, #38BDF8 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    font-weight: bold;
    margin: 0 auto;
}

.stat-item {
    padding: 10px;
}

.stat-number {
    font-size: 1.5rem;
    font-weight: bold;
    color: #0EA5E9;
}

.stat-label {
    font-size: 0.9rem;
    color: #6B7280;
}

.review-item:last-child {
    border-bottom: none !important;
}
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle tab switching
    const tabLinks = document.querySelectorAll('[data-bs-toggle="tab"]');
    tabLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Remove active class from all links and panes
            tabLinks.forEach(l => l.classList.remove('active'));
            document.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('show', 'active'));
            
            // Add active class to clicked link
            this.classList.add('active');
            
            // Show corresponding pane
            const targetPane = document.querySelector(this.getAttribute('href'));
            if (targetPane) {
                targetPane.classList.add('show', 'active');
            }
        });
    });
});
</script>
@endsection

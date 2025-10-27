@extends('layouts.admin')

@section('title', 'Quản lý Đánh giá - Admin')

@section('breadcrumb')
<li class="breadcrumb-item active">Quản lý Đánh giá</li>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2><i class="fas fa-star text-primary"></i> Quản lý Đánh giá</h2>
        <p class="text-muted mb-0">Quản lý đánh giá và bình luận của khách hàng</p>
    </div>
</div>

<!-- Reviews Table -->
<div class="row">
    <div class="col-12">
        <div class="admin-card">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">
                    Danh sách đánh giá ({{ $reviews->total() }} đánh giá)
                </h6>
            </div>
            <div class="card-body">
                @if($reviews->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Khách hàng</th>
                                    <th>Tour</th>
                                    <th>Đánh giá</th>
                                    <th>Bình luận</th>
                                    <th>Trạng thái</th>
                                    <th>Ngày đánh giá</th>
                                    <th width="150">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($reviews as $review)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="user-avatar me-2">
                                                    {{ strtoupper(substr($review->user->name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <div class="fw-bold">{{ $review->user->name }}</div>
                                                    <small class="text-muted">{{ $review->user->email }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="fw-bold">{{ $review->tour->title }}</div>
                                        </td>
                                        <td>
                                            <div class="rating">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="fas fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-muted' }}"></i>
                                                @endfor
                                                <span class="ms-2 fw-bold">{{ $review->rating }}/5</span>
                                            </div>
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ Str::limit($review->comment, 50) }}</small>
                                        </td>
                                        <td>
                                            <span class="badge 
                                                @if($review->status === 'visible') bg-success
                                                @elseif($review->status === 'hidden') bg-warning
                                                @else bg-secondary
                                                @endif">
                                                @switch($review->status)
                                                    @case('visible') Hiển thị @break
                                                    @case('hidden') Ẩn @break
                                                    @default {{ $review->status }} @break
                                                @endswitch
                                            </span>
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ $review->created_at->format('d/m/Y H:i') }}</small>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <button class="btn btn-outline-info" onclick="viewReview({{ $review->id }})" title="Xem">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="btn btn-outline-primary" onclick="editReview({{ $review->id }})" title="Sửa">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-outline-danger" onclick="deleteReview({{ $review->id }})" title="Xóa">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="text-muted">
                            Hiển thị {{ $reviews->firstItem() }} đến {{ $reviews->lastItem() }} 
                            trong tổng số {{ $reviews->total() }} đánh giá
                        </div>
                        <div>
                            {{ $reviews->links() }}
                        </div>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-star fa-4x text-muted mb-3"></i>
                        <h5 class="text-muted">Chưa có đánh giá nào</h5>
                        <p class="text-muted">Khách hàng chưa đánh giá tour nào</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
.user-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 0.875rem;
}

.rating {
    display: flex;
    align-items: center;
}
</style>
@endsection

@section('scripts')
<script>
function viewReview(reviewId) {
    // Implement view functionality
    console.log('View review:', reviewId);
}

function editReview(reviewId) {
    // Implement edit functionality
    console.log('Edit review:', reviewId);
}

function deleteReview(reviewId) {
    if (confirm('Bạn có chắc chắn muốn xóa đánh giá này?')) {
        // Implement delete functionality
        console.log('Delete review:', reviewId);
    }
}
</script>
@endsection

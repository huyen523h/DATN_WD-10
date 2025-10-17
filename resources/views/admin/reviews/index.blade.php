@extends('layouts.admin')

@section('content')
<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Đánh giá</h1>
    </div>

    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="reviewsTable">
                    <thead>
                        <tr>
                            <th>Khách hàng</th>
                            <th>Tour</th>
                            <th>Đánh giá</th>
                            <th>Bình luận</th>
                            <th>Trạng thái</th>
                            <th>Ngày tạo</th>
                            <th class="text-end">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reviews as $review)
                        <tr id="review-row-{{ $review->id }}">
                            <td class="align-middle">{{ $review->user->name ?? 'N/A' }}</td>
                            <td class="align-middle">{{ Str::limit($review->tour->title ?? 'N/A', 30) }}</td>
                            <td class="align-middle">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-muted' }}"></i>
                                @endfor
                            </td>
                            <td class="align-middle">{{ Str::limit($review->comment, 50) }}</td>
                            <td class="align-middle">
                                <span id="status-badge-{{ $review->id }}" class="badge @if($review->status == 'approved') bg-success @elseif($review->status == 'pending') bg-warning @else bg-secondary @endif">
                                    {{ ucfirst($review->status) }}
                                </span>
                            </td>
                            <td class="align-middle">{{ $review->created_at->format('d/m/Y') }}</td>
                            <td class="text-end align-middle">
                                <div class="btn-group" role="group">
                                    @if($review->status == 'pending' || $review->status == 'rejected')
                                    <button class="btn btn-sm btn-primary btn-approve" data-id="{{ $review->id }}" title="Duyệt">Duyệt</button>
                                    @endif
                                    @if($review->status == 'approved')
                                    <button class="btn btn-sm btn-secondary btn-reject" data-id="{{ $review->id }}" title="Ẩn">Ẩn</button>
                                    @endif
                                    <button class="btn btn-sm btn-danger btn-delete" data-id="{{ $review->id }}" title="Xóa">Xóa</button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted p-4">Chưa có đánh giá</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-4 d-flex justify-content-center">{{ $reviews->links() }}</div>
</div>
@endsection

@section('scripts')
<script>
    // Chú thích: Chúng ta đặt script vào section 'scripts' mà layout của bạn đã có sẵn (@yield('scripts'))
    document.addEventListener('DOMContentLoaded', function () {
        let apiToken = null;
        @auth
            apiToken = '{{ auth()->user()->createToken("admin-review-actions-token")->plainTextToken }}';
        @endauth

        const reviewsTable = document.getElementById('reviewsTable');

        if (reviewsTable) {
            reviewsTable.addEventListener('click', function (event) {
                const button = event.target.closest('button');
                if (!button) return;

                const reviewId = button.dataset.id;
                
                if (button.classList.contains('btn-approve')) {
                    updateReviewStatus(reviewId, 'approved');
                } else if (button.classList.contains('btn-reject')) {
                    updateReviewStatus(reviewId, 'rejected');
                } else if (button.classList.contains('btn-delete')) {
                    deleteReview(reviewId);
                }
            });
        }

        function updateReviewStatus(id, newStatus) {
            if (!apiToken) return alert('Lỗi xác thực. Vui lòng tải lại trang.');
            fetch(`/api/reviews/${id}`, {
                method: 'PATCH',
                headers: {'Content-Type': 'application/json', 'Accept': 'application/json', 'Authorization': `Bearer ${apiToken}`},
                body: JSON.stringify({ status: newStatus })
            })
            .then(response => response.ok ? response.json() : Promise.reject('API request failed'))
            .then(data => {
                alert('Cập nhật trạng thái thành công!');
                location.reload(); 
            })
            .catch(error => {
                console.error('Lỗi khi cập nhật trạng thái:', error);
                alert('Đã có lỗi xảy ra. Vui lòng thử lại.');
            });
        }

        function deleteReview(id) {
            if (!confirm('Bạn có chắc chắn muốn xóa vĩnh viễn đánh giá này?')) return;
            if (!apiToken) return alert('Lỗi xác thực. Vui lòng tải lại trang.');
            fetch(`/api/reviews/${id}`, {
                method: 'DELETE',
                headers: {'Accept': 'application/json', 'Authorization': `Bearer ${apiToken}`}
            })
            .then(response => {
                if (response.status === 204) {
                    document.getElementById(`review-row-${id}`).remove();
                    alert('Đã xóa đánh giá thành công.');
                } else {
                    return Promise.reject('API request failed');
                }
            })
            .catch(error => {
                console.error('Lỗi khi xóa đánh giá:', error);
                alert('Đã có lỗi xảy ra. Vui lòng thử lại.');
            });
        }
    });
</script>
@endsection
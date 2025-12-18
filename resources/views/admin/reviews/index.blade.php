@extends('layouts.admin')

@section('content')
    <div class="container-fluid p-4">
        <style>
            #reviewsTable {
                width: 100%;
                border-collapse: collapse;
            }

            #reviewsTable thead {
                display: table-header-group;
            }

            #reviewsTable tbody {
                display: table-row-group;
            }

            #reviewsTable tr {
                display: table-row;
            }

            #reviewsTable th,
            #reviewsTable td {
                display: table-cell;
                vertical-align: middle;
            }

            #reviewsTable tbody tr {
                position: relative;
                transition: 0.2s ease;
            }

            #reviewsTable tbody tr:hover {
                background-color: #f9fafb;
            }
        </style>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-gray-800">Đánh giá</h1>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card shadow">
            <div class="card-body">
                <div class="table-responsive">
                    <table class=" table  table-hover" id="reviewsTable">
                        <thead>
                            <tr>
                                <th></th>
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
                                <tr id="review-row-{{ $review->id }}" class="align-middle">
                                    <td>{{ $review->user->name ?? 'N/A' }}</td>

                                    <td>{{ Str::limit($review->tour->title ?? 'N/A', 30) }}</td>

                                    <td>
                                        @if ($review->rating)
                                            @for ($i = 1; $i <= 5; $i++)
                                                <i
                                                    class="fas fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-muted' }}"></i>
                                            @endfor
                                        @else
                                            <small class="text-muted">(Không có)</small>
                                        @endif
                                    </td>

                                    <td>{{ Str::limit($review->comment, 50) }}</td>

                                    <td>
                                        <span
                                            class="badge 
                                        @if ($review->status == 'approved') bg-success
                                        @elseif($review->status == 'pending') bg-warning
                                        @elseif($review->status == 'hidden') bg-secondary
                                        @else bg-dark @endif">
                                            {{ ucfirst($review->status) }}
                                        </span>
                                    </td>

                                    <td>{{ $review->created_at->format('d/m/Y') }}</td>

                                    <td class="text-end align-middle">
                                        <div class="d-flex gap-1 justify-content-end">

                                            @if ($review->status === 'pending')
                                                <form action="{{ route('admin.reviews.approve', $review) }}" method="POST"
                                                    class="d-inline-block"
                                                    onsubmit="return confirm('Duyệt/Hiển thị lại bình luận này?')">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-primary"
                                                        title="Duyệt">Duyệt</button>
                                                </form>
                                            @endif

                                            @if ($review->status === 'pending' || $review->status === 'approved')
                                                <form action="{{ route('admin.reviews.hide', $review) }}" method="POST"
                                                    class="d-inline-block" onsubmit="return confirm('Ẩn bình luận này?')">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-secondary"
                                                        title="Ẩn">Ẩn</button>
                                                </form>
                                            @endif
                                            @if (
                                                $review->status === 'approved' &&
                                                    is_null($review->parent_id) &&
                                                    $review->replies->isEmpty() &&
                                                    $review->status !== 'hidden')
                                                <button type="button" class="btn btn-sm btn-info d-inline-block"
                                                    data-bs-toggle="modal" data-bs-target="#replyModal-{{ $review->id }}"
                                                    title="Trả lời">
                                                    Trả lời
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>

                                @if ($review->replies->isNotEmpty())
                                    @foreach ($review->replies as $reply)
                                        <tr class="table-info" id="review-row-{{ $reply->id }}">

                                            <td></td>
                                            <td></td>
                                            <td class="text-end pe-3">
                                                <i class="fas fa-reply fa-flip-horizontal"></i>
                                                <strong>Admin ({{ $reply->user->name ?? 'N/A' }}) trả lời:</strong>
                                            </td>

                                            <td>{{ Str::limit($reply->comment, 50) }}</td>
                                            <td>
                                                <span class="badge bg-info">Đã trả lời</span>
                                            </td>
                                            <td>{{ $reply->created_at->format('d/m/Y') }}</td>
                                            <td class="text-end align-middle">
                                                <div class="d-flex gap-1 justify-content-end">
                                                    <button type="button" class="btn btn-sm btn-outline-primary"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#editReplyModal-{{ $reply->id }}">
                                                        Sửa
                                                    </button>
                                                    <form action="{{ route('admin.reviews.reply.destroy', $reply) }}"
                                                        method="POST" class="d-inline-block"
                                                        onsubmit="return confirm('Xóa trả lời này?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="btn btn-sm btn-outline-danger">Xóa</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif

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

    @foreach ($reviews as $review)
        @if ($review->status === 'approved' && is_null($review->parent_id))
            <div class="modal fade" id="replyModal-{{ $review->id }}" tabindex="-1"
                aria-labelledby="replyModalLabel-{{ $review->id }}" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="{{ route('admin.reviews.reply', $review) }}" method="POST">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title" id="replyModalLabel-{{ $review->id }}">Trả lời bình luận</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p><strong>Bình luận của khách hàng:</strong></p>
                                <blockquote class="blockquote bg-light p-3 rounded">
                                    <p class="mb-0">{{ $review->comment }}</p>
                                </blockquote>
                                <hr>
                                <div class="mb-3">
                                    <label for="comment-{{ $review->id }}" class="form-label"><strong>Nội dung trả lời
                                            của bạn:</strong></label>
                                    <textarea name="comment" id="comment-{{ $review->id }}" class="form-control" rows="4" required></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                <button type="submit" class="btn btn-primary">Gửi trả lời</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    @endforeach

    @foreach ($reviews as $review)
        @if ($review->replies->isNotEmpty())
            @foreach ($review->replies as $reply)
                <div class="modal fade" id="editReplyModal-{{ $reply->id }}" tabindex="-1"
                    aria-labelledby="editReplyModalLabel-{{ $reply->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="{{ route('admin.reviews.reply.update', $reply) }}" method="POST">
                                @csrf
                                @method('PUT') <div class="modal-header">
                                    <h5 class="modal-title" id="editReplyModalLabel-{{ $reply->id }}">Sửa trả lời bình
                                        luận</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p><strong>Bình luận gốc của khách hàng:</strong></p>
                                    <blockquote class="blockquote bg-light p-3 rounded">
                                        <p class="mb-0">{{ $review->comment }}</p>
                                    </blockquote>
                                    <hr>
                                    <div class="mb-3">
                                        <label for="edit-comment-{{ $reply->id }}" class="form-label"><strong>Nội dung
                                                trả lời
                                                của bạn:</strong></label>
                                        <textarea name="comment" id="edit-comment-{{ $reply->id }}" class="form-control" rows="4" required>{{ $reply->comment }}</textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                    <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    @endforeach

@endsection

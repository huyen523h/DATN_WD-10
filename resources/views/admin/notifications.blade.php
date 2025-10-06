@extends('layouts.admin')

@section('title', 'Quản lý Thông báo - Admin')

@section('breadcrumb')
<li class="breadcrumb-item active">Quản lý Thông báo</li>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2><i class="fas fa-bell text-primary"></i> Quản lý Thông báo</h2>
        <p class="text-muted mb-0">Gửi và quản lý thông báo cho khách hàng</p>
    </div>
    <button class="btn btn-admin-primary" data-bs-toggle="modal" data-bs-target="#sendNotificationModal">
        <i class="fas fa-paper-plane"></i> Gửi thông báo mới
    </button>
</div>

<!-- Notifications Table -->
<div class="row">
    <div class="col-12">
        <div class="admin-card">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">
                    Danh sách thông báo ({{ $notifications->total() }} thông báo)
                </h6>
            </div>
            <div class="card-body">
                @if($notifications->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Tiêu đề</th>
                                    <th>Nội dung</th>
                                    <th>Người nhận</th>
                                    <th>Loại</th>
                                    <th>Trạng thái</th>
                                    <th>Ngày gửi</th>
                                    <th width="150">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($notifications as $notification)
                                    <tr>
                                        <td>
                                            <div class="fw-bold">{{ $notification->title }}</div>
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ Str::limit($notification->content, 50) }}</small>
                                        </td>
                                        <td>
                                            @if($notification->user)
                                                <div class="d-flex align-items-center">
                                                    <div class="user-avatar me-2">
                                                        {{ strtoupper(substr($notification->user->name, 0, 1)) }}
                                                    </div>
                                                    <div>
                                                        <div class="fw-bold">{{ $notification->user->name }}</div>
                                                        <small class="text-muted">{{ $notification->user->email }}</small>
                                                    </div>
                                                </div>
                                            @else
                                                <span class="badge bg-info">Tất cả người dùng</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge 
                                                @if($notification->type === 'info') bg-info
                                                @elseif($notification->type === 'success') bg-success
                                                @elseif($notification->type === 'warning') bg-warning
                                                @elseif($notification->type === 'error') bg-danger
                                                @else bg-secondary
                                                @endif">
                                                @switch($notification->type)
                                                    @case('info') Thông tin @break
                                                    @case('success') Thành công @break
                                                    @case('warning') Cảnh báo @break
                                                    @case('error') Lỗi @break
                                                    @default {{ $notification->type }} @break
                                                @endswitch
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge 
                                                @if($notification->is_read) bg-success
                                                @else bg-warning
                                                @endif">
                                                @if($notification->is_read)
                                                    Đã đọc
                                                @else
                                                    Chưa đọc
                                                @endif
                                            </span>
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ $notification->created_at->format('d/m/Y H:i') }}</small>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <button class="btn btn-outline-info" onclick="viewNotification({{ $notification->id }})" title="Xem">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="btn btn-outline-primary" onclick="editNotification({{ $notification->id }})" title="Sửa">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-outline-danger" onclick="deleteNotification({{ $notification->id }})" title="Xóa">
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
                            Hiển thị {{ $notifications->firstItem() }} đến {{ $notifications->lastItem() }} 
                            trong tổng số {{ $notifications->total() }} thông báo
                        </div>
                        <div>
                            {{ $notifications->links() }}
                        </div>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-bell fa-4x text-muted mb-3"></i>
                        <h5 class="text-muted">Chưa có thông báo nào</h5>
                        <p class="text-muted">Hãy gửi thông báo đầu tiên</p>
                        <button class="btn btn-admin-primary" data-bs-toggle="modal" data-bs-target="#sendNotificationModal">
                            <i class="fas fa-paper-plane"></i> Gửi thông báo mới
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Send Notification Modal -->
<div class="modal fade" id="sendNotificationModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Gửi thông báo mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.notifications.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="title" class="form-label">Tiêu đề <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="title" name="title" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="type" class="form-label">Loại thông báo</label>
                                <select class="form-select" id="type" name="type">
                                    <option value="info">Thông tin</option>
                                    <option value="success">Thành công</option>
                                    <option value="warning">Cảnh báo</option>
                                    <option value="error">Lỗi</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="content" class="form-label">Nội dung <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="content" name="content" rows="4" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="user_id" class="form-label">Gửi đến</label>
                        <select class="form-select" id="user_id" name="user_id">
                            <option value="">Tất cả người dùng</option>
                            @foreach(\App\Models\User::all() as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-admin-primary">Gửi thông báo</button>
                </div>
            </form>
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
</style>
@endsection

@section('scripts')
<script>
function viewNotification(notificationId) {
    // Implement view functionality
    console.log('View notification:', notificationId);
}

function editNotification(notificationId) {
    // Implement edit functionality
    console.log('Edit notification:', notificationId);
}

function deleteNotification(notificationId) {
    if (confirm('Bạn có chắc chắn muốn xóa thông báo này?')) {
        // Implement delete functionality
        console.log('Delete notification:', notificationId);
    }
}
</script>
@endsection

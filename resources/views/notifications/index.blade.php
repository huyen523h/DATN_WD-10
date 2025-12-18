@extends('layouts.app')

@section('title', 'Thông báo - Tour365')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-bell text-primary"></i> Thông báo
                        </h5>
                        <div>
                            <button id="markAllReadBtn" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-check-double"></i> Đánh dấu tất cả đã đọc
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($notifications->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($notifications as $notification)
                                <div class="list-group-item notification-item {{ $notification->status === 'unread' ? 'unread' : '' }}" 
                                     data-id="{{ $notification->id }}">
                                    <div class="d-flex align-items-start">
                                        <div class="notification-icon me-3">
                                            @switch($notification->type)
                                                @case('booking_success')
                                                    <i class="fas fa-check-circle text-success"></i>
                                                    @break
                                                @case('payment_success')
                                                    <i class="fas fa-check-circle text-success"></i>
                                                    @break
                                                @case('payment_failed')
                                                    <i class="fas fa-times-circle text-danger"></i>
                                                    @break
                                                @case('departure_upcoming')
                                                    <i class="fas fa-calendar-alt text-warning"></i>
                                                    @break
                                                @case('tour_schedule_changed')
                                                    <i class="fas fa-exchange-alt text-info"></i>
                                                    @break
                                                @case('refund')
                                                    <i class="fas fa-money-bill-wave text-primary"></i>
                                                    @break
                                                @case('booking_cancelled')
                                                    <i class="fas fa-ban text-secondary"></i>
                                                    @break
                                                @default
                                                    <i class="fas fa-bell text-primary"></i>
                                            @endswitch
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">
                                                {{ $notification->title }}
                                                @if($notification->status === 'unread')
                                                    <span class="badge bg-primary rounded-pill ms-2">Mới</span>
                                                @endif
                                            </h6>
                                            <p class="mb-1 text-muted">{{ $notification->message }}</p>
                                            <small class="text-muted">
                                                <i class="fas fa-clock"></i> {{ $notification->created_at->diffForHumans() }}
                                            </small>
                                        </div>
                                        <div class="notification-actions ms-3">
                                            @if($notification->related_id && $notification->related_type === 'booking')
                                                <a href="{{ route('bookings.show', $notification->related_id) }}" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            @endif
                                            <button class="btn btn-sm btn-outline-secondary delete-notification" 
                                                    data-id="{{ $notification->id }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="card-footer bg-white">
                            {{ $notifications->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Chưa có thông báo nào</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
.notification-item {
    transition: background-color 0.2s;
    border-left: 3px solid transparent;
}

.notification-item.unread {
    background-color: #f0f9ff;
    border-left-color: #0EA5E9;
}

.notification-item:hover {
    background-color: #f8f9fa;
}

.notification-icon {
    font-size: 1.5rem;
    margin-top: 0.25rem;
}

.notification-actions {
    opacity: 0;
    transition: opacity 0.2s;
}

.notification-item:hover .notification-actions {
    opacity: 1;
}
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mark all as read
    document.getElementById('markAllReadBtn')?.addEventListener('click', function() {
        fetch('{{ route("notifications.mark-all-read") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        });
    });

    // Mark as read when clicking notification
    document.querySelectorAll('.notification-item').forEach(item => {
        item.addEventListener('click', function(e) {
            if (e.target.closest('.notification-actions')) return;
            
            const notificationId = this.dataset.id;
            if (this.classList.contains('unread')) {
                fetch(`/notifications/${notificationId}/read`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        this.classList.remove('unread');
                        const badge = this.querySelector('.badge');
                        if (badge) badge.remove();
                    }
                });
            }
        });
    });

    // Delete notification
    document.querySelectorAll('.delete-notification').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            if (!confirm('Bạn có chắc chắn muốn xóa thông báo này?')) return;
            
            const notificationId = this.dataset.id;
            fetch(`/notifications/${notificationId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.closest('.notification-item').remove();
                }
            });
        });
    });
});
</script>
@endsection


@auth
<div class="notification-bell-wrapper position-relative">
    <button class="btn btn-link text-dark position-relative" id="notificationBell" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="fas fa-bell"></i>
        <span class="badge bg-danger rounded-pill position-absolute top-0 start-100 translate-middle" id="notificationBadge" style="display: none;">
            0
        </span>
    </button>
    <ul class="dropdown-menu dropdown-menu-end notification-dropdown" style="width: 350px; max-height: 400px; overflow-y: auto;">
        <li class="dropdown-header d-flex justify-content-between align-items-center">
            <span><strong>Thông báo</strong></span>
            <a href="{{ route('notifications.index') }}" class="text-decoration-none small">Xem tất cả</a>
        </li>
        <li><hr class="dropdown-divider"></li>
        <li>
            <div id="notificationList" class="p-2">
                <div class="text-center py-3">
                    <div class="spinner-border spinner-border-sm text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </li>
        <li><hr class="dropdown-divider"></li>
        <li class="dropdown-item-text text-center">
            <a href="{{ route('notifications.index') }}" class="btn btn-sm btn-outline-primary w-100">
                Xem tất cả thông báo
            </a>
        </li>
    </ul>
</div>

<style>
.notification-bell-wrapper .btn-link {
    text-decoration: none;
    padding: 8px 12px;
    border-radius: 50%;
    transition: all 0.3s;
}

.notification-bell-wrapper .btn-link:hover {
    background-color: rgba(14, 165, 233, 0.1);
}

.notification-dropdown {
    padding: 0;
}

.notification-item-dropdown {
    padding: 12px;
    border-bottom: 1px solid #f0f0f0;
    cursor: pointer;
    transition: background-color 0.2s;
}

.notification-item-dropdown:hover {
    background-color: #f8f9fa;
}

.notification-item-dropdown.unread {
    background-color: #f0f9ff;
    border-left: 3px solid #0EA5E9;
}

.notification-item-dropdown .notification-title {
    font-weight: 600;
    font-size: 0.9rem;
    margin-bottom: 4px;
}

.notification-item-dropdown .notification-message {
    font-size: 0.85rem;
    color: #6c757d;
    margin-bottom: 4px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.notification-item-dropdown .notification-time {
    font-size: 0.75rem;
    color: #adb5bd;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const bell = document.getElementById('notificationBell');
    const badge = document.getElementById('notificationBadge');
    const list = document.getElementById('notificationList');

    function loadNotifications() {
        fetch('{{ route("notifications.recent") }}')
            .then(response => response.json())
            .then(data => {
                updateBadge(data.notifications.filter(n => n.status === 'unread').length);
                renderNotifications(data.notifications);
            })
            .catch(error => {
                console.error('Error loading notifications:', error);
            });
    }

    function updateBadge(count) {
        if (count > 0) {
            badge.textContent = count > 99 ? '99+' : count;
            badge.style.display = 'block';
        } else {
            badge.style.display = 'none';
        }
    }

    function renderNotifications(notifications) {
        if (notifications.length === 0) {
            list.innerHTML = '<div class="text-center py-3 text-muted"><small>Chưa có thông báo nào</small></div>';
            return;
        }

        list.innerHTML = notifications.map(notif => `
            <div class="notification-item-dropdown ${notif.status === 'unread' ? 'unread' : ''}" 
                 data-id="${notif.id}"
                 ${notif.url ? `onclick="window.location.href='${notif.url}'"` : ''}>
                <div class="notification-title">${notif.title}</div>
                <div class="notification-message">${notif.message}</div>
                <div class="notification-time">${notif.created_at}</div>
            </div>
        `).join('');

        // Mark as read on click
        list.querySelectorAll('.notification-item-dropdown').forEach(item => {
            item.addEventListener('click', function() {
                if (this.classList.contains('unread')) {
                    const notificationId = this.dataset.id;
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
                            loadNotifications();
                        }
                    });
                }
            });
        });
    }

    // Load notifications on page load
    loadNotifications();

    // Refresh notifications every 30 seconds
    setInterval(loadNotifications, 30000);

    // Refresh when dropdown is opened
    bell.addEventListener('click', function() {
        loadNotifications();
    });
});
</script>
@endauth


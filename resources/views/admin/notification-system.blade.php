<!-- Real-time Notification System -->
<div id="notification-system" class="fixed top-4 right-4 z-50 space-y-2">
    <!-- Notifications will be dynamically added here -->
</div>

<!-- Notification Bell Icon -->
<div id="notification-bell" class="fixed bottom-6 right-6 z-40">
    <button onclick="toggleNotificationPanel()" class="bg-blue-600 hover:bg-blue-700 text-white rounded-full p-4 shadow-lg transition-all duration-300 transform hover:scale-110">
        <i class="fas fa-bell text-xl"></i>
        <span id="notification-count" class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-6 w-6 flex items-center justify-center hidden">0</span>
    </button>
</div>

<!-- Notification Panel -->
<div id="notification-panel" class="fixed bottom-20 right-6 w-80 bg-white rounded-lg shadow-xl border hidden z-40 max-h-96 overflow-hidden">
    <div class="p-4 border-b bg-gray-50 rounded-t-lg">
        <div class="flex items-center justify-between">
            <h3 class="font-semibold text-gray-800">Thông báo</h3>
            <div class="flex items-center space-x-2">
                <button onclick="markAllAsRead()" class="text-xs text-blue-600 hover:text-blue-800">Đánh dấu đã đọc</button>
                <button onclick="toggleNotificationPanel()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    </div>
    
    <div id="notification-list" class="max-h-64 overflow-y-auto">
        <!-- Notifications will be loaded here -->
    </div>
    
    <div class="p-3 border-t bg-gray-50 rounded-b-lg">
        <button onclick="viewAllNotifications()" class="w-full text-center text-sm text-blue-600 hover:text-blue-800">
            Xem tất cả thông báo
        </button>
    </div>
</div>

<script>
class NotificationSystem {
    constructor() {
        this.notifications = [];
        this.unreadCount = 0;
        this.isConnected = false;
        this.reconnectAttempts = 0;
        this.maxReconnectAttempts = 5;
        
        this.init();
    }

    init() {
        this.loadExistingNotifications();
        this.setupEventListeners();
        this.startPolling();
    }

    // Load existing notifications from server
    async loadExistingNotifications() {
        try {
            const response = await fetch('/api/notifications/recent');
            const data = await response.json();
            
            if (data.success) {
                this.notifications = data.data;
                this.updateNotificationDisplay();
                this.updateUnreadCount();
            }
        } catch (error) {
            console.error('Error loading notifications:', error);
        }
    }

    // Setup event listeners
    setupEventListeners() {
        // Listen for custom notification events
        document.addEventListener('newNotification', (event) => {
            this.addNotification(event.detail);
        });

        // Listen for page visibility changes
        document.addEventListener('visibilitychange', () => {
            if (!document.hidden) {
                this.loadExistingNotifications();
            }
        });
    }

    // Start polling for new notifications
    startPolling() {
        setInterval(() => {
            this.checkForNewNotifications();
        }, 30000); // Check every 30 seconds
    }

    // Check for new notifications
    async checkForNewNotifications() {
        try {
            const lastNotificationId = this.notifications.length > 0 ? this.notifications[0].id : 0;
            const response = await fetch(`/api/notifications/new?since=${lastNotificationId}`);
            const data = await response.json();
            
            if (data.success && data.data.length > 0) {
                data.data.forEach(notification => {
                    this.addNotification(notification);
                });
            }
        } catch (error) {
            console.error('Error checking for new notifications:', error);
        }
    }

    // Add new notification
    addNotification(notification) {
        // Add to beginning of array
        this.notifications.unshift(notification);
        
        // Keep only last 50 notifications
        if (this.notifications.length > 50) {
            this.notifications = this.notifications.slice(0, 50);
        }

        // Show toast notification
        this.showToastNotification(notification);
        
        // Update displays
        this.updateNotificationDisplay();
        this.updateUnreadCount();
        
        // Play notification sound (optional)
        this.playNotificationSound();
    }

    // Show toast notification
    showToastNotification(notification) {
        const toast = document.createElement('div');
        toast.className = `notification-toast bg-white border-l-4 ${this.getNotificationColor(notification.type)} rounded-lg shadow-lg p-4 mb-2 transform translate-x-full transition-all duration-300`;
        
        toast.innerHTML = `
            <div class="flex items-start space-x-3">
                <div class="flex-shrink-0">
                    <i class="fas ${this.getNotificationIcon(notification.type)} ${this.getNotificationTextColor(notification.type)} text-lg"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900">${notification.title}</p>
                    <p class="text-sm text-gray-600">${notification.message}</p>
                    <p class="text-xs text-gray-400 mt-1">${this.formatTime(notification.created_at)}</p>
                </div>
                <button onclick="this.parentElement.parentElement.parentElement.remove()" class="flex-shrink-0 text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;

        const container = document.getElementById('notification-system');
        container.appendChild(toast);

        // Animate in
        setTimeout(() => {
            toast.classList.remove('translate-x-full');
        }, 100);

        // Auto remove after 5 seconds
        setTimeout(() => {
            toast.classList.add('translate-x-full');
            setTimeout(() => {
                if (toast.parentElement) {
                    toast.parentElement.removeChild(toast);
                }
            }, 300);
        }, 5000);
    }

    // Update notification panel display
    updateNotificationDisplay() {
        const container = document.getElementById('notification-list');
        
        if (this.notifications.length === 0) {
            container.innerHTML = `
                <div class="p-4 text-center text-gray-500">
                    <i class="fas fa-bell-slash text-2xl mb-2"></i>
                    <p>Không có thông báo nào</p>
                </div>
            `;
            return;
        }

        let html = '';
        this.notifications.forEach(notification => {
            html += `
                <div class="notification-item p-3 border-b hover:bg-gray-50 ${notification.read_at ? '' : 'bg-blue-50'}" data-id="${notification.id}">
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <i class="fas ${this.getNotificationIcon(notification.type)} ${this.getNotificationTextColor(notification.type)}"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900">${notification.title}</p>
                            <p class="text-sm text-gray-600">${notification.message}</p>
                            <p class="text-xs text-gray-400 mt-1">${this.formatTime(notification.created_at)}</p>
                        </div>
                        ${!notification.read_at ? '<div class="w-2 h-2 bg-blue-500 rounded-full flex-shrink-0"></div>' : ''}
                    </div>
                </div>
            `;
        });

        container.innerHTML = html;
    }

    // Update unread count
    updateUnreadCount() {
        this.unreadCount = this.notifications.filter(n => !n.read_at).length;
        const badge = document.getElementById('notification-count');
        
        if (this.unreadCount > 0) {
            badge.textContent = this.unreadCount > 99 ? '99+' : this.unreadCount;
            badge.classList.remove('hidden');
        } else {
            badge.classList.add('hidden');
        }
    }

    // Get notification color class
    getNotificationColor(type) {
        const colors = {
            'info': 'border-blue-500',
            'success': 'border-green-500',
            'warning': 'border-yellow-500',
            'error': 'border-red-500',
            'departure': 'border-purple-500',
            'guide': 'border-indigo-500'
        };
        return colors[type] || 'border-gray-500';
    }

    // Get notification icon
    getNotificationIcon(type) {
        const icons = {
            'info': 'fa-info-circle',
            'success': 'fa-check-circle',
            'warning': 'fa-exclamation-triangle',
            'error': 'fa-times-circle',
            'departure': 'fa-plane-departure',
            'guide': 'fa-user-tie'
        };
        return icons[type] || 'fa-bell';
    }

    // Get notification text color
    getNotificationTextColor(type) {
        const colors = {
            'info': 'text-blue-500',
            'success': 'text-green-500',
            'warning': 'text-yellow-500',
            'error': 'text-red-500',
            'departure': 'text-purple-500',
            'guide': 'text-indigo-500'
        };
        return colors[type] || 'text-gray-500';
    }

    // Format time
    formatTime(timestamp) {
        const date = new Date(timestamp);
        const now = new Date();
        const diff = now - date;
        
        if (diff < 60000) { // Less than 1 minute
            return 'Vừa xong';
        } else if (diff < 3600000) { // Less than 1 hour
            return `${Math.floor(diff / 60000)} phút trước`;
        } else if (diff < 86400000) { // Less than 1 day
            return `${Math.floor(diff / 3600000)} giờ trước`;
        } else {
            return date.toLocaleDateString('vi-VN');
        }
    }

    // Play notification sound
    playNotificationSound() {
        // Create audio element for notification sound
        const audio = new Audio('data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvmwhBSuBzvLZiTYIG2m98OScTgwOUarm7blmGgU7k9n1unEiBC13yO/eizEIHWq+8+OWT');
        audio.volume = 0.3;
        audio.play().catch(() => {
            // Ignore audio play errors (browser restrictions)
        });
    }

    // Mark all notifications as read
    async markAllAsRead() {
        try {
            const response = await fetch('/api/notifications/mark-all-read', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            const data = await response.json();
            
            if (data.success) {
                this.notifications.forEach(n => n.read_at = new Date().toISOString());
                this.updateNotificationDisplay();
                this.updateUnreadCount();
            }
        } catch (error) {
            console.error('Error marking notifications as read:', error);
        }
    }

    // Create notification manually (for testing)
    createTestNotification(type = 'info') {
        const testNotifications = {
            'info': {
                title: 'Thông tin hệ thống',
                message: 'Hệ thống đang hoạt động bình thường',
                type: 'info'
            },
            'success': {
                title: 'Cập nhật thành công',
                message: 'Đã cập nhật thông tin khởi hành ID: 42',
                type: 'success'
            },
            'warning': {
                title: 'Cảnh báo',
                message: 'HDV chính chưa được gán cho chuyến đi ngày mai',
                type: 'warning'
            },
            'error': {
                title: 'Lỗi hệ thống',
                message: 'Không thể kết nối đến server',
                type: 'error'
            },
            'departure': {
                title: 'Thay đổi lịch khởi hành',
                message: 'Lịch khởi hành tour Sapa đã được cập nhật',
                type: 'departure'
            },
            'guide': {
                title: 'Phân công HDV',
                message: 'Đã gán HDV Nguyễn Văn Hùng cho tour Sapa',
                type: 'guide'
            }
        };

        const notification = {
            id: Date.now(),
            ...testNotifications[type],
            created_at: new Date().toISOString(),
            read_at: null
        };

        this.addNotification(notification);
    }
}

// Global functions
function toggleNotificationPanel() {
    const panel = document.getElementById('notification-panel');
    panel.classList.toggle('hidden');
}

function markAllAsRead() {
    if (window.notificationSystem) {
        window.notificationSystem.markAllAsRead();
    }
}

function viewAllNotifications() {
    // Redirect to full notifications page
    window.location.href = '/admin/notifications';
}

// Initialize notification system when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    window.notificationSystem = new NotificationSystem();
    
    // Add test buttons for development (remove in production)
    if (window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1') {
        const testButtons = document.createElement('div');
        testButtons.className = 'fixed bottom-6 left-6 space-y-2 z-40';
        testButtons.innerHTML = `
            <div class="bg-white rounded-lg shadow-lg p-2 space-y-1">
                <p class="text-xs font-medium text-gray-600 mb-2">Test Notifications:</p>
                <button onclick="window.notificationSystem.createTestNotification('success')" class="block w-full text-xs bg-green-100 text-green-800 px-2 py-1 rounded hover:bg-green-200">Success</button>
                <button onclick="window.notificationSystem.createTestNotification('warning')" class="block w-full text-xs bg-yellow-100 text-yellow-800 px-2 py-1 rounded hover:bg-yellow-200">Warning</button>
                <button onclick="window.notificationSystem.createTestNotification('error')" class="block w-full text-xs bg-red-100 text-red-800 px-2 py-1 rounded hover:bg-red-200">Error</button>
                <button onclick="window.notificationSystem.createTestNotification('departure')" class="block w-full text-xs bg-purple-100 text-purple-800 px-2 py-1 rounded hover:bg-purple-200">Departure</button>
            </div>
        `;
        document.body.appendChild(testButtons);
    }
});
</script>

<style>
.notification-toast {
    max-width: 400px;
    animation: slideIn 0.3s ease-out;
}

@keyframes slideIn {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

.notification-item:hover {
    cursor: pointer;
}

#notification-bell button {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.05);
    }
}
</style>
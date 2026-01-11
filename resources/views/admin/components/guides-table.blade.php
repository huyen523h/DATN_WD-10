<div class="table-container">
    <div class="table-header">
        <h3 class="table-title">
            <i class="fas fa-users me-2 text-purple"></i>
            Danh sách hướng dẫn viên
        </h3>
        <div class="table-actions">
            <button onclick="refreshGuides()" class="btn btn-sm btn-outline-purple">
                <i class="fas fa-sync-alt me-1"></i>Làm mới
            </button>
            <a href="{{ route('admin.guides.create') }}" class="btn btn-sm btn-purple">
                <i class="fas fa-user-plus me-1"></i>Thêm HDV
            </a>
        </div>
    </div>
    
    <div class="table-responsive-fixed">
        <table class="guides-table table-fixed">
            <thead>
                <tr>
                    <th class="col-avatar">
                        <i class="fas fa-image me-1"></i>
                        Ảnh
                    </th>
                    <th class="col-name">
                        <i class="fas fa-user me-1"></i>
                        Họ tên
                    </th>
                    <th class="col-contact">
                        <i class="fas fa-phone me-1"></i>
                        Liên hệ
                    </th>
                    <th class="col-experience">
                        <i class="fas fa-calendar-alt me-1"></i>
                        Kinh nghiệm
                    </th>
                    <th class="col-rating">
                        <i class="fas fa-star me-1"></i>
                        Đánh giá
                    </th>
                    <th class="col-status">
                        <i class="fas fa-info-circle me-1"></i>
                        Trạng thái
                    </th>
                    <th class="col-actions">
                        <i class="fas fa-cogs me-1"></i>
                        Thao tác
                    </th>
                </tr>
            </thead>
            <tbody id="guides-table-body">
                <!-- Data will be loaded via JavaScript -->
                <tr>
                    <td colspan="7" class="text-center py-4">
                        <div class="loading-state">
                            <div class="loading-spinner"></div>
                            <p class="text-muted mb-0">Đang tải dữ liệu...</p>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    
    <div class="pagination-container" id="guides-pagination" style="display: none;">
        <div class="pagination-info">
            Hiển thị <span id="guides-from">1</span> - <span id="guides-to">10</span> 
            trong tổng số <span id="guides-total">0</span> bản ghi
        </div>
        <div class="pagination-buttons">
            <button class="pagination-btn" id="guides-prev" onclick="changeGuidePage('prev')">
                <i class="fas fa-chevron-left"></i>
            </button>
            <span id="guides-pages"></span>
            <button class="pagination-btn" id="guides-next" onclick="changeGuidePage('next')">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
    </div>
</div>

<script>
// Render guides table
function renderGuidesTable(guides) {
    const tbody = document.getElementById('guides-table-body');
    const pagination = document.getElementById('guides-pagination');
    
    if (!guides || guides.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="7">
                    <div class="empty-state">
                        <i class="fas fa-user-plus"></i>
                        <h3>Chưa có hướng dẫn viên</h3>
                        <p>Thêm hướng dẫn viên đầu tiên vào hệ thống</p>
                        <a href="{{ route('admin.guides.create') }}" class="btn btn-purple">
                            <i class="fas fa-user-plus me-2"></i>Thêm HDV
                        </a>
                    </div>
                </td>
            </tr>
        `;
        pagination.style.display = 'none';
        return;
    }

    tbody.innerHTML = guides.map(guide => `
        <tr data-guide-id="${guide.id}">
            <td class="col-avatar">
                ${guide.avatar_url ? 
                    `<img src="${guide.avatar_url}" alt="${guide.name}" class="guide-avatar">` :
                    `<div class="guide-avatar-placeholder">
                        ${(guide.name || guide.full_name || 'G').charAt(0).toUpperCase()}
                    </div>`
                }
            </td>
            <td class="col-name">
                <div class="fw-semibold">${guide.name || guide.full_name || 'N/A'}</div>
                <div class="text-muted small">
                    ${guide.code ? `Mã: ${guide.code}` : 'Chưa có mã'}
                </div>
            </td>
            <td class="col-contact">
                <div class="small">
                    ${guide.email ? 
                        `<div><i class="fas fa-envelope text-primary me-1"></i>${guide.email}</div>` : 
                        ''
                    }
                    ${guide.phone ? 
                        `<div><i class="fas fa-phone text-success me-1"></i>${guide.phone}</div>` : 
                        '<div class="text-muted">Chưa có SĐT</div>'
                    }
                </div>
            </td>
            <td class="col-experience">
                <div class="text-center">
                    <div class="fw-semibold text-primary">${guide.experience_years || 0}</div>
                    <div class="text-muted small">năm</div>
                </div>
            </td>
            <td class="col-rating">
                <div class="text-center">
                    <div class="rating-stars">
                        ${renderStars(guide.rating_average || 0)}
                    </div>
                    <div class="text-muted small">
                        ${(guide.rating_average || 0).toFixed(1)} (${guide.rating_count || 0})
                    </div>
                </div>
            </td>
            <td class="col-status">
                <span class="status-badge ${getGuideStatusClass(guide)}">
                    ${getGuideStatusText(guide)}
                </span>
            </td>
            <td class="col-actions">
                <div class="d-flex gap-1">
                    <button class="action-btn action-btn-view" 
                            onclick="viewGuide(${guide.id})" 
                            title="Xem chi tiết">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="action-btn action-btn-edit" 
                            onclick="editGuide(${guide.id})" 
                            title="Chỉnh sửa">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="action-btn action-btn-delete" 
                            onclick="deleteGuide(${guide.id})" 
                            title="Xóa">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </td>
        </tr>
    `).join('');
    
    pagination.style.display = 'flex';
    updateGuidePaginationInfo(guides.length, guides.length, 1, 1);
}

function renderStars(rating) {
    const fullStars = Math.floor(rating);
    const hasHalfStar = rating % 1 >= 0.5;
    const emptyStars = 5 - fullStars - (hasHalfStar ? 1 : 0);
    
    let starsHtml = '';
    
    // Full stars
    for (let i = 0; i < fullStars; i++) {
        starsHtml += '<i class="fas fa-star rating-star"></i>';
    }
    
    // Half star
    if (hasHalfStar) {
        starsHtml += '<i class="fas fa-star-half-alt rating-star"></i>';
    }
    
    // Empty stars
    for (let i = 0; i < emptyStars; i++) {
        starsHtml += '<i class="far fa-star rating-star empty"></i>';
    }
    
    return starsHtml;
}

function getGuideStatusClass(guide) {
    if (guide.user_id) {
        return 'status-ready'; // Có tài khoản user
    }
    return 'status-pending'; // Chưa có tài khoản user
}

function getGuideStatusText(guide) {
    if (guide.user_id) {
        return 'Hoạt động';
    }
    return 'Chưa kích hoạt';
}

function updateGuidePaginationInfo(from, to, total, currentPage) {
    document.getElementById('guides-from').textContent = from;
    document.getElementById('guides-to').textContent = to;
    document.getElementById('guides-total').textContent = total;
}

// Action functions
function viewGuide(id) {
    window.location.href = `/admin/guides/${id}`;
}

function editGuide(id) {
    window.location.href = `/admin/guides/${id}/edit`;
}

function deleteGuide(id) {
    if (confirm('Bạn có chắc chắn muốn xóa hướng dẫn viên này?')) {
        // Implement delete logic
        fetch(`/admin/guides/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Đã xóa hướng dẫn viên thành công', 'success');
                refreshGuides();
            } else {
                showNotification(data.message || 'Có lỗi xảy ra', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Có lỗi xảy ra khi xóa', 'error');
        });
    }
}

function refreshGuides() {
    if (typeof loadAllGuides === 'function') {
        loadAllGuides();
    } else {
        console.log('Refresh guides');
        location.reload();
    }
}

function changeGuidePage(direction) {
    console.log('Change guide page:', direction);
    // Implement pagination logic
}

// Notification function
function showNotification(message, type = 'info') {
    const alertClass = type === 'error' ? 'danger' : type;
    const notification = document.createElement('div');
    notification.className = `alert alert-${alertClass} alert-dismissible fade show position-fixed`;
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    
    notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        if (notification.parentNode) {
            notification.parentNode.removeChild(notification);
        }
    }, 3000);
}
</script>

<style>
.btn-purple {
    background-color: #9f7aea;
    border-color: #9f7aea;
    color: white;
}

.btn-purple:hover {
    background-color: #805ad5;
    border-color: #805ad5;
    color: white;
}

.btn-outline-purple {
    border-color: #9f7aea;
    color: #9f7aea;
}

.btn-outline-purple:hover {
    background-color: #9f7aea;
    border-color: #9f7aea;
    color: white;
}

.text-purple {
    color: #9f7aea !important;
}
</style>
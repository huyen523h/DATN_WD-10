<div class="table-container">
    <div class="table-header">
        <h3 class="table-title">
            <i class="fas fa-calendar-alt me-2 text-success"></i>
            Lịch trình từng ngày
        </h3>
        <div class="table-actions">
            <div class="d-flex align-items-center gap-3">
                <div class="d-flex align-items-center gap-2">
                    <label class="small text-muted mb-0">Hiển thị:</label>
                    <select id="schedule-view-mode" onchange="toggleScheduleViewMode()" class="form-select form-select-sm" style="width: auto;">
                        <option value="template">Lịch trình gốc (Ngày 1, 2, 3...)</option>
                        <option value="actual">Ngày cụ thể (theo departure)</option>
                    </select>
                </div>
                <button onclick="refreshSchedules()" class="btn btn-sm btn-outline-success">
                    <i class="fas fa-sync-alt me-1"></i>Làm mới
                </button>
                <button onclick="openScheduleModal()" class="btn btn-sm btn-success">
                    <i class="fas fa-plus me-1"></i>Thêm ngày
                </button>
            </div>
        </div>
    </div>
    
    <div class="table-responsive-fixed">
        <table class="schedules-table table-fixed">
            <thead>
                <tr>
                    <th class="col-day">
                        <i class="fas fa-hashtag me-1"></i>
                        Ngày
                    </th>
                    <th class="col-title">
                        <i class="fas fa-heading me-1"></i>
                        Tiêu đề
                    </th>
                    <th class="col-location">
                        <i class="fas fa-map-marker-alt me-1"></i>
                        Địa điểm
                    </th>
                    <th class="col-description">
                        <i class="fas fa-align-left me-1"></i>
                        Mô tả
                    </th>
                    <th class="col-activities">
                        <i class="fas fa-tasks me-1"></i>
                        Hoạt động
                    </th>
                    <th class="col-actions">
                        <i class="fas fa-cogs me-1"></i>
                        Thao tác
                    </th>
                </tr>
            </thead>
            <tbody id="schedules-table-body">
                <!-- Data will be loaded via JavaScript -->
                <tr>
                    <td colspan="6" class="text-center py-4">
                        <div class="loading-state">
                            <div class="loading-spinner"></div>
                            <p class="text-muted mb-0">Đang tải dữ liệu...</p>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    
    <div class="pagination-container" id="schedules-pagination" style="display: none;">
        <div class="pagination-info">
            Hiển thị <span id="schedules-from">1</span> - <span id="schedules-to">10</span> 
            trong tổng số <span id="schedules-total">0</span> bản ghi
        </div>
        <div class="pagination-buttons">
            <button class="pagination-btn" id="schedules-prev" onclick="changeSchedulePage('prev')">
                <i class="fas fa-chevron-left"></i>
            </button>
            <span id="schedules-pages"></span>
            <button class="pagination-btn" id="schedules-next" onclick="changeSchedulePage('next')">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
    </div>
</div>

<script>
// Render schedules table
function renderSchedulesTable(schedules, viewMode = 'template') {
    const tbody = document.getElementById('schedules-table-body');
    const pagination = document.getElementById('schedules-pagination');
    
    if (!schedules || schedules.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="6">
                    <div class="empty-state">
                        <i class="fas fa-calendar-plus"></i>
                        <h3>Chưa có lịch trình</h3>
                        <p>Thêm lịch trình đầu tiên cho tour này</p>
                        <button onclick="openScheduleModal()" class="btn btn-success">
                            <i class="fas fa-plus me-2"></i>Thêm lịch trình
                        </button>
                    </div>
                </td>
            </tr>
        `;
        pagination.style.display = 'none';
        return;
    }

    // Sort schedules by day_number
    const sortedSchedules = [...schedules].sort((a, b) => a.day_number - b.day_number);

    tbody.innerHTML = sortedSchedules.map(schedule => `
        <tr data-schedule-id="${schedule.id}">
            <td class="col-day">
                <div class="d-flex align-items-center">
                    <div class="bg-success bg-gradient rounded-circle d-flex align-items-center justify-content-center text-white fw-bold" 
                         style="width: 32px; height: 32px; font-size: 14px;">
                        ${schedule.day_number}
                    </div>
                    <div class="ms-2">
                        <div class="fw-semibold">Ngày ${schedule.day_number}</div>
                        ${viewMode === 'actual' && schedule.actual_date ? 
                            `<div class="text-muted small">${formatDate(schedule.actual_date)}</div>` : 
                            ''
                        }
                    </div>
                </div>
            </td>
            <td class="col-title">
                <div class="fw-semibold">${schedule.title}</div>
                ${schedule.meals ? `<div class="text-muted small"><i class="fas fa-utensils me-1"></i>${schedule.meals}</div>` : ''}
            </td>
            <td class="col-location">
                <div class="text-truncate" style="max-width: 180px;" title="${schedule.location || 'Chưa xác định'}">
                    ${schedule.location ? 
                        `<i class="fas fa-map-marker-alt text-danger me-1"></i>${schedule.location}` : 
                        '<span class="text-muted">Chưa xác định</span>'
                    }
                </div>
            </td>
            <td class="col-description">
                <div class="text-truncate" style="max-width: 250px;" title="${schedule.description || ''}">
                    ${schedule.description || '<span class="text-muted">Chưa có mô tả</span>'}
                </div>
            </td>
            <td class="col-activities">
                ${schedule.activities ? 
                    `<span class="badge bg-info">${schedule.activities}</span>` : 
                    '<span class="text-muted small">Chưa có</span>'
                }
            </td>
            <td class="col-actions">
                <div class="d-flex gap-1">
                    <button class="action-btn action-btn-view" 
                            onclick="viewSchedule(${schedule.id})" 
                            title="Xem chi tiết">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="action-btn action-btn-edit" 
                            onclick="editSchedule(${schedule.id})" 
                            title="Chỉnh sửa">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="action-btn action-btn-delete" 
                            onclick="deleteSchedule(${schedule.id})" 
                            title="Xóa">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </td>
        </tr>
    `).join('');
    
    pagination.style.display = 'flex';
    updateSchedulePaginationInfo(sortedSchedules.length, sortedSchedules.length, 1, 1);
}

function toggleScheduleViewMode() {
    const viewMode = document.getElementById('schedule-view-mode').value;
    console.log('Toggle schedule view mode:', viewMode);
    
    // Re-render table with new view mode
    if (window.currentTourSchedules) {
        renderSchedulesTable(window.currentTourSchedules, viewMode);
    }
    
    // Show notification
    const modeText = viewMode === 'template' ? 'Lịch trình gốc' : 'Ngày cụ thể';
    showNotification(`Đã chuyển sang chế độ hiển thị: ${modeText}`, 'info');
}

function formatDate(dateString) {
    if (!dateString) return 'N/A';
    const date = new Date(dateString);
    return date.toLocaleDateString('vi-VN', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
    });
}

function updateSchedulePaginationInfo(from, to, total, currentPage) {
    document.getElementById('schedules-from').textContent = from;
    document.getElementById('schedules-to').textContent = to;
    document.getElementById('schedules-total').textContent = total;
}

// Action functions
function viewSchedule(id) {
    console.log('View schedule:', id);
    // Implement view logic
}

function editSchedule(id) {
    if (typeof openScheduleModal === 'function') {
        openScheduleModal(id);
    } else {
        console.log('Edit schedule:', id);
    }
}

function deleteSchedule(id) {
    if (confirm('Bạn có chắc chắn muốn xóa lịch trình này?')) {
        console.log('Delete schedule:', id);
        // Implement delete logic
    }
}

function refreshSchedules() {
    if (typeof loadTourData === 'function') {
        loadTourData();
    } else {
        console.log('Refresh schedules');
    }
}

function changeSchedulePage(direction) {
    console.log('Change schedule page:', direction);
    // Implement pagination logic
}

// Notification function
function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    
    notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(notification);
    
    // Auto remove after 3 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.parentNode.removeChild(notification);
        }
    }, 3000);
}
</script>
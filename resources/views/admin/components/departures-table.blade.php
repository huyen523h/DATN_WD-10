<div class="table-container">
    <div class="table-header">
        <h3 class="table-title">
            <i class="fas fa-plane-departure me-2 text-primary"></i>
            Danh sách khởi hành
        </h3>
        <div class="table-actions">
            <button onclick="refreshDepartures()" class="btn btn-sm btn-outline-primary">
                <i class="fas fa-sync-alt me-1"></i>Làm mới
            </button>
            <button onclick="openCreateDepartureModal()" class="btn btn-sm btn-success">
                <i class="fas fa-plus me-1"></i>Thêm mới
            </button>
        </div>
    </div>
    
    <div class="table-responsive-fixed">
        <table class="departures-table table-fixed">
            <thead>
                <tr>
                    <th class="col-date">
                        <i class="fas fa-calendar me-1"></i>
                        Ngày khởi hành
                    </th>
                    <th class="col-time">
                        <i class="fas fa-clock me-1"></i>
                        Giờ
                    </th>
                    <th class="col-location">
                        <i class="fas fa-map-marker-alt me-1"></i>
                        Địa điểm
                    </th>
                    <th class="col-guide">
                        <i class="fas fa-user-tie me-1"></i>
                        HDV chính
                    </th>
                    <th class="col-backup">
                        <i class="fas fa-user-shield me-1"></i>
                        HDV dự phòng
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
            <tbody id="departures-table-body">
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
    
    <div class="pagination-container" id="departures-pagination" style="display: none;">
        <div class="pagination-info">
            Hiển thị <span id="departures-from">1</span> - <span id="departures-to">10</span> 
            trong tổng số <span id="departures-total">0</span> bản ghi
        </div>
        <div class="pagination-buttons">
            <button class="pagination-btn" id="departures-prev" onclick="changePage('prev')">
                <i class="fas fa-chevron-left"></i>
            </button>
            <span id="departures-pages"></span>
            <button class="pagination-btn" id="departures-next" onclick="changePage('next')">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
    </div>
</div>

<script>
// Render departures table
function renderDeparturesTable(departures) {
    const tbody = document.getElementById('departures-table-body');
    const pagination = document.getElementById('departures-pagination');
    
    if (!departures || departures.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="7">
                    <div class="empty-state">
                        <i class="fas fa-plane-departure"></i>
                        <h3>Chưa có lịch khởi hành</h3>
                        <p>Thêm lịch khởi hành đầu tiên cho tour này</p>
                        <button onclick="openCreateDepartureModal()" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Thêm departure
                        </button>
                    </div>
                </td>
            </tr>
        `;
        pagination.style.display = 'none';
        return;
    }

    tbody.innerHTML = departures.map(departure => `
        <tr data-departure-id="${departure.id}">
            <td class="col-date">
                <div class="fw-semibold">${formatDate(departure.departure_date)}</div>
                <div class="text-muted small">${getDayOfWeek(departure.departure_date)}</div>
            </td>
            <td class="col-time">
                <span class="badge bg-info">
                    ${departure.departure_time || 'Chưa xác định'}
                </span>
            </td>
            <td class="col-location">
                <div class="text-truncate" style="max-width: 200px;" title="${departure.departure_location || 'Chưa xác định'}">
                    <i class="fas fa-map-marker-alt text-danger me-1"></i>
                    ${departure.departure_location || 'Chưa xác định'}
                </div>
            </td>
            <td class="col-guide">
                ${renderGuideInfo(departure.guide_name, departure.guide_phone, 'primary')}
            </td>
            <td class="col-backup">
                ${renderGuideInfo(departure.backup_guide_name, departure.backup_guide_phone, 'warning')}
            </td>
            <td class="col-status">
                <span class="status-badge status-${departure.preparation_status || 'pending'}">
                    ${getStatusText(departure.preparation_status)}
                </span>
            </td>
            <td class="col-actions">
                <div class="d-flex gap-1">
                    <button class="action-btn action-btn-view" 
                            onclick="viewDeparture(${departure.id})" 
                            title="Xem chi tiết">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="action-btn action-btn-edit" 
                            onclick="editDeparture(${departure.id})" 
                            title="Chỉnh sửa">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="action-btn action-btn-delete" 
                            onclick="deleteDeparture(${departure.id})" 
                            title="Xóa">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </td>
        </tr>
    `).join('');
    
    pagination.style.display = 'flex';
    updatePaginationInfo(departures.length, departures.length, 1, 1);
}

function renderGuideInfo(name, phone, colorClass) {
    if (!name) {
        return `
            <div class="text-muted small">
                <i class="fas fa-user-slash me-1"></i>
                Chưa gán
            </div>
        `;
    }
    
    return `
        <div class="d-flex align-items-center">
            <div class="guide-avatar-placeholder bg-${colorClass} me-2">
                ${name.charAt(0).toUpperCase()}
            </div>
            <div>
                <div class="fw-semibold small">${name}</div>
                ${phone ? `<div class="text-muted" style="font-size: 11px;">${phone}</div>` : ''}
            </div>
        </div>
    `;
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

function getDayOfWeek(dateString) {
    if (!dateString) return '';
    const date = new Date(dateString);
    const days = ['Chủ nhật', 'Thứ 2', 'Thứ 3', 'Thứ 4', 'Thứ 5', 'Thứ 6', 'Thứ 7'];
    return days[date.getDay()];
}

function getStatusText(status) {
    const statusTexts = {
        'pending': 'Đang chuẩn bị',
        'ready': 'Sẵn sàng',
        'confirmed': 'Đã xác nhận',
        'cancelled': 'Đã hủy',
        'draft': 'Nháp'
    };
    return statusTexts[status] || 'Không xác định';
}

function updatePaginationInfo(from, to, total, currentPage) {
    document.getElementById('departures-from').textContent = from;
    document.getElementById('departures-to').textContent = to;
    document.getElementById('departures-total').textContent = total;
}

// Action functions
function viewDeparture(id) {
    console.log('View departure:', id);
    // Implement view logic
}

function editDeparture(id) {
    if (typeof openDepartureEditModal === 'function') {
        openDepartureEditModal(id);
    } else {
        console.log('Edit departure:', id);
    }
}

function deleteDeparture(id) {
    if (confirm('Bạn có chắc chắn muốn xóa departure này?')) {
        console.log('Delete departure:', id);
        // Implement delete logic
    }
}

function changePage(direction) {
    console.log('Change page:', direction);
    // Implement pagination logic
}
</script>
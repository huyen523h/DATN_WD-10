<!-- Departure Edit Modal -->
<div id="departure-edit-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-4xl max-h-screen overflow-y-auto m-4">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-semibold text-gray-800">
                <i class="fas fa-edit mr-2 text-blue-600"></i>
                Chỉnh sửa thông tin khởi hành
            </h3>
            <button onclick="closeDepartureModal()" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <form id="departure-edit-form" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Basic Information -->
                <div class="space-y-4">
                    <h4 class="font-semibold text-gray-700 border-b pb-2">Thông tin cơ bản</h4>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Ngày khởi hành</label>
                        <input type="date" id="departure-date" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required onchange="showDateChangeWarning()">
                        <div id="date-change-warning" class="hidden mt-2 p-2 bg-yellow-50 border border-yellow-200 rounded text-xs text-yellow-700">
                            <i class="fas fa-exclamation-triangle mr-1"></i>
                            Thay đổi ngày khởi hành sẽ ảnh hưởng đến ngày cụ thể của tất cả lịch trình trong tour này.
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Giờ khởi hành</label>
                        <input type="time" id="departure-time" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Địa điểm khởi hành</label>
                        <input type="text" id="departure-location" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Ví dụ: Văn phòng công ty - 123 Đường ABC, Quận Ba Đình, Hà Nội">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Hướng dẫn khởi hành</label>
                        <textarea id="departure-instructions" rows="3" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Hướng dẫn chi tiết cho khách hàng..."></textarea>
                    </div>
                </div>

                <!-- Guide Assignment -->
                <div class="space-y-4">
                    <h4 class="font-semibold text-gray-700 border-b pb-2">Phân công hướng dẫn viên</h4>
                    
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label class="block text-sm font-medium text-gray-700">Hướng dẫn viên chính</label>
                            <button type="button" onclick="refreshGuidesList()" class="text-xs text-blue-600 hover:text-blue-800">
                                <i class="fas fa-refresh mr-1"></i>Làm mới
                            </button>
                        </div>
                        <select id="main-guide" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Chọn hướng dẫn viên chính</option>
                        </select>
                    </div>
                    
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label class="block text-sm font-medium text-gray-700">Hướng dẫn viên dự phòng</label>
                            <button type="button" onclick="refreshGuidesList()" class="text-xs text-blue-600 hover:text-blue-800">
                                <i class="fas fa-refresh mr-1"></i>Làm mới
                            </button>
                        </div>
                        <select id="backup-guide" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Chọn hướng dẫn viên dự phòng</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Liên hệ khẩn cấp</label>
                        <input type="text" id="emergency-contact" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Tên người liên hệ khẩn cấp">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">SĐT khẩn cấp</label>
                        <input type="tel" id="emergency-phone" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="0123456789">
                    </div>
                </div>
            </div>

            <!-- Advanced Settings -->
            <div class="border-t pt-6">
                <h4 class="font-semibold text-gray-700 mb-4">Cài đặt nâng cao</h4>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Trạng thái chuẩn bị</label>
                        <select id="preparation-status" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="pending">Đang chuẩn bị</option>
                            <option value="ready">Sẵn sàng</option>
                            <option value="confirmed">Đã xác nhận</option>
                            <option value="cancelled">Đã hủy</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Số chỗ tối đa</label>
                        <input type="number" id="seats-total" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" min="1" max="50">
                    </div>
                </div>
                
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Ghi chú đặc biệt</label>
                    <textarea id="special-notes" rows="3" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Ghi chú đặc biệt cho chuyến đi này..."></textarea>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-4 pt-6 border-t">
                <button type="button" onclick="closeDepartureModal()" class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors">
                    <i class="fas fa-times mr-2"></i>Hủy
                </button>
                <button type="button" onclick="saveDraftDeparture()" class="px-6 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors">
                    <i class="fas fa-save mr-2"></i>Lưu nháp
                </button>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                    <i class="fas fa-check mr-2"></i>Lưu thay đổi
                </button>
            </div>
        </form>
    </div>
</div>

<script>
let currentDepartureId = null;

// Open departure edit modal
function openDepartureEditModal(departureId) {
    currentDepartureId = departureId;
    document.getElementById('departure-edit-modal').classList.remove('hidden');
    loadDepartureData(departureId);
    loadAvailableGuides();
}

// Close departure edit modal
function closeDepartureModal() {
    document.getElementById('departure-edit-modal').classList.add('hidden');
    currentDepartureId = null;
}

// Load departure data
async function loadDepartureData(departureId) {
    try {
        const response = await fetch(`/api/departures/${departureId}`);
        const data = await response.json();
        
        if (data.success) {
            const departure = data.data;
            
            // Fill form fields
            document.getElementById('departure-date').value = departure.departure_date;
            document.getElementById('departure-time').value = departure.departure_time || '';
            document.getElementById('departure-location').value = departure.departure_location || '';
            document.getElementById('departure-instructions').value = departure.departure_instructions || '';
            
            // Check for invalid guide assignment
            if (departure.guide_id && departure.backup_guide_id && 
                departure.guide_id === departure.backup_guide_id) {
                console.warn('Invalid data detected: Same guide for both roles!', {
                    guide_id: departure.guide_id,
                    backup_guide_id: departure.backup_guide_id
                });
                showNotification('⚠️ Dữ liệu không hợp lệ: Cùng HDV được gán cho cả 2 vai trò!', 'warning');
            }
            
            document.getElementById('main-guide').value = departure.guide_id || '';
            document.getElementById('backup-guide').value = departure.backup_guide_id || '';
            document.getElementById('emergency-contact').value = departure.emergency_contact || '';
            document.getElementById('emergency-phone').value = departure.emergency_phone || '';
            document.getElementById('preparation-status').value = departure.preparation_status || 'pending';
            document.getElementById('seats-total').value = departure.seats_total || '';
            document.getElementById('special-notes').value = departure.special_notes || '';
        }
    } catch (error) {
        console.error('Error loading departure data:', error);
        showNotification('Không thể tải dữ liệu khởi hành', 'error');
    }
}

// Load available guides
async function loadAvailableGuides() {
    try {
        // Get departure date to check for conflicts
        const departureDate = document.getElementById('departure-date').value;
        let url = '/api/guides/available';
        
        if (departureDate && currentDepartureId) {
            url += `?date=${departureDate}&exclude_departure_id=${currentDepartureId}`;
        } else if (departureDate) {
            url += `?date=${departureDate}`;
        }
        
        console.log('Loading guides from:', url);
        
        const response = await fetch(url);
        const data = await response.json();
        
        console.log('Guides API response:', data);
        
        if (data.success) {
            const mainGuideSelect = document.getElementById('main-guide');
            const backupGuideSelect = document.getElementById('backup-guide');
            
            // Clear existing options (except first one)
            mainGuideSelect.innerHTML = '<option value="">Chọn hướng dẫn viên chính</option>';
            backupGuideSelect.innerHTML = '<option value="">Chọn hướng dẫn viên dự phòng</option>';
            
            // Add guide options
            console.log(`Adding ${data.data.length} guides to dropdowns`);
            
            data.data.forEach((guide, index) => {
                let guideName = guide.name;
                let guideInfo = `${guide.name} (${guide.email})`;
                
                console.log(`Adding guide ${index + 1}:`, guide);
                
                // Add conflict warning if guide is not available
                if (guide.is_available === false && guide.conflicts && guide.conflicts.length > 0) {
                    const conflictInfo = guide.conflicts.map(c => `${c.tour_title} (${c.role})`).join(', ');
                    guideInfo += ` - ⚠️ Đã gán: ${conflictInfo}`;
                    guideName += ' (Xung đột)';
                }
                
                const option1 = new Option(guideInfo, guide.id);
                const option2 = new Option(guideInfo, guide.id);
                
                // Disable options if there are conflicts
                if (guide.is_available === false) {
                    option1.disabled = true;
                    option2.disabled = true;
                    option1.style.color = '#ef4444';
                    option2.style.color = '#ef4444';
                }
                
                mainGuideSelect.add(option1);
                backupGuideSelect.add(option2);
            });
            
            console.log('Guides loaded successfully');
            showNotification(`Đã tải ${data.data.length} HDV`, 'success');
        }
    } catch (error) {
        console.error('Error loading guides:', error);
    }
}

// Save departure changes
document.getElementById('departure-edit-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    if (!currentDepartureId) return;
    
    const formData = {
        departure_date: document.getElementById('departure-date').value,
        departure_time: document.getElementById('departure-time').value,
        departure_location: document.getElementById('departure-location').value,
        departure_instructions: document.getElementById('departure-instructions').value,
        guide_id: document.getElementById('main-guide').value || null,
        backup_guide_id: document.getElementById('backup-guide').value || null,
        emergency_contact: document.getElementById('emergency-contact').value,
        emergency_phone: document.getElementById('emergency-phone').value,
        preparation_status: document.getElementById('preparation-status').value,
        seats_total: document.getElementById('seats-total').value,
        special_notes: document.getElementById('special-notes').value
    };
    
    // Frontend validation for guide conflicts
    if (formData.guide_id && formData.backup_guide_id && 
        (formData.guide_id === formData.backup_guide_id || 
         parseInt(formData.guide_id) === parseInt(formData.backup_guide_id))) {
        showNotification('HDV chính và HDV dự phòng không thể là cùng một người!', 'error');
        return;
    }
    
    // Check if selected guides are available
    const mainGuideSelect = document.getElementById('main-guide');
    const backupGuideSelect = document.getElementById('backup-guide');
    
    if (formData.guide_id) {
        const mainOption = mainGuideSelect.querySelector(`option[value="${formData.guide_id}"]`);
        if (mainOption && mainOption.disabled) {
            showNotification('HDV chính đã được chọn bị xung đột lịch trình!', 'error');
            return;
        }
    }
    
    if (formData.backup_guide_id) {
        const backupOption = backupGuideSelect.querySelector(`option[value="${formData.backup_guide_id}"]`);
        if (backupOption && backupOption.disabled) {
            showNotification('HDV dự phòng đã được chọn bị xung đột lịch trình!', 'error');
            return;
        }
    }
    
    try {
        console.log('=== DEPARTURE UPDATE DEBUG ===');
        console.log('Current departure ID:', currentDepartureId);
        console.log('Sending update request with data:', formData);
        
        const response = await fetch(`/api/departures/${currentDepartureId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(formData)
        });
        
        console.log('Response status:', response.status);
        console.log('Response headers:', response.headers);
        
        const result = await response.json();
        console.log('Response result:', result);
        
        if (result.success) {
            showNotification('Cập nhật thông tin khởi hành thành công!', 'success');
            
            // Refresh departure data in main page BEFORE closing modal
            if (typeof syncAllData === 'function') {
                await syncAllData();
            } else if (typeof refreshDepartures === 'function') {
                await refreshDepartures();
            } else if (typeof loadTourData === 'function') {
                await loadTourData();
            }
            
            // Close modal after data sync
            setTimeout(() => {
                closeDepartureModal();
            }, 500);
            
            // Trigger notification in main system
            if (window.notificationSystem) {
                window.notificationSystem.addNotification({
                    id: Date.now(),
                    title: 'Cập nhật thành công',
                    message: `Đã cập nhật thông tin khởi hành ID: ${currentDepartureId}`,
                    type: 'success',
                    created_at: new Date().toISOString(),
                    read_at: null
                });
            }
            
            // Trigger custom event for parent page
            window.dispatchEvent(new CustomEvent('departureUpdated', {
                detail: {
                    departureId: currentDepartureId,
                    newData: result.data
                }
            }));
        } else {
            showNotification(result.message || 'Có lỗi xảy ra', 'error');
        }
    } catch (error) {
        console.error('Error saving departure:', error);
        showNotification('Không thể lưu thay đổi', 'error');
    }
});

// Save draft function
async function saveDraftDeparture() {
    if (!currentDepartureId) return;
    
    const formData = {
        departure_date: document.getElementById('departure-date').value,
        departure_time: document.getElementById('departure-time').value,
        departure_location: document.getElementById('departure-location').value,
        departure_instructions: document.getElementById('departure-instructions').value,
        guide_id: document.getElementById('main-guide').value || null,
        backup_guide_id: document.getElementById('backup-guide').value || null,
        emergency_contact: document.getElementById('emergency-contact').value,
        emergency_phone: document.getElementById('emergency-phone').value,
        preparation_status: 'draft',
        seats_total: document.getElementById('seats-total').value,
        special_notes: document.getElementById('special-notes').value
    };
    
    try {
        const response = await fetch(`/api/departures/${currentDepartureId}/draft`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(formData)
        });
        
        const result = await response.json();
        
        if (result.success) {
            showNotification('Đã lưu nháp thành công!', 'info');
        } else {
            showNotification(result.message || 'Không thể lưu nháp', 'error');
        }
    } catch (error) {
        console.error('Error saving draft:', error);
        showNotification('Không thể lưu nháp', 'error');
    }
}

// Notification function
function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full`;
    
    // Set notification style based on type
    switch(type) {
        case 'success':
            notification.classList.add('bg-green-500', 'text-white');
            break;
        case 'error':
            notification.classList.add('bg-red-500', 'text-white');
            break;
        case 'info':
            notification.classList.add('bg-blue-500', 'text-white');
            break;
        default:
            notification.classList.add('bg-gray-500', 'text-white');
    }
    
    notification.innerHTML = `
        <div class="flex items-center space-x-2">
            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'}"></i>
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 100);
    
    // Auto remove after 3 seconds
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 3000);
}

// Show warning when date changes
function showDateChangeWarning() {
    const warning = document.getElementById('date-change-warning');
    const newDate = document.getElementById('departure-date').value;
    
    console.log('Date changed to:', newDate);
    
    if (warning) {
        warning.classList.remove('hidden');
        setTimeout(() => {
            warning.classList.add('hidden');
        }, 5000);
    }
}

// Close modal when clicking outside
document.getElementById('departure-edit-modal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDepartureModal();
    }
});

// Reload guides when departure date changes
document.getElementById('departure-date').addEventListener('change', function() {
    loadAvailableGuides();
});

// Add validation when guide selection changes
document.getElementById('main-guide').addEventListener('change', function() {
    validateGuideSelection();
});

document.getElementById('backup-guide').addEventListener('change', function() {
    validateGuideSelection();
});

// Validate guide selection to prevent same guide for both roles
function validateGuideSelection() {
    const mainGuideId = document.getElementById('main-guide').value;
    const backupGuideId = document.getElementById('backup-guide').value;
    
    // Clear previous warnings
    clearGuideWarnings();
    
    if (mainGuideId && backupGuideId && 
        (mainGuideId === backupGuideId || parseInt(mainGuideId) === parseInt(backupGuideId))) {
        
        showGuideWarning('HDV chính và HDV dự phòng không thể là cùng một người!');
        
        // Reset the last changed selection
        const lastChanged = event.target.id;
        if (lastChanged === 'backup-guide') {
            document.getElementById('backup-guide').value = '';
        } else if (lastChanged === 'main-guide') {
            document.getElementById('main-guide').value = '';
        }
        
        return false;
    }
    
    return true;
}

function showGuideWarning(message) {
    // Remove existing warning
    clearGuideWarnings();
    
    // Create warning element
    const warning = document.createElement('div');
    warning.id = 'guide-selection-warning';
    warning.className = 'mt-2 p-2 bg-red-50 border border-red-200 rounded text-sm text-red-700';
    warning.innerHTML = `<i class="fas fa-exclamation-triangle mr-1"></i>${message}`;
    
    // Add after backup guide select
    const backupGuideDiv = document.getElementById('backup-guide').parentElement;
    backupGuideDiv.appendChild(warning);
}

function clearGuideWarnings() {
    const existingWarning = document.getElementById('guide-selection-warning');
    if (existingWarning) {
        existingWarning.remove();
    }
}

// Force refresh guides list
function refreshGuidesList() {
    console.log('Refreshing guides list...');
    showNotification('Đang làm mới danh sách HDV...', 'info');
    
    // Clear current selections
    document.getElementById('main-guide').innerHTML = '<option value="">Chọn hướng dẫn viên chính</option>';
    document.getElementById('backup-guide').innerHTML = '<option value="">Chọn hướng dẫn viên dự phòng</option>';
    
    // Reload guides
    loadAvailableGuides();
}
</script>
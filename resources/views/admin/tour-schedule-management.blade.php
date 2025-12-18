<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Quản lý Lịch trình Tour - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <!-- Admin Header -->
    <header class="bg-white shadow-sm border-b">
        <div class="container mx-auto px-4 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <a href="/admin" class="text-blue-600 hover:text-blue-800">
                        <i class="fas fa-arrow-left mr-2"></i>Quay lại Dashboard
                    </a>
                    <h1 class="text-xl font-semibold text-gray-800">Quản lý Lịch trình Tour</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <button onclick="syncAllData()" class="text-sm bg-green-100 text-green-700 px-3 py-1 rounded-md hover:bg-green-200 transition-colors" title="Đồng bộ dữ liệu">
                        <i class="fas fa-sync mr-1"></i>Đồng bộ
                    </button>
                    <span class="text-sm text-gray-600">Admin Panel</span>
                    <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white text-sm font-medium">
                        A
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container mx-auto px-4 py-8">
        <!-- Tour Selection -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">
                <i class="fas fa-search mr-2 text-blue-600"></i>
                Chọn Tour để quản lý
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tour ID</label>
                    <input 
                        type="number" 
                        id="tour-id-input" 
                        value="14" 
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Nhập ID tour"
                    >
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Departure ID (tùy chọn)</label>
                    <input 
                        type="number" 
                        id="departure-id-input" 
                        value="42" 
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Nhập ID departure"
                    >
                </div>
                <div class="flex items-end">
                    <button 
                        onclick="loadTourData()" 
                        class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors"
                    >
                        <i class="fas fa-search mr-2"></i>Tải dữ liệu
                    </button>
                </div>
                <div class="flex items-end">
                    <button 
                        onclick="showAllDepartures()" 
                        class="w-full bg-orange-600 text-white px-4 py-2 rounded-md hover:bg-orange-700 transition-colors"
                    >
                        <i class="fas fa-list mr-2"></i>Tất cả departures
                    </button>
                </div>
            </div>
        </div>

        <!-- Management Tabs -->
        <div class="bg-white rounded-lg shadow-md mb-8">
            <div class="border-b border-gray-200">
                <nav class="flex space-x-8 px-6">
                    <button 
                        onclick="switchTab('overview')" 
                        id="tab-overview"
                        class="py-4 px-1 border-b-2 font-medium text-sm tab-button border-blue-500 text-blue-600"
                    >
                        <i class="fas fa-eye mr-2"></i>Tổng quan
                    </button>
                    <button 
                        onclick="switchTab('schedules')" 
                        id="tab-schedules"
                        class="py-4 px-1 border-b-2 font-medium text-sm tab-button border-transparent text-gray-500 hover:text-gray-700"
                    >
                        <i class="fas fa-calendar-alt mr-2"></i>Lịch trình
                    </button>
                    <button 
                        onclick="switchTab('departures')" 
                        id="tab-departures"
                        class="py-4 px-1 border-b-2 font-medium text-sm tab-button border-transparent text-gray-500 hover:text-gray-700"
                    >
                        <i class="fas fa-plane-departure mr-2"></i>Khởi hành
                    </button>
                    <button 
                        onclick="switchTab('guides')" 
                        id="tab-guides"
                        class="py-4 px-1 border-b-2 font-medium text-sm tab-button border-transparent text-gray-500 hover:text-gray-700"
                    >
                        <i class="fas fa-users mr-2"></i>Hướng dẫn viên
                    </button>
                </nav>
            </div>

            <!-- Tab Contents -->
            <div class="p-6">
                <!-- Overview Tab -->
                <div id="content-overview" class="tab-content">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-blue-600 text-sm font-medium">Tổng ngày</p>
                                    <p class="text-2xl font-bold text-blue-900" id="total-days">-</p>
                                </div>
                                <i class="fas fa-calendar-day text-blue-600 text-2xl"></i>
                            </div>
                        </div>
                        
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-green-600 text-sm font-medium">Departures</p>
                                    <p class="text-2xl font-bold text-green-900" id="total-departures">-</p>
                                </div>
                                <i class="fas fa-plane-departure text-green-600 text-2xl"></i>
                            </div>
                        </div>
                        
                        <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-purple-600 text-sm font-medium">HDV có sẵn</p>
                                    <p class="text-2xl font-bold text-purple-900" id="total-guides">-</p>
                                </div>
                                <i class="fas fa-user-tie text-purple-600 text-2xl"></i>
                            </div>
                        </div>
                        
                        <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-orange-600 text-sm font-medium">Trạng thái</p>
                                    <p class="text-lg font-bold text-orange-900" id="tour-status">-</p>
                                </div>
                                <i class="fas fa-info-circle text-orange-600 text-2xl"></i>
                            </div>
                        </div>
                    </div>

                    <div id="tour-overview-content">
                        <div class="text-center py-8">
                            <i class="fas fa-search text-3xl text-gray-400 mb-4"></i>
                            <p class="text-gray-600">Chọn tour để xem thông tin tổng quan</p>
                        </div>
                    </div>
                </div>

                <!-- Schedules Tab -->
                <div id="content-schedules" class="tab-content hidden">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold text-gray-800">Quản lý lịch trình từng ngày</h3>
                        <div class="flex items-center space-x-4">
                            <div class="flex items-center space-x-2">
                                <label class="text-sm text-gray-600">Hiển thị:</label>
                                <select id="schedule-view-mode" onchange="toggleScheduleViewMode()" class="text-sm border border-gray-300 rounded px-2 py-1">
                                    <option value="template">Lịch trình gốc (Ngày 1, 2, 3...)</option>
                                    <option value="actual">Ngày cụ thể (theo departure)</option>
                                </select>
                            </div>
                            <button 
                                onclick="openScheduleModal()" 
                                class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition-colors"
                            >
                                <i class="fas fa-plus mr-2"></i>Thêm ngày mới
                            </button>
                        </div>
                    </div>
                    <div id="schedules-content">
                        <div class="text-center py-8">
                            <i class="fas fa-calendar-alt text-3xl text-gray-400 mb-4"></i>
                            <p class="text-gray-600">Tải dữ liệu tour để quản lý lịch trình</p>
                        </div>
                    </div>
                </div>

                <!-- Departures Tab -->
                <div id="content-departures" class="tab-content hidden">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold text-gray-800">Quản lý thông tin khởi hành</h3>
                        <div class="flex space-x-2">
                            <button 
                                onclick="openCreateDepartureModal()" 
                                class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition-colors"
                            >
                                <i class="fas fa-plus mr-2"></i>Thêm departure
                            </button>
                            <button 
                                onclick="refreshDepartures()" 
                                class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors"
                            >
                                <i class="fas fa-sync mr-2"></i>Làm mới
                            </button>
                        </div>
                    </div>
                    <div id="departures-content">
                        <div class="text-center py-8">
                            <i class="fas fa-plane-departure text-3xl text-gray-400 mb-4"></i>
                            <p class="text-gray-600">Tải dữ liệu tour để quản lý thông tin khởi hành</p>
                        </div>
                    </div>
                </div>

                <!-- Guides Tab -->
                <div id="content-guides" class="tab-content hidden">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold text-gray-800">Quản lý hướng dẫn viên</h3>
                        <button 
                            onclick="loadAllGuides()" 
                            class="bg-purple-600 text-white px-4 py-2 rounded-md hover:bg-purple-700 transition-colors"
                        >
                            <i class="fas fa-users mr-2"></i>Tải danh sách HDV
                        </button>
                    </div>
                    <div id="guides-content">
                        <div class="text-center py-8">
                            <i class="fas fa-users text-3xl text-gray-400 mb-4"></i>
                            <p class="text-gray-600">Click "Tải danh sách HDV" để xem tất cả hướng dẫn viên</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>

    <!-- Create Departure Modal -->
    <div id="create-departure-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-full max-w-3xl max-h-screen overflow-y-auto">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-semibold">Thêm Departure Mới</h3>
                <button onclick="closeCreateDepartureModal()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <form id="create-departure-form" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Ngày khởi hành <span class="text-red-500">*</span></label>
                        <input type="date" id="new-departure-date" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Giờ khởi hành</label>
                        <input type="time" id="new-departure-time" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Địa điểm khởi hành</label>
                    <input type="text" id="new-departure-location" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Ví dụ: Văn phòng công ty - 123 Đường ABC">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Hướng dẫn khởi hành</label>
                    <textarea id="new-departure-instructions" rows="3" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Hướng dẫn chi tiết cho khách hàng..."></textarea>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Số chỗ tối đa</label>
                        <input type="number" id="new-seats-total" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500" min="1" max="50" value="25">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Giá tour (VNĐ)</label>
                        <input type="number" id="new-price" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500" min="0" step="1000">
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Hướng dẫn viên chính</label>
                        <select id="new-main-guide" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="">Chọn hướng dẫn viên chính</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Hướng dẫn viên dự phòng</label>
                        <select id="new-backup-guide" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="">Chọn hướng dẫn viên dự phòng</option>
                        </select>
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Ghi chú đặc biệt</label>
                    <textarea id="new-special-notes" rows="2" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Ghi chú đặc biệt cho departure này..."></textarea>
                </div>
                
                <div class="flex justify-end space-x-4">
                    <button type="button" onclick="closeCreateDepartureModal()" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        Hủy
                    </button>
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                        <i class="fas fa-plus mr-2"></i>Tạo departure
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Schedule Modal -->
    <div id="schedule-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-full max-w-2xl max-h-screen overflow-y-auto">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-semibold">Thêm/Sửa lịch trình</h3>
                <button onclick="closeScheduleModal()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <form id="schedule-form" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Ngày thứ <span class="text-red-500">*</span></label>
                        <input type="number" id="day-number" min="1" max="30" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required placeholder="Ví dụ: 1">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Địa điểm</label>
                        <input type="text" id="location" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Ví dụ: Hà Nội - Sapa">
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tiêu đề <span class="text-red-500">*</span></label>
                    <input type="text" id="title" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required placeholder="Ví dụ: Khám phá bản Cát Cát">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Mô tả chi tiết</label>
                    <textarea id="description" rows="4" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Mô tả chi tiết hoạt động trong ngày..."></textarea>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Hoạt động chính</label>
                        <input type="text" id="activities" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Ví dụ: Tham quan, chụp ảnh, mua sắm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Bữa ăn</label>
                        <input type="text" id="meals" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Ví dụ: Sáng, Trưa, Tối">
                    </div>
                </div>
                
                <div class="flex justify-end space-x-4">
                    <button type="button" onclick="closeScheduleModal()" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        Hủy
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Lưu
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let currentTourId = null;
        let currentDepartureId = null;
        let tourData = null;

        // Tab management
        function switchTab(tabName) {
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
            });
            
            // Remove active class from all tabs
            document.querySelectorAll('.tab-button').forEach(button => {
                button.classList.remove('border-blue-500', 'text-blue-600');
                button.classList.add('border-transparent', 'text-gray-500');
            });
            
            // Show selected tab content
            document.getElementById(`content-${tabName}`).classList.remove('hidden');
            
            // Add active class to selected tab
            const activeTab = document.getElementById(`tab-${tabName}`);
            activeTab.classList.remove('border-transparent', 'text-gray-500');
            activeTab.classList.add('border-blue-500', 'text-blue-600');
            
            // Auto-refresh data when switching to certain tabs
            if (currentTourId && (tabName === 'departures' || tabName === 'schedules')) {
                setTimeout(() => {
                    syncAllData();
                }, 100);
            }
        }

        // Load tour data
        async function loadTourData() {
            const tourId = document.getElementById('tour-id-input').value;
            const departureId = document.getElementById('departure-id-input').value;
            
            if (!tourId) {
                alert('Vui lòng nhập Tour ID');
                return;
            }

            currentTourId = tourId;
            currentDepartureId = departureId || null;

            try {
                // Load tour schedule
                const url = `/api/tours/${tourId}/schedules${departureId ? '?departure_id=' + departureId : ''}`;
                const response = await fetch(url);
                const data = await response.json();

                if (data.success) {
                    tourData = data.data;
                    updateOverview(data.data);
                    updateSchedulesTab(data.data.schedules, data.data.departure);
                    updateDeparturesTab(data.data.departure);
                    
                    // Load guides count
                    loadGuidesCount();
                    
                    // If no departure found but departure_id was provided, suggest available departures
                    if (!data.data.departure && departureId) {
                        loadAvailableDepartures(tourId);
                    }
                } else {
                    alert('Không thể tải dữ liệu tour: ' + data.message);
                }
            } catch (error) {
                alert('Lỗi kết nối: ' + error.message);
            }
        }

        // Update overview tab
        function updateOverview(data) {
            document.getElementById('total-days').textContent = data.schedules ? data.schedules.length : 0;
            document.getElementById('total-departures').textContent = data.departure ? 1 : 0;
            document.getElementById('tour-status').textContent = data.tour?.status || 'N/A';

            const overviewContent = document.getElementById('tour-overview-content');
            overviewContent.innerHTML = `
                <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-lg p-6 mb-6">
                    <h4 class="text-xl font-semibold text-gray-800 mb-4">${data.tour?.title || 'Tour không có tên'}</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h5 class="font-medium text-gray-700 mb-2">Thông tin cơ bản</h5>
                            <ul class="space-y-1 text-sm text-gray-600">
                                <li><strong>ID:</strong> ${data.tour?.id}</li>
                                <li><strong>Giá:</strong> ${data.tour?.price ? new Intl.NumberFormat('vi-VN').format(data.tour.price) + ' VNĐ' : 'Chưa có'}</li>
                                <li><strong>Thời gian:</strong> ${data.tour?.duration_days || 'N/A'} ngày</li>
                                <li><strong>Trạng thái:</strong> ${data.tour?.status || 'N/A'}</li>
                            </ul>
                        </div>
                        <div>
                            <h5 class="font-medium text-gray-700 mb-2">Thống kê</h5>
                            <ul class="space-y-1 text-sm text-gray-600">
                                <li><strong>Số ngày lịch trình:</strong> ${data.schedules?.length || 0}</li>
                                <li><strong>Có departure:</strong> ${data.departure ? 'Có' : 'Không'}</li>
                                <li><strong>Có HDV:</strong> ${data.departure?.guide ? 'Có' : 'Không'}</li>
                                <li><strong>Có HDV dự phòng:</strong> ${data.departure?.backup_guide ? 'Có' : 'Không'}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            `;
        }

        // Update schedules tab
        function updateSchedulesTab(schedules, departure) {
            const content = document.getElementById('schedules-content');
            
            if (!schedules || schedules.length === 0) {
                content.innerHTML = `
                    <div class="text-center py-8">
                        <i class="fas fa-calendar-times text-3xl text-gray-400 mb-4"></i>
                        <p class="text-gray-600">Chưa có lịch trình nào</p>
                    </div>
                `;
                return;
            }

            // Store data for view mode toggle
            window.currentSchedules = schedules;
            window.currentDeparture = departure;
            
            renderSchedules();
        }

        function renderSchedules() {
            const schedules = window.currentSchedules;
            const departure = window.currentDeparture;
            const content = document.getElementById('schedules-content');
            const viewMode = document.getElementById('schedule-view-mode').value;
            
            if (!schedules) return;

            // Calculate actual dates if departure date is available and view mode is actual
            const departureDate = departure ? new Date(departure.departure_date) : null;
            const showActualDates = viewMode === 'actual' && departureDate;

            let html = '<div class="space-y-4">';
            
            // Add info banner about view mode
            if (showActualDates) {
                html += `
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-info-circle text-blue-600"></i>
                            <span class="text-blue-800 font-medium">Hiển thị ngày cụ thể dựa trên departure ${departure.id} (${departureDate.toLocaleDateString('vi-VN')})</span>
                        </div>
                    </div>
                `;
            } else {
                html += `
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-4">
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-calendar-alt text-gray-600"></i>
                            <span class="text-gray-700 font-medium">Hiển thị lịch trình gốc (template) - không phụ thuộc vào departure cụ thể</span>
                        </div>
                    </div>
                `;
            }
            
            schedules.forEach(schedule => {
                let dateDisplay = `Ngày ${schedule.day_number}`;
                let actualDateStr = '';
                
                if (showActualDates) {
                    const actualDate = new Date(departureDate);
                    actualDate.setDate(actualDate.getDate() + (schedule.day_number - 1));
                    actualDateStr = actualDate.toLocaleDateString('vi-VN');
                    const dayOfWeek = actualDate.toLocaleDateString('vi-VN', { weekday: 'short' });
                    dateDisplay = `${actualDateStr} (${dayOfWeek})`;
                }

                html += `
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <h4 class="font-semibold text-gray-800">
                                    ${showActualDates ? 
                                        `${dateDisplay}: ${schedule.title}` : 
                                        `Ngày ${schedule.day_number}: ${schedule.title}`
                                    }
                                </h4>
                                ${!showActualDates ? `<p class="text-xs text-gray-500">Template ngày ${schedule.day_number} - sẽ áp dụng cho tất cả departures</p>` : ''}
                                <p class="text-sm text-gray-600">${schedule.location || 'Chưa có địa điểm'}</p>
                            </div>
                            <div class="flex space-x-2">
                                <button onclick="editSchedule(${schedule.id})" class="text-blue-600 hover:text-blue-800 p-1" title="Chỉnh sửa lịch trình">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="deleteSchedule(${schedule.id})" class="text-red-600 hover:text-red-800 p-1" title="Xóa lịch trình">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600">
                            ${schedule.activities ? `<div><strong>Hoạt động:</strong> ${schedule.activities}</div>` : ''}
                            ${schedule.meals ? `<div><strong>Bữa ăn:</strong> ${schedule.meals}</div>` : ''}
                        </div>
                    </div>
                `;
            });
            html += '</div>';
            
            content.innerHTML = html;
        }

        function toggleScheduleViewMode() {
            renderSchedules();
        }

        // Update departures tab
        function updateDeparturesTab(departure) {
            const content = document.getElementById('departures-content');
            
            if (!departure) {
                const departureId = document.getElementById('departure-id-input').value;
                const tourId = document.getElementById('tour-id-input').value;
                
                content.innerHTML = `
                    <div class="text-center py-8">
                        <i class="fas fa-plane-departure text-3xl text-gray-400 mb-4"></i>
                        <p class="text-gray-600">
                            ${departureId ? 
                                `Không tìm thấy departure ID ${departureId} cho tour ${tourId}` : 
                                'Chưa nhập Departure ID'
                            }
                        </p>
                        ${departureId ? `
                            <div class="mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg text-left max-w-md mx-auto">
                                <h5 class="font-medium text-yellow-800 mb-2">Gợi ý:</h5>
                                <ul class="text-sm text-yellow-700 space-y-1">
                                    <li>• Kiểm tra lại Departure ID có đúng không</li>
                                    <li>• Departure ID phải thuộc về Tour ID này</li>
                                    <li>• Hoặc để trống để xem tất cả lịch trình</li>
                                </ul>
                            </div>
                        ` : ''}
                    </div>
                `;
                return;
            }

            content.innerHTML = `
                <div class="bg-white border border-gray-200 rounded-lg p-6">
                    <div class="flex justify-between items-start mb-4">
                        <h4 class="text-lg font-semibold text-gray-800">Thông tin khởi hành</h4>
                        <div class="flex space-x-2">
                            <button onclick="editDeparture(${departure.id})" class="bg-blue-600 text-white px-3 py-1 rounded text-sm hover:bg-blue-700 transition-colors">
                                <i class="fas fa-edit mr-1"></i>Chỉnh sửa
                            </button>
                            <button onclick="viewDepartureDetails(${departure.id})" class="bg-gray-600 text-white px-3 py-1 rounded text-sm hover:bg-gray-700 transition-colors">
                                <i class="fas fa-eye mr-1"></i>Chi tiết
                            </button>
                        </div>
                    </div>
                    

                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-3">
                            <div>
                                <label class="text-sm font-medium text-gray-700">Ngày khởi hành</label>
                                <p class="text-gray-900">${new Date(departure.departure_date).toLocaleDateString('vi-VN')}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700">Giờ khởi hành</label>
                                <p class="text-gray-900">${departure.departure_time || 'Chưa xác định'}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700">Địa điểm</label>
                                <p class="text-gray-900">${departure.departure_location || 'Chưa xác định'}</p>
                            </div>
                        </div>
                        
                        <div class="space-y-3">
                            <div>
                                <label class="text-sm font-medium text-gray-700">HDV chính</label>
                                <p class="text-gray-900">${departure.guide ? departure.guide.name : 'Chưa gán'}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700">HDV dự phòng</label>
                                <p class="text-gray-900">${departure.backup_guide ? departure.backup_guide.name : 'Chưa gán'}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700">Trạng thái</label>
                                <p class="text-gray-900">
                                    <span class="inline-block px-2 py-1 rounded text-xs font-medium ${getStatusClass(departure.preparation_status)}">
                                        ${getStatusText(departure.preparation_status)}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }

        // Load guides count
        async function loadGuidesCount() {
            try {
                const response = await fetch('/api/guides/available');
                const data = await response.json();
                
                if (data.success) {
                    document.getElementById('total-guides').textContent = data.data.length;
                }
            } catch (error) {
                console.error('Error loading guides count:', error);
            }
        }

        // Load all guides
        async function loadAllGuides() {
            try {
                const response = await fetch('/api/guides/available');
                const data = await response.json();
                
                if (data.success) {
                    displayAllGuides(data.data);
                } else {
                    alert('Không thể tải danh sách hướng dẫn viên');
                }
            } catch (error) {
                alert('Lỗi kết nối: ' + error.message);
            }
        }

        // Display all guides
        function displayAllGuides(guides) {
            const content = document.getElementById('guides-content');
            
            if (!guides || guides.length === 0) {
                content.innerHTML = `
                    <div class="text-center py-8">
                        <i class="fas fa-users text-3xl text-gray-400 mb-4"></i>
                        <p class="text-gray-600">Không có hướng dẫn viên nào</p>
                    </div>
                `;
                return;
            }

            let html = `
                <div class="mb-4 p-4 bg-purple-50 rounded-lg">
                    <p class="text-purple-800">
                        <i class="fas fa-info-circle mr-2"></i>
                        Tổng cộng có <strong>${guides.length}</strong> hướng dẫn viên có sẵn
                    </p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            `;
            
            guides.forEach(guide => {
                html += `
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                        <div class="flex justify-between items-start mb-3">
                            <h4 class="font-semibold text-gray-800">${guide.name}</h4>
                            <span class="px-2 py-1 bg-purple-100 text-purple-800 text-xs rounded">
                                #${guide.id}
                            </span>
                        </div>
                        <div class="space-y-2 text-sm text-gray-600">
                            <p><i class="fas fa-envelope mr-2"></i>${guide.email}</p>
                            <p><i class="fas fa-phone mr-2"></i>${guide.phone || 'Chưa có SĐT'}</p>
                        </div>
                        <div class="mt-3 pt-3 border-t border-gray-100 flex space-x-2">
                            <button onclick="assignGuide(${guide.id}, 'main')" class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded hover:bg-blue-200">
                                Gán làm HDV chính
                            </button>
                            <button onclick="assignGuide(${guide.id}, 'backup')" class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded hover:bg-green-200">
                                Gán làm HDV dự phòng
                            </button>
                        </div>
                    </div>
                `;
            });
            
            html += '</div>';
            content.innerHTML = html;
        }

        // Modal functions
        function openScheduleModal() {
            document.getElementById('schedule-modal').classList.remove('hidden');
        }

        function closeScheduleModal() {
            document.getElementById('schedule-modal').classList.add('hidden');
            document.getElementById('schedule-form').reset();
        }

        // Quick actions
        function viewCustomerPage() {
            if (currentTourId) {
                const url = `/customer/tour-schedule?tour_id=${currentTourId}${currentDepartureId ? '&departure_id=' + currentDepartureId : ''}`;
                window.open(url, '_blank');
            } else {
                alert('Vui lòng tải dữ liệu tour trước');
            }
        }

        // Edit departure function
        function editDeparture(departureId) {
            try {
                // Set current departure ID
                currentDepartureId = departureId;
                
                // Show modal
                document.getElementById('departure-edit-modal').classList.remove('hidden');
                
                // Load departure data
                loadDepartureData(departureId);
                
                // Load available guides
                loadAvailableGuides();
                
                showNotification('Đang mở modal chỉnh sửa...', 'info');
            } catch (error) {
                console.error('Error opening edit modal:', error);
                showNotification('Không thể mở modal chỉnh sửa: ' + error.message, 'error');
            }
        }

        // Assign guide function
        async function assignGuide(guideId, type) {
            if (!currentDepartureId || currentDepartureId === '') {
                showNotification('Vui lòng nhập Departure ID trước khi gán hướng dẫn viên', 'warning');
                return;
            }

            const fieldName = type === 'main' ? 'guide_id' : 'backup_guide_id';
            
            try {
                const response = await fetch(`/api/departures/${currentDepartureId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        [fieldName]: guideId
                    })
                });

                const result = await response.json();
                
                if (result.success) {
                    showNotification(`Đã gán hướng dẫn viên ${type === 'main' ? 'chính' : 'dự phòng'} thành công!`, 'success');
                    
                    // Update departure data immediately
                    updateDeparturesTab(result.data);
                    
                    // Sync all data across tabs
                    syncAllData();
                    
                    // Trigger notification event
                    if (window.notificationSystem) {
                        window.notificationSystem.addNotification({
                            id: Date.now(),
                            title: 'Phân công HDV',
                            message: `Đã gán HDV ${type === 'main' ? 'chính' : 'dự phòng'} cho departure ID: ${currentDepartureId}`,
                            type: 'guide',
                            created_at: new Date().toISOString(),
                            read_at: null
                        });
                    }
                } else {
                    showNotification(result.message || 'Có lỗi xảy ra khi gán hướng dẫn viên', 'error');
                }
            } catch (error) {
                console.error('Error assigning guide:', error);
                showNotification('Không thể kết nối đến server', 'error');
            }
        }

        // Helper functions
        function getStatusClass(status) {
            const classes = {
                pending: 'bg-yellow-100 text-yellow-800',
                ready: 'bg-green-100 text-green-800',
                confirmed: 'bg-blue-100 text-blue-800',
                cancelled: 'bg-red-100 text-red-800',
                draft: 'bg-gray-100 text-gray-800',
                departed: 'bg-purple-100 text-purple-800',
                completed: 'bg-indigo-100 text-indigo-800'
            };
            return classes[status] || 'bg-gray-100 text-gray-800';
        }

        function getStatusText(status) {
            const texts = {
                pending: 'Đang chuẩn bị',
                ready: 'Sẵn sàng',
                confirmed: 'Đã xác nhận',
                cancelled: 'Đã hủy',
                draft: 'Nháp',
                departed: 'Đã khởi hành',
                completed: 'Hoàn thành'
            };
            return texts[status] || 'Không xác định';
        }

        // Schedule management functions
        function editSchedule(id) {
            showNotification(`Chỉnh sửa lịch trình ID: ${id} - Tính năng đang phát triển`, 'info');
        }

        async function deleteSchedule(id) {
            if (!confirm('Bạn có chắc chắn muốn xóa lịch trình này?')) {
                return;
            }
            
            if (!currentTourId) {
                showNotification('Vui lòng chọn tour trước khi xóa lịch trình', 'warning');
                return;
            }
            
            try {
                showNotification('Đang xóa lịch trình...', 'info');
                
                const response = await fetch(`/api/schedule-delete/${currentTourId}/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                const result = await response.json();
                
                if (result.success) {
                    showNotification('Đã xóa lịch trình thành công!', 'success');
                    
                    // Trigger notification
                    if (window.notificationSystem) {
                        window.notificationSystem.addNotification({
                            id: Date.now(),
                            title: 'Xóa lịch trình',
                            message: `Đã xóa lịch trình ID: ${id}`,
                            type: 'success',
                            created_at: new Date().toISOString(),
                            read_at: null
                        });
                    }
                    
                    // Sync all data across tabs
                    syncAllData();
                } else {
                    showNotification(result.message || 'Có lỗi xảy ra khi xóa lịch trình', 'error');
                }
            } catch (error) {
                console.error('Error deleting schedule:', error);
                showNotification('Không thể kết nối đến server', 'error');
            }
        }

        function refreshDepartures() {
            if (currentTourId) {
                showNotification('Đang làm mới dữ liệu...', 'info');
                loadTourData();
            } else {
                showNotification('Vui lòng chọn tour trước khi làm mới', 'warning');
            }
        }

        // Sync all data across tabs
        async function syncAllData() {
            if (currentTourId) {
                console.log('=== SYNCING ALL DATA ===');
                console.log('Current tour ID:', currentTourId);
                
                // Show sync indicator
                showSyncIndicator();
                
                try {
                    // Reload tour data
                    await loadTourData();
                    
                    // Force refresh departures list if visible
                    const departuresTab = document.getElementById('content-departures');
                    if (departuresTab && !departuresTab.classList.contains('hidden')) {
                        console.log('Refreshing departures tab...');
                        // Just reload tour data which will update departures tab
                    }
                    
                    console.log('Data sync completed successfully');
                    showNotification('Đã đồng bộ dữ liệu thành công', 'success');
                } catch (error) {
                    console.error('Error during data sync:', error);
                    showNotification('Lỗi đồng bộ dữ liệu: ' + error.message, 'error');
                }
            }
        }

        // Show all departures for current tour
        async function showAllDepartures() {
            const tourId = document.getElementById('tour-id-input').value;
            
            if (!tourId) {
                showNotification('Vui lòng nhập Tour ID', 'warning');
                return;
            }

            try {
                const response = await fetch(`/api/tours/${tourId}/departures`);
                const data = await response.json();
                
                if (data.success) {
                    displayAllDeparturesModal(data.data, tourId);
                } else {
                    showNotification('Không thể tải danh sách departures: ' + data.message, 'error');
                }
            } catch (error) {
                showNotification('Lỗi kết nối: ' + error.message, 'error');
            }
        }

        function displayAllDeparturesModal(departures, tourId) {
            const modalHtml = `
                <div id="all-departures-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                    <div class="bg-white rounded-lg p-6 w-full max-w-4xl max-h-screen overflow-y-auto m-4">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-xl font-semibold">Tất cả Departures của Tour ${tourId}</h3>
                            <button onclick="closeAllDeparturesModal()" class="text-gray-500 hover:text-gray-700">
                                <i class="fas fa-times text-xl"></i>
                            </button>
                        </div>
                        
                        <div class="mb-4 p-3 bg-blue-50 rounded-lg">
                            <p class="text-blue-800 text-sm">
                                <i class="fas fa-info-circle mr-2"></i>
                                Tìm thấy <strong>${departures.length}</strong> departures. Click vào departure để chọn và quản lý.
                            </p>
                        </div>
                        
                        <div class="space-y-3 max-h-96 overflow-y-auto">
                            ${departures.map(departure => {
                                const departureDate = new Date(departure.departure_date);
                                const today = new Date();
                                const daysDiff = Math.ceil((departureDate - today) / (1000 * 60 * 60 * 24));
                                
                                let statusBadge = '';
                                if (daysDiff < 0) {
                                    statusBadge = `<span class="px-2 py-1 bg-red-100 text-red-800 text-xs rounded">Đã qua ${Math.abs(daysDiff)} ngày</span>`;
                                } else if (daysDiff === 0) {
                                    statusBadge = '<span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded">Hôm nay</span>';
                                } else if (daysDiff <= 7) {
                                    statusBadge = `<span class="px-2 py-1 bg-orange-100 text-orange-800 text-xs rounded">Còn ${daysDiff} ngày</span>`;
                                } else {
                                    statusBadge = `<span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded">Còn ${daysDiff} ngày</span>`;
                                }
                                
                                return `
                                    <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 cursor-pointer transition-colors" onclick="selectDeparture(${departure.id})">
                                        <div class="flex justify-between items-start mb-2">
                                            <div>
                                                <h4 class="font-semibold text-gray-800">Departure #${departure.id}</h4>
                                                <p class="text-lg text-blue-600 font-medium">${departureDate.toLocaleDateString('vi-VN')}</p>
                                                <p class="text-sm text-gray-600">${departureDate.toLocaleDateString('vi-VN', { weekday: 'long' })}</p>
                                            </div>
                                            <div class="text-right">
                                                ${statusBadge}
                                            </div>
                                        </div>
                                        
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-gray-600">
                                            <div>
                                                <p><strong>Giờ:</strong> ${departure.departure_time || 'Chưa có'}</p>
                                                <p><strong>Địa điểm:</strong> ${departure.departure_location || 'Chưa có'}</p>
                                            </div>
                                            <div>
                                                <p><strong>HDV chính:</strong> ${departure.guide ? departure.guide.name : 'Chưa gán'}</p>
                                                <p><strong>HDV dự phòng:</strong> ${departure.backup_guide ? departure.backup_guide.name : 'Chưa gán'}</p>
                                            </div>
                                            <div>
                                                <p><strong>Chỗ ngồi:</strong> ${departure.seats_available}/${departure.seats_total}</p>
                                                <p><strong>Trạng thái:</strong> <span class="px-1 py-0.5 rounded text-xs ${getStatusClass(departure.preparation_status)}">${getStatusText(departure.preparation_status)}</span></p>
                                            </div>
                                        </div>
                                    </div>
                                `;
                            }).join('')}
                        </div>
                        
                        <div class="mt-4 flex justify-end">
                            <button onclick="closeAllDeparturesModal()" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                                Đóng
                            </button>
                        </div>
                    </div>
                </div>
            `;
            
            document.body.insertAdjacentHTML('beforeend', modalHtml);
        }

        function selectDeparture(departureId) {
            document.getElementById('departure-id-input').value = departureId;
            closeAllDeparturesModal();
            loadTourData();
            showNotification(`Đã chọn departure #${departureId}`, 'success');
        }

        function closeAllDeparturesModal() {
            const modal = document.getElementById('all-departures-modal');
            if (modal) {
                modal.remove();
            }
        }

        // Show sync indicator
        function showSyncIndicator() {
            // Add sync indicator to all tab buttons
            document.querySelectorAll('.tab-button').forEach(button => {
                const icon = button.querySelector('i');
                if (icon) {
                    icon.classList.add('fa-spin');
                    setTimeout(() => {
                        icon.classList.remove('fa-spin');
                    }, 1000);
                }
            });
        }

        // Notification helper function
        function showNotification(message, type = 'info') {
            // Use the notification system if available
            if (window.notificationSystem) {
                window.notificationSystem.addNotification({
                    id: Date.now(),
                    title: type.charAt(0).toUpperCase() + type.slice(1),
                    message: message,
                    type: type,
                    created_at: new Date().toISOString(),
                    read_at: null
                });
            } else {
                // Fallback to alert
                alert(message);
            }
        }

        // View departure details function
        function viewDepartureDetails(departureId) {
            if (currentTourId) {
                const url = `/customer/tour-schedule?tour_id=${currentTourId}&departure_id=${departureId}`;
                window.open(url, '_blank');
            } else {
                showNotification('Không thể xem chi tiết departure', 'error');
            }
        }

        // Schedule form handler
        document.getElementById('schedule-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            if (!currentTourId) {
                showNotification('Vui lòng chọn tour trước khi thêm lịch trình', 'warning');
                return;
            }

            const formData = {
                tour_id: currentTourId,
                day_number: parseInt(document.getElementById('day-number').value),
                title: document.getElementById('title').value.trim(),
                description: document.getElementById('description').value.trim(),
                location: document.getElementById('location').value.trim(),
                activities: document.getElementById('activities').value.trim(),
                meals: document.getElementById('meals').value.trim()
            };

            // Validation
            if (!formData.title) {
                showNotification('Vui lòng nhập tiêu đề', 'warning');
                return;
            }
            
            if (formData.day_number < 1 || formData.day_number > 30) {
                showNotification('Ngày phải từ 1 đến 30', 'warning');
                return;
            }

            try {
                showNotification('Đang lưu lịch trình...', 'info');
                
                const response = await fetch(`/api/schedule-create/${currentTourId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(formData)
                });

                const result = await response.json();
                
                if (result.success) {
                    showNotification('Đã thêm lịch trình thành công!', 'success');
                    closeScheduleModal();
                    
                    // Trigger notification
                    if (window.notificationSystem) {
                        window.notificationSystem.addNotification({
                            id: Date.now(),
                            title: 'Thêm lịch trình',
                            message: `Đã thêm lịch trình ngày ${formData.day_number}: ${formData.title}`,
                            type: 'success',
                            created_at: new Date().toISOString(),
                            read_at: null
                        });
                    }
                    
                    // Sync all data across tabs
                    syncAllData();
                } else {
                    showNotification(result.message || 'Có lỗi xảy ra khi lưu lịch trình', 'error');
                }
                
            } catch (error) {
                console.error('Error saving schedule:', error);
                showNotification('Không thể kết nối đến server', 'error');
            }
        });

        // Load available departures for tour
        async function loadAvailableDepartures(tourId) {
            try {
                const response = await fetch(`/api/tours/${tourId}/departures`);
                const data = await response.json();
                
                const content = document.getElementById('departures-content');
                const currentContent = content.innerHTML;
                
                if (data.success && data.data.length > 0) {
                    let departuresList = data.data.map(dep => 
                        `<li>• ID ${dep.id}: ${new Date(dep.departure_date).toLocaleDateString('vi-VN')} ${dep.departure_time ? '- ' + dep.departure_time : ''}</li>`
                    ).join('');
                    
                    content.innerHTML = currentContent + `
                        <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                            <h5 class="font-medium text-blue-800 mb-2">
                                <i class="fas fa-info-circle mr-2"></i>
                                Departure IDs có sẵn cho Tour ${tourId}:
                            </h5>
                            <div class="text-sm text-blue-700">
                                <ul class="space-y-1 ml-4 mb-3">
                                    ${departuresList}
                                </ul>
                                <p class="text-xs">Click vào ID để tự động điền vào form</p>
                            </div>
                        </div>
                    `;
                } else {
                    content.innerHTML = currentContent + `
                        <div class="mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                            <h5 class="font-medium text-yellow-800 mb-2">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                Không tìm thấy departure nào cho Tour ${tourId}
                            </h5>
                            <p class="text-sm text-yellow-700">Tour này chưa có lịch khởi hành nào được tạo.</p>
                        </div>
                    `;
                }
            } catch (error) {
                console.error('Error loading available departures:', error);
            }
        }

        // Create departure modal functions
        function openCreateDepartureModal() {
            if (!currentTourId) {
                showNotification('Vui lòng chọn tour trước khi thêm departure', 'warning');
                return;
            }
            
            document.getElementById('create-departure-modal').classList.remove('hidden');
            loadGuidesForModal();
            
            // Set default date to tomorrow
            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            document.getElementById('new-departure-date').value = tomorrow.toISOString().split('T')[0];
        }

        function closeCreateDepartureModal() {
            document.getElementById('create-departure-modal').classList.add('hidden');
            document.getElementById('create-departure-form').reset();
        }

        async function loadGuidesForModal() {
            try {
                // Get departure date to check for conflicts
                const departureDate = document.getElementById('new-departure-date').value;
                let url = '/api/guides/available';
                
                if (departureDate) {
                    url += `?date=${departureDate}`;
                }
                
                const response = await fetch(url);
                const data = await response.json();
                
                if (data.success) {
                    const mainGuideSelect = document.getElementById('new-main-guide');
                    const backupGuideSelect = document.getElementById('new-backup-guide');
                    
                    // Clear existing options
                    mainGuideSelect.innerHTML = '<option value="">Chọn hướng dẫn viên chính</option>';
                    backupGuideSelect.innerHTML = '<option value="">Chọn hướng dẫn viên dự phòng</option>';
                    
                    // Add guide options
                    data.data.forEach(guide => {
                        let guideInfo = `${guide.name} (${guide.email})`;
                        
                        // Add conflict warning if guide is not available
                        if (guide.is_available === false && guide.conflicts && guide.conflicts.length > 0) {
                            const conflictInfo = guide.conflicts.map(c => `${c.tour_title} (${c.role})`).join(', ');
                            guideInfo += ` - ⚠️ Đã gán: ${conflictInfo}`;
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
                }
            } catch (error) {
                console.error('Error loading guides:', error);
            }
        }

        // Handle create departure form
        document.getElementById('create-departure-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            if (!currentTourId) {
                showNotification('Vui lòng chọn tour trước', 'error');
                return;
            }

            const formData = {
                tour_id: currentTourId,
                departure_date: document.getElementById('new-departure-date').value,
                departure_time: document.getElementById('new-departure-time').value,
                departure_location: document.getElementById('new-departure-location').value,
                departure_instructions: document.getElementById('new-departure-instructions').value,
                seats_total: document.getElementById('new-seats-total').value,
                price: document.getElementById('new-price').value,
                guide_id: document.getElementById('new-main-guide').value || null,
                backup_guide_id: document.getElementById('new-backup-guide').value || null,
                special_notes: document.getElementById('new-special-notes').value,
                preparation_status: 'pending'
            };

            // Validation
            if (!formData.departure_date) {
                showNotification('Vui lòng chọn ngày khởi hành', 'error');
                return;
            }

            if (formData.guide_id && formData.backup_guide_id && formData.guide_id === formData.backup_guide_id) {
                showNotification('Hướng dẫn viên chính và dự phòng không thể là cùng một người', 'error');
                return;
            }

            try {
                showNotification('Đang tạo departure mới...', 'info');
                
                const response = await fetch('/api/departures/create', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(formData)
                });

                const result = await response.json();
                
                if (result.success) {
                    showNotification('Đã tạo departure mới thành công!', 'success');
                    closeCreateDepartureModal();
                    
                    // Update departure ID input with new ID
                    document.getElementById('departure-id-input').value = result.data.id;
                    currentDepartureId = result.data.id;
                    
                    // Sync all data across tabs
                    syncAllData();
                    
                    // Trigger notification
                    if (window.notificationSystem) {
                        window.notificationSystem.addNotification({
                            id: Date.now(),
                            title: 'Tạo departure mới',
                            message: `Đã tạo departure ID: ${result.data.id} cho ngày ${formData.departure_date}`,
                            type: 'success',
                            created_at: new Date().toISOString(),
                            read_at: null
                        });
                    }
                } else {
                    showNotification(result.message || 'Có lỗi xảy ra khi tạo departure', 'error');
                }
            } catch (error) {
                console.error('Error creating departure:', error);
                showNotification('Không thể kết nối đến server', 'error');
            }
        });

        // Departure edit modal functions
        function closeDepartureModal() {
            document.getElementById('departure-edit-modal').classList.add('hidden');
            currentDepartureId = null;
        }

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
                    document.getElementById('main-guide').value = departure.guide_id || '';
                    document.getElementById('backup-guide').value = departure.backup_guide_id || '';
                    document.getElementById('emergency-contact').value = departure.emergency_contact || '';
                    document.getElementById('emergency-phone').value = departure.emergency_phone || '';
                    document.getElementById('preparation-status').value = departure.preparation_status || 'pending';
                    document.getElementById('seats-total').value = departure.seats_total || '';
                    document.getElementById('special-notes').value = departure.special_notes || '';
                    
                    showNotification('Đã tải thông tin departure', 'success');
                } else {
                    showNotification('Không thể tải thông tin departure: ' + data.message, 'error');
                }
            } catch (error) {
                console.error('Error loading departure data:', error);
                showNotification('Lỗi kết nối khi tải departure', 'error');
            }
        }

        async function loadAvailableGuides() {
            try {
                const response = await fetch('/api/guides/available');
                const data = await response.json();
                
                if (data.success) {
                    const mainGuideSelect = document.getElementById('main-guide');
                    const backupGuideSelect = document.getElementById('backup-guide');
                    
                    if (mainGuideSelect && backupGuideSelect) {
                        // Clear existing options
                        mainGuideSelect.innerHTML = '<option value="">Chọn hướng dẫn viên chính</option>';
                        backupGuideSelect.innerHTML = '<option value="">Chọn hướng dẫn viên dự phòng</option>';
                        
                        // Add guide options
                        data.data.forEach(guide => {
                            const option1 = new Option(`${guide.name} (${guide.email})`, guide.id);
                            const option2 = new Option(`${guide.name} (${guide.email})`, guide.id);
                            
                            mainGuideSelect.add(option1);
                            backupGuideSelect.add(option2);
                        });
                    }
                }
            } catch (error) {
                console.error('Error loading guides:', error);
            }
        }

        // Handle departure edit form submission
        function setupDepartureEditForm() {
            const form = document.getElementById('departure-edit-form');
            if (form) {
                form.addEventListener('submit', async function(e) {
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
                    
                    try {
                        showNotification('Đang lưu thay đổi...', 'info');
                        
                        const response = await fetch(`/api/departures/${currentDepartureId}`, {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify(formData)
                        });
                        
                        const result = await response.json();
                        
                        if (result.success) {
                            showNotification('Cập nhật thông tin khởi hành thành công!', 'success');
                            closeDepartureModal();
                            
                            // Update departure data immediately
                            updateDeparturesTab(result.data);
                            
                            // Sync all data across tabs
                            syncAllData();
                            
                            // Trigger notification
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
                        } else {
                            showNotification(result.message || 'Có lỗi xảy ra', 'error');
                        }
                    } catch (error) {
                        console.error('Error saving departure:', error);
                        showNotification('Không thể lưu thay đổi', 'error');
                    }
                });
            }
        }



        // Auto-load data on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadTourData();
            setupDepartureEditForm();
            
            // Add event listener for departure date change in create modal
            const newDepartureDateInput = document.getElementById('new-departure-date');
            if (newDepartureDateInput) {
                newDepartureDateInput.addEventListener('change', function() {
                    loadGuidesForModal();
                });
            }
            
            // Listen for departure updates from modal
            window.addEventListener('departureUpdated', function(event) {
                console.log('Departure updated event received:', event.detail);
                showNotification('Đã nhận được thông báo cập nhật departure', 'info');
                
                // Force sync data after a short delay
                setTimeout(() => {
                    syncAllData();
                }, 1000);
            });
        });
    </script>
</body>
</html>

    <!-- Include Departure Edit Modal -->
    @include('admin.departure-edit-modal')
    
    <!-- Include Notification System -->
    @include('admin.notification-system')
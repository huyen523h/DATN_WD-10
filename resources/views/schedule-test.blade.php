<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Test Thêm Lịch trình</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-md p-6">
            <h1 class="text-2xl font-bold text-gray-800 mb-6">
                <i class="fas fa-calendar-plus mr-2 text-blue-600"></i>
                Test Thêm Lịch trình Tour
            </h1>

            <!-- Tour Selection -->
            <div class="mb-6 p-4 bg-blue-50 rounded-lg">
                <label class="block text-sm font-medium text-gray-700 mb-2">Tour ID</label>
                <input type="number" id="tour-id" value="14" class="w-full border border-gray-300 rounded-md px-3 py-2">
                <p class="text-sm text-gray-600 mt-1">Sử dụng Tour ID 14 (Tour Sapa 3 ngày 2 đêm)</p>
            </div>

            <!-- Schedule Form -->
            <form id="schedule-form" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Ngày thứ <span class="text-red-500">*</span></label>
                        <input type="number" id="day-number" min="1" max="30" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required placeholder="Ví dụ: 4">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Địa điểm</label>
                        <input type="text" id="location" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Ví dụ: Sapa - Hà Nội">
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tiêu đề <span class="text-red-500">*</span></label>
                    <input type="text" id="title" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required placeholder="Ví dụ: Trở về Hà Nội - Kết thúc chuyến đi">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Mô tả chi tiết</label>
                    <textarea id="description" rows="4" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Mô tả chi tiết hoạt động trong ngày..."></textarea>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Hoạt động chính</label>
                        <input type="text" id="activities" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Ví dụ: Check-out, di chuyển về Hà Nội">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Bữa ăn</label>
                        <input type="text" id="meals" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Ví dụ: Sáng">
                    </div>
                </div>

                <div class="flex justify-end space-x-4 pt-4">
                    <button type="button" onclick="clearForm()" class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        <i class="fas fa-eraser mr-2"></i>Xóa form
                    </button>
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        <i class="fas fa-save mr-2"></i>Lưu lịch trình
                    </button>
                </div>
            </form>

            <!-- Test Results -->
            <div class="mt-8">
                <h3 class="font-semibold text-gray-800 mb-4">
                    <i class="fas fa-clipboard-list mr-2 text-gray-600"></i>
                    Kết quả Test
                </h3>
                <div id="test-results" class="bg-gray-50 rounded-lg p-4 min-h-32">
                    <p class="text-gray-600 text-sm">Chưa có test nào được thực hiện.</p>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="mt-6 flex flex-wrap gap-2">
                <button onclick="loadExistingSchedules()" class="px-4 py-2 bg-green-600 text-white rounded text-sm hover:bg-green-700">
                    <i class="fas fa-list mr-1"></i>Xem lịch trình hiện tại
                </button>
                <button onclick="testAPI()" class="px-4 py-2 bg-purple-600 text-white rounded text-sm hover:bg-purple-700">
                    <i class="fas fa-flask mr-1"></i>Test API
                </button>
                <button onclick="openAdminPanel()" class="px-4 py-2 bg-gray-600 text-white rounded text-sm hover:bg-gray-700">
                    <i class="fas fa-external-link-alt mr-1"></i>Mở Admin Panel
                </button>
            </div>
        </div>
    </div>

    <script>
        let testResults = [];

        // Add test result
        function addTestResult(message, type = 'info', details = null) {
            const timestamp = new Date().toLocaleTimeString('vi-VN');
            const result = { message, type, details, timestamp };
            testResults.push(result);
            updateTestResultsDisplay();
        }

        // Update test results display
        function updateTestResultsDisplay() {
            const container = document.getElementById('test-results');
            
            if (testResults.length === 0) {
                container.innerHTML = '<p class="text-gray-600 text-sm">Chưa có test nào được thực hiện.</p>';
                return;
            }

            let html = '';
            testResults.slice(-5).reverse().forEach(result => {
                const statusClass = result.type === 'success' ? 'text-green-600' : 
                                  result.type === 'error' ? 'text-red-600' : 'text-blue-600';
                const statusIcon = result.type === 'success' ? 'fa-check-circle' : 
                                 result.type === 'error' ? 'fa-times-circle' : 'fa-info-circle';
                
                html += `
                    <div class="flex items-start space-x-3 mb-3 p-2 bg-white rounded border-l-4 ${result.type === 'success' ? 'border-green-500' : result.type === 'error' ? 'border-red-500' : 'border-blue-500'}">
                        <i class="fas ${statusIcon} ${statusClass} mt-1"></i>
                        <div class="flex-1">
                            <div class="flex justify-between items-start">
                                <span class="text-sm text-gray-800">${result.message}</span>
                                <span class="text-xs text-gray-500">${result.timestamp}</span>
                            </div>
                            ${result.details ? `<pre class="text-xs text-gray-500 mt-1 bg-gray-100 p-2 rounded overflow-x-auto">${JSON.stringify(result.details, null, 2)}</pre>` : ''}
                        </div>
                    </div>
                `;
            });
            
            container.innerHTML = html;
        }

        // Form handler
        document.getElementById('schedule-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const tourId = document.getElementById('tour-id').value;
            if (!tourId) {
                addTestResult('Vui lòng nhập Tour ID', 'error');
                return;
            }

            const formData = {
                day_number: parseInt(document.getElementById('day-number').value),
                title: document.getElementById('title').value.trim(),
                description: document.getElementById('description').value.trim(),
                location: document.getElementById('location').value.trim(),
                activities: document.getElementById('activities').value.trim(),
                meals: document.getElementById('meals').value.trim()
            };

            // Validation
            if (!formData.title) {
                addTestResult('Vui lòng nhập tiêu đề', 'error');
                return;
            }
            
            if (formData.day_number < 1 || formData.day_number > 30) {
                addTestResult('Ngày phải từ 1 đến 30', 'error');
                return;
            }

            try {
                addTestResult('Đang gửi request tạo lịch trình...', 'info');
                
                const response = await fetch(`/api/schedule-create/${tourId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(formData)
                });

                const result = await response.json();
                
                if (result.success) {
                    addTestResult('✅ Đã tạo lịch trình thành công!', 'success', result.data);
                    clearForm();
                } else {
                    addTestResult('❌ Lỗi: ' + result.message, 'error', result);
                }
                
            } catch (error) {
                console.error('Error:', error);
                addTestResult('❌ Lỗi kết nối: ' + error.message, 'error');
            }
        });

        // Clear form
        function clearForm() {
            document.getElementById('schedule-form').reset();
            addTestResult('Đã xóa form', 'info');
        }

        // Load existing schedules
        async function loadExistingSchedules() {
            const tourId = document.getElementById('tour-id').value;
            if (!tourId) {
                addTestResult('Vui lòng nhập Tour ID', 'error');
                return;
            }

            try {
                addTestResult('Đang tải lịch trình hiện tại...', 'info');
                
                const response = await fetch(`/api/tours/${tourId}/schedules`);
                const result = await response.json();
                
                if (result.success) {
                    addTestResult(`📋 Tìm thấy ${result.data.schedules?.length || 0} lịch trình`, 'success', result.data.schedules);
                } else {
                    addTestResult('❌ Không thể tải lịch trình: ' + result.message, 'error');
                }
            } catch (error) {
                addTestResult('❌ Lỗi kết nối: ' + error.message, 'error');
            }
        }

        // Test API
        async function testAPI() {
            try {
                addTestResult('🧪 Đang test API...', 'info');
                
                const response = await fetch('/api/guides/available');
                const result = await response.json();
                
                if (result.success) {
                    addTestResult('✅ API hoạt động bình thường', 'success');
                } else {
                    addTestResult('❌ API có vấn đề', 'error');
                }
            } catch (error) {
                addTestResult('❌ API không thể kết nối', 'error');
            }
        }

        // Open admin panel
        function openAdminPanel() {
            window.open('/admin/tour-schedule-management', '_blank');
        }

        // Auto-fill sample data
        document.addEventListener('DOMContentLoaded', function() {
            // Fill sample data
            document.getElementById('day-number').value = '4';
            document.getElementById('title').value = 'Trở về Hà Nội - Kết thúc chuyến đi';
            document.getElementById('location').value = 'Sapa - Hà Nội';
            document.getElementById('description').value = 'Sau bữa sáng tại khách sạn, quý khách check-out và khởi hành về Hà Nội. Trên đường có dừng chân nghỉ ngơi và dùng cơm trưa. Dự kiến về đến Hà Nội vào chiều tối, kết thúc chuyến đi tham quan Sapa 3 ngày 2 đêm.';
            document.getElementById('activities').value = 'Check-out khách sạn, di chuyển về Hà Nội, nghỉ ngơi trên đường';
            document.getElementById('meals').value = 'Sáng, Trưa';
            
            addTestResult('📝 Đã điền sẵn dữ liệu mẫu', 'info');
        });
    </script>
</body>
</html>
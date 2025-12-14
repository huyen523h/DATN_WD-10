<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Test Đồng Bộ Ngày Khởi Hành</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-6xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h1 class="text-2xl font-bold text-gray-800 mb-4">
                <i class="fas fa-calendar-sync mr-2 text-green-600"></i>
                Test Đồng Bộ Ngày Khởi Hành
            </h1>
            
            <!-- Input Section -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tour ID</label>
                    <input type="number" id="tour-id" value="14" class="w-full border border-gray-300 rounded-md px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Departure ID</label>
                    <input type="number" id="departure-id" value="42" class="w-full border border-gray-300 rounded-md px-3 py-2">
                </div>
                <div class="flex items-end">
                    <button onclick="loadAndCompareData()" class="w-full bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">
                        <i class="fas fa-search mr-2"></i>Kiểm tra đồng bộ
                    </button>
                </div>
            </div>
        </div>

        <!-- Departure Info -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">
                <i class="fas fa-plane-departure mr-2 text-blue-600"></i>
                Thông Tin Khởi Hành
            </h2>
            <div id="departure-info" class="bg-gray-50 rounded-lg p-4">
                <p class="text-gray-600">Chưa tải dữ liệu...</p>
            </div>
        </div>

        <!-- Schedule Comparison -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">
                <i class="fas fa-calendar-alt mr-2 text-purple-600"></i>
                So Sánh Lịch Trình vs Ngày Thực Tế
            </h2>
            <div id="schedule-comparison" class="space-y-4">
                <p class="text-gray-600">Chưa tải dữ liệu...</p>
            </div>
        </div>

        <!-- Test Results -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">
                <i class="fas fa-clipboard-check mr-2 text-gray-600"></i>
                Kết Quả Kiểm Tra
            </h2>
            <div id="test-results" class="bg-gray-50 rounded-lg p-4 min-h-32">
                <p class="text-gray-600">Chưa có kết quả...</p>
            </div>
        </div>
    </div>

    <script>
        let testResults = [];

        function addTestResult(message, type = 'info', details = null) {
            const timestamp = new Date().toLocaleTimeString('vi-VN');
            const result = { message, type, details, timestamp };
            testResults.unshift(result);
            updateTestResultsDisplay();
        }

        function updateTestResultsDisplay() {
            const container = document.getElementById('test-results');
            
            if (testResults.length === 0) {
                container.innerHTML = '<p class="text-gray-600">Chưa có kết quả...</p>';
                return;
            }

            let html = '';
            testResults.slice(0, 10).forEach(result => {
                const statusClass = result.type === 'success' ? 'text-green-600' : 
                                  result.type === 'error' ? 'text-red-600' : 
                                  result.type === 'warning' ? 'text-yellow-600' : 'text-blue-600';
                const statusIcon = result.type === 'success' ? 'fa-check-circle' : 
                                 result.type === 'error' ? 'fa-times-circle' : 
                                 result.type === 'warning' ? 'fa-exclamation-triangle' : 'fa-info-circle';
                
                html += `
                    <div class="flex items-start space-x-3 mb-3 p-2 bg-white rounded border-l-4 ${
                        result.type === 'success' ? 'border-green-500' : 
                        result.type === 'error' ? 'border-red-500' : 
                        result.type === 'warning' ? 'border-yellow-500' : 'border-blue-500'
                    }">
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

        async function loadAndCompareData() {
            const tourId = document.getElementById('tour-id').value;
            const departureId = document.getElementById('departure-id').value;
            
            if (!tourId) {
                addTestResult('Vui lòng nhập Tour ID', 'error');
                return;
            }

            try {
                addTestResult('Đang tải dữ liệu tour và departure...', 'info');
                
                // Load tour data with departure
                const url = `/api/tours/${tourId}/schedules${departureId ? '?departure_id=' + departureId : ''}`;
                const response = await fetch(url);
                const data = await response.json();
                
                if (data.success) {
                    displayDepartureInfo(data.data.departure);
                    compareScheduleDates(data.data.schedules, data.data.departure);
                    addTestResult('✅ Đã tải dữ liệu thành công', 'success');
                } else {
                    addTestResult('❌ Lỗi: ' + data.message, 'error');
                }
            } catch (error) {
                addTestResult('❌ Lỗi kết nối: ' + error.message, 'error');
            }
        }

        function displayDepartureInfo(departure) {
            const container = document.getElementById('departure-info');
            
            if (!departure) {
                container.innerHTML = `
                    <div class="text-center py-4">
                        <i class="fas fa-exclamation-triangle text-yellow-500 text-2xl mb-2"></i>
                        <p class="text-yellow-700">Không tìm thấy thông tin departure</p>
                    </div>
                `;
                return;
            }

            container.innerHTML = `
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <h4 class="font-semibold text-gray-700 mb-2">Thông tin cơ bản</h4>
                        <ul class="space-y-1 text-sm">
                            <li><strong>ID:</strong> ${departure.id}</li>
                            <li><strong>Ngày khởi hành:</strong> <span class="text-blue-600 font-medium">${new Date(departure.departure_date).toLocaleDateString('vi-VN')}</span></li>
                            <li><strong>Giờ khởi hành:</strong> ${departure.departure_time || 'Chưa xác định'}</li>
                            <li><strong>Địa điểm:</strong> ${departure.departure_location || 'Chưa xác định'}</li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-700 mb-2">Hướng dẫn viên</h4>
                        <ul class="space-y-1 text-sm">
                            <li><strong>HDV chính:</strong> ${departure.guide ? departure.guide.name : 'Chưa gán'}</li>
                            <li><strong>HDV dự phòng:</strong> ${departure.backup_guide ? departure.backup_guide.name : 'Chưa gán'}</li>
                            <li><strong>Trạng thái:</strong> <span class="px-2 py-1 rounded text-xs ${getStatusClass(departure.preparation_status)}">${getStatusText(departure.preparation_status)}</span></li>
                        </ul>
                    </div>
                </div>
            `;
        }

        function compareScheduleDates(schedules, departure) {
            const container = document.getElementById('schedule-comparison');
            
            if (!schedules || schedules.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-4">
                        <i class="fas fa-calendar-times text-gray-400 text-2xl mb-2"></i>
                        <p class="text-gray-600">Không có lịch trình nào</p>
                    </div>
                `;
                return;
            }

            if (!departure) {
                container.innerHTML = `
                    <div class="text-center py-4">
                        <i class="fas fa-exclamation-triangle text-yellow-500 text-2xl mb-2"></i>
                        <p class="text-yellow-700">Không có thông tin departure để so sánh</p>
                    </div>
                `;
                return;
            }

            const departureDate = new Date(departure.departure_date);
            let allDatesMatch = true;
            let html = '';

            schedules.forEach(schedule => {
                // Calculate expected date for this day
                const expectedDate = new Date(departureDate);
                expectedDate.setDate(expectedDate.getDate() + (schedule.day_number - 1));
                
                const expectedDateStr = expectedDate.toLocaleDateString('vi-VN');
                const dayOfWeek = expectedDate.toLocaleDateString('vi-VN', { weekday: 'long' });
                
                // Check if this is a reasonable date (not too far in past/future)
                const today = new Date();
                const daysDiff = Math.abs((expectedDate - today) / (1000 * 60 * 60 * 24));
                const isReasonable = daysDiff <= 365; // Within 1 year
                
                if (!isReasonable) {
                    allDatesMatch = false;
                }

                html += `
                    <div class="border rounded-lg p-4 ${isReasonable ? 'border-green-200 bg-green-50' : 'border-yellow-200 bg-yellow-50'}">
                        <div class="flex justify-between items-start mb-2">
                            <h4 class="font-semibold text-gray-800">
                                Ngày ${schedule.day_number}: ${schedule.title}
                            </h4>
                            <span class="px-2 py-1 rounded text-xs font-medium ${isReasonable ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'}">
                                ${isReasonable ? 'Hợp lý' : 'Cần kiểm tra'}
                            </span>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <div>
                                <p><strong>Ngày dự kiến:</strong> <span class="text-blue-600">${expectedDateStr}</span></p>
                                <p><strong>Thứ:</strong> ${dayOfWeek}</p>
                            </div>
                            <div>
                                <p><strong>Địa điểm:</strong> ${schedule.location || 'Chưa có'}</p>
                                <p><strong>Hoạt động:</strong> ${schedule.activities || 'Chưa có'}</p>
                            </div>
                        </div>
                    </div>
                `;
            });

            container.innerHTML = html;

            // Add test results
            if (allDatesMatch) {
                addTestResult('✅ Tất cả ngày trong lịch trình đều hợp lý', 'success');
            } else {
                addTestResult('⚠️ Có một số ngày trong lịch trình cần kiểm tra', 'warning');
            }

            addTestResult(`📊 Tổng cộng ${schedules.length} ngày trong lịch trình`, 'info');
            addTestResult(`📅 Ngày khởi hành: ${departureDate.toLocaleDateString('vi-VN')}`, 'info');
        }

        function getStatusClass(status) {
            const classes = {
                pending: 'bg-yellow-100 text-yellow-800',
                ready: 'bg-green-100 text-green-800',
                confirmed: 'bg-blue-100 text-blue-800',
                cancelled: 'bg-red-100 text-red-800',
                draft: 'bg-gray-100 text-gray-800'
            };
            return classes[status] || 'bg-gray-100 text-gray-800';
        }

        function getStatusText(status) {
            const texts = {
                pending: 'Đang chuẩn bị',
                ready: 'Sẵn sàng',
                confirmed: 'Đã xác nhận',
                cancelled: 'Đã hủy',
                draft: 'Nháp'
            };
            return texts[status] || 'Không xác định';
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            addTestResult('Trang test đồng bộ ngày đã sẵn sàng', 'info');
            loadAndCompareData();
        });
    </script>
</body>
</html>
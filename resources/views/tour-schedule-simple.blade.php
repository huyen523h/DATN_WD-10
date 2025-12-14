<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Test Lịch trình Tour</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto py-8">
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <h1 class="text-3xl font-bold text-center text-gray-800 mb-4">
                <i class="fas fa-map-marked-alt mr-3 text-blue-600"></i>
                Test Lịch trình Tour Chi tiết
            </h1>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <button 
                    onclick="testAPI()"
                    class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors"
                >
                    <i class="fas fa-play mr-2"></i>Test API Lịch trình
                </button>
                
                <button 
                    onclick="testGuides()"
                    class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition-colors"
                >
                    <i class="fas fa-users mr-2"></i>Test API Hướng dẫn viên
                </button>

                <button 
                    onclick="testAll()"
                    class="bg-purple-600 text-white px-6 py-3 rounded-lg hover:bg-purple-700 transition-colors"
                >
                    <i class="fas fa-check-double mr-2"></i>Test Tất cả
                </button>
            </div>

            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                <h3 class="text-green-800 font-semibold mb-2">
                    <i class="fas fa-check-circle mr-2"></i>Trạng thái API
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div id="api-status-schedule" class="flex items-center">
                        <span class="w-3 h-3 bg-gray-400 rounded-full mr-2"></span>
                        <span>API Lịch trình: Chưa test</span>
                    </div>
                    <div id="api-status-guides" class="flex items-center">
                        <span class="w-3 h-3 bg-gray-400 rounded-full mr-2"></span>
                        <span>API Hướng dẫn viên: Chưa test</span>
                    </div>
                </div>
            </div>
            
            <div id="result" class="mt-6 p-4 bg-gray-50 rounded-lg hidden">
                <h3 class="font-semibold text-gray-700 mb-2">Kết quả:</h3>
                <pre id="result-content" class="text-sm text-gray-600 overflow-auto max-h-96"></pre>
            </div>
        </div>

        <!-- Manual Schedule Display -->
        <div id="schedule-display" class="bg-white rounded-lg shadow-lg p-6 hidden">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">
                <i class="fas fa-calendar-alt mr-2 text-blue-600"></i>
                Lịch trình Tour
            </h2>
            <div id="schedule-content"></div>
        </div>
    </div>

    <script>
        async function testAPI() {
            const resultDiv = document.getElementById('result');
            const resultContent = document.getElementById('result-content');
            
            try {
                resultDiv.classList.remove('hidden');
                resultContent.textContent = 'Đang tải...';
                
                const response = await fetch('/api/tours/14/schedules?departure_id=42');
                const data = await response.json();
                
                resultContent.textContent = JSON.stringify(data, null, 2);
                
                if (data.success) {
                    displaySchedule(data.data);
                }
            } catch (error) {
                resultContent.textContent = 'Lỗi: ' + error.message;
            }
        }

        async function testGuides() {
            const resultDiv = document.getElementById('result');
            const resultContent = document.getElementById('result-content');
            
            try {
                resultDiv.classList.remove('hidden');
                resultContent.textContent = 'Đang tải...';
                
                const response = await fetch('/api/guides/available');
                const data = await response.json();
                
                resultContent.textContent = JSON.stringify(data, null, 2);
                
                if (data.success) {
                    displayGuides(data.data);
                }
            } catch (error) {
                resultContent.textContent = 'Lỗi: ' + error.message;
            }
        }

        function displayGuides(guides) {
            const scheduleDisplay = document.getElementById('schedule-display');
            const scheduleContent = document.getElementById('schedule-content');
            
            if (!guides || guides.length === 0) {
                scheduleContent.innerHTML = '<p class="text-gray-500">Không có hướng dẫn viên nào.</p>';
                scheduleDisplay.classList.remove('hidden');
                return;
            }

            let html = `
                <div class="bg-green-50 p-4 rounded-lg mb-6">
                    <h3 class="text-lg font-semibold text-green-800 mb-4">
                        <i class="fas fa-users mr-2"></i>
                        Danh sách Hướng dẫn viên (${guides.length})
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            `;
            
            guides.forEach(guide => {
                html += `
                    <div class="bg-white p-4 rounded-lg shadow border-l-4 border-green-500">
                        <h4 class="font-semibold text-gray-800">${guide.name}</h4>
                        <p class="text-sm text-gray-600 mt-1">
                            <i class="fas fa-envelope mr-1"></i>${guide.email}
                        </p>
                        <p class="text-sm text-gray-600">
                            <i class="fas fa-phone mr-1"></i>${guide.phone || 'Chưa có SĐT'}
                        </p>
                        <span class="inline-block mt-2 px-2 py-1 bg-green-100 text-green-800 text-xs rounded">
                            ID: ${guide.id}
                        </span>
                    </div>
                `;
            });
            
            html += '</div></div>';
            
            scheduleContent.innerHTML = html;
            scheduleDisplay.classList.remove('hidden');
        }

        function displaySchedule(data) {
            const scheduleDisplay = document.getElementById('schedule-display');
            const scheduleContent = document.getElementById('schedule-content');
            
            if (!data.schedules || data.schedules.length === 0) {
                scheduleContent.innerHTML = '<p class="text-gray-500">Không có lịch trình nào.</p>';
                scheduleDisplay.classList.remove('hidden');
                return;
            }

            let html = '';
            
            // Tour info
            if (data.tour) {
                html += `
                    <div class="bg-blue-50 p-4 rounded-lg mb-6">
                        <h3 class="text-lg font-semibold text-blue-800">${data.tour.name || data.tour.title}</h3>
                        <p class="text-blue-600">${data.tour.description || ''}</p>
                    </div>
                `;
            }

            // Departure info
            if (data.departure) {
                html += `
                    <div class="bg-green-50 p-4 rounded-lg mb-6">
                        <h4 class="font-semibold text-green-800 mb-2">Thông tin khởi hành</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <div><strong>Ngày:</strong> ${data.departure.departure_date}</div>
                            <div><strong>Giờ:</strong> ${data.departure.departure_time || 'Chưa xác định'}</div>
                            <div><strong>Địa điểm:</strong> ${data.departure.departure_location || 'Chưa xác định'}</div>
                            <div><strong>Trạng thái:</strong> ${data.departure.preparation_status || 'pending'}</div>
                        </div>
                        ${data.departure.guide ? `
                            <div class="mt-3 p-3 bg-white rounded">
                                <strong>Hướng dẫn viên:</strong> ${data.departure.guide.name}
                                <br><small>SĐT: ${data.departure.guide.phone || 'N/A'}</small>
                            </div>
                        ` : ''}
                    </div>
                `;
            }

            // Schedules
            html += '<div class="space-y-4">';
            data.schedules.forEach((schedule, index) => {
                html += `
                    <div class="border-l-4 border-blue-500 bg-white p-4 rounded-lg shadow">
                        <h4 class="text-lg font-semibold text-gray-800 mb-2">
                            Ngày ${schedule.day_number}: ${schedule.title}
                        </h4>
                        
                        ${schedule.location ? `<p class="text-gray-600 mb-2"><i class="fas fa-map-marker-alt mr-2 text-red-500"></i>${schedule.location}</p>` : ''}
                        
                        ${schedule.start_time || schedule.end_time ? `
                            <p class="text-gray-600 mb-2">
                                <i class="fas fa-clock mr-2 text-blue-500"></i>
                                ${schedule.start_time || ''} ${schedule.end_time ? '- ' + schedule.end_time : ''}
                            </p>
                        ` : ''}
                        
                        ${schedule.description ? `<p class="text-gray-700 mb-3">${schedule.description}</p>` : ''}
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            ${schedule.activities ? `<div><strong>Hoạt động:</strong> ${schedule.activities}</div>` : ''}
                            ${schedule.meals ? `<div><strong>Bữa ăn:</strong> ${schedule.meals}</div>` : ''}
                            ${schedule.accommodation ? `<div><strong>Nơi nghỉ:</strong> ${schedule.accommodation}</div>` : ''}
                            ${schedule.transportation ? `<div><strong>Phương tiện:</strong> ${schedule.transportation}</div>` : ''}
                        </div>
                        
                        ${schedule.notes ? `
                            <div class="mt-3 p-3 bg-yellow-50 rounded border-l-4 border-yellow-400">
                                <strong>Ghi chú:</strong> ${schedule.notes}
                            </div>
                        ` : ''}
                    </div>
                `;
            });
            html += '</div>';

            scheduleContent.innerHTML = html;
            scheduleDisplay.classList.remove('hidden');
        }

        async function testAll() {
            updateApiStatus('schedule', 'testing', 'Đang test...');
            updateApiStatus('guides', 'testing', 'Đang test...');
            
            try {
                // Test API lịch trình
                const scheduleResponse = await fetch('/api/tours/14/schedules?departure_id=42');
                const scheduleData = await scheduleResponse.json();
                
                if (scheduleData.success) {
                    updateApiStatus('schedule', 'success', 'Hoạt động tốt');
                    displaySchedule(scheduleData.data);
                } else {
                    updateApiStatus('schedule', 'error', 'Có lỗi');
                }

                // Test API hướng dẫn viên
                const guidesResponse = await fetch('/api/guides/available');
                const guidesData = await guidesResponse.json();
                
                if (guidesData.success) {
                    updateApiStatus('guides', 'success', `${guidesData.data.length} HDV`);
                } else {
                    updateApiStatus('guides', 'error', 'Có lỗi');
                }

                // Hiển thị kết quả
                const resultDiv = document.getElementById('result');
                const resultContent = document.getElementById('result-content');
                
                resultDiv.classList.remove('hidden');
                resultContent.textContent = JSON.stringify({
                    schedule_api: scheduleData,
                    guides_api: guidesData
                }, null, 2);

            } catch (error) {
                updateApiStatus('schedule', 'error', 'Lỗi kết nối');
                updateApiStatus('guides', 'error', 'Lỗi kết nối');
                
                const resultDiv = document.getElementById('result');
                const resultContent = document.getElementById('result-content');
                resultDiv.classList.remove('hidden');
                resultContent.textContent = 'Lỗi: ' + error.message;
            }
        }

        function updateApiStatus(type, status, message) {
            const element = document.getElementById(`api-status-${type}`);
            const indicator = element.querySelector('.w-3');
            const text = element.querySelector('span:last-child');
            
            // Reset classes
            indicator.className = 'w-3 h-3 rounded-full mr-2';
            
            switch (status) {
                case 'testing':
                    indicator.classList.add('bg-yellow-400');
                    break;
                case 'success':
                    indicator.classList.add('bg-green-400');
                    break;
                case 'error':
                    indicator.classList.add('bg-red-400');
                    break;
                default:
                    indicator.classList.add('bg-gray-400');
            }
            
            text.textContent = `API ${type === 'schedule' ? 'Lịch trình' : 'Hướng dẫn viên'}: ${message}`;
        }
    </script>
</body>
</html>
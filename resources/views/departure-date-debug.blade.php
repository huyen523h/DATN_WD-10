<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Debug Cập Nhật Ngày Khởi Hành</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h1 class="text-2xl font-bold text-gray-800 mb-4">
                <i class="fas fa-bug mr-2 text-red-600"></i>
                Debug Cập Nhật Ngày Khởi Hành
            </h1>
            
            <!-- Test Form -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Departure ID</label>
                    <input type="number" id="departure-id" value="42" class="w-full border border-gray-300 rounded-md px-3 py-2">
                </div>
                <div class="flex items-end">
                    <button onclick="loadCurrentData()" class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                        <i class="fas fa-download mr-2"></i>Tải dữ liệu hiện tại
                    </button>
                </div>
            </div>
        </div>

        <!-- Current Data -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">
                <i class="fas fa-database mr-2 text-green-600"></i>
                Dữ Liệu Hiện Tại
            </h2>
            <div id="current-data" class="bg-gray-50 rounded-lg p-4">
                <p class="text-gray-600">Chưa tải dữ liệu...</p>
            </div>
        </div>

        <!-- Update Form -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">
                <i class="fas fa-edit mr-2 text-purple-600"></i>
                Test Cập Nhật Ngày
            </h2>
            
            <form id="update-form" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Ngày khởi hành mới</label>
                        <input type="date" id="new-departure-date" class="w-full border border-gray-300 rounded-md px-3 py-2" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Giờ khởi hành</label>
                        <input type="time" id="new-departure-time" class="w-full border border-gray-300 rounded-md px-3 py-2">
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Địa điểm khởi hành</label>
                    <input type="text" id="new-departure-location" class="w-full border border-gray-300 rounded-md px-3 py-2" placeholder="Địa điểm khởi hành">
                </div>
                
                <div class="flex justify-end space-x-4">
                    <button type="button" onclick="testUpdate()" class="bg-orange-600 text-white px-4 py-2 rounded-md hover:bg-orange-700">
                        <i class="fas fa-flask mr-2"></i>Test Update
                    </button>
                    <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded-md hover:bg-purple-700">
                        <i class="fas fa-save mr-2"></i>Lưu thay đổi
                    </button>
                </div>
            </form>
        </div>

        <!-- Debug Log -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">
                <i class="fas fa-terminal mr-2 text-gray-600"></i>
                Debug Log
            </h2>
            <div id="debug-log" class="bg-black text-green-400 rounded-lg p-4 min-h-32 max-h-64 overflow-y-auto font-mono text-sm">
                <p>Debug log sẵn sàng...</p>
            </div>
        </div>
    </div>

    <script>
        let debugLog = [];
        let currentDepartureData = null;

        function addDebugLog(message, type = 'info') {
            const timestamp = new Date().toLocaleTimeString('vi-VN');
            const logEntry = `[${timestamp}] ${type.toUpperCase()}: ${message}`;
            debugLog.unshift(logEntry);
            updateDebugDisplay();
        }

        function updateDebugDisplay() {
            const container = document.getElementById('debug-log');
            const logText = debugLog.slice(0, 50).join('\n');
            container.innerHTML = `<pre>${logText}</pre>`;
            container.scrollTop = 0;
        }

        async function loadCurrentData() {
            const departureId = document.getElementById('departure-id').value;
            
            if (!departureId) {
                addDebugLog('Departure ID không được cung cấp', 'error');
                return;
            }

            try {
                addDebugLog(`Đang tải dữ liệu departure ${departureId}...`, 'info');
                
                const response = await fetch(`/api/departures/${departureId}`);
                addDebugLog(`Response status: ${response.status}`, 'info');
                
                const data = await response.json();
                addDebugLog(`Response data: ${JSON.stringify(data)}`, 'info');
                
                if (data.success) {
                    currentDepartureData = data.data;
                    displayCurrentData(data.data);
                    
                    // Pre-fill form with current data
                    document.getElementById('new-departure-date').value = data.data.departure_date;
                    document.getElementById('new-departure-time').value = data.data.departure_time || '';
                    document.getElementById('new-departure-location').value = data.data.departure_location || '';
                    
                    addDebugLog('Đã tải dữ liệu thành công', 'success');
                } else {
                    addDebugLog(`Lỗi API: ${data.message}`, 'error');
                }
            } catch (error) {
                addDebugLog(`Lỗi kết nối: ${error.message}`, 'error');
            }
        }

        function displayCurrentData(departure) {
            const container = document.getElementById('current-data');
            
            container.innerHTML = `
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <h4 class="font-semibold text-gray-700 mb-2">Thông tin cơ bản</h4>
                        <ul class="space-y-1 text-sm">
                            <li><strong>ID:</strong> ${departure.id}</li>
                            <li><strong>Tour ID:</strong> ${departure.tour_id}</li>
                            <li><strong>Ngày khởi hành:</strong> <span class="text-blue-600 font-medium">${departure.departure_date}</span></li>
                            <li><strong>Giờ khởi hành:</strong> ${departure.departure_time || 'Chưa có'}</li>
                            <li><strong>Địa điểm:</strong> ${departure.departure_location || 'Chưa có'}</li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-700 mb-2">Metadata</h4>
                        <ul class="space-y-1 text-sm">
                            <li><strong>Tạo lúc:</strong> ${new Date(departure.created_at).toLocaleString('vi-VN')}</li>
                            <li><strong>Cập nhật lúc:</strong> ${new Date(departure.updated_at).toLocaleString('vi-VN')}</li>
                            <li><strong>Trạng thái:</strong> ${departure.preparation_status}</li>
                            <li><strong>Chỗ ngồi:</strong> ${departure.seats_available}/${departure.seats_total}</li>
                        </ul>
                    </div>
                </div>
            `;
        }

        async function testUpdate() {
            const departureId = document.getElementById('departure-id').value;
            const newDate = document.getElementById('new-departure-date').value;
            
            if (!departureId || !newDate) {
                addDebugLog('Departure ID và ngày mới là bắt buộc', 'error');
                return;
            }

            const updateData = {
                departure_date: newDate,
                departure_time: document.getElementById('new-departure-time').value,
                departure_location: document.getElementById('new-departure-location').value
            };

            try {
                addDebugLog(`Test update departure ${departureId} với dữ liệu: ${JSON.stringify(updateData)}`, 'info');
                
                const response = await fetch(`/api/departures/${departureId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(updateData)
                });

                addDebugLog(`Update response status: ${response.status}`, 'info');
                
                const result = await response.json();
                addDebugLog(`Update response: ${JSON.stringify(result)}`, 'info');
                
                if (result.success) {
                    addDebugLog('✅ Cập nhật thành công!', 'success');
                    
                    // Reload data to verify
                    setTimeout(() => {
                        addDebugLog('Đang tải lại dữ liệu để xác minh...', 'info');
                        loadCurrentData();
                    }, 1000);
                } else {
                    addDebugLog(`❌ Cập nhật thất bại: ${result.message}`, 'error');
                }
            } catch (error) {
                addDebugLog(`❌ Lỗi khi cập nhật: ${error.message}`, 'error');
            }
        }

        // Handle form submission
        document.getElementById('update-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const departureId = document.getElementById('departure-id').value;
            
            if (!departureId) {
                addDebugLog('Vui lòng nhập Departure ID', 'error');
                return;
            }

            const formData = {
                departure_date: document.getElementById('new-departure-date').value,
                departure_time: document.getElementById('new-departure-time').value,
                departure_location: document.getElementById('new-departure-location').value
            };

            try {
                addDebugLog(`Đang lưu thay đổi cho departure ${departureId}...`, 'info');
                
                const response = await fetch(`/api/departures/${departureId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(formData)
                });

                const result = await response.json();
                
                if (result.success) {
                    addDebugLog('✅ Đã lưu thay đổi thành công!', 'success');
                    
                    // Show before/after comparison
                    if (currentDepartureData) {
                        addDebugLog(`Trước: ${currentDepartureData.departure_date} -> Sau: ${result.data.departure_date}`, 'info');
                    }
                    
                    // Reload data
                    loadCurrentData();
                } else {
                    addDebugLog(`❌ Lỗi: ${result.message}`, 'error');
                }
            } catch (error) {
                addDebugLog(`❌ Lỗi kết nối: ${error.message}`, 'error');
            }
        });

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            addDebugLog('Trang debug đã sẵn sàng', 'info');
            
            // Set default date to tomorrow
            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            document.getElementById('new-departure-date').value = tomorrow.toISOString().split('T')[0];
            
            // Auto load data
            loadCurrentData();
        });
    </script>
</body>
</html>
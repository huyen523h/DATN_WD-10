<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Test Đồng Bộ Departure</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-6xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h1 class="text-2xl font-bold text-gray-800 mb-4">
                <i class="fas fa-sync mr-2 text-blue-600"></i>
                Test Đồng Bộ Thông Tin Departure
            </h1>
            
            <!-- Input Section -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tour ID</label>
                    <input type="number" id="tour-id" value="15" class="w-full border border-gray-300 rounded-md px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Departure ID (tùy chọn)</label>
                    <input type="number" id="departure-id" value="50" class="w-full border border-gray-300 rounded-md px-3 py-2">
                </div>
                <div class="flex items-end">
                    <button onclick="testSync()" class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                        <i class="fas fa-sync mr-2"></i>Test Đồng Bộ
                    </button>
                </div>
            </div>
        </div>

        <!-- Results Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- API Response -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">
                    <i class="fas fa-code mr-2 text-green-600"></i>
                    API Response
                </h2>
                <div id="api-response" class="bg-gray-50 rounded-lg p-4 min-h-64 overflow-auto">
                    <p class="text-gray-600">Chưa có dữ liệu...</p>
                </div>
            </div>

            <!-- Departure Info -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">
                    <i class="fas fa-plane-departure mr-2 text-purple-600"></i>
                    Thông Tin Departure
                </h2>
                <div id="departure-info" class="space-y-4">
                    <p class="text-gray-600">Chưa có dữ liệu...</p>
                </div>
            </div>
        </div>

        <!-- Available Departures -->
        <div class="mt-6 bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">
                <i class="fas fa-list mr-2 text-orange-600"></i>
                Tất Cả Departures Của Tour
            </h2>
            <div id="all-departures" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <p class="text-gray-600">Chưa có dữ liệu...</p>
            </div>
        </div>

        <!-- Test Log -->
        <div class="mt-6 bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">
                <i class="fas fa-clipboard-list mr-2 text-gray-600"></i>
                Test Log
            </h2>
            <div id="test-log" class="bg-gray-50 rounded-lg p-4 min-h-32 max-h-64 overflow-y-auto">
                <p class="text-gray-600">Chưa có log...</p>
            </div>
        </div>
    </div>

    <script>
        let testLogs = [];

        function addLog(message, type = 'info') {
            const timestamp = new Date().toLocaleTimeString('vi-VN');
            testLogs.unshift({ message, type, timestamp });
            updateLogDisplay();
        }

        function updateLogDisplay() {
            const container = document.getElementById('test-log');
            
            if (testLogs.length === 0) {
                container.innerHTML = '<p class="text-gray-600">Chưa có log...</p>';
                return;
            }

            let html = '';
            testLogs.slice(0, 10).forEach(log => {
                const colorClass = log.type === 'success' ? 'text-green-600' : 
                                 log.type === 'error' ? 'text-red-600' : 'text-blue-600';
                const icon = log.type === 'success' ? 'fa-check-circle' : 
                           log.type === 'error' ? 'fa-times-circle' : 'fa-info-circle';
                
                html += `
                    <div class="flex items-start space-x-2 mb-2 text-sm">
                        <i class="fas ${icon} ${colorClass} mt-0.5"></i>
                        <span class="flex-1">${log.message}</span>
                        <span class="text-xs text-gray-500">${log.timestamp}</span>
                    </div>
                `;
            });
            
            container.innerHTML = html;
        }

        async function testSync() {
            const tourId = document.getElementById('tour-id').value;
            const departureId = document.getElementById('departure-id').value;
            
            if (!tourId) {
                addLog('Vui lòng nhập Tour ID', 'error');
                return;
            }

            addLog(`Bắt đầu test với Tour ID: ${tourId}, Departure ID: ${departureId || 'không có'}`, 'info');

            // Test 1: Load tour schedules
            await testTourSchedules(tourId, departureId);
            
            // Test 2: Load all departures for tour
            await testAllDepartures(tourId);
            
            // Test 3: Load specific departure if provided
            if (departureId) {
                await testSpecificDeparture(departureId);
            }
        }

        async function testTourSchedules(tourId, departureId) {
            try {
                addLog('Đang test API tour schedules...', 'info');
                
                const url = `/api/tours/${tourId}/schedules${departureId ? '?departure_id=' + departureId : ''}`;
                const response = await fetch(url);
                const data = await response.json();
                
                // Display API response
                document.getElementById('api-response').innerHTML = `<pre class="text-xs">${JSON.stringify(data, null, 2)}</pre>`;
                
                if (data.success) {
                    addLog('✅ API tour schedules thành công', 'success');
                    
                    // Display departure info
                    if (data.data.departure) {
                        displayDepartureInfo(data.data.departure);
                        addLog('✅ Tìm thấy thông tin departure', 'success');
                    } else {
                        document.getElementById('departure-info').innerHTML = `
                            <div class="text-center py-8">
                                <i class="fas fa-exclamation-triangle text-3xl text-yellow-500 mb-2"></i>
                                <p class="text-yellow-700">Không tìm thấy departure</p>
                                <p class="text-sm text-gray-600 mt-2">
                                    ${departureId ? `Departure ID ${departureId} không thuộc Tour ${tourId}` : 'Chưa nhập Departure ID'}
                                </p>
                            </div>
                        `;
                        addLog('⚠️ Không tìm thấy departure', 'error');
                    }
                } else {
                    addLog('❌ API tour schedules lỗi: ' + data.message, 'error');
                }
            } catch (error) {
                addLog('❌ Lỗi kết nối API tour schedules: ' + error.message, 'error');
            }
        }

        async function testAllDepartures(tourId) {
            try {
                addLog('Đang test API all departures...', 'info');
                
                const response = await fetch(`/api/tours/${tourId}/departures`);
                const data = await response.json();
                
                if (data.success) {
                    addLog(`✅ Tìm thấy ${data.data.length} departures`, 'success');
                    displayAllDepartures(data.data);
                } else {
                    addLog('❌ API all departures lỗi: ' + data.message, 'error');
                }
            } catch (error) {
                addLog('❌ Lỗi kết nối API all departures: ' + error.message, 'error');
            }
        }

        async function testSpecificDeparture(departureId) {
            try {
                addLog(`Đang test API departure ${departureId}...`, 'info');
                
                const response = await fetch(`/api/departures/${departureId}`);
                const data = await response.json();
                
                if (data.success) {
                    addLog('✅ API departure cụ thể thành công', 'success');
                } else {
                    addLog('❌ API departure cụ thể lỗi: ' + data.message, 'error');
                }
            } catch (error) {
                addLog('❌ Lỗi kết nối API departure cụ thể: ' + error.message, 'error');
            }
        }

        function displayDepartureInfo(departure) {
            document.getElementById('departure-info').innerHTML = `
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <h3 class="font-semibold text-lg">Departure ID: ${departure.id}</h3>
                        <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded">${departure.preparation_status || 'N/A'}</span>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-medium text-gray-700">Ngày khởi hành</label>
                            <p class="text-gray-900">${new Date(departure.departure_date).toLocaleDateString('vi-VN')}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700">Giờ khởi hành</label>
                            <p class="text-gray-900">${departure.departure_time || 'Chưa xác định'}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700">HDV chính</label>
                            <p class="text-gray-900">${departure.guide ? departure.guide.name : 'Chưa gán'}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700">HDV dự phòng</label>
                            <p class="text-gray-900">${departure.backup_guide ? departure.backup_guide.name : 'Chưa gán'}</p>
                        </div>
                    </div>
                    
                    <div>
                        <label class="text-sm font-medium text-gray-700">Địa điểm khởi hành</label>
                        <p class="text-gray-900">${departure.departure_location || 'Chưa xác định'}</p>
                    </div>
                </div>
            `;
        }

        function displayAllDepartures(departures) {
            const container = document.getElementById('all-departures');
            
            if (!departures || departures.length === 0) {
                container.innerHTML = '<p class="text-gray-600 col-span-full text-center py-8">Không có departure nào</p>';
                return;
            }

            let html = '';
            departures.forEach(departure => {
                html += `
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow cursor-pointer" onclick="selectDeparture(${departure.id})">
                        <div class="flex justify-between items-start mb-2">
                            <h4 class="font-semibold text-gray-800">ID: ${departure.id}</h4>
                            <span class="px-2 py-1 bg-gray-100 text-gray-800 text-xs rounded">${departure.preparation_status || 'N/A'}</span>
                        </div>
                        <div class="text-sm text-gray-600 space-y-1">
                            <p><i class="fas fa-calendar mr-2"></i>${new Date(departure.departure_date).toLocaleDateString('vi-VN')}</p>
                            <p><i class="fas fa-clock mr-2"></i>${departure.departure_time || 'Chưa có giờ'}</p>
                            <p><i class="fas fa-user-tie mr-2"></i>${departure.guide ? departure.guide.name : 'Chưa có HDV'}</p>
                        </div>
                    </div>
                `;
            });
            
            container.innerHTML = html;
        }

        function selectDeparture(departureId) {
            document.getElementById('departure-id').value = departureId;
            addLog(`Đã chọn Departure ID: ${departureId}`, 'info');
            testSync();
        }

        // Auto test on load
        document.addEventListener('DOMContentLoaded', function() {
            addLog('Trang test đã sẵn sàng', 'info');
        });
    </script>
</body>
</html>
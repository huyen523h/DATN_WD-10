<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Test Cập Nhật Departure</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h1 class="text-2xl font-bold text-gray-800 mb-4">
                <i class="fas fa-calendar-alt mr-2 text-blue-600"></i>
                Test Cập Nhật Departure
            </h1>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Current Data -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="font-semibold text-gray-700 mb-3">Dữ liệu hiện tại</h3>
                    <div id="current-data">
                        <p class="text-gray-600">Đang tải...</p>
                    </div>
                    <button onclick="loadCurrentData()" class="mt-3 bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                        <i class="fas fa-refresh mr-2"></i>Tải lại
                    </button>
                </div>
                
                <!-- Update Form -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="font-semibold text-gray-700 mb-3">Cập nhật</h3>
                    <form id="update-form" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Departure ID</label>
                            <input type="number" id="departure-id" value="42" class="w-full border border-gray-300 rounded-md px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Ngày khởi hành mới</label>
                            <input type="date" id="new-date" class="w-full border border-gray-300 rounded-md px-3 py-2" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Giờ khởi hành</label>
                            <input type="time" id="new-time" class="w-full border border-gray-300 rounded-md px-3 py-2">
                        </div>
                        <button type="submit" class="w-full bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">
                            <i class="fas fa-save mr-2"></i>Cập nhật
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Log -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">
                <i class="fas fa-list mr-2 text-green-600"></i>
                Log hoạt động
            </h2>
            <div id="activity-log" class="bg-gray-900 text-green-400 rounded-lg p-4 min-h-32 max-h-64 overflow-y-auto font-mono text-sm">
                <p>[SYSTEM] Test page loaded</p>
            </div>
        </div>
    </div>

    <script>
        let activityLog = [];

        function addLog(message, type = 'info') {
            const timestamp = new Date().toLocaleTimeString('vi-VN');
            const logEntry = `[${timestamp}] ${type.toUpperCase()}: ${message}`;
            activityLog.unshift(logEntry);
            
            const container = document.getElementById('activity-log');
            container.innerHTML = '<p>' + activityLog.slice(0, 20).join('</p><p>') + '</p>';
            container.scrollTop = 0;
        }

        async function loadCurrentData() {
            const departureId = document.getElementById('departure-id').value;
            
            try {
                addLog(`Loading departure ${departureId}...`);
                
                const response = await fetch(`/api/departures/${departureId}`);
                const data = await response.json();
                
                if (data.success) {
                    const departure = data.data;
                    
                    document.getElementById('current-data').innerHTML = `
                        <div class="space-y-2 text-sm">
                            <div><strong>ID:</strong> ${departure.id}</div>
                            <div><strong>Tour ID:</strong> ${departure.tour_id}</div>
                            <div><strong>Ngày:</strong> <span class="text-blue-600 font-medium">${departure.departure_date}</span></div>
                            <div><strong>Giờ:</strong> ${departure.departure_time || 'Chưa có'}</div>
                            <div><strong>Địa điểm:</strong> ${departure.departure_location || 'Chưa có'}</div>
                            <div><strong>Cập nhật lần cuối:</strong> ${new Date(departure.updated_at).toLocaleString('vi-VN')}</div>
                        </div>
                    `;
                    
                    // Pre-fill form
                    document.getElementById('new-date').value = departure.departure_date;
                    document.getElementById('new-time').value = departure.departure_time || '';
                    
                    addLog('Data loaded successfully', 'success');
                } else {
                    addLog(`Error: ${data.message}`, 'error');
                }
            } catch (error) {
                addLog(`Connection error: ${error.message}`, 'error');
            }
        }

        document.getElementById('update-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const departureId = document.getElementById('departure-id').value;
            const newDate = document.getElementById('new-date').value;
            const newTime = document.getElementById('new-time').value;
            
            const updateData = {
                departure_date: newDate,
                departure_time: newTime
            };
            
            try {
                addLog(`Updating departure ${departureId} with new date: ${newDate}`);
                
                const response = await fetch(`/api/departures/${departureId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(updateData)
                });
                
                addLog(`Response status: ${response.status}`);
                
                const result = await response.json();
                
                if (result.success) {
                    addLog('✅ Update successful!', 'success');
                    addLog(`New date: ${result.data.departure_date}`, 'success');
                    
                    // Reload data to verify
                    setTimeout(() => {
                        loadCurrentData();
                    }, 1000);
                } else {
                    addLog(`❌ Update failed: ${result.message}`, 'error');
                }
            } catch (error) {
                addLog(`❌ Update error: ${error.message}`, 'error');
            }
        });

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            // Set default date to tomorrow
            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            document.getElementById('new-date').value = tomorrow.toISOString().split('T')[0];
            
            // Load initial data
            loadCurrentData();
        });
    </script>
</body>
</html>
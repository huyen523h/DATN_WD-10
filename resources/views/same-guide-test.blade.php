<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Test Cùng HDV</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h1 class="text-2xl font-bold text-gray-800 mb-4">
                <i class="fas fa-user-times mr-2 text-red-600"></i>
                Test Gán Cùng HDV cho Cả 2 Vai Trò
            </h1>
            
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-triangle text-yellow-600 mr-2"></i>
                    <p class="text-yellow-800">
                        <strong>Mục đích:</strong> Test xem hệ thống có ngăn chặn việc gán cùng một HDV cho cả HDV chính và HDV dự phòng không.
                    </p>
                </div>
            </div>
            
            <form id="test-form" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Departure ID</label>
                        <input type="number" id="departure-id" value="42" class="w-full border border-gray-300 rounded-md px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">HDV ID (sẽ dùng cho cả 2 vai trò)</label>
                        <input type="number" id="guide-id" value="25" class="w-full border border-gray-300 rounded-md px-3 py-2">
                        <p class="text-xs text-gray-500 mt-1">ID 25 = Trần Thị Mai</p>
                    </div>
                </div>
                
                <div class="flex space-x-4">
                    <button type="button" onclick="testSameGuide()" class="bg-red-600 text-white px-6 py-2 rounded-md hover:bg-red-700">
                        <i class="fas fa-bug mr-2"></i>Test Gán Cùng HDV
                    </button>
                    <button type="button" onclick="testValidAssignment()" class="bg-green-600 text-white px-6 py-2 rounded-md hover:bg-green-700">
                        <i class="fas fa-check mr-2"></i>Test Gán Hợp Lệ
                    </button>
                    <button type="button" onclick="getCurrentData()" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700">
                        <i class="fas fa-info mr-2"></i>Xem Dữ Liệu Hiện Tại
                    </button>
                </div>
            </form>
        </div>

        <!-- Current Data -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">
                <i class="fas fa-database mr-2 text-blue-600"></i>
                Dữ Liệu Hiện Tại
            </h2>
            <div id="current-data" class="bg-gray-50 rounded-lg p-4">
                <p class="text-gray-600">Chưa tải dữ liệu...</p>
            </div>
        </div>

        <!-- Test Results -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">
                <i class="fas fa-flask mr-2 text-purple-600"></i>
                Kết Quả Test
            </h2>
            <div id="test-results" class="space-y-3">
                <p class="text-gray-600">Chưa có test nào được chạy...</p>
            </div>
        </div>

        <!-- Activity Log -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">
                <i class="fas fa-list mr-2 text-gray-600"></i>
                Log Hoạt Động
            </h2>
            <div id="activity-log" class="bg-gray-900 text-green-400 rounded-lg p-4 min-h-32 max-h-64 overflow-y-auto font-mono text-sm">
                <p>[SYSTEM] Test page loaded</p>
            </div>
        </div>
    </div>

    <script>
        let activityLog = [];
        let testResults = [];

        function addLog(message, type = 'info') {
            const timestamp = new Date().toLocaleTimeString('vi-VN');
            const logEntry = `[${timestamp}] ${type.toUpperCase()}: ${message}`;
            activityLog.unshift(logEntry);
            
            const container = document.getElementById('activity-log');
            container.innerHTML = '<p>' + activityLog.slice(0, 20).join('</p><p>') + '</p>';
            container.scrollTop = 0;
        }

        function addTestResult(testName, success, message, details = null) {
            const result = {
                testName,
                success,
                message,
                details,
                timestamp: new Date().toLocaleTimeString('vi-VN')
            };
            
            testResults.unshift(result);
            updateTestResultsDisplay();
        }

        function updateTestResultsDisplay() {
            const container = document.getElementById('test-results');
            
            if (testResults.length === 0) {
                container.innerHTML = '<p class="text-gray-600">Chưa có test nào được chạy...</p>';
                return;
            }
            
            container.innerHTML = testResults.map(result => `
                <div class="p-4 border rounded-lg ${result.success ? 'border-green-200 bg-green-50' : 'border-red-200 bg-red-50'}">
                    <div class="flex items-center justify-between mb-2">
                        <h4 class="font-medium ${result.success ? 'text-green-800' : 'text-red-800'}">
                            <i class="fas fa-${result.success ? 'check-circle' : 'times-circle'} mr-2"></i>
                            ${result.testName}
                        </h4>
                        <span class="text-xs text-gray-500">${result.timestamp}</span>
                    </div>
                    <p class="text-sm ${result.success ? 'text-green-700' : 'text-red-700'}">${result.message}</p>
                    ${result.details ? `<pre class="text-xs mt-2 p-2 bg-gray-100 rounded overflow-x-auto">${JSON.stringify(result.details, null, 2)}</pre>` : ''}
                </div>
            `).join('');
        }

        async function getCurrentData() {
            const departureId = document.getElementById('departure-id').value;
            
            try {
                addLog(`Loading current data for departure ${departureId}...`);
                
                const response = await fetch(`/api/departures/${departureId}`);
                const data = await response.json();
                
                if (data.success) {
                    const departure = data.data;
                    
                    document.getElementById('current-data').innerHTML = `
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <h4 class="font-semibold text-gray-700 mb-2">Thông tin cơ bản</h4>
                                <ul class="space-y-1 text-sm">
                                    <li><strong>ID:</strong> ${departure.id}</li>
                                    <li><strong>Tour ID:</strong> ${departure.tour_id}</li>
                                    <li><strong>Ngày khởi hành:</strong> ${departure.departure_date}</li>
                                    <li><strong>Giờ khởi hành:</strong> ${departure.departure_time || 'Chưa có'}</li>
                                </ul>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-700 mb-2">Hướng dẫn viên</h4>
                                <ul class="space-y-1 text-sm">
                                    <li><strong>HDV Chính:</strong> ${departure.guide ? `${departure.guide.name} (ID: ${departure.guide.id})` : 'Chưa gán'}</li>
                                    <li><strong>HDV Dự phòng:</strong> ${departure.backup_guide ? `${departure.backup_guide.name} (ID: ${departure.backup_guide.id})` : 'Chưa gán'}</li>
                                    <li><strong>Trạng thái:</strong> ${departure.preparation_status}</li>
                                </ul>
                            </div>
                        </div>
                    `;
                    
                    addLog('Data loaded successfully', 'success');
                } else {
                    addLog(`Error: ${data.message}`, 'error');
                }
            } catch (error) {
                addLog(`Connection error: ${error.message}`, 'error');
            }
        }

        async function testSameGuide() {
            const departureId = document.getElementById('departure-id').value;
            const guideId = document.getElementById('guide-id').value;
            
            if (!departureId || !guideId) {
                addLog('Please provide departure ID and guide ID', 'error');
                return;
            }
            
            const testData = {
                guide_id: parseInt(guideId),
                backup_guide_id: parseInt(guideId) // Same guide for both roles!
            };
            
            try {
                addLog(`Testing assignment of same guide (ID: ${guideId}) for both roles...`);
                
                const response = await fetch(`/api/departures/${departureId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(testData)
                });
                
                const result = await response.json();
                
                if (result.success) {
                    // This should NOT happen - it's a bug!
                    addTestResult(
                        'Test Gán Cùng HDV', 
                        false, 
                        '🚨 BUG DETECTED: Hệ thống cho phép gán cùng HDV cho cả 2 vai trò!', 
                        result
                    );
                    addLog('❌ BUG: System allowed same guide assignment!', 'error');
                } else {
                    // This is the expected behavior
                    addTestResult(
                        'Test Gán Cùng HDV', 
                        true, 
                        '✅ PASS: Hệ thống đã ngăn chặn việc gán cùng HDV', 
                        result
                    );
                    addLog('✅ PASS: System correctly rejected same guide assignment', 'success');
                }
            } catch (error) {
                addLog(`❌ Test error: ${error.message}`, 'error');
                addTestResult('Test Gán Cùng HDV', false, `Error: ${error.message}`);
            }
        }

        async function testValidAssignment() {
            const departureId = document.getElementById('departure-id').value;
            const guideId = document.getElementById('guide-id').value;
            
            if (!departureId || !guideId) {
                addLog('Please provide departure ID and guide ID', 'error');
                return;
            }
            
            // Use different guides for main and backup
            const testData = {
                guide_id: parseInt(guideId),        // Guide 25
                backup_guide_id: parseInt(guideId) + 1  // Guide 26 (if exists)
            };
            
            try {
                addLog(`Testing valid assignment: Main=${testData.guide_id}, Backup=${testData.backup_guide_id}...`);
                
                const response = await fetch(`/api/departures/${departureId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(testData)
                });
                
                const result = await response.json();
                
                if (result.success) {
                    addTestResult(
                        'Test Gán Hợp Lệ', 
                        true, 
                        '✅ PASS: Hệ thống cho phép gán HDV khác nhau', 
                        result
                    );
                    addLog('✅ PASS: Valid assignment accepted', 'success');
                } else {
                    addTestResult(
                        'Test Gán Hợp Lệ', 
                        false, 
                        `❌ FAIL: ${result.message}`, 
                        result
                    );
                    addLog(`❌ FAIL: Valid assignment rejected: ${result.message}`, 'error');
                }
            } catch (error) {
                addLog(`❌ Test error: ${error.message}`, 'error');
                addTestResult('Test Gán Hợp Lệ', false, `Error: ${error.message}`);
            }
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            addLog('Same guide test page ready');
            getCurrentData();
        });
    </script>
</body>
</html>
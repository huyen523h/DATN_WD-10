<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Hub - Hệ thống Quản lý Tour</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-blue-50 to-purple-50 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">
                <i class="fas fa-flask mr-2 text-purple-600"></i>
                Test Hub
            </h1>
            <p class="text-gray-600">Trung tâm test tất cả tính năng hệ thống</p>
        </div>

        <!-- Quick Status -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
            <div class="bg-white rounded-lg shadow-md p-4 text-center">
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-2">
                    <i class="fas fa-check text-green-600 text-xl"></i>
                </div>
                <h3 class="font-semibold text-gray-800">API Fixed</h3>
                <p class="text-sm text-gray-600">Routes conflict resolved</p>
            </div>
            
            <div class="bg-white rounded-lg shadow-md p-4 text-center">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-2">
                    <i class="fas fa-server text-blue-600 text-xl"></i>
                </div>
                <h3 class="font-semibold text-gray-800">Server Online</h3>
                <p class="text-sm text-gray-600">Laravel running on :8000</p>
            </div>
            
            <div class="bg-white rounded-lg shadow-md p-4 text-center">
                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-2">
                    <i class="fas fa-database text-purple-600 text-xl"></i>
                </div>
                <h3 class="font-semibold text-gray-800">Database Ready</h3>
                <p class="text-sm text-gray-600">MySQL connected</p>
            </div>
        </div>

        <!-- Test Links -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Quick Tests -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="bg-green-500 text-white p-4">
                    <h3 class="font-semibold flex items-center">
                        <i class="fas fa-bolt mr-2"></i>
                        Quick Tests
                    </h3>
                </div>
                <div class="p-4 space-y-3">
                    <a href="/quick-test" target="_blank" class="block p-3 bg-green-50 hover:bg-green-100 rounded-lg transition-colors">
                        <i class="fas fa-plus mr-2 text-green-600"></i>
                        <strong>Quick Add Schedule</strong>
                        <p class="text-sm text-gray-600">Test thêm lịch trình nhanh</p>
                    </a>
                    
                    <a href="/schedule-test" target="_blank" class="block p-3 bg-green-50 hover:bg-green-100 rounded-lg transition-colors">
                        <i class="fas fa-calendar-plus mr-2 text-green-600"></i>
                        <strong>Full Schedule Test</strong>
                        <p class="text-sm text-gray-600">Test đầy đủ tính năng</p>
                    </a>
                    
                    <a href="/guide-assignment-test" target="_blank" class="block p-3 bg-green-50 hover:bg-green-100 rounded-lg transition-colors">
                        <i class="fas fa-user-tie mr-2 text-green-600"></i>
                        <strong>Guide Assignment Test</strong>
                        <p class="text-sm text-gray-600">Test gán hướng dẫn viên</p>
                    </a>
                    
                    <a href="/departure-sync-test" target="_blank" class="block p-3 bg-green-50 hover:bg-green-100 rounded-lg transition-colors">
                        <i class="fas fa-sync mr-2 text-green-600"></i>
                        <strong>Departure Sync Test</strong>
                        <p class="text-sm text-gray-600">Test đồng bộ thông tin khởi hành</p>
                    </a>
                    
                    <a href="/create-departure-test" target="_blank" class="block p-3 bg-green-50 hover:bg-green-100 rounded-lg transition-colors">
                        <i class="fas fa-plus mr-2 text-green-600"></i>
                        <strong>Create Departure Test</strong>
                        <p class="text-sm text-gray-600">Test tạo departure mới</p>
                    </a>
                    
                    <a href="/guide-conflict-test" target="_blank" class="block p-3 bg-red-50 hover:bg-red-100 rounded-lg transition-colors">
                        <i class="fas fa-user-check mr-2 text-red-600"></i>
                        <strong>Guide Conflict Test</strong>
                        <p class="text-sm text-gray-600">Test xung đột lịch trình HDV</p>
                    </a>
                    
                    <a href="/date-sync-test" target="_blank" class="block p-3 bg-green-50 hover:bg-green-100 rounded-lg transition-colors">
                        <i class="fas fa-calendar-sync mr-2 text-green-600"></i>
                        <strong>Date Sync Test</strong>
                        <p class="text-sm text-gray-600">Test đồng bộ ngày khởi hành</p>
                    </a>
                    
                    <a href="/departure-comparison-test" target="_blank" class="block p-3 bg-orange-50 hover:bg-orange-100 rounded-lg transition-colors">
                        <i class="fas fa-calendar-check mr-2 text-orange-600"></i>
                        <strong>Departure Comparison</strong>
                        <p class="text-sm text-gray-600">So sánh các departure khác nhau</p>
                    </a>
                    
                    <a href="/departure-date-debug" target="_blank" class="block p-3 bg-red-50 hover:bg-red-100 rounded-lg transition-colors">
                        <i class="fas fa-bug mr-2 text-red-600"></i>
                        <strong>Departure Date Debug</strong>
                        <p class="text-sm text-gray-600">Debug cập nhật ngày khởi hành</p>
                    </a>
                    
                    <a href="/departure-update-test" target="_blank" class="block p-3 bg-green-50 hover:bg-green-100 rounded-lg transition-colors">
                        <i class="fas fa-calendar-check mr-2 text-green-600"></i>
                        <strong>Departure Update Test</strong>
                        <p class="text-sm text-gray-600">Test đơn giản cập nhật departure</p>
                    </a>
                    
                    <a href="/guide-conflict-debug" target="_blank" class="block p-3 bg-yellow-50 hover:bg-yellow-100 rounded-lg transition-colors">
                        <i class="fas fa-user-times mr-2 text-yellow-600"></i>
                        <strong>Guide Conflict Debug</strong>
                        <p class="text-sm text-gray-600">Debug chi tiết xung đột HDV</p>
                    </a>
                    
                    <a href="/same-guide-test" target="_blank" class="block p-3 bg-red-50 hover:bg-red-100 rounded-lg transition-colors">
                        <i class="fas fa-bug mr-2 text-red-600"></i>
                        <strong>Same Guide Test</strong>
                        <p class="text-sm text-gray-600">Test gán cùng HDV cho 2 vai trò</p>
                    </a>
                    
                    <a href="/test-create-guide" target="_blank" class="block p-3 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">
                        <i class="fas fa-user-plus mr-2 text-blue-600"></i>
                        <strong>Test Create Guide</strong>
                        <p class="text-sm text-gray-600">Test tạo HDV mới</p>
                    </a>
                </div>
            </div>

            <!-- Admin Panel -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="bg-blue-500 text-white p-4">
                    <h3 class="font-semibold flex items-center">
                        <i class="fas fa-cogs mr-2"></i>
                        Admin Panel
                    </h3>
                </div>
                <div class="p-4 space-y-3">
                    <a href="/admin/tour-schedule-management" target="_blank" class="block p-3 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">
                        <i class="fas fa-calendar-alt mr-2 text-blue-600"></i>
                        <strong>Schedule Management</strong>
                        <p class="text-sm text-gray-600">Quản lý lịch trình admin</p>
                    </a>
                    
                    <a href="/admin-system-test" target="_blank" class="block p-3 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">
                        <i class="fas fa-flask mr-2 text-blue-600"></i>
                        <strong>System Test</strong>
                        <p class="text-sm text-gray-600">Test toàn bộ hệ thống</p>
                    </a>
                </div>
            </div>

            <!-- Customer View -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="bg-purple-500 text-white p-4">
                    <h3 class="font-semibold flex items-center">
                        <i class="fas fa-eye mr-2"></i>
                        Customer View
                    </h3>
                </div>
                <div class="p-4 space-y-3">
                    <a href="/customer/tour-schedule?tour_id=14&departure_id=42" target="_blank" class="block p-3 bg-purple-50 hover:bg-purple-100 rounded-lg transition-colors">
                        <i class="fas fa-map-marked-alt mr-2 text-purple-600"></i>
                        <strong>Tour Schedule</strong>
                        <p class="text-sm text-gray-600">Xem lịch trình khách hàng</p>
                    </a>
                    
                    <a href="/system-dashboard" target="_blank" class="block p-3 bg-purple-50 hover:bg-purple-100 rounded-lg transition-colors">
                        <i class="fas fa-dashboard mr-2 text-purple-600"></i>
                        <strong>System Dashboard</strong>
                        <p class="text-sm text-gray-600">Dashboard tổng hợp</p>
                    </a>
                </div>
            </div>
        </div>

        <!-- API Test Section -->
        <div class="mt-8 bg-white rounded-lg shadow-md p-6">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">
                <i class="fas fa-plug mr-2 text-gray-600"></i>
                API Test
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h4 class="font-medium text-gray-700 mb-3">Test API Endpoints</h4>
                    <div class="space-y-2">
                        <button onclick="testAPI('/api/guides/available', 'GET')" class="w-full text-left p-2 bg-gray-50 hover:bg-gray-100 rounded text-sm">
                            GET /api/guides/available
                        </button>
                        <button onclick="testAPI('/api/tours/14/schedules', 'GET')" class="w-full text-left p-2 bg-gray-50 hover:bg-gray-100 rounded text-sm">
                            GET /api/tours/14/schedules
                        </button>
                        <button onclick="testCreateSchedule()" class="w-full text-left p-2 bg-green-50 hover:bg-green-100 rounded text-sm">
                            POST /api/schedule-create/14
                        </button>
                    </div>
                </div>
                
                <div>
                    <h4 class="font-medium text-gray-700 mb-3">Test Results</h4>
                    <div id="api-results" class="bg-gray-50 rounded p-3 min-h-32 text-sm">
                        <p class="text-gray-600">Click vào API endpoints để test...</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Instructions -->
        <div class="mt-8 bg-yellow-50 border border-yellow-200 rounded-lg p-6">
            <h3 class="font-semibold text-yellow-800 mb-2">
                <i class="fas fa-info-circle mr-2"></i>
                Hướng dẫn sử dụng
            </h3>
            <ul class="text-yellow-700 space-y-1 text-sm">
                <li>• <strong>Quick Test:</strong> Test nhanh thêm lịch trình</li>
                <li>• <strong>Admin Panel:</strong> Quản lý đầy đủ với giao diện admin</li>
                <li>• <strong>Customer View:</strong> Xem giao diện như khách hàng</li>
                <li>• <strong>API Test:</strong> Test trực tiếp các API endpoints</li>
            </ul>
        </div>
    </div>

    <script>
        let testResults = [];

        async function testAPI(endpoint, method = 'GET') {
            const resultDiv = document.getElementById('api-results');
            
            try {
                resultDiv.innerHTML = `<p class="text-blue-600">Testing ${method} ${endpoint}...</p>`;
                
                const response = await fetch(endpoint, { method });
                const data = await response.json();
                
                const result = {
                    endpoint,
                    method,
                    status: response.status,
                    success: data.success || response.ok,
                    data: data
                };
                
                testResults.unshift(result);
                updateResults();
                
            } catch (error) {
                const result = {
                    endpoint,
                    method,
                    status: 'Error',
                    success: false,
                    error: error.message
                };
                
                testResults.unshift(result);
                updateResults();
            }
        }

        async function testCreateSchedule() {
            const endpoint = '/api/schedule-create/14';
            const resultDiv = document.getElementById('api-results');
            
            try {
                resultDiv.innerHTML = `<p class="text-blue-600">Testing POST ${endpoint}...</p>`;
                
                const testData = {
                    day_number: Math.floor(Math.random() * 10) + 10, // Random day 10-19
                    title: `Test Schedule ${Date.now()}`,
                    description: 'Auto-generated test schedule'
                };
                
                const response = await fetch(endpoint, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(testData)
                });
                
                const data = await response.json();
                
                const result = {
                    endpoint,
                    method: 'POST',
                    status: response.status,
                    success: data.success || response.ok,
                    data: data,
                    requestData: testData
                };
                
                testResults.unshift(result);
                updateResults();
                
            } catch (error) {
                const result = {
                    endpoint,
                    method: 'POST',
                    status: 'Error',
                    success: false,
                    error: error.message
                };
                
                testResults.unshift(result);
                updateResults();
            }
        }

        function updateResults() {
            const resultDiv = document.getElementById('api-results');
            
            if (testResults.length === 0) {
                resultDiv.innerHTML = '<p class="text-gray-600">Chưa có test nào...</p>';
                return;
            }
            
            let html = '';
            testResults.slice(0, 3).forEach(result => {
                const statusClass = result.success ? 'text-green-600' : 'text-red-600';
                const statusIcon = result.success ? '✅' : '❌';
                
                html += `
                    <div class="mb-3 p-2 border-l-4 ${result.success ? 'border-green-500 bg-green-50' : 'border-red-500 bg-red-50'}">
                        <div class="flex justify-between items-start">
                            <span class="font-medium ${statusClass}">${statusIcon} ${result.method} ${result.endpoint}</span>
                            <span class="text-xs text-gray-500">${result.status}</span>
                        </div>
                        ${result.success ? 
                            `<p class="text-xs text-green-700 mt-1">${result.data.message || 'Success'}</p>` :
                            `<p class="text-xs text-red-700 mt-1">${result.error || result.data?.message || 'Failed'}</p>`
                        }
                    </div>
                `;
            });
            
            resultDiv.innerHTML = html;
        }

        // Auto-test on load
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(() => {
                testAPI('/api/guides/available', 'GET');
            }, 1000);
        });
    </script>
</body>
</html>
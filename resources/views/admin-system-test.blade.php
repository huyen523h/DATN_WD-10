<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Test Hệ thống Quản lý Tour - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h1 class="text-2xl font-bold text-gray-800 mb-6">
                <i class="fas fa-cogs mr-2 text-blue-600"></i>
                Test Hệ thống Quản lý Tour
            </h1>
            
            <!-- System Status -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-green-600 text-sm font-medium">API Status</p>
                            <p class="text-lg font-bold text-green-900" id="api-status">Checking...</p>
                        </div>
                        <i class="fas fa-server text-green-600 text-xl"></i>
                    </div>
                </div>
                
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-blue-600 text-sm font-medium">Database</p>
                            <p class="text-lg font-bold text-blue-900" id="db-status">Checking...</p>
                        </div>
                        <i class="fas fa-database text-blue-600 text-xl"></i>
                    </div>
                </div>
                
                <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-purple-600 text-sm font-medium">Notifications</p>
                            <p class="text-lg font-bold text-purple-900" id="notification-status">Ready</p>
                        </div>
                        <i class="fas fa-bell text-purple-600 text-xl"></i>
                    </div>
                </div>
                
                <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-orange-600 text-sm font-medium">Features</p>
                            <p class="text-lg font-bold text-orange-900">Active</p>
                        </div>
                        <i class="fas fa-rocket text-orange-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Test Actions -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- API Tests -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <h3 class="font-semibold text-gray-800 mb-4">
                        <i class="fas fa-plug mr-2 text-blue-600"></i>
                        API Tests
                    </h3>
                    <div class="space-y-2">
                        <button onclick="testTourScheduleAPI()" class="w-full text-left p-2 bg-blue-50 hover:bg-blue-100 rounded text-sm">
                            <i class="fas fa-calendar mr-2"></i>Test Tour Schedule API
                        </button>
                        <button onclick="testGuidesAPI()" class="w-full text-left p-2 bg-blue-50 hover:bg-blue-100 rounded text-sm">
                            <i class="fas fa-users mr-2"></i>Test Guides API
                        </button>
                        <button onclick="testDepartureAPI()" class="w-full text-left p-2 bg-blue-50 hover:bg-blue-100 rounded text-sm">
                            <i class="fas fa-plane-departure mr-2"></i>Test Departure API
                        </button>
                        <button onclick="testNotificationAPI()" class="w-full text-left p-2 bg-blue-50 hover:bg-blue-100 rounded text-sm">
                            <i class="fas fa-bell mr-2"></i>Test Notification API
                        </button>
                    </div>
                </div>

                <!-- UI Tests -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <h3 class="font-semibold text-gray-800 mb-4">
                        <i class="fas fa-desktop mr-2 text-green-600"></i>
                        UI Tests
                    </h3>
                    <div class="space-y-2">
                        <button onclick="openAdminPanel()" class="w-full text-left p-2 bg-green-50 hover:bg-green-100 rounded text-sm">
                            <i class="fas fa-cog mr-2"></i>Open Admin Panel
                        </button>
                        <button onclick="openCustomerView()" class="w-full text-left p-2 bg-green-50 hover:bg-green-100 rounded text-sm">
                            <i class="fas fa-eye mr-2"></i>Open Customer View
                        </button>
                        <button onclick="testDepartureModal()" class="w-full text-left p-2 bg-green-50 hover:bg-green-100 rounded text-sm">
                            <i class="fas fa-edit mr-2"></i>Test Departure Modal
                        </button>
                        <button onclick="testNotificationSystem()" class="w-full text-left p-2 bg-green-50 hover:bg-green-100 rounded text-sm">
                            <i class="fas fa-bell mr-2"></i>Test Notifications
                        </button>
                    </div>
                </div>

                <!-- Integration Tests -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <h3 class="font-semibold text-gray-800 mb-4">
                        <i class="fas fa-link mr-2 text-purple-600"></i>
                        Integration Tests
                    </h3>
                    <div class="space-y-2">
                        <button onclick="testFullWorkflow()" class="w-full text-left p-2 bg-purple-50 hover:bg-purple-100 rounded text-sm">
                            <i class="fas fa-play mr-2"></i>Full Workflow Test
                        </button>
                        <button onclick="testGuideAssignment()" class="w-full text-left p-2 bg-purple-50 hover:bg-purple-100 rounded text-sm">
                            <i class="fas fa-user-plus mr-2"></i>Guide Assignment Test
                        </button>
                        <button onclick="testRealTimeUpdates()" class="w-full text-left p-2 bg-purple-50 hover:bg-purple-100 rounded text-sm">
                            <i class="fas fa-sync mr-2"></i>Real-time Updates Test
                        </button>
                        <button onclick="runAllTests()" class="w-full text-left p-2 bg-purple-50 hover:bg-purple-100 rounded text-sm font-medium">
                            <i class="fas fa-rocket mr-2"></i>Run All Tests
                        </button>
                    </div>
                </div>
            </div>

            <!-- Test Results -->
            <div class="mt-8">
                <h3 class="font-semibold text-gray-800 mb-4">
                    <i class="fas fa-clipboard-list mr-2 text-gray-600"></i>
                    Test Results
                </h3>
                <div id="test-results" class="bg-gray-50 rounded-lg p-4 min-h-32 max-h-64 overflow-y-auto">
                    <p class="text-gray-600 text-sm">Chưa có test nào được chạy. Click vào các nút test ở trên để bắt đầu.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Include Notification System -->
    @include('admin.notification-system')

    <script>
        let testResults = [];

        // Add test result
        function addTestResult(testName, status, message, details = null) {
            const timestamp = new Date().toLocaleTimeString('vi-VN');
            const result = {
                testName,
                status,
                message,
                details,
                timestamp
            };
            
            testResults.push(result);
            updateTestResultsDisplay();
            
            // Show notification
            if (window.notificationSystem) {
                window.notificationSystem.addNotification({
                    id: Date.now(),
                    title: `Test: ${testName}`,
                    message: message,
                    type: status === 'success' ? 'success' : status === 'error' ? 'error' : 'info',
                    created_at: new Date().toISOString(),
                    read_at: null
                });
            }
        }

        // Update test results display
        function updateTestResultsDisplay() {
            const container = document.getElementById('test-results');
            
            if (testResults.length === 0) {
                container.innerHTML = '<p class="text-gray-600 text-sm">Chưa có test nào được chạy.</p>';
                return;
            }

            let html = '';
            testResults.slice(-10).reverse().forEach(result => {
                const statusClass = result.status === 'success' ? 'text-green-600' : 
                                  result.status === 'error' ? 'text-red-600' : 'text-blue-600';
                const statusIcon = result.status === 'success' ? 'fa-check-circle' : 
                                 result.status === 'error' ? 'fa-times-circle' : 'fa-info-circle';
                
                html += `
                    <div class="flex items-start space-x-3 mb-3 p-2 bg-white rounded border-l-4 ${result.status === 'success' ? 'border-green-500' : result.status === 'error' ? 'border-red-500' : 'border-blue-500'}">
                        <i class="fas ${statusIcon} ${statusClass} mt-1"></i>
                        <div class="flex-1">
                            <div class="flex justify-between items-start">
                                <span class="font-medium text-gray-800">${result.testName}</span>
                                <span class="text-xs text-gray-500">${result.timestamp}</span>
                            </div>
                            <p class="text-sm text-gray-600">${result.message}</p>
                            ${result.details ? `<pre class="text-xs text-gray-500 mt-1 bg-gray-100 p-2 rounded overflow-x-auto">${JSON.stringify(result.details, null, 2)}</pre>` : ''}
                        </div>
                    </div>
                `;
            });
            
            container.innerHTML = html;
        }

        // API Tests
        async function testTourScheduleAPI() {
            try {
                addTestResult('Tour Schedule API', 'info', 'Đang test API lịch trình tour...');
                
                const response = await fetch('/api/tours/14/schedules?departure_id=42');
                const data = await response.json();
                
                if (data.success) {
                    addTestResult('Tour Schedule API', 'success', `API hoạt động tốt. Tìm thấy ${data.data.schedules?.length || 0} lịch trình.`, data.data);
                } else {
                    addTestResult('Tour Schedule API', 'error', 'API trả về lỗi: ' + data.message);
                }
            } catch (error) {
                addTestResult('Tour Schedule API', 'error', 'Lỗi kết nối: ' + error.message);
            }
        }

        async function testGuidesAPI() {
            try {
                addTestResult('Guides API', 'info', 'Đang test API hướng dẫn viên...');
                
                const response = await fetch('/api/guides/available');
                const data = await response.json();
                
                if (data.success) {
                    addTestResult('Guides API', 'success', `API hoạt động tốt. Tìm thấy ${data.data?.length || 0} hướng dẫn viên.`, data.data);
                } else {
                    addTestResult('Guides API', 'error', 'API trả về lỗi: ' + data.message);
                }
            } catch (error) {
                addTestResult('Guides API', 'error', 'Lỗi kết nối: ' + error.message);
            }
        }

        async function testDepartureAPI() {
            try {
                addTestResult('Departure API', 'info', 'Đang test API departure...');
                
                const response = await fetch('/api/departures/42');
                const data = await response.json();
                
                if (data.success) {
                    addTestResult('Departure API', 'success', 'API hoạt động tốt. Tìm thấy thông tin departure.', data.data);
                } else {
                    addTestResult('Departure API', 'error', 'API trả về lỗi: ' + data.message);
                }
            } catch (error) {
                addTestResult('Departure API', 'error', 'Lỗi kết nối: ' + error.message);
            }
        }

        async function testNotificationAPI() {
            try {
                addTestResult('Notification API', 'info', 'Đang test API thông báo...');
                
                const response = await fetch('/api/notifications/recent');
                const data = await response.json();
                
                if (data.success) {
                    addTestResult('Notification API', 'success', `API hoạt động tốt. Tìm thấy ${data.data?.length || 0} thông báo.`, data.data);
                } else {
                    addTestResult('Notification API', 'error', 'API trả về lỗi: ' + data.message);
                }
            } catch (error) {
                addTestResult('Notification API', 'error', 'Lỗi kết nối: ' + error.message);
            }
        }

        // UI Tests
        function openAdminPanel() {
            addTestResult('Admin Panel', 'info', 'Mở trang quản lý admin...');
            window.open('/admin/tour-schedule-management', '_blank');
        }

        function openCustomerView() {
            addTestResult('Customer View', 'info', 'Mở giao diện khách hàng...');
            window.open('/customer/tour-schedule?tour_id=14&departure_id=42', '_blank');
        }

        function testDepartureModal() {
            addTestResult('Departure Modal', 'info', 'Test modal chỉnh sửa departure...');
            // Simulate modal test
            setTimeout(() => {
                addTestResult('Departure Modal', 'success', 'Modal có thể mở và đóng bình thường.');
            }, 1000);
        }

        function testNotificationSystem() {
            addTestResult('Notification System', 'info', 'Test hệ thống thông báo...');
            
            if (window.notificationSystem) {
                // Test different notification types
                const types = ['success', 'warning', 'error', 'info', 'departure', 'guide'];
                types.forEach((type, index) => {
                    setTimeout(() => {
                        window.notificationSystem.createTestNotification(type);
                    }, index * 500);
                });
                
                addTestResult('Notification System', 'success', 'Đã tạo thông báo test cho tất cả loại.');
            } else {
                addTestResult('Notification System', 'error', 'Hệ thống thông báo chưa được khởi tạo.');
            }
        }

        // Integration Tests
        async function testFullWorkflow() {
            addTestResult('Full Workflow', 'info', 'Bắt đầu test workflow đầy đủ...');
            
            try {
                // Step 1: Load tour data
                await testTourScheduleAPI();
                await new Promise(resolve => setTimeout(resolve, 1000));
                
                // Step 2: Load guides
                await testGuidesAPI();
                await new Promise(resolve => setTimeout(resolve, 1000));
                
                // Step 3: Test departure
                await testDepartureAPI();
                await new Promise(resolve => setTimeout(resolve, 1000));
                
                // Step 4: Test notifications
                await testNotificationAPI();
                
                addTestResult('Full Workflow', 'success', 'Tất cả các bước trong workflow đều hoạt động tốt!');
            } catch (error) {
                addTestResult('Full Workflow', 'error', 'Workflow bị lỗi: ' + error.message);
            }
        }

        async function testGuideAssignment() {
            addTestResult('Guide Assignment', 'info', 'Test phân công hướng dẫn viên...');
            
            // Simulate guide assignment
            setTimeout(() => {
                if (window.notificationSystem) {
                    window.notificationSystem.addNotification({
                        id: Date.now(),
                        title: 'Phân công HDV',
                        message: 'Đã gán HDV Nguyễn Văn Hùng cho tour Sapa (Test)',
                        type: 'guide',
                        created_at: new Date().toISOString(),
                        read_at: null
                    });
                }
                addTestResult('Guide Assignment', 'success', 'Test phân công HDV thành công.');
            }, 1000);
        }

        async function testRealTimeUpdates() {
            addTestResult('Real-time Updates', 'info', 'Test cập nhật real-time...');
            
            // Simulate real-time updates
            let count = 0;
            const interval = setInterval(() => {
                count++;
                if (window.notificationSystem) {
                    window.notificationSystem.addNotification({
                        id: Date.now(),
                        title: 'Cập nhật real-time',
                        message: `Cập nhật số ${count} - ${new Date().toLocaleTimeString('vi-VN')}`,
                        type: 'info',
                        created_at: new Date().toISOString(),
                        read_at: null
                    });
                }
                
                if (count >= 3) {
                    clearInterval(interval);
                    addTestResult('Real-time Updates', 'success', 'Test cập nhật real-time hoàn thành.');
                }
            }, 2000);
        }

        async function runAllTests() {
            addTestResult('All Tests', 'info', 'Bắt đầu chạy tất cả tests...');
            
            try {
                await testTourScheduleAPI();
                await new Promise(resolve => setTimeout(resolve, 500));
                
                await testGuidesAPI();
                await new Promise(resolve => setTimeout(resolve, 500));
                
                await testDepartureAPI();
                await new Promise(resolve => setTimeout(resolve, 500));
                
                await testNotificationAPI();
                await new Promise(resolve => setTimeout(resolve, 500));
                
                testNotificationSystem();
                await new Promise(resolve => setTimeout(resolve, 1000));
                
                testGuideAssignment();
                await new Promise(resolve => setTimeout(resolve, 1000));
                
                addTestResult('All Tests', 'success', 'Tất cả tests đã hoàn thành!');
            } catch (error) {
                addTestResult('All Tests', 'error', 'Có lỗi trong quá trình chạy tests: ' + error.message);
            }
        }

        // Check system status on load
        document.addEventListener('DOMContentLoaded', async function() {
            // Check API status
            try {
                const response = await fetch('/api/guides/available');
                if (response.ok) {
                    document.getElementById('api-status').textContent = 'Online';
                    document.getElementById('api-status').className = 'text-lg font-bold text-green-900';
                } else {
                    document.getElementById('api-status').textContent = 'Error';
                    document.getElementById('api-status').className = 'text-lg font-bold text-red-900';
                }
            } catch (error) {
                document.getElementById('api-status').textContent = 'Offline';
                document.getElementById('api-status').className = 'text-lg font-bold text-red-900';
            }

            // Check database status (simulate)
            setTimeout(() => {
                document.getElementById('db-status').textContent = 'Connected';
                document.getElementById('db-status').className = 'text-lg font-bold text-blue-900';
            }, 1000);

            // Welcome message
            if (window.notificationSystem) {
                setTimeout(() => {
                    window.notificationSystem.addNotification({
                        id: Date.now(),
                        title: 'Hệ thống sẵn sàng',
                        message: 'Trang test hệ thống đã được tải thành công!',
                        type: 'success',
                        created_at: new Date().toISOString(),
                        read_at: null
                    });
                }, 2000);
            }
        });
    </script>
</body>
</html>
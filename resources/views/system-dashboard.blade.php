<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hệ thống Quản lý Tour - Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-blue-50 to-purple-50 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-800 mb-4">
                <i class="fas fa-map-marked-alt mr-3 text-blue-600"></i>
                Hệ thống Quản lý Tour
            </h1>
            <p class="text-xl text-gray-600">Dashboard tổng hợp tất cả tính năng</p>
        </div>

        <!-- System Status -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-12">
            <div class="bg-white rounded-lg shadow-md p-6 text-center">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-server text-2xl text-green-600"></i>
                </div>
                <h3 class="font-semibold text-gray-800 mb-2">Server Status</h3>
                <p class="text-green-600 font-medium" id="server-status">Online</p>
            </div>
            
            <div class="bg-white rounded-lg shadow-md p-6 text-center">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-database text-2xl text-blue-600"></i>
                </div>
                <h3 class="font-semibold text-gray-800 mb-2">Database</h3>
                <p class="text-blue-600 font-medium">Connected</p>
            </div>
            
            <div class="bg-white rounded-lg shadow-md p-6 text-center">
                <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-calendar-alt text-2xl text-purple-600"></i>
                </div>
                <h3 class="font-semibold text-gray-800 mb-2">Schedules</h3>
                <p class="text-purple-600 font-medium" id="schedule-count">Loading...</p>
            </div>
            
            <div class="bg-white rounded-lg shadow-md p-6 text-center">
                <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-users text-2xl text-orange-600"></i>
                </div>
                <h3 class="font-semibold text-gray-800 mb-2">Guides</h3>
                <p class="text-orange-600 font-medium" id="guide-count">Loading...</p>
            </div>
        </div>

        <!-- Main Features -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Admin Panel -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-6 text-white">
                    <i class="fas fa-cogs text-3xl mb-3"></i>
                    <h3 class="text-xl font-semibold">Admin Panel</h3>
                    <p class="text-blue-100">Quản lý toàn bộ hệ thống</p>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        <a href="/admin/tour-schedule-management" target="_blank" class="block w-full text-left p-3 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">
                            <i class="fas fa-calendar-alt mr-2 text-blue-600"></i>
                            Quản lý Lịch trình
                        </a>
                        <a href="/admin-system-test" target="_blank" class="block w-full text-left p-3 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">
                            <i class="fas fa-flask mr-2 text-blue-600"></i>
                            System Test
                        </a>
                        <a href="/features-dashboard" target="_blank" class="block w-full text-left p-3 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">
                            <i class="fas fa-rocket mr-2 text-blue-600"></i>
                            Features Dashboard
                        </a>
                    </div>
                </div>
            </div>

            <!-- Customer Interface -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                <div class="bg-gradient-to-r from-green-500 to-green-600 p-6 text-white">
                    <i class="fas fa-eye text-3xl mb-3"></i>
                    <h3 class="text-xl font-semibold">Customer View</h3>
                    <p class="text-green-100">Giao diện khách hàng</p>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        <a href="/customer/tour-schedule?tour_id=14&departure_id=42" target="_blank" class="block w-full text-left p-3 bg-green-50 hover:bg-green-100 rounded-lg transition-colors">
                            <i class="fas fa-map-marked-alt mr-2 text-green-600"></i>
                            Xem Lịch trình Tour
                        </a>
                        <a href="/tour-system-demo" target="_blank" class="block w-full text-left p-3 bg-green-50 hover:bg-green-100 rounded-lg transition-colors">
                            <i class="fas fa-desktop mr-2 text-green-600"></i>
                            Tour System Demo
                        </a>
                        <a href="/guide-backup-demo" target="_blank" class="block w-full text-left p-3 bg-green-50 hover:bg-green-100 rounded-lg transition-colors">
                            <i class="fas fa-user-tie mr-2 text-green-600"></i>
                            Guide Backup Demo
                        </a>
                    </div>
                </div>
            </div>

            <!-- Development Tools -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                <div class="bg-gradient-to-r from-purple-500 to-purple-600 p-6 text-white">
                    <i class="fas fa-tools text-3xl mb-3"></i>
                    <h3 class="text-xl font-semibold">Dev Tools</h3>
                    <p class="text-purple-100">Công cụ phát triển</p>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        <a href="/schedule-test" target="_blank" class="block w-full text-left p-3 bg-purple-50 hover:bg-purple-100 rounded-lg transition-colors">
                            <i class="fas fa-calendar-plus mr-2 text-purple-600"></i>
                            Test Thêm Lịch trình
                        </a>
                        <button onclick="testAllAPIs()" class="block w-full text-left p-3 bg-purple-50 hover:bg-purple-100 rounded-lg transition-colors">
                            <i class="fas fa-plug mr-2 text-purple-600"></i>
                            Test All APIs
                        </button>
                        <button onclick="generateSampleData()" class="block w-full text-left p-3 bg-purple-50 hover:bg-purple-100 rounded-lg transition-colors">
                            <i class="fas fa-database mr-2 text-purple-600"></i>
                            Generate Sample Data
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- API Status -->
        <div class="mt-12 bg-white rounded-lg shadow-md p-6">
            <h3 class="text-xl font-semibold text-gray-800 mb-6">
                <i class="fas fa-plug mr-2 text-gray-600"></i>
                API Status
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="p-4 border border-gray-200 rounded-lg">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-700">Tour Schedules</span>
                        <span class="px-2 py-1 text-xs rounded" id="api-schedules">Testing...</span>
                    </div>
                </div>
                <div class="p-4 border border-gray-200 rounded-lg">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-700">Guides</span>
                        <span class="px-2 py-1 text-xs rounded" id="api-guides">Testing...</span>
                    </div>
                </div>
                <div class="p-4 border border-gray-200 rounded-lg">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-700">Departures</span>
                        <span class="px-2 py-1 text-xs rounded" id="api-departures">Testing...</span>
                    </div>
                </div>
                <div class="p-4 border border-gray-200 rounded-lg">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-700">Notifications</span>
                        <span class="px-2 py-1 text-xs rounded" id="api-notifications">Testing...</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="mt-8 bg-white rounded-lg shadow-md p-6">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">
                <i class="fas fa-clock mr-2 text-gray-600"></i>
                Recent Activity
            </h3>
            <div id="recent-activity" class="space-y-3">
                <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                    <i class="fas fa-info-circle text-blue-500"></i>
                    <span class="text-gray-700">System initialized</span>
                    <span class="text-xs text-gray-500 ml-auto" id="init-time"></span>
                </div>
            </div>
        </div>
    </div>

    <script>
        let activityLog = [];

        // Add activity
        function addActivity(message, type = 'info') {
            const activity = {
                message,
                type,
                timestamp: new Date().toLocaleTimeString('vi-VN')
            };
            activityLog.unshift(activity);
            updateActivityDisplay();
        }

        // Update activity display
        function updateActivityDisplay() {
            const container = document.getElementById('recent-activity');
            let html = '';
            
            activityLog.slice(0, 5).forEach(activity => {
                const iconClass = activity.type === 'success' ? 'fa-check-circle text-green-500' :
                                activity.type === 'error' ? 'fa-times-circle text-red-500' :
                                'fa-info-circle text-blue-500';
                
                html += `
                    <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                        <i class="fas ${iconClass}"></i>
                        <span class="text-gray-700">${activity.message}</span>
                        <span class="text-xs text-gray-500 ml-auto">${activity.timestamp}</span>
                    </div>
                `;
            });
            
            container.innerHTML = html;
        }

        // Test API endpoint
        async function testAPI(endpoint, elementId, name) {
            try {
                const response = await fetch(endpoint);
                const data = await response.json();
                
                if (data.success) {
                    document.getElementById(elementId).textContent = 'Online';
                    document.getElementById(elementId).className = 'px-2 py-1 text-xs rounded bg-green-100 text-green-800';
                    addActivity(`${name} API is working`, 'success');
                    return data;
                } else {
                    throw new Error('API returned error');
                }
            } catch (error) {
                document.getElementById(elementId).textContent = 'Offline';
                document.getElementById(elementId).className = 'px-2 py-1 text-xs rounded bg-red-100 text-red-800';
                addActivity(`${name} API failed`, 'error');
                return null;
            }
        }

        // Test all APIs
        async function testAllAPIs() {
            addActivity('Testing all APIs...', 'info');
            
            const results = await Promise.all([
                testAPI('/api/tours/14/schedules', 'api-schedules', 'Schedules'),
                testAPI('/api/guides/available', 'api-guides', 'Guides'),
                testAPI('/api/departures/42', 'api-departures', 'Departures'),
                testAPI('/api/notifications/recent', 'api-notifications', 'Notifications')
            ]);
            
            const successCount = results.filter(r => r !== null).length;
            addActivity(`API test completed: ${successCount}/4 endpoints working`, successCount === 4 ? 'success' : 'error');
        }

        // Generate sample data
        async function generateSampleData() {
            addActivity('Generating sample data...', 'info');
            
            const sampleSchedule = {
                day_number: 5,
                title: 'Ngày bổ sung - Khám phá thêm',
                description: 'Ngày bổ sung để khám phá thêm các địa điểm thú vị',
                location: 'Sapa - Các điểm tham quan khác',
                activities: 'Tham quan, chụp ảnh, mua sắm',
                meals: 'Sáng, Trưa'
            };
            
            try {
                const response = await fetch('/api/tours/14/schedules', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(sampleSchedule)
                });
                
                const result = await response.json();
                
                if (result.success) {
                    addActivity('Sample schedule created successfully', 'success');
                } else {
                    addActivity('Failed to create sample data: ' + result.message, 'error');
                }
            } catch (error) {
                addActivity('Error creating sample data: ' + error.message, 'error');
            }
        }

        // Initialize dashboard
        document.addEventListener('DOMContentLoaded', async function() {
            document.getElementById('init-time').textContent = new Date().toLocaleTimeString('vi-VN');
            
            // Test APIs on load
            setTimeout(testAllAPIs, 1000);
            
            // Load counts
            try {
                const schedulesData = await fetch('/api/tours/14/schedules').then(r => r.json());
                if (schedulesData.success) {
                    document.getElementById('schedule-count').textContent = schedulesData.data.schedules?.length || 0;
                }
                
                const guidesData = await fetch('/api/guides/available').then(r => r.json());
                if (guidesData.success) {
                    document.getElementById('guide-count').textContent = guidesData.data?.length || 0;
                }
            } catch (error) {
                console.error('Error loading counts:', error);
            }
            
            addActivity('Dashboard loaded successfully', 'success');
        });
    </script>
</body>
</html>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Tính năng đang phát triển - Tour365</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .feature-card {
            transition: all 0.3s ease;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }
        .status-badge {
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .card-hover {
            transition: all 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="gradient-bg text-white shadow-lg">
        <div class="container mx-auto px-4 py-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="bg-white bg-opacity-20 rounded-lg p-3">
                        <i class="fas fa-rocket text-2xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold">Tính năng đang phát triển</h1>
                        <p class="text-blue-100">Hệ thống quản lý lịch trình tour Tour365</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="/" class="bg-white bg-opacity-20 hover:bg-opacity-30 px-4 py-2 rounded-lg transition-colors">
                        <i class="fas fa-home mr-2"></i>Trang chủ
                    </a>
                    <a href="/admin" class="bg-white bg-opacity-20 hover:bg-opacity-30 px-4 py-2 rounded-lg transition-colors">
                        <i class="fas fa-cog mr-2"></i>Admin
                    </a>
                </div>
            </div>
        </div>
    </header>

    <div class="container mx-auto px-4 py-8">
        <!-- Overview Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-md p-6 card-hover">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm">Tính năng hoàn thành</p>
                        <p class="text-3xl font-bold text-green-600">8</p>
                    </div>
                    <div class="bg-green-100 rounded-full p-3">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-md p-6 card-hover">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm">Đang phát triển</p>
                        <p class="text-3xl font-bold text-blue-600">5</p>
                    </div>
                    <div class="bg-blue-100 rounded-full p-3">
                        <i class="fas fa-code text-blue-600 text-xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-md p-6 card-hover">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm">Sắp ra mắt</p>
                        <p class="text-3xl font-bold text-orange-600">3</p>
                    </div>
                    <div class="bg-orange-100 rounded-full p-3">
                        <i class="fas fa-clock text-orange-600 text-xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-md p-6 card-hover">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm">API Endpoints</p>
                        <p class="text-3xl font-bold text-purple-600">12</p>
                    </div>
                    <div class="bg-purple-100 rounded-full p-3">
                        <i class="fas fa-plug text-purple-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Feature Categories -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Core Features -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-star text-yellow-500 mr-2"></i>
                    Tính năng cốt lõi
                </h2>
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-calendar-alt text-green-600"></i>
                            <span class="font-medium">Quản lý lịch trình chi tiết</span>
                        </div>
                        <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-medium">Hoàn thành</span>
                    </div>
                    
                    <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-plane-departure text-green-600"></i>
                            <span class="font-medium">Quản lý khởi hành</span>
                        </div>
                        <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-medium">Hoàn thành</span>
                    </div>
                    
                    <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-user-tie text-green-600"></i>
                            <span class="font-medium">Hướng dẫn viên dự phòng</span>
                        </div>
                        <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-medium">Hoàn thành</span>
                    </div>
                    
                    <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-mobile-alt text-blue-600"></i>
                            <span class="font-medium">Giao diện responsive</span>
                        </div>
                        <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs font-medium status-badge">Đang phát triển</span>
                    </div>
                </div>
            </div>

            <!-- Admin Features -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-cogs text-blue-500 mr-2"></i>
                    Tính năng Admin
                </h2>
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-tachometer-alt text-green-600"></i>
                            <span class="font-medium">Dashboard quản lý</span>
                        </div>
                        <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-medium">Hoàn thành</span>
                    </div>
                    
                    <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-edit text-green-600"></i>
                            <span class="font-medium">CRUD lịch trình</span>
                        </div>
                        <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-medium">Hoàn thành</span>
                    </div>
                    
                    <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-chart-bar text-blue-600"></i>
                            <span class="font-medium">Báo cáo thống kê</span>
                        </div>
                        <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs font-medium status-badge">Đang phát triển</span>
                    </div>
                    
                    <div class="flex items-center justify-between p-3 bg-orange-50 rounded-lg">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-bell text-orange-600"></i>
                            <span class="font-medium">Hệ thống thông báo</span>
                        </div>
                        <span class="bg-orange-100 text-orange-800 px-2 py-1 rounded-full text-xs font-medium">Sắp ra mắt</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Feature Showcase -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-6 flex items-center">
                <i class="fas fa-rocket text-purple-500 mr-2"></i>
                Demo tính năng
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Customer Interface -->
                <div class="feature-card text-white rounded-lg p-6 card-hover">
                    <div class="flex items-center justify-between mb-4">
                        <i class="fas fa-user text-2xl"></i>
                        <span class="bg-white bg-opacity-20 px-2 py-1 rounded-full text-xs">Khách hàng</span>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">Giao diện khách hàng</h3>
                    <p class="text-sm opacity-90 mb-4">Xem lịch trình tour chi tiết với timeline đẹp mắt</p>
                    <a href="/customer/tour-schedule?tour_id=14&departure_id=42" class="bg-white text-purple-600 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-100 transition-colors">
                        <i class="fas fa-eye mr-2"></i>Xem demo
                    </a>
                </div>

                <!-- Admin Interface -->
                <div class="feature-card text-white rounded-lg p-6 card-hover" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                    <div class="flex items-center justify-between mb-4">
                        <i class="fas fa-cog text-2xl"></i>
                        <span class="bg-white bg-opacity-20 px-2 py-1 rounded-full text-xs">Admin</span>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">Quản lý Admin</h3>
                    <p class="text-sm opacity-90 mb-4">Dashboard quản lý lịch trình với đầy đủ tính năng CRUD</p>
                    <a href="/admin/tour-schedule-management" class="bg-white text-pink-600 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-100 transition-colors">
                        <i class="fas fa-cogs mr-2"></i>Vào Admin
                    </a>
                </div>

                <!-- Guide Backup Demo -->
                <div class="feature-card text-white rounded-lg p-6 card-hover" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                    <div class="flex items-center justify-between mb-4">
                        <i class="fas fa-users text-2xl"></i>
                        <span class="bg-white bg-opacity-20 px-2 py-1 rounded-full text-xs">HDV</span>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">HDV dự phòng</h3>
                    <p class="text-sm opacity-90 mb-4">Hệ thống hướng dẫn viên dự phòng thông minh</p>
                    <a href="/guide-backup-demo" class="bg-white text-blue-600 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-100 transition-colors">
                        <i class="fas fa-info-circle mr-2"></i>Tìm hiểu
                    </a>
                </div>

                <!-- System Demo -->
                <div class="feature-card text-white rounded-lg p-6 card-hover" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                    <div class="flex items-center justify-between mb-4">
                        <i class="fas fa-desktop text-2xl"></i>
                        <span class="bg-white bg-opacity-20 px-2 py-1 rounded-full text-xs">Tổng hợp</span>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">Demo tổng hợp</h3>
                    <p class="text-sm opacity-90 mb-4">Showcase tất cả tính năng trong một trang</p>
                    <a href="/tour-system-demo" class="bg-white text-orange-600 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-100 transition-colors">
                        <i class="fas fa-play mr-2"></i>Xem demo
                    </a>
                </div>

                <!-- API Documentation -->
                <div class="feature-card text-white rounded-lg p-6 card-hover" style="background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);">
                    <div class="flex items-center justify-between mb-4">
                        <i class="fas fa-code text-2xl"></i>
                        <span class="bg-white bg-opacity-20 px-2 py-1 rounded-full text-xs">API</span>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">API Endpoints</h3>
                    <p class="text-sm opacity-90 mb-4">RESTful API cho tích hợp và phát triển</p>
                    <button onclick="showApiDocs()" class="bg-white text-teal-600 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-100 transition-colors">
                        <i class="fas fa-book mr-2"></i>Xem API
                    </button>
                </div>

                <!-- Mobile App -->
                <div class="feature-card text-white rounded-lg p-6 card-hover" style="background: linear-gradient(135deg, #d299c2 0%, #fef9d7 100%);">
                    <div class="flex items-center justify-between mb-4">
                        <i class="fas fa-mobile-alt text-2xl"></i>
                        <span class="bg-white bg-opacity-20 px-2 py-1 rounded-full text-xs">Sắp có</span>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">Mobile App</h3>
                    <p class="text-sm opacity-90 mb-4">Ứng dụng di động cho khách hàng và HDV</p>
                    <button class="bg-white text-purple-600 px-4 py-2 rounded-lg text-sm font-medium opacity-75 cursor-not-allowed">
                        <i class="fas fa-clock mr-2"></i>Sắp ra mắt
                    </button>
                </div>
            </div>
        </div>

        <!-- Technical Details -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Database Schema -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-database text-green-500 mr-2"></i>
                    Cấu trúc Database
                </h2>
                <div class="space-y-3">
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <span class="font-mono text-sm">tour_schedules</span>
                        <span class="text-xs text-gray-600">Lịch trình từng ngày</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <span class="font-mono text-sm">tour_departures</span>
                        <span class="text-xs text-gray-600">Thông tin khởi hành</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <span class="font-mono text-sm">users (guides)</span>
                        <span class="text-xs text-gray-600">HDV chính & dự phòng</span>
                    </div>
                </div>
            </div>

            <!-- API Endpoints -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-plug text-blue-500 mr-2"></i>
                    API Endpoints
                </h2>
                <div class="space-y-3">
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <span class="font-mono text-sm">GET /api/tours/{id}/schedules</span>
                        <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">GET</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <span class="font-mono text-sm">POST /api/schedules</span>
                        <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs">POST</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <span class="font-mono text-sm">GET /api/guides/available</span>
                        <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">GET</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Roadmap -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-6 flex items-center">
                <i class="fas fa-road text-purple-500 mr-2"></i>
                Lộ trình phát triển
            </h2>
            
            <div class="relative">
                <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-gray-300"></div>
                
                <div class="space-y-6">
                    <!-- Phase 1 -->
                    <div class="flex items-start space-x-4">
                        <div class="bg-green-500 rounded-full p-2 relative z-10">
                            <i class="fas fa-check text-white text-sm"></i>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-800">Phase 1: Core Features</h3>
                            <p class="text-gray-600 text-sm">Quản lý lịch trình cơ bản, CRUD operations</p>
                            <span class="text-xs text-green-600 font-medium">✅ Hoàn thành</span>
                        </div>
                    </div>
                    
                    <!-- Phase 2 -->
                    <div class="flex items-start space-x-4">
                        <div class="bg-blue-500 rounded-full p-2 relative z-10 status-badge">
                            <i class="fas fa-code text-white text-sm"></i>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-800">Phase 2: Advanced Features</h3>
                            <p class="text-gray-600 text-sm">HDV dự phòng, thông báo real-time, báo cáo</p>
                            <span class="text-xs text-blue-600 font-medium">🔄 Đang phát triển</span>
                        </div>
                    </div>
                    
                    <!-- Phase 3 -->
                    <div class="flex items-start space-x-4">
                        <div class="bg-orange-500 rounded-full p-2 relative z-10">
                            <i class="fas fa-mobile-alt text-white text-sm"></i>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-800">Phase 3: Mobile & Integration</h3>
                            <p class="text-gray-600 text-sm">Mobile app, API mở rộng, tích hợp bên thứ 3</p>
                            <span class="text-xs text-orange-600 font-medium">⏳ Sắp ra mắt</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- API Documentation Modal -->
    <div id="api-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-full max-w-4xl max-h-screen overflow-y-auto m-4">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-semibold">API Documentation</h3>
                <button onclick="closeApiDocs()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <div class="space-y-6">
                <div class="bg-gray-50 rounded-lg p-4">
                    <h4 class="font-semibold mb-2">GET /api/tours/{id}/schedules</h4>
                    <p class="text-sm text-gray-600 mb-2">Lấy lịch trình chi tiết của tour</p>
                    <div class="bg-gray-800 text-green-400 p-3 rounded text-sm font-mono">
                        curl -X GET "http://127.0.0.1:8000/api/tours/14/schedules?departure_id=42"
                    </div>
                </div>
                
                <div class="bg-gray-50 rounded-lg p-4">
                    <h4 class="font-semibold mb-2">GET /api/guides/available</h4>
                    <p class="text-sm text-gray-600 mb-2">Lấy danh sách hướng dẫn viên có sẵn</p>
                    <div class="bg-gray-800 text-green-400 p-3 rounded text-sm font-mono">
                        curl -X GET "http://127.0.0.1:8000/api/guides/available"
                    </div>
                </div>
                
                <div class="bg-gray-50 rounded-lg p-4">
                    <h4 class="font-semibold mb-2">POST /api/schedules</h4>
                    <p class="text-sm text-gray-600 mb-2">Tạo lịch trình mới (Admin only)</p>
                    <div class="bg-gray-800 text-green-400 p-3 rounded text-sm font-mono">
                        curl -X POST "http://127.0.0.1:8000/api/schedules" \<br>
                        -H "Content-Type: application/json" \<br>
                        -d '{"tour_id": 14, "day_number": 1, "title": "Ngày 1"}'
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showApiDocs() {
            document.getElementById('api-modal').classList.remove('hidden');
        }

        function closeApiDocs() {
            document.getElementById('api-modal').classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('api-modal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeApiDocs();
            }
        });

        // Add some interactive animations
        document.addEventListener('DOMContentLoaded', function() {
            // Animate counters
            const counters = document.querySelectorAll('.text-3xl');
            counters.forEach(counter => {
                const target = parseInt(counter.textContent);
                let current = 0;
                const increment = target / 50;
                const timer = setInterval(() => {
                    current += increment;
                    if (current >= target) {
                        counter.textContent = target;
                        clearInterval(timer);
                    } else {
                        counter.textContent = Math.floor(current);
                    }
                }, 30);
            });
        });
    </script>
</body>
</html>
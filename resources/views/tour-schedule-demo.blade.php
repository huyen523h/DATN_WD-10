<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Demo Lịch trình Tour Chi tiết</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
</head>
<body class="bg-gray-100">
    <div id="app">
        <div class="container mx-auto py-8">
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <h1 class="text-3xl font-bold text-center text-gray-800 mb-4">
                    <i class="fas fa-map-marked-alt mr-3 text-blue-600"></i>
                    Demo Lịch trình Tour Chi tiết
                </h1>
                <p class="text-center text-gray-600 mb-6">
                    Hệ thống quản lý lịch trình tour với thông tin chi tiết từng ngày, hướng dẫn viên và thông tin khởi hành
                </p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <h3 class="font-semibold text-blue-800 mb-2">
                            <i class="fas fa-eye mr-2"></i>Xem lịch trình (Khách hàng)
                        </h3>
                        <p class="text-sm text-blue-600 mb-3">Hiển thị lịch trình chi tiết cho khách hàng</p>
                        <button 
                            @click="showCustomerView = true; showAdminView = false"
                            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition-colors"
                        >
                            Xem Demo
                        </button>
                    </div>
                    
                    <div class="bg-green-50 p-4 rounded-lg">
                        <h3 class="font-semibold text-green-800 mb-2">
                            <i class="fas fa-cog mr-2"></i>Quản lý lịch trình (Admin)
                        </h3>
                        <p class="text-sm text-green-600 mb-3">Giao diện quản lý cho admin</p>
                        <button 
                            @click="showAdminView = true; showCustomerView = false"
                            class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition-colors"
                        >
                            Xem Demo
                        </button>
                    </div>
                </div>
            </div>

            <!-- Customer View -->
            <div v-if="showCustomerView" class="mb-6">
                <div class="bg-white rounded-lg shadow-lg p-4 mb-4">
                    <div class="flex justify-between items-center">
                        <h2 class="text-xl font-semibold text-gray-800">
                            <i class="fas fa-user mr-2 text-blue-600"></i>
                            Giao diện khách hàng
                        </h2>
                        <button 
                            @click="showCustomerView = false"
                            class="text-gray-500 hover:text-gray-700"
                        >
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <tour-schedule-detail :tour-id="14" :departure-id="42"></tour-schedule-detail>
            </div>

            <!-- Admin View -->
            <div v-if="showAdminView" class="mb-6">
                <div class="bg-white rounded-lg shadow-lg p-4 mb-4">
                    <div class="flex justify-between items-center">
                        <h2 class="text-xl font-semibold text-gray-800">
                            <i class="fas fa-user-shield mr-2 text-green-600"></i>
                            Giao diện quản trị
                        </h2>
                        <button 
                            @click="showAdminView = false"
                            class="text-gray-500 hover:text-gray-700"
                        >
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <tour-schedule-manager :tour-id="14"></tour-schedule-manager>
            </div>

            <!-- Features List -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">
                    <i class="fas fa-star mr-2 text-yellow-500"></i>
                    Tính năng đã bổ sung
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700 mb-3">
                            <i class="fas fa-calendar-day mr-2 text-blue-500"></i>
                            Lịch trình từng ngày
                        </h3>
                        <ul class="space-y-2 text-sm text-gray-600">
                            <li><i class="fas fa-check text-green-500 mr-2"></i>Mô tả chi tiết từng ngày</li>
                            <li><i class="fas fa-check text-green-500 mr-2"></i>Địa điểm và giờ hoạt động</li>
                            <li><i class="fas fa-check text-green-500 mr-2"></i>Điểm tập trung</li>
                            <li><i class="fas fa-check text-green-500 mr-2"></i>Hoạt động và bữa ăn</li>
                            <li><i class="fas fa-check text-green-500 mr-2"></i>Nơi nghỉ và phương tiện</li>
                            <li><i class="fas fa-check text-green-500 mr-2"></i>Ghi chú và hình ảnh</li>
                        </ul>
                    </div>
                    
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700 mb-3">
                            <i class="fas fa-user-tie mr-2 text-green-500"></i>
                            Thông tin khởi hành
                        </h3>
                        <ul class="space-y-2 text-sm text-gray-600">
                            <li><i class="fas fa-check text-green-500 mr-2"></i>Giờ khởi hành cụ thể</li>
                            <li><i class="fas fa-check text-green-500 mr-2"></i>Địa điểm khởi hành</li>
                            <li><i class="fas fa-check text-green-500 mr-2"></i>Hướng dẫn viên phụ trách</li>
                            <li><i class="fas fa-check text-green-500 mr-2"></i>HDV dự phòng</li>
                            <li><i class="fas fa-check text-green-500 mr-2"></i>Liên hệ khẩn cấp</li>
                            <li><i class="fas fa-check text-green-500 mr-2"></i>Ghi chú đặc biệt</li>
                        </ul>
                    </div>
                </div>

                <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                    <h4 class="font-semibold text-gray-700 mb-2">
                        <i class="fas fa-database mr-2 text-purple-500"></i>
                        Cấu trúc Database
                    </h4>
                    <p class="text-sm text-gray-600">
                        Đã bổ sung các trường mới vào bảng <code class="bg-gray-200 px-1 rounded">tour_schedules</code> 
                        và <code class="bg-gray-200 px-1 rounded">tour_departures</code> để lưu trữ thông tin chi tiết.
                        Tạo API endpoints và Vue components để quản lý và hiển thị dữ liệu.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        const { createApp } = Vue;

        // Import components (trong thực tế sẽ được build bởi Vite/Webpack)
        const TourScheduleDetail = {
            template: `<div class="text-center p-8 bg-blue-50 rounded-lg">
                <i class="fas fa-spinner fa-spin text-2xl text-blue-600 mb-4"></i>
                <p class="text-blue-700">Component TourScheduleDetail sẽ được load ở đây</p>
                <p class="text-sm text-blue-600 mt-2">API: GET /api/tours/{{ tourId }}/schedules?departure_id={{ departureId }}</p>
            </div>`,
            props: ['tourId', 'departureId']
        };

        const TourScheduleManager = {
            template: `<div class="text-center p-8 bg-green-50 rounded-lg">
                <i class="fas fa-cogs text-2xl text-green-600 mb-4"></i>
                <p class="text-green-700">Component TourScheduleManager sẽ được load ở đây</p>
                <p class="text-sm text-green-600 mt-2">Quản lý lịch trình và thông tin khởi hành</p>
            </div>`,
            props: ['tourId']
        };

        createApp({
            components: {
                TourScheduleDetail,
                TourScheduleManager
            },
            data() {
                return {
                    showCustomerView: false,
                    showAdminView: false
                }
            }
        }).mount('#app');
    </script>
</body>
</html>
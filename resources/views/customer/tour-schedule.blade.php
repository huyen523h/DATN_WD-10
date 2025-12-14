<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Lịch trình Tour - {{ $tour->title ?? 'Tour' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b">
        <div class="container mx-auto px-4 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <a href="#" onclick="goBackToTour()" class="text-blue-600 hover:text-blue-800">
                        <i class="fas fa-arrow-left mr-2"></i>Quay lại trang tour
                    </a>
                    <h1 class="text-xl font-semibold text-gray-800">Lịch trình Tour</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="/tours" class="text-gray-600 hover:text-gray-800 text-sm">
                        <i class="fas fa-list mr-2"></i>Danh sách tours
                    </a>
                    <button onclick="window.print()" class="text-gray-600 hover:text-gray-800">
                        <i class="fas fa-print mr-2"></i>In lịch trình
                    </button>
                    <button onclick="shareSchedule()" class="text-blue-600 hover:text-blue-800">
                        <i class="fas fa-share mr-2"></i>Chia sẻ
                    </button>
                </div>
            </div>
        </div>
    </header>

    <div class="container mx-auto px-4 py-8">
        <!-- Breadcrumb -->
        <nav class="flex mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="/" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                        <i class="fas fa-home mr-2"></i>
                        Trang chủ
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                        <a href="/tours" class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2">Tours</a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                        <a href="#" onclick="goBackToTour()" class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2" id="breadcrumb-tour">Chi tiết tour</a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                        <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Lịch trình</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Tour Header -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white rounded-lg p-6 mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div>
                    <h2 class="text-2xl md:text-3xl font-bold mb-2" id="tour-title">Đang tải...</h2>
                    <div class="flex flex-wrap gap-4 text-sm opacity-90">
                        <div id="departure-info">
                            <i class="fas fa-calendar-alt mr-2"></i>
                            <span>Đang tải thông tin...</span>
                        </div>
                    </div>
                </div>
                <div class="mt-4 md:mt-0 flex flex-col items-center space-y-4">
                    <div class="bg-white bg-opacity-20 rounded-lg p-4 text-center">
                        <div class="text-sm opacity-90">Giá từ</div>
                        <div class="text-2xl font-bold" id="tour-price">---</div>
                        <div class="text-xs opacity-75">VNĐ/người</div>
                    </div>
                    <button onclick="bookTour()" class="bg-white text-blue-600 px-6 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors shadow-md">
                        <i class="fas fa-calendar-plus mr-2"></i>Đặt tour ngay
                    </button>
                </div>
            </div>
        </div>

        <!-- Departure Information -->
        <div id="departure-section" class="bg-white rounded-lg shadow-md p-6 mb-8 hidden">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">
                <i class="fas fa-info-circle mr-2 text-blue-600"></i>
                Thông tin khởi hành
            </h3>
            <div id="departure-content"></div>
        </div>

        <!-- Guide Information -->
        <div id="guide-section" class="bg-white rounded-lg shadow-md p-6 mb-8 hidden">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">
                <i class="fas fa-user-tie mr-2 text-green-600"></i>
                Thông tin hướng dẫn viên
            </h3>
            <div id="guide-content"></div>
        </div>

        <!-- Schedule Timeline -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-xl font-semibold text-gray-800 mb-6">
                <i class="fas fa-route mr-2 text-purple-600"></i>
                Lịch trình chi tiết
            </h3>
            <div id="schedule-timeline" class="space-y-6">
                <div class="text-center py-8">
                    <i class="fas fa-spinner fa-spin text-3xl text-blue-600 mb-4"></i>
                    <p class="text-gray-600">Đang tải lịch trình...</p>
                </div>
            </div>
        </div>

        <!-- Important Notes -->
        <div class="bg-yellow-50 border-l-4 border-yellow-400 rounded-lg p-6 mt-8">
            <h4 class="font-semibold text-yellow-800 mb-3">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                Lưu ý quan trọng
            </h4>
            <ul class="text-yellow-700 space-y-2 text-sm">
                <li><i class="fas fa-check mr-2"></i>Vui lòng có mặt tại điểm tập trung trước 30 phút</li>
                <li><i class="fas fa-check mr-2"></i>Mang theo CMND/CCCD và các giấy tờ cần thiết</li>
                <li><i class="fas fa-check mr-2"></i>Liên hệ hướng dẫn viên nếu có thắc mắc</li>
                <li><i class="fas fa-check mr-2"></i>Lịch trình có thể thay đổi tùy điều kiện thời tiết</li>
            </ul>
        </div>
    </div>

    <script>
        // Get tour ID from URL or set default
        const urlParams = new URLSearchParams(window.location.search);
        const tourId = urlParams.get('tour_id') || '14';
        const departureId = urlParams.get('departure_id') || '42';

        // Load tour schedule on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadTourSchedule();
        });

        // Global variable to store tour data
        let tourData = null;

        async function loadTourSchedule() {
            try {
                const response = await fetch(`/api/tours/${tourId}/schedules?departure_id=${departureId}`);
                const data = await response.json();

                if (data.success) {
                    // Store data globally for use in other functions
                    window.tourData = data.data;
                    tourData = data.data;
                    
                    displayTourInfo(data.data);
                    displayDepartureInfo(data.data.departure);
                    displayGuideInfo(data.data.departure);
                    displaySchedule(data.data.schedules);
                } else {
                    showError('Không thể tải thông tin tour');
                }
            } catch (error) {
                showError('Lỗi kết nối: ' + error.message);
            }
        }

        function displayTourInfo(data) {
            const tour = data.tour;
            document.getElementById('tour-title').textContent = tour.title;
            
            // Update breadcrumb
            const breadcrumbTour = document.getElementById('breadcrumb-tour');
            if (breadcrumbTour) {
                breadcrumbTour.textContent = tour.title;
            }
            
            if (tour.price) {
                document.getElementById('tour-price').textContent = 
                    new Intl.NumberFormat('vi-VN').format(tour.price);
            }

            if (data.departure) {
                const departureInfo = document.getElementById('departure-info');
                departureInfo.innerHTML = `
                    <i class="fas fa-calendar-alt mr-2"></i>
                    <span>${formatDate(data.departure.departure_date)}</span>
                    <span class="mx-2">•</span>
                    <i class="fas fa-clock mr-2"></i>
                    <span>${data.departure.departure_time || 'Chưa xác định'}</span>
                `;
            }
        }

        function displayDepartureInfo(departure) {
            if (!departure) return;

            const section = document.getElementById('departure-section');
            const content = document.getElementById('departure-content');

            let html = `
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div class="flex items-start space-x-3">
                            <i class="fas fa-calendar-alt text-blue-600 mt-1"></i>
                            <div>
                                <div class="font-medium text-gray-800">Ngày khởi hành</div>
                                <div class="text-gray-600">${formatDate(departure.departure_date)}</div>
                            </div>
                        </div>
                        
                        <div class="flex items-start space-x-3">
                            <i class="fas fa-clock text-blue-600 mt-1"></i>
                            <div>
                                <div class="font-medium text-gray-800">Giờ khởi hành</div>
                                <div class="text-gray-600">${departure.departure_time || 'Chưa xác định'}</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="space-y-4">
                        <div class="flex items-start space-x-3">
                            <i class="fas fa-map-marker-alt text-red-600 mt-1"></i>
                            <div>
                                <div class="font-medium text-gray-800">Điểm tập trung</div>
                                <div class="text-gray-600">${departure.departure_location || 'Chưa xác định'}</div>
                            </div>
                        </div>
                        
                        <div class="flex items-start space-x-3">
                            <i class="fas fa-users text-green-600 mt-1"></i>
                            <div>
                                <div class="font-medium text-gray-800">Số chỗ</div>
                                <div class="text-gray-600">${departure.seats_available}/${departure.seats_total} chỗ trống</div>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            if (departure.departure_instructions) {
                html += `
                    <div class="mt-6 p-4 bg-blue-50 rounded-lg border-l-4 border-blue-400">
                        <h5 class="font-medium text-blue-800 mb-2">Hướng dẫn tập trung</h5>
                        <p class="text-blue-700 text-sm">${departure.departure_instructions}</p>
                    </div>
                `;
            }

            content.innerHTML = html;
            section.classList.remove('hidden');
        }

        function displayGuideInfo(departure) {
            if (!departure || (!departure.guide && !departure.backup_guide)) return;

            const section = document.getElementById('guide-section');
            const content = document.getElementById('guide-content');

            let html = '<div class="grid grid-cols-1 md:grid-cols-2 gap-6">';

            // Hướng dẫn viên chính
            if (departure.guide) {
                html += `
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <h4 class="font-semibold text-green-800 mb-3 flex items-center">
                            <i class="fas fa-user-tie mr-2"></i>
                            Hướng dẫn viên chính
                        </h4>
                        <div class="space-y-2">
                            <div class="font-medium text-green-900">${departure.guide.name}</div>
                            <div class="text-sm text-green-700 flex items-center">
                                <i class="fas fa-phone mr-2"></i>
                                <a href="tel:${departure.guide.phone}" class="hover:underline">
                                    ${departure.guide.phone}
                                </a>
                            </div>
                            <div class="text-sm text-green-700 flex items-center">
                                <i class="fas fa-envelope mr-2"></i>
                                <a href="mailto:${departure.guide.email}" class="hover:underline">
                                    ${departure.guide.email}
                                </a>
                            </div>
                        </div>
                    </div>
                `;
            }

            // Hướng dẫn viên dự phòng
            if (departure.backup_guide) {
                html += `
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <h4 class="font-semibold text-blue-800 mb-3 flex items-center">
                            <i class="fas fa-user-shield mr-2"></i>
                            Hướng dẫn viên dự phòng
                        </h4>
                        <div class="space-y-2">
                            <div class="font-medium text-blue-900">${departure.backup_guide.name}</div>
                            <div class="text-sm text-blue-700 flex items-center">
                                <i class="fas fa-phone mr-2"></i>
                                <a href="tel:${departure.backup_guide.phone}" class="hover:underline">
                                    ${departure.backup_guide.phone}
                                </a>
                            </div>
                            <div class="text-sm text-blue-700 flex items-center">
                                <i class="fas fa-envelope mr-2"></i>
                                <a href="mailto:${departure.backup_guide.email}" class="hover:underline">
                                    ${departure.backup_guide.email}
                                </a>
                            </div>
                        </div>
                    </div>
                `;
            }

            html += '</div>';

            // Thông tin khẩn cấp
            if (departure.emergency_contact) {
                html += `
                    <div class="mt-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                        <h5 class="font-semibold text-red-800 mb-2 flex items-center">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            Liên hệ khẩn cấp
                        </h5>
                        <p class="text-red-700">
                            ${departure.emergency_contact}: 
                            <a href="tel:${departure.emergency_phone}" class="font-medium hover:underline">
                                ${departure.emergency_phone}
                            </a>
                        </p>
                    </div>
                `;
            }

            content.innerHTML = html;
            section.classList.remove('hidden');
        }

        function displaySchedule(schedules) {
            const timeline = document.getElementById('schedule-timeline');

            if (!schedules || schedules.length === 0) {
                timeline.innerHTML = '<p class="text-gray-500 text-center py-8">Chưa có lịch trình chi tiết</p>';
                return;
            }

            // Get departure date from global data
            const departureDate = window.tourData?.departure?.departure_date ? new Date(window.tourData.departure.departure_date) : null;

            let html = '';
            schedules.forEach((schedule, index) => {
                const isLast = index === schedules.length - 1;
                
                // Calculate actual date for this day
                let actualDateStr = '';
                let dayOfWeek = '';
                if (departureDate) {
                    const actualDate = new Date(departureDate);
                    actualDate.setDate(actualDate.getDate() + (schedule.day_number - 1));
                    actualDateStr = actualDate.toLocaleDateString('vi-VN');
                    dayOfWeek = actualDate.toLocaleDateString('vi-VN', { weekday: 'long' });
                }
                
                html += `
                    <div class="relative">
                        <!-- Timeline line -->
                        ${!isLast ? '<div class="absolute left-6 top-16 w-0.5 h-full bg-gray-300"></div>' : ''}
                        
                        <div class="flex items-start space-x-4">
                            <!-- Day number circle -->
                            <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 text-white rounded-full flex items-center justify-center font-bold text-lg shadow-lg">
                                ${schedule.day_number}
                            </div>
                            
                            <!-- Content -->
                            <div class="flex-1 bg-white border border-gray-200 rounded-lg p-6 shadow-sm hover:shadow-md transition-shadow">
                                <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4">
                                    <div>
                                        <h4 class="text-lg font-semibold text-gray-800 mb-1">
                                            ${schedule.title}
                                        </h4>
                                        ${actualDateStr ? `
                                            <div class="text-sm text-blue-600 font-medium">
                                                <i class="fas fa-calendar mr-1"></i>
                                                ${actualDateStr} (${dayOfWeek})
                                            </div>
                                        ` : ''}
                                    </div>
                                    ${schedule.start_time || schedule.end_time ? `
                                        <div class="text-sm text-gray-600 flex items-center">
                                            <i class="fas fa-clock mr-2"></i>
                                            ${schedule.start_time || ''} ${schedule.end_time ? '- ' + schedule.end_time : ''}
                                        </div>
                                    ` : ''}
                                </div>

                                ${schedule.location ? `
                                    <div class="mb-3 flex items-center text-gray-600">
                                        <i class="fas fa-map-marker-alt mr-2 text-red-500"></i>
                                        <span class="font-medium">${schedule.location}</span>
                                    </div>
                                ` : ''}

                                ${schedule.description ? `
                                    <p class="text-gray-700 mb-4">${schedule.description}</p>
                                ` : ''}

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                    ${schedule.activities ? `
                                        <div class="flex items-start space-x-2">
                                            <i class="fas fa-hiking text-green-500 mt-0.5"></i>
                                            <div>
                                                <div class="font-medium text-gray-700">Hoạt động</div>
                                                <div class="text-gray-600">${schedule.activities}</div>
                                            </div>
                                        </div>
                                    ` : ''}

                                    ${schedule.meals ? `
                                        <div class="flex items-start space-x-2">
                                            <i class="fas fa-utensils text-orange-500 mt-0.5"></i>
                                            <div>
                                                <div class="font-medium text-gray-700">Bữa ăn</div>
                                                <div class="text-gray-600">${schedule.meals}</div>
                                            </div>
                                        </div>
                                    ` : ''}

                                    ${schedule.accommodation ? `
                                        <div class="flex items-start space-x-2">
                                            <i class="fas fa-bed text-indigo-500 mt-0.5"></i>
                                            <div>
                                                <div class="font-medium text-gray-700">Nơi nghỉ</div>
                                                <div class="text-gray-600">${schedule.accommodation}</div>
                                            </div>
                                        </div>
                                    ` : ''}

                                    ${schedule.transportation ? `
                                        <div class="flex items-start space-x-2">
                                            <i class="fas fa-bus text-purple-500 mt-0.5"></i>
                                            <div>
                                                <div class="font-medium text-gray-700">Phương tiện</div>
                                                <div class="text-gray-600">${schedule.transportation}</div>
                                            </div>
                                        </div>
                                    ` : ''}
                                </div>

                                ${schedule.notes ? `
                                    <div class="mt-4 p-3 bg-yellow-50 border-l-4 border-yellow-400 rounded">
                                        <div class="flex items-start space-x-2">
                                            <i class="fas fa-sticky-note text-yellow-500 mt-0.5"></i>
                                            <div>
                                                <div class="font-medium text-yellow-800">Ghi chú</div>
                                                <div class="text-yellow-700 text-sm">${schedule.notes}</div>
                                            </div>
                                        </div>
                                    </div>
                                ` : ''}
                            </div>
                        </div>
                    </div>
                `;
            });

            timeline.innerHTML = html;
        }

        function formatDate(dateString) {
            if (!dateString) return 'Chưa xác định';
            const date = new Date(dateString);
            return date.toLocaleDateString('vi-VN', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
        }

        function showError(message) {
            const timeline = document.getElementById('schedule-timeline');
            timeline.innerHTML = `
                <div class="text-center py-8">
                    <i class="fas fa-exclamation-triangle text-3xl text-red-500 mb-4"></i>
                    <p class="text-red-600">${message}</p>
                </div>
            `;
        }

        function goBackToTour() {
            // Quay lại trang chi tiết tour
            const tourId = urlParams.get('tour_id') || '14';
            window.location.href = `/tours/${tourId}`;
        }

        function bookTour() {
            // Chuyển đến trang đặt tour
            const tourId = urlParams.get('tour_id') || '14';
            window.location.href = `/bookings/create?tour_id=${tourId}`;
        }

        function shareSchedule() {
            if (navigator.share) {
                navigator.share({
                    title: document.getElementById('tour-title').textContent,
                    text: 'Xem lịch trình tour chi tiết',
                    url: window.location.href
                });
            } else {
                // Fallback: copy to clipboard
                navigator.clipboard.writeText(window.location.href).then(() => {
                    alert('Đã copy link lịch trình vào clipboard!');
                });
            }
        }
    </script>

    <style>
        @media print {
            header, .no-print {
                display: none !important;
            }
            body {
                background: white !important;
            }
            .shadow-md, .shadow-sm {
                box-shadow: none !important;
                border: 1px solid #e5e7eb !important;
            }
        }
    </style>
</body>
</html>
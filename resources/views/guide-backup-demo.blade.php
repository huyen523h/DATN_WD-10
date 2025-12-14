<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Demo Hướng dẫn viên dự phòng</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto py-8">
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <h1 class="text-3xl font-bold text-center text-gray-800 mb-4">
                <i class="fas fa-user-friends mr-3 text-blue-600"></i>
                Demo Hệ thống Hướng dẫn viên dự phòng
            </h1>
            <p class="text-center text-gray-600 mb-6">
                Hệ thống quản lý hướng dẫn viên chính và hướng dẫn viên dự phòng cho mỗi chuyến tour
            </p>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <button 
                    onclick="loadGuideInfo()"
                    class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors"
                >
                    <i class="fas fa-users mr-2"></i>Xem thông tin HDV
                </button>
                
                <button 
                    onclick="loadAllGuides()"
                    class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition-colors"
                >
                    <i class="fas fa-list mr-2"></i>Danh sách tất cả HDV
                </button>
            </div>
        </div>

        <!-- Guide Information Display -->
        <div id="guide-display" class="bg-white rounded-lg shadow-lg p-6 hidden">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">
                <i class="fas fa-user-tie mr-2 text-blue-600"></i>
                Thông tin Hướng dẫn viên Tour
            </h2>
            <div id="guide-content"></div>
        </div>

        <!-- All Guides Display -->
        <div id="all-guides-display" class="bg-white rounded-lg shadow-lg p-6 hidden">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">
                <i class="fas fa-users mr-2 text-green-600"></i>
                Danh sách Hướng dẫn viên có sẵn
            </h2>
            <div id="all-guides-content"></div>
        </div>

        <!-- Features Info -->
        <div class="bg-white rounded-lg shadow-lg p-6 mt-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">
                <i class="fas fa-star mr-2 text-yellow-500"></i>
                Tính năng Hướng dẫn viên dự phòng
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-700 mb-3">
                        <i class="fas fa-shield-alt mr-2 text-blue-500"></i>
                        Lợi ích
                    </h3>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li><i class="fas fa-check text-green-500 mr-2"></i>Đảm bảo tour luôn có HDV</li>
                        <li><i class="fas fa-check text-green-500 mr-2"></i>Phòng trường hợp HDV chính bận đột xuất</li>
                        <li><i class="fas fa-check text-green-500 mr-2"></i>Tăng độ tin cậy với khách hàng</li>
                        <li><i class="fas fa-check text-green-500 mr-2"></i>Quản lý rủi ro tốt hơn</li>
                        <li><i class="fas fa-check text-green-500 mr-2"></i>Linh hoạt trong điều phối nhân sự</li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold text-gray-700 mb-3">
                        <i class="fas fa-cogs mr-2 text-green-500"></i>
                        Cách hoạt động
                    </h3>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li><i class="fas fa-arrow-right text-blue-500 mr-2"></i>Mỗi tour có 1 HDV chính</li>
                        <li><i class="fas fa-arrow-right text-blue-500 mr-2"></i>Có thể gán thêm 1 HDV dự phòng</li>
                        <li><i class="fas fa-arrow-right text-blue-500 mr-2"></i>HDV dự phòng sẵn sàng thay thế</li>
                        <li><i class="fas fa-arrow-right text-blue-500 mr-2"></i>Thông tin liên hệ đầy đủ</li>
                        <li><i class="fas fa-arrow-right text-blue-500 mr-2"></i>Quản lý qua API và giao diện</li>
                    </ul>
                </div>
            </div>

            <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                <h4 class="font-semibold text-blue-800 mb-2">
                    <i class="fas fa-database mr-2"></i>
                    Cấu trúc Database
                </h4>
                <div class="text-sm text-blue-700">
                    <p class="mb-2"><strong>Bảng tour_departures:</strong></p>
                    <ul class="list-disc list-inside space-y-1 ml-4">
                        <li><code class="bg-blue-100 px-1 rounded">guide_id</code> - ID hướng dẫn viên chính</li>
                        <li><code class="bg-blue-100 px-1 rounded">backup_guide_id</code> - ID hướng dẫn viên dự phòng</li>
                        <li><code class="bg-blue-100 px-1 rounded">emergency_contact</code> - Liên hệ khẩn cấp</li>
                        <li><code class="bg-blue-100 px-1 rounded">emergency_phone</code> - SĐT khẩn cấp</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script>
        async function loadGuideInfo() {
            const guideDisplay = document.getElementById('guide-display');
            const guideContent = document.getElementById('guide-content');
            
            try {
                guideContent.innerHTML = '<div class="text-center py-4"><i class="fas fa-spinner fa-spin text-2xl text-blue-600"></i><p class="mt-2">Đang tải...</p></div>';
                guideDisplay.classList.remove('hidden');
                
                const response = await fetch('/api/tours/14/schedules?departure_id=42');
                const data = await response.json();
                
                if (data.success && data.data.departure) {
                    displayGuideInfo(data.data.departure);
                } else {
                    guideContent.innerHTML = '<p class="text-red-600">Không thể tải thông tin hướng dẫn viên</p>';
                }
            } catch (error) {
                guideContent.innerHTML = '<p class="text-red-600">Lỗi: ' + error.message + '</p>';
            }
        }

        async function loadAllGuides() {
            const allGuidesDisplay = document.getElementById('all-guides-display');
            const allGuidesContent = document.getElementById('all-guides-content');
            
            try {
                allGuidesContent.innerHTML = '<div class="text-center py-4"><i class="fas fa-spinner fa-spin text-2xl text-green-600"></i><p class="mt-2">Đang tải...</p></div>';
                allGuidesDisplay.classList.remove('hidden');
                
                const response = await fetch('/api/guides/available');
                const data = await response.json();
                
                if (data.success) {
                    displayAllGuides(data.data);
                } else {
                    allGuidesContent.innerHTML = '<p class="text-red-600">Không thể tải danh sách hướng dẫn viên</p>';
                }
            } catch (error) {
                allGuidesContent.innerHTML = '<p class="text-red-600">Lỗi: ' + error.message + '</p>';
            }
        }

        function displayGuideInfo(departure) {
            const guideContent = document.getElementById('guide-content');
            
            let html = `
                <div class="bg-gradient-to-r from-blue-50 to-green-50 p-6 rounded-lg mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">
                        Tour: ${departure.tour_id} | Ngày khởi hành: ${departure.departure_date}
                    </h3>
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            `;

            // Hướng dẫn viên chính
            if (departure.guide) {
                html += `
                    <div class="guide-primary">
                        <div class="bg-blue-100 border-l-4 border-blue-500 p-4 rounded-lg">
                            <h4 class="font-semibold text-blue-800 mb-3 flex items-center">
                                <i class="fas fa-user-tie mr-2"></i>
                                Hướng dẫn viên chính
                                <span class="ml-2 px-2 py-1 bg-blue-500 text-white text-xs rounded">CHÍNH</span>
                            </h4>
                            <div class="space-y-2">
                                <p class="font-medium text-blue-900">${departure.guide.name}</p>
                                <p class="text-sm text-blue-700">
                                    <i class="fas fa-envelope mr-2"></i>${departure.guide.email}
                                </p>
                                <p class="text-sm text-blue-700">
                                    <i class="fas fa-phone mr-2"></i>${departure.guide.phone}
                                </p>
                                <p class="text-xs text-blue-600">ID: ${departure.guide.id}</p>
                            </div>
                        </div>
                    </div>
                `;
            } else {
                html += `
                    <div class="guide-primary">
                        <div class="bg-gray-100 border-l-4 border-gray-400 p-4 rounded-lg">
                            <h4 class="font-semibold text-gray-600 mb-2">
                                <i class="fas fa-user-tie mr-2"></i>
                                Hướng dẫn viên chính
                            </h4>
                            <p class="text-gray-500">Chưa được gán</p>
                        </div>
                    </div>
                `;
            }

            // Hướng dẫn viên dự phòng
            if (departure.backup_guide) {
                html += `
                    <div class="guide-backup">
                        <div class="bg-green-100 border-l-4 border-green-500 p-4 rounded-lg">
                            <h4 class="font-semibold text-green-800 mb-3 flex items-center">
                                <i class="fas fa-user-shield mr-2"></i>
                                Hướng dẫn viên dự phòng
                                <span class="ml-2 px-2 py-1 bg-green-500 text-white text-xs rounded">DỰ PHÒNG</span>
                            </h4>
                            <div class="space-y-2">
                                <p class="font-medium text-green-900">${departure.backup_guide.name}</p>
                                <p class="text-sm text-green-700">
                                    <i class="fas fa-envelope mr-2"></i>${departure.backup_guide.email}
                                </p>
                                <p class="text-sm text-green-700">
                                    <i class="fas fa-phone mr-2"></i>${departure.backup_guide.phone}
                                </p>
                                <p class="text-xs text-green-600">ID: ${departure.backup_guide.id}</p>
                            </div>
                        </div>
                    </div>
                `;
            } else {
                html += `
                    <div class="guide-backup">
                        <div class="bg-gray-100 border-l-4 border-gray-400 p-4 rounded-lg">
                            <h4 class="font-semibold text-gray-600 mb-2">
                                <i class="fas fa-user-shield mr-2"></i>
                                Hướng dẫn viên dự phòng
                            </h4>
                            <p class="text-gray-500">Chưa được gán</p>
                        </div>
                    </div>
                `;
            }

            html += `
                    </div>
                </div>
            `;

            // Thông tin khẩn cấp
            if (departure.emergency_contact) {
                html += `
                    <div class="emergency-info bg-red-50 border-l-4 border-red-400 p-4 rounded-lg">
                        <h4 class="font-semibold text-red-800 mb-2">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            Thông tin khẩn cấp
                        </h4>
                        <p class="text-red-700">${departure.emergency_contact}: ${departure.emergency_phone}</p>
                    </div>
                `;
            }

            guideContent.innerHTML = html;
        }

        function displayAllGuides(guides) {
            const allGuidesContent = document.getElementById('all-guides-content');
            
            if (!guides || guides.length === 0) {
                allGuidesContent.innerHTML = '<p class="text-gray-500">Không có hướng dẫn viên nào.</p>';
                return;
            }

            let html = `
                <div class="mb-4 p-4 bg-green-50 rounded-lg">
                    <p class="text-green-800">
                        <i class="fas fa-info-circle mr-2"></i>
                        Tổng cộng có <strong>${guides.length}</strong> hướng dẫn viên có sẵn trong hệ thống
                    </p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            `;
            
            guides.forEach((guide, index) => {
                html += `
                    <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="font-semibold text-gray-800">${guide.name}</h4>
                            <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded">
                                #${guide.id}
                            </span>
                        </div>
                        <div class="space-y-2 text-sm text-gray-600">
                            <p><i class="fas fa-envelope mr-2 text-blue-500"></i>${guide.email}</p>
                            <p><i class="fas fa-phone mr-2 text-green-500"></i>${guide.phone || 'Chưa có SĐT'}</p>
                        </div>
                        <div class="mt-3 pt-3 border-t border-gray-100">
                            <span class="inline-block px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded">
                                Có thể làm HDV chính hoặc dự phòng
                            </span>
                        </div>
                    </div>
                `;
            });
            
            html += '</div>';
            
            allGuidesContent.innerHTML = html;
        }
    </script>
</body>
</html>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Test Tables - Fixed Layout</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{ asset('css/admin-tables-fixed.css') }}" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="h3 mb-0 text-gray-800">
                    <i class="fas fa-table me-2 text-primary"></i>
                    Test Bảng đã sửa lệch cột
                </h1>
                <p class="text-muted">Kiểm tra giao diện các bảng sau khi sửa lỗi lệch cột</p>
            </div>
        </div>

        <!-- Departures Table -->
        <div class="row mb-5">
            <div class="col-12">
                <h4 class="mb-3">1. Bảng Departures (Khởi hành)</h4>
                @include('admin.components.departures-table')
            </div>
        </div>

        <!-- Schedules Table -->
        <div class="row mb-5">
            <div class="col-12">
                <h4 class="mb-3">2. Bảng Schedules (Lịch trình)</h4>
                @include('admin.components.schedules-table')
            </div>
        </div>

        <!-- Guides Table -->
        <div class="row mb-5">
            <div class="col-12">
                <h4 class="mb-3">3. Bảng Guides (Hướng dẫn viên)</h4>
                @include('admin.components.guides-table')
            </div>
        </div>

        <!-- Test Controls -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-cogs me-2"></i>
                            Test Controls
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <button onclick="loadSampleDepartures()" class="btn btn-primary w-100">
                                    <i class="fas fa-plane-departure me-2"></i>
                                    Load Sample Departures
                                </button>
                            </div>
                            <div class="col-md-4">
                                <button onclick="loadSampleSchedules()" class="btn btn-success w-100">
                                    <i class="fas fa-calendar-alt me-2"></i>
                                    Load Sample Schedules
                                </button>
                            </div>
                            <div class="col-md-4">
                                <button onclick="loadSampleGuides()" class="btn btn-purple w-100">
                                    <i class="fas fa-users me-2"></i>
                                    Load Sample Guides
                                </button>
                            </div>
                        </div>
                        <div class="row g-3 mt-2">
                            <div class="col-md-6">
                                <button onclick="clearAllTables()" class="btn btn-warning w-100">
                                    <i class="fas fa-eraser me-2"></i>
                                    Clear All Tables
                                </button>
                            </div>
                            <div class="col-md-6">
                                <button onclick="testResponsive()" class="btn btn-info w-100">
                                    <i class="fas fa-mobile-alt me-2"></i>
                                    Test Responsive
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Sample data
        const sampleDepartures = [
            {
                id: 1,
                departure_date: '2024-12-25',
                departure_time: '08:00',
                departure_location: 'Văn phòng công ty - 123 Đường Láng, Ba Đình, Hà Nội',
                guide_name: 'Nguyễn Văn Hùng',
                guide_phone: '0123456789',
                backup_guide_name: 'Trần Thị Mai',
                backup_guide_phone: '0987654321',
                preparation_status: 'ready'
            },
            {
                id: 2,
                departure_date: '2024-12-28',
                departure_time: '07:30',
                departure_location: 'Bến xe Mỹ Đình - Hà Nội',
                guide_name: 'Lê Văn Nam',
                guide_phone: '0111222333',
                backup_guide_name: null,
                backup_guide_phone: null,
                preparation_status: 'pending'
            },
            {
                id: 3,
                departure_date: '2025-01-02',
                departure_time: '09:00',
                departure_location: 'Sân bay Nội Bài - Terminal 1',
                guide_name: 'Phạm Thị Lan',
                guide_phone: '0444555666',
                backup_guide_name: 'Hoàng Văn Đức',
                backup_guide_phone: '0777888999',
                preparation_status: 'confirmed'
            }
        ];

        const sampleSchedules = [
            {
                id: 1,
                day_number: 1,
                title: 'Hà Nội - Sapa',
                location: 'Hà Nội - Lào Cai - Sapa',
                description: 'Khởi hành từ Hà Nội, di chuyển bằng xe khách đến Sapa. Nhận phòng khách sạn và nghỉ ngơi.',
                activities: 'Di chuyển, nhận phòng',
                meals: 'Trưa, Tối'
            },
            {
                id: 2,
                day_number: 2,
                title: 'Khám phá bản Cát Cát',
                location: 'Bản Cát Cát - Sapa',
                description: 'Tham quan bản Cát Cát, tìm hiểu văn hóa dân tộc H\'Mông, chụp ảnh ruộng bậc thang.',
                activities: 'Tham quan, chụp ảnh, mua sắm',
                meals: 'Sáng, Trưa, Tối'
            },
            {
                id: 3,
                day_number: 3,
                title: 'Fansipan - Về Hà Nội',
                location: 'Đỉnh Fansipan - Hà Nội',
                description: 'Chinh phục đỉnh Fansipan bằng cáp treo, ngắm cảnh từ nóc nhà Đông Dương. Trở về Hà Nội.',
                activities: 'Leo núi, ngắm cảnh, di chuyển',
                meals: 'Sáng, Trưa'
            }
        ];

        const sampleGuides = [
            {
                id: 1,
                name: 'Nguyễn Văn Hùng',
                full_name: 'Nguyễn Văn Hùng',
                code: 'HDV001',
                email: 'hung.nguyen@tour365.vn',
                phone: '0123456789',
                experience_years: 5,
                rating_average: 4.8,
                rating_count: 124,
                user_id: 1,
                avatar_url: null
            },
            {
                id: 2,
                name: 'Trần Thị Mai',
                full_name: 'Trần Thị Mai',
                code: 'HDV002',
                email: 'mai.tran@tour365.vn',
                phone: '0987654321',
                experience_years: 3,
                rating_average: 4.6,
                rating_count: 89,
                user_id: 2,
                avatar_url: null
            },
            {
                id: 3,
                name: 'Lê Văn Nam',
                full_name: 'Lê Văn Nam',
                code: 'HDV003',
                email: 'nam.le@tour365.vn',
                phone: '0111222333',
                experience_years: 7,
                rating_average: 4.9,
                rating_count: 156,
                user_id: 3,
                avatar_url: null
            },
            {
                id: 4,
                name: 'Phạm Thị Lan',
                full_name: 'Phạm Thị Lan',
                code: null,
                email: 'lan.pham@tour365.vn',
                phone: null,
                experience_years: 2,
                rating_average: 4.3,
                rating_count: 45,
                user_id: null,
                avatar_url: null
            }
        ];

        // Load sample data functions
        function loadSampleDepartures() {
            renderDeparturesTable(sampleDepartures);
            showNotification('Đã tải dữ liệu mẫu cho bảng Departures', 'success');
        }

        function loadSampleSchedules() {
            renderSchedulesTable(sampleSchedules);
            window.currentTourSchedules = sampleSchedules; // Store for view mode toggle
            showNotification('Đã tải dữ liệu mẫu cho bảng Schedules', 'success');
        }

        function loadSampleGuides() {
            renderGuidesTable(sampleGuides);
            showNotification('Đã tải dữ liệu mẫu cho bảng Guides', 'success');
        }

        function clearAllTables() {
            // Clear departures
            document.getElementById('departures-table-body').innerHTML = `
                <tr>
                    <td colspan="7" class="text-center py-4 text-muted">
                        <i class="fas fa-table fa-2x mb-2 d-block"></i>
                        Bảng đã được xóa
                    </td>
                </tr>
            `;
            
            // Clear schedules
            document.getElementById('schedules-table-body').innerHTML = `
                <tr>
                    <td colspan="6" class="text-center py-4 text-muted">
                        <i class="fas fa-table fa-2x mb-2 d-block"></i>
                        Bảng đã được xóa
                    </td>
                </tr>
            `;
            
            // Clear guides
            document.getElementById('guides-table-body').innerHTML = `
                <tr>
                    <td colspan="7" class="text-center py-4 text-muted">
                        <i class="fas fa-table fa-2x mb-2 d-block"></i>
                        Bảng đã được xóa
                    </td>
                </tr>
            `;
            
            showNotification('Đã xóa tất cả dữ liệu trong bảng', 'info');
        }

        function testResponsive() {
            const currentWidth = window.innerWidth;
            let message = `Độ rộng hiện tại: ${currentWidth}px - `;
            
            if (currentWidth >= 1200) {
                message += 'Desktop (XL)';
            } else if (currentWidth >= 992) {
                message += 'Desktop (LG)';
            } else if (currentWidth >= 768) {
                message += 'Tablet (MD)';
            } else if (currentWidth >= 576) {
                message += 'Mobile (SM)';
            } else {
                message += 'Mobile (XS)';
            }
            
            showNotification(message, 'info');
        }

        // Auto load sample data on page load
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(() => {
                loadSampleDepartures();
            }, 500);
            
            setTimeout(() => {
                loadSampleSchedules();
            }, 1000);
            
            setTimeout(() => {
                loadSampleGuides();
            }, 1500);
        });

        // Notification function
        function showNotification(message, type = 'info') {
            const alertClass = type === 'error' ? 'danger' : type;
            const notification = document.createElement('div');
            notification.className = `alert alert-${alertClass} alert-dismissible fade show position-fixed`;
            notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
            
            notification.innerHTML = `
                <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'} me-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 4000);
        }
    </script>
</body>
</html>
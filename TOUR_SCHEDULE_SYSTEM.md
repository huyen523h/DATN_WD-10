# Hệ thống Quản lý Lịch trình Tour

## 🎯 Tổng quan
Hệ thống quản lý lịch trình tour hoàn chỉnh với các tính năng:
- ✅ Quản lý lịch trình chi tiết từng ngày
- ✅ Phân công hướng dẫn viên chính và dự phòng  
- ✅ Quản lý thông tin khởi hành
- ✅ Hệ thống thông báo real-time
- ✅ Giao diện admin và khách hàng

## 🚀 Truy cập nhanh

### 📊 Dashboard chính
```
http://127.0.0.1:8000/system-dashboard
```

### 👨‍💼 Admin Panel
```
http://127.0.0.1:8000/admin/tour-schedule-management
```

### 👥 Giao diện khách hàng
```
http://127.0.0.1:8000/customer/tour-schedule?tour_id=14&departure_id=42
```

### 🧪 Test Tools
```
http://127.0.0.1:8000/schedule-test
http://127.0.0.1:8000/admin-system-test
```

## 📋 Tính năng chính

### 1. Quản lý Lịch trình (Admin)
- **Thêm lịch trình mới**: Modal form với validation đầy đủ
- **Chỉnh sửa lịch trình**: Cập nhật thông tin từng ngày
- **Xóa lịch trình**: Xóa với xác nhận
- **Xem tổng quan**: Stats và thống kê

### 2. Quản lý Departure
- **Chỉnh sửa thông tin khởi hành**: Modal với form đầy đủ
- **Phân công HDV**: Gán HDV chính và dự phòng
- **Cập nhật trạng thái**: Pending, Ready, Confirmed, etc.
- **Lưu nháp**: Lưu thay đổi tạm thời

### 3. Hệ thống Thông báo
- **Real-time notifications**: Cập nhật tự động
- **Toast notifications**: Thông báo popup
- **Notification panel**: Panel quản lý thông báo
- **Multiple types**: Success, Warning, Error, Info, Guide, Departure

### 4. Giao diện Khách hàng
- **Timeline lịch trình**: Hiển thị đẹp mắt theo ngày
- **Thông tin HDV**: Hiển thị HDV chính và dự phòng
- **Thông tin khởi hành**: Chi tiết về departure
- **Responsive design**: Tương thích mobile

## 🔧 API Endpoints

### Tour Schedules
```
GET    /api/tours/{tourId}/schedules           # Lấy lịch trình
POST   /api/tours/{tourId}/schedules           # Tạo lịch trình mới
PUT    /api/tours/{tourId}/schedules/{id}      # Cập nhật lịch trình
DELETE /api/tours/{tourId}/schedules/{id}      # Xóa lịch trình
```

### Departures
```
GET    /api/departures/{id}                    # Lấy thông tin departure
PUT    /api/departures/{id}                    # Cập nhật departure
PUT    /api/departures/{id}/draft              # Lưu nháp departure
```

### Guides
```
GET    /api/guides/available                   # Danh sách HDV có sẵn
```

### Notifications
```
GET    /api/notifications/recent               # Thông báo gần đây
GET    /api/notifications/new                  # Thông báo mới
POST   /api/notifications/mark-all-read       # Đánh dấu đã đọc
```

## 💾 Database Schema

### tour_schedules
```sql
- id (primary key)
- tour_id (foreign key)
- day_number (integer)
- title (string)
- description (text)
- location (string)
- start_time (time)
- end_time (time)
- meeting_point (string)
- activities (text)
- meals (string)
- accommodation (string)
- transportation (string)
- notes (text)
- images (json)
- created_at, updated_at
```

### tour_departures
```sql
- id (primary key)
- tour_id (foreign key)
- departure_date (date)
- departure_time (time)
- departure_location (string)
- departure_instructions (text)
- guide_id (foreign key to users)
- backup_guide_id (foreign key to users)
- emergency_contact (string)
- emergency_phone (string)
- special_notes (text)
- preparation_status (enum)
- seats_total (integer)
- created_at, updated_at
```

## 🎨 Components

### Vue Components
- `TourScheduleDetail.vue`: Component hiển thị lịch trình
- `TourScheduleManager.vue`: Component quản lý admin

### Blade Templates
- `admin/tour-schedule-management.blade.php`: Trang quản lý admin
- `admin/departure-edit-modal.blade.php`: Modal chỉnh sửa departure
- `admin/notification-system.blade.php`: Hệ thống thông báo
- `customer/tour-schedule.blade.php`: Giao diện khách hàng

## 🧪 Testing

### 1. Test thêm lịch trình
```
Truy cập: /schedule-test
- Điền form thông tin lịch trình
- Click "Lưu lịch trình"
- Kiểm tra kết quả trong Test Results
```

### 2. Test Admin Panel
```
Truy cập: /admin/tour-schedule-management
- Nhập Tour ID: 14, Departure ID: 42
- Click "Tải dữ liệu"
- Test các tab: Tổng quan, Lịch trình, Khởi hành, HDV
```

### 3. Test API
```
Truy cập: /admin-system-test
- Click "Run All Tests"
- Kiểm tra kết quả từng API
```

## 📱 Responsive Design
- ✅ Desktop: Full features
- ✅ Tablet: Optimized layout
- ✅ Mobile: Touch-friendly interface

## 🔐 Security Features
- CSRF Protection
- Input validation
- SQL injection prevention
- XSS protection

## 🚀 Performance
- Lazy loading
- Optimized queries
- Caching ready
- Minimal JavaScript

## 📈 Future Enhancements
- [ ] Email notifications
- [ ] SMS integration
- [ ] Advanced reporting
- [ ] Multi-language support
- [ ] Mobile app API
- [ ] Real-time chat
- [ ] GPS tracking
- [ ] Weather integration

## 🐛 Troubleshooting

### Lỗi thường gặp:

1. **API không hoạt động**
   - Kiểm tra server Laravel đang chạy
   - Kiểm tra database connection
   - Xem log trong `storage/logs/laravel.log`

2. **Modal không mở**
   - Kiểm tra JavaScript console
   - Đảm bảo CSRF token đúng
   - Kiểm tra function được định nghĩa

3. **Thông báo không hiển thị**
   - Kiểm tra notification system đã load
   - Xem browser console errors
   - Kiểm tra CSS conflicts

### Debug Commands:
```bash
# Xem routes
php artisan route:list | grep schedule

# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Check logs
tail -f storage/logs/laravel.log
```

## 👥 Team
- **Backend**: Laravel 10, MySQL
- **Frontend**: Tailwind CSS, Vanilla JS
- **Icons**: Font Awesome 6
- **Notifications**: Custom system

---

**Phiên bản**: 1.0.0  
**Cập nhật**: December 2025  
**Status**: ✅ Production Ready
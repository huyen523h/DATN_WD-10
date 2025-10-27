# Hệ thống Quản lý Check-in/Check-out

## Tổng quan
Hệ thống quản lý check-in/check-out cho phép theo dõi và quản lý việc khách hàng tham gia và kết thúc tour du lịch.

## Tính năng chính

### 1. Quản lý Check-in/Check-out
- **Tạo mới**: Thêm check-in/check-out cho khách hàng
- **Xem danh sách**: Hiển thị tất cả check-in/check-out với bộ lọc
- **Chi tiết**: Xem thông tin chi tiết của từng check-in/check-out
- **Chỉnh sửa**: Cập nhật thông tin check-in/check-out
- **Xóa**: Xóa check-in/check-out không cần thiết

### 2. Xác nhận và Hủy
- **Xác nhận**: Admin có thể xác nhận check-in/check-out
- **Hủy**: Hủy check-in/check-out nếu cần thiết

### 3. Thống kê và Báo cáo
- **Thống kê tổng quan**: Số liệu check-in/check-out theo ngày, tuần, tháng
- **Biểu đồ**: Biểu đồ trực quan hóa dữ liệu
- **Báo cáo chi tiết**: Bảng chi tiết theo ngày

### 4. Tích hợp GPS
- **Vị trí GPS**: Lưu trữ tọa độ GPS của check-in/check-out
- **Bản đồ**: Hiển thị vị trí trên bản đồ Google Maps
- **Lấy vị trí tự động**: Sử dụng GPS của thiết bị

## Cấu trúc Database

### Bảng `check_in_outs`
```sql
- id (Primary Key)
- user_id (Foreign Key -> users.id)
- booking_id (Foreign Key -> bookings.id)
- type (check_in/check_out)
- check_time (datetime)
- location (string)
- latitude (decimal)
- longitude (decimal)
- notes (text)
- status (pending/confirmed/cancelled)
- verified_by (string)
- verified_at (datetime)
- metadata (json)
- created_at, updated_at
```

## API Endpoints

### Web Routes (Admin)
```
GET    /admin/check-in-out                    # Danh sách
GET    /admin/check-in-out/create             # Form tạo mới
POST   /admin/check-in-out                    # Tạo mới
GET    /admin/check-in-out/{id}               # Chi tiết
GET    /admin/check-in-out/{id}/edit          # Form chỉnh sửa
PUT    /admin/check-in-out/{id}               # Cập nhật
DELETE /admin/check-in-out/{id}               # Xóa
POST   /admin/check-in-out/{id}/confirm       # Xác nhận
POST   /admin/check-in-out/{id}/cancel        # Hủy
GET    /admin/check-in-out-statistics         # API thống kê
GET    /admin/check-in-out-statistics-page    # Trang thống kê
```

### API Routes (Mobile)
```
POST   /api/check-in-out                      # Check-in/out từ mobile
GET    /api/check-in-out/history              # Lịch sử check-in/out
```

## Cách sử dụng

### 1. Truy cập Admin Panel
- Đăng nhập với tài khoản admin
- Vào menu "Check-in/out" trong sidebar

### 2. Tạo Check-in/Check-out mới
1. Click nút "Thêm mới"
2. Chọn khách hàng và booking
3. Chọn loại (check-in hoặc check-out)
4. Nhập thời gian và địa điểm
5. Thêm tọa độ GPS (tùy chọn)
6. Nhập ghi chú (tùy chọn)
7. Click "Tạo mới"

### 3. Quản lý Check-in/Check-out
- **Xem danh sách**: Sử dụng bộ lọc để tìm kiếm
- **Xem chi tiết**: Click vào icon "Xem"
- **Chỉnh sửa**: Click vào icon "Chỉnh sửa"
- **Xác nhận/Hủy**: Click vào các nút tương ứng
- **Xóa**: Click vào icon "Xóa"

### 4. Xem thống kê
1. Vào trang thống kê
2. Chọn khoảng thời gian
3. Click "Tải dữ liệu"
4. Xem biểu đồ và bảng chi tiết

## Tích hợp Mobile App

### Check-in/Check-out từ Mobile
```javascript
// POST /api/check-in-out
{
    "booking_id": 1,
    "type": "check_in",
    "latitude": 10.8231,
    "longitude": 106.6297,
    "location": "Địa điểm check-in",
    "notes": "Ghi chú"
}
```

### Lấy lịch sử Check-in/Check-out
```javascript
// GET /api/check-in-out/history
// Trả về danh sách check-in/check-out của user hiện tại
```

## Cấu hình

### 1. Google Maps API
Để sử dụng tính năng bản đồ, cần cấu hình Google Maps API key:
```javascript
// Trong các view có bản đồ
<script async defer src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&callback=initMap"></script>
```

### 2. Permissions
Đảm bảo user có role admin để truy cập các chức năng quản lý.

## Troubleshooting

### 1. Lỗi 500 khi tạo check-in/check-out
- Kiểm tra xem có user và booking với ID tương ứng không
- Kiểm tra validation rules trong controller

### 2. Bản đồ không hiển thị
- Kiểm tra Google Maps API key
- Kiểm tra console browser để xem lỗi JavaScript

### 3. Không thể xác nhận check-in/check-out
- Kiểm tra quyền admin của user
- Kiểm tra CSRF token

## Mở rộng

### 1. Thêm tính năng mới
- Thêm trường mới vào migration
- Cập nhật model và controller
- Cập nhật views tương ứng

### 2. Tích hợp thông báo
- Gửi email/SMS khi check-in/check-out
- Push notification cho mobile app

### 3. Báo cáo nâng cao
- Export Excel/PDF
- Báo cáo theo tour, khách hàng
- Dashboard real-time

## Liên hệ
Nếu có vấn đề hoặc cần hỗ trợ, vui lòng liên hệ team phát triển.

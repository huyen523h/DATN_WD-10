# Hướng dẫn thêm dữ liệu check-in/check-out mẫu

## Tổng quan
Đã tạo 2 file để thêm 4 bản ghi check-in/check-out mẫu vào database:

## Các lỗi đã khắc phục
✅ **CheckInOutSeeder.php**: Đã sửa metadata không cần json_encode() vì model đã cast thành array  
✅ **CheckInOutController.php**: Đã thay match() thành switch-case để tương thích PHP 8.0+  
✅ **Syntax errors**: Đã kiểm tra và đảm bảo không còn lỗi syntax  

## Lưu ý quan trọng
⚠️ Dự án yêu cầu PHP >= 8.2 (theo conventional.json), nhưng hiện tại đang dùng PHP 8.0  
⚠️ Khuyến nghị: Nếu gặp lỗi khi chạy seeder, hãy dùng **Cách 1** (SQL script) thay vì seeder

## Cách 1: Chạy SQL Script (Khuyến nghị)

### Bước 1: Kiểm tra database connection
Đảm bảo file `.env` có cấu hình database đúng:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=<tên_database>
DB_USERNAME=root
DB_PASSWORD=<mật_khẩu>
```

### Bước 2: Chạy SQL script
Có 2 cách để chạy:

#### Option A: Qua MySQL Command Line
```bash
mysql -u root -p <database_name> < database/seed_checkin_checkout.sql
```

#### Option B: Qua phpMyAdmin hoặc MySQL Workbench
1. Mở phpMyAdmin hoặc MySQL Workbench
2. Chọn database của bạn
3. Click tab "SQL"
4. Copy nội dung file `database/seed_checkin_checkout.sql`
5. Paste vào và click "Go"

## Cách 2: Sử dụng Laravel Seeder

### Bước 1: Kiểm tra PHP version
```bash
php -v
```

Dự án yêu cầu PHP >= 8.2. Nếu bạn đang dùng PHP < 8.2, vui lòng:
- **Cách A**: Nâng cấp PHP lên 8.2+
- **Cách B**: Sử dụng **Cách 1** (chạy SQL script) thay vì seeder

### Bước 2: Chạy seeder
```bash
php artisan db:seed --class=CheckInOutSeeder
```

**Lưu ý**: 
- Seeder đã được cập nhật để tương thích với PHP 8.0+ (đã thay match() thành switch-case)
- Nếu gặp lỗi với PHP 8.0, vui lòng dùng **Cách 1** (SQL script) thay thế

## Kiểm tra dữ liệu đã thêm

Sau khi chạy script, kiểm tra bằng SQL:
```sql
SELECT * FROM check_in_outs ORDER BY check_time DESC;
```

Hoặc qua Laravel Tinker (nếu PHP >= 8.1):
```bash
php artisan tinker
>>> App\Models\CheckInOut::count()
```

## Dữ liệu mẫu đã tạo

4 bản ghi check-in/check-out bao gồm:

1. **Check-in 1**: Đã xác nhận (2 ngày trước)
   - Địa điểm: Bến xe Miền Tây, Q. Bình Tân, TP.HCM
   - Trạng thái: confirmed
   
2. **Check-out 1**: Đã xác nhận (1 ngày trước)
   - Địa điểm: Khách sạn Pullman Saigon, Q. 1, TP.HCM
   - Trạng thái: confirmed

3. **Check-in 2**: Chờ xác nhận (12 giờ trước)
   - Địa điểm: Sân bay Nội Bài, Hà Ninstructionsội
   - Trạng thái: pending

4. **Check-out 2**: Chờ xác nhận (3 giờ trước)
   - Địa điểm: Bãi biển Mỹ Khê, Đà Nẵng
   - Trạng thái: pending

## Lưu ý

- Đảm bảo đã có dữ liệu users và bookings trong database trước
- Nếu không có user hoặc booking, script SQL sẽ sử dụng giá trị mặc định (user_id = 3, 4; booking_id = 1, 2)
- Mỗi lần chạy sẽ thêm 4 bản ghi mới (không xóa dữ liệu cũ)

## Xem dữ liệu trong Admin Panel

Sau khi seed dữ liệu, truy cập:
```
http://your-domain.com/admin/check-in-out
```

Để xem danh sách các bản ghi check-in/check-out vừa thêm.


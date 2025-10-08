# User Management System - CRUD Admin

Hệ thống quản lý người dùng với đầy đủ chức năng CRUD cho Admin quản lý Customer và Staff.

## Tính năng

### 1. Quản lý User
- **Tạo mới user**: Admin có thể tạo user mới với các role khác nhau
- **Xem danh sách user**: Hiển thị tất cả users với phân trang và tìm kiếm
- **Chỉnh sửa user**: Cập nhật thông tin user
- **Xóa user**: Xóa user khỏi hệ thống
- **Kích hoạt/Vô hiệu hóa**: Toggle trạng thái active của user

### 2. Phân quyền Role
- **Admin**: Toàn quyền quản lý hệ thống
- **Staff**: Quyền hạn chế, có thể quản lý customer
- **Customer**: Quyền cơ bản, chỉ xem thông tin cá nhân

### 3. Tìm kiếm và Lọc
- Tìm kiếm theo tên hoặc email
- Lọc theo role (admin, staff, customer)
- Lọc theo trạng thái (active, inactive)

## Cấu trúc Database

### Bảng Users
```sql
- id (bigint, primary key)
- name (varchar)
- email (varchar, unique)
- password (varchar, hashed)
- role (enum: admin, staff, customer)
- phone (varchar, nullable)
- address (text, nullable)
- is_active (boolean, default: true)
- created_at (timestamp)
- updated_at (timestamp)
```

## Cài đặt và Sử dụng

### 1. Chạy Migration
```bash
php artisan migrate
```

### 2. Tạo dữ liệu mẫu
```bash
php artisan db:seed --class=AdminUserSeeder
```

### 3. Truy cập hệ thống
- **URL chính**: `http://your-domain/admin`
- **Danh sách users**: `http://your-domain/admin/users`

### 4. Tài khoản mặc định
- **Admin**: admin@example.com / password
- **Staff**: staff@example.com / password  
- **Customer**: customer@example.com / password

## Routes

```php
// Admin User Management
GET    /admin/users              - Danh sách users
GET    /admin/users/create       - Form tạo user mới
POST   /admin/users              - Lưu user mới
GET    /admin/users/{user}       - Xem chi tiết user
GET    /admin/users/{user}/edit  - Form chỉnh sửa user
PUT    /admin/users/{user}       - Cập nhật user
DELETE /admin/users/{user}       - Xóa user
POST   /admin/users/{user}/toggle-status - Toggle trạng thái user
```

## Files đã tạo

### Controllers
- `app/Http/Controllers/UserController.php`

### Models
- `app/Models/User.php` (đã cập nhật)

### Requests
- `app/Http/Requests/StoreUserRequest.php`
- `app/Http/Requests/UpdateUserRequest.php`

### Views
- `resources/views/admin/users/index.blade.php`
- `resources/views/admin/users/create.blade.php`
- `resources/views/admin/users/edit.blade.php`
- `resources/views/admin/users/show.blade.php`

### Migrations
- `database/migrations/2025_10_08_055045_check_and_add_user_columns.php`

### Seeders
- `database/seeders/AdminUserSeeder.php`

## Tính năng bảo mật

1. **Validation**: Tất cả input đều được validate
2. **Password hashing**: Mật khẩu được hash bằng bcrypt
3. **CSRF Protection**: Tất cả form đều có CSRF token
4. **Authorization**: Kiểm tra quyền trước khi thực hiện action
5. **Self-protection**: Admin không thể xóa hoặc vô hiệu hóa chính mình

## Giao diện

- **Responsive Design**: Tương thích với mọi thiết bị
- **Bootstrap 5**: Sử dụng Bootstrap cho UI
- **Font Awesome**: Icons đẹp mắt
- **User-friendly**: Giao diện thân thiện, dễ sử dụng

## Mở rộng

Hệ thống có thể dễ dàng mở rộng thêm:
- Authentication/Authorization middleware
- Role-based permissions
- User activity logs
- Email notifications
- Bulk operations
- Export/Import users

## Lưu ý

- Đảm bảo chạy migration trước khi sử dụng
- Tạo ít nhất 1 admin user để có thể đăng nhập
- Backup database trước khi thực hiện thay đổi lớn
- Test kỹ các chức năng trước khi deploy production

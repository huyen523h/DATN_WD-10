# Sửa lỗi Route [admin.tours] not defined

## Lỗi gốc
```
Symfony\Component\Routing\Exception\RouteNotFoundException
Route [admin.tours] not defined.
```

## Nguyên nhân
- File `resources/views/admin/dashboard.blade.php` line 371 sử dụng route name không đúng
- Code gốc: `route('admin.tours')`
- Route thực tế trong `routes/web.php`: `admin.tours.index` (line 389)

## Giải pháp

### Đã sửa:
```php
// ❌ Sai
route('admin.tours')

// ✅ Đúng
route('admin.tours.index')
```

### Chi tiết thay đổi:
- **File**: `resources/views/admin/dashboard.blade.php`
- **Line**: 371
- **Thay đổi**: Đổi từ `admin.tours` sang `admin.tours.index`

## Kiểm tra các routes khác
Các routes khác trong dashboard đã đúng:
- `admin.bookings` ✅ (line 410 trong web.php)
- `admin.customers` ✅ (line 416 trong web.php)
- `admin.banners` ✅ (line 496 trong web.php synonym)
- `admin.reports` ✅ (line 463 trong web.php)

## Kết quả
✅ Lỗi đã được khắc phục
✅ Không còn lỗi linter
✅ Các route khác hoạt động bình thường

## Test
Refresh lại trang admin dashboard để xác nhận không còn lỗi.


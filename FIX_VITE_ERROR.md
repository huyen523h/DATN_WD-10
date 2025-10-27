# Hướng dẫn sửa lỗi ViteManifestNotFoundException

## Lỗi gặp phải
```
Illuminate \ Foundation \ ViteManifestNotFoundException
Vite manifest not found at: G:\laragon\www\DATN_WD-10-dev-v2\public\build/manifest.json
```

## Nguyên nhân
Lỗi này xảy ra khi:
1. Vite chưa được build để tạo file `manifest.json`
2. Dependencies chưa được cài đặt
3. File `manifest.json` bị thiếu hoặc không đúng vị trí

## Cách sửa lỗi

### Bước 1: Cài đặt Dependencies
```bash
npm install
```

### Bước 2: Build Vite Assets
```bash
npm run build
```

### Bước 3: Kiểm tra file manifest.json
Sau khi build, kiểm tra xem file `public/build/manifest.json` đã được tạo chưa:
```
public/build/
├── manifest.json
└── assets/
    ├── app-xxxxx.css
    └── app-xxxxx.js
```

### Bước 4: Chạy Development Server (Tùy chọn)
Nếu muốn chạy trong môi trường development:
```bash
npm run dev
```

## Kiểm tra sau khi sửa

### 1. Kiểm tra file manifest.json
Truy cập: `http://localhost:8000/build/manifest.json`
- Nếu trả về JSON, Vite đã hoạt động đúng
- Nếu trả về 404, cần build lại

### 2. Kiểm tra trang admin
Truy cập: `http://localhost:8000/admin/check-in-out`
- Nếu trang load bình thường, lỗi đã được sửa
- Nếu vẫn có lỗi ViteManifestNotFoundException, kiểm tra lại bước 2

### 3. Sử dụng file test
Chạy file `test_admin_page.php` để kiểm tra tự động:
```bash
php test_admin_page.php
```

## Lưu ý quan trọng

### 1. Node.js Version
Vite yêu cầu Node.js version 20.19+ hoặc 22.12+
- Kiểm tra version: `node -v`
- Nếu version thấp hơn, cần cập nhật Node.js

### 2. Production vs Development
- **Production**: Sử dụng `npm run build` để tạo assets tĩnh
- **Development**: Sử dụng `npm run dev` để chạy server phát triển

### 3. Fallback CSS
Trong file `resources/views/admin/layout.blade.php` đã có fallback CSS:
```php
@vite(['resources/css/app.css', 'resources/js/app.js'])
<!-- Fallback CSS nếu cần -->
<link href="{{ asset('css/admin.css') }}" rel="stylesheet">
```

## Troubleshooting

### Lỗi "vite is not recognized"
```bash
# Cài đặt lại dependencies
npm install

# Hoặc cài đặt global
npm install -g vite
```

### Lỗi "Node.js version not supported"
```bash
# Cập nhật Node.js lên version mới nhất
# Hoặc sử dụng nvm để quản lý version
nvm install 20.19.0
nvm use 20.19.0
```

### Lỗi "Permission denied"
```bash
# Chạy với quyền admin (Windows)
# Hoặc sử dụng sudo (Linux/Mac)
sudo npm run build
```

## Kết quả mong đợi
Sau khi sửa lỗi:
- ✅ Trang admin load bình thường
- ✅ Không có lỗi ViteManifestNotFoundException
- ✅ CSS và JS assets load đúng
- ✅ Chức năng check-in/check-out hoạt động

## Liên hệ hỗ trợ
Nếu vẫn gặp lỗi sau khi làm theo hướng dẫn, vui lòng:
1. Chạy file test và gửi kết quả
2. Gửi log lỗi chi tiết
3. Kiểm tra version Node.js và npm

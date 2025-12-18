# Hướng dẫn sửa lỗi VNPay với Localhost

## Vấn đề
VNPay không thể callback về localhost (`127.0.0.1` hoặc `localhost`), điều này có thể gây ra lỗi "Sai chữ ký" (code 70).

## Giải pháp: Sử dụng Ngrok

### Bước 1: Cài đặt Ngrok
1. Tải Ngrok từ: https://ngrok.com/download
2. Giải nén và đặt vào thư mục dễ truy cập

### Bước 2: Chạy Ngrok
Mở terminal/command prompt và chạy:
```bash
ngrok http 8000
```

### Bước 3: Lấy URL public
Ngrok sẽ hiển thị URL dạng:
```
Forwarding: https://abc123.ngrok.io -> http://localhost:8000
```

### Bước 4: Cập nhật .env
Thêm hoặc cập nhật trong file `.env`:
```
VNP_RETURN_URL=https://abc123.ngrok.io/payment/vnpay_return
```

### Bước 5: Clear cache và thử lại
```bash
php artisan config:clear
php artisan route:clear
```

## Lưu ý
- URL ngrok sẽ thay đổi mỗi lần chạy (trừ khi dùng tài khoản ngrok có domain cố định)
- Đảm bảo ngrok đang chạy khi test thanh toán
- VNPay sandbox có thể cần vài phút để nhận diện URL mới

## Kiểm tra
Sau khi cập nhật, kiểm tra log để đảm bảo Return URL không còn là localhost:
```
VNPay Return URL: https://abc123.ngrok.io/payment/vnpay_return
```


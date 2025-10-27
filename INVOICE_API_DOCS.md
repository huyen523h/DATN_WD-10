# 📄 Invoice API Documentation

## Tổng quan
API Invoice cung cấp các chức năng quản lý hóa đơn cho hệ thống Tour365, bao gồm tạo, xem, tải xuống và in hóa đơn PDF.

## 🔐 Authentication
Tất cả các endpoint đều yêu cầu authentication với Laravel Sanctum token.

```bash
Authorization: Bearer {your_token}
```

## 📋 Endpoints

### 1. Lấy danh sách hóa đơn
```http
GET /api/invoices
```

**Query Parameters:**
- `status` (optional): Lọc theo trạng thái (issued, paid, cancelled)
- `date_from` (optional): Lọc từ ngày (YYYY-MM-DD)
- `date_to` (optional): Lọc đến ngày (YYYY-MM-DD)
- `search` (optional): Tìm kiếm theo số hóa đơn, tên tour, tên khách hàng
- `per_page` (optional): Số lượng kết quả mỗi trang (default: 15)

**Response:**
```json
{
  "success": true,
  "data": {
    "current_page": 1,
    "data": [
      {
        "id": 1,
        "booking_id": 1,
        "invoice_number": "INV20250120001",
        "issue_date": "2025-01-20T10:30:00.000000Z",
        "amount": "2500000.00",
        "status": "issued",
        "booking": {
          "id": 1,
          "tour": {
            "title": "Tour Hà Nội - Sapa",
            "location": "Hà Nội, Sapa"
          },
          "user": {
            "name": "Nguyễn Văn A",
            "email": "nguyenvana@example.com"
          }
        }
      }
    ],
    "total": 10
  }
}
```

### 2. Lấy hóa đơn theo booking ID
```http
GET /api/invoices/booking/{bookingId}
```

**Response:**
```json
{
  "success": true,
  "data": {
    "invoice": {
      "id": 1,
      "booking_id": 1,
      "invoice_number": "INV20250120001",
      "issue_date": "2025-01-20T10:30:00.000000Z",
      "amount": "2500000.00",
      "status": "issued"
    },
    "booking": {
      "id": 1,
      "adults": 2,
      "children": 1,
      "infants": 0,
      "total_amount": "2500000.00"
    },
    "tour": {
      "title": "Tour Hà Nội - Sapa",
      "location": "Hà Nội, Sapa",
      "duration": "3 ngày 2 đêm",
      "price": "1000000.00"
    },
    "user": {
      "name": "Nguyễn Văn A",
      "email": "nguyenvana@example.com",
      "phone": "0901234567"
    },
    "departure": {
      "departure_date": "2025-02-01",
      "meeting_point": "Ga Hà Nội",
      "meeting_time": "07:00"
    }
  }
}
```

### 3. Tạo hóa đơn (Admin/Staff only)
```http
POST /api/invoices
```

**Request Body:**
```json
{
  "booking_id": 1,
  "amount": 2500000
}
```

**Response:**
```json
{
  "success": true,
  "message": "Invoice created successfully",
  "data": {
    "id": 1,
    "booking_id": 1,
    "invoice_number": "INV20250120001",
    "issue_date": "2025-01-20T10:30:00.000000Z",
    "amount": "2500000.00",
    "status": "issued"
  }
}
```

### 4. Cập nhật hóa đơn (Admin/Staff only)
```http
PUT /api/invoices/{invoiceId}
```

**Request Body:**
```json
{
  "status": "paid",
  "amount": 2500000
}
```

### 5. Tạo PDF hóa đơn
```http
GET /api/invoices/booking/{bookingId}/pdf
```

**Response:**
```json
{
  "success": true,
  "message": "PDF generated successfully",
  "data": {
    "filename": "invoice_INV20250120001_20250120103000.pdf",
    "download_url": "http://localhost/storage/temp/invoices/invoice_INV20250120001_20250120103000.pdf",
    "invoice_number": "INV20250120001",
    "booking_id": 1
  }
}
```

### 6. Tải xuống PDF hóa đơn
```http
GET /api/invoices/booking/{bookingId}/download
```

**Response:** Trả về file PDF để tải xuống trực tiếp.

## 🔒 Phân quyền

### Customer
- ✅ Xem hóa đơn của chính mình
- ✅ Tạo và tải xuống PDF hóa đơn của chính mình
- ❌ Không thể tạo hoặc chỉnh sửa hóa đơn

### Staff
- ✅ Xem tất cả hóa đơn
- ✅ Tạo hóa đơn mới
- ✅ Cập nhật trạng thái hóa đơn
- ✅ Tạo và tải xuống PDF hóa đơn

### Admin
- ✅ Tất cả quyền của Staff
- ✅ Xóa hóa đơn (nếu cần)

## 📝 Trạng thái hóa đơn
- `issued`: Đã phát hành
- `paid`: Đã thanh toán
- `cancelled`: Đã hủy

## 🎨 Template PDF
PDF hóa đơn được tạo với template chuyên nghiệp bao gồm:
- Thông tin công ty Tour365
- Thông tin khách hàng
- Chi tiết tour và hành khách
- Bảng giá và tổng tiền
- Thông tin khởi hành
- Mã giảm giá (nếu có)

## 🧪 Test API
Truy cập endpoint sau để xem tài liệu API đầy đủ:
```http
GET /api/invoice-api-docs
```

## 📱 Ví dụ sử dụng

### JavaScript/Fetch
```javascript
// Lấy danh sách hóa đơn
const response = await fetch('/api/invoices', {
  headers: {
    'Authorization': 'Bearer ' + token,
    'Accept': 'application/json'
  }
});
const data = await response.json();

// Tạo PDF hóa đơn
const pdfResponse = await fetch('/api/invoices/booking/1/pdf', {
  headers: {
    'Authorization': 'Bearer ' + token,
    'Accept': 'application/json'
  }
});
const pdfData = await pdfResponse.json();
window.open(pdfData.data.download_url, '_blank');
```

### cURL
```bash
# Lấy danh sách hóa đơn
curl -X GET "http://localhost/api/invoices" \
  -H "Authorization: Bearer {token}" \
  -H "Accept: application/json"

# Tạo hóa đơn
curl -X POST "http://localhost/api/invoices" \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"booking_id": 1, "amount": 2500000}'
```

## ⚠️ Lưu ý
- Tất cả số tiền được trả về dưới dạng string để tránh mất độ chính xác
- PDF được tạo với font DejaVu Sans để hỗ trợ tiếng Việt
- File PDF tạm thời sẽ được lưu trong `storage/app/public/temp/invoices/`
- Cần có quyền admin hoặc staff để tạo/chỉnh sửa hóa đơn


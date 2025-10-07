# Promotion API Documentation

## Base URL
```
http://127.0.0.1:8000/api
```

## Public Endpoints (Không cần authentication)

### 1. Get All Active Promotions
```http
GET /api/promotions
```

**Response:**
```json
{
    "success": true,
    "data": [
        {
            "id": 5,
            "code": "WELCOME10",
            "description": "Giảm 10% cho khách hàng mới",
            "discount_percent": "10.00",
            "discount_amount": null,
            "start_date": "2025-10-07T00:00:00.000000Z",
            "end_date": "2025-11-06T00:00:00.000000Z",
            "status": "active",
            "created_at": "2025-10-07T14:35:14.000000Z",
            "updated_at": "2025-10-07T14:35:14.000000Z"
        }
    ],
    "message": "Danh sách mã giảm giá"
}
```

### 2. Get Promotion by Code
```http
GET /api/promotions/{code}
```

**Example:**
```http
GET /api/promotions/WELCOME10
```

**Response:**
```json
{
    "success": true,
    "data": {
        "id": 5,
        "code": "WELCOME10",
        "description": "Giảm 10% cho khách hàng mới",
        "discount_percent": "10.00",
        "discount_amount": null,
        "start_date": "2025-10-07T00:00:00.000000Z",
        "end_date": "2025-11-06T00:00:00.000000Z",
        "status": "active"
    },
    "message": "Thông tin mã giảm giá"
}
```

### 3. Validate Promotion Code
```http
POST /api/promotions/validate
```

**Request Body:**
```json
{
    "code": "WELCOME10",
    "amount": 1000000
}
```

**Response:**
```json
{
    "success": true,
    "data": {
        "promotion": {
            "id": 5,
            "code": "WELCOME10",
            "description": "Giảm 10% cho khách hàng mới",
            "discount_percent": "10.00",
            "discount_amount": null
        },
        "original_amount": 1000000,
        "discount_amount": 100000,
        "final_amount": 900000
    },
    "message": "Mã giảm giá hợp lệ"
}
```

## Admin Endpoints (Cần authentication + admin role)

### 1. Get All Promotions (Admin)
```http
GET /api/admin/promotions
Authorization: Bearer {token}
```

### 2. Create Promotion (Admin)
```http
POST /api/admin/promotions
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body:**
```json
{
    "code": "NEWCODE20",
    "description": "Mã giảm giá mới",
    "discount_percent": 20,
    "discount_amount": null,
    "start_date": "2025-10-07",
    "end_date": "2025-12-07",
    "status": "active"
}
```

### 3. Update Promotion (Admin)
```http
PUT /api/admin/promotions/{id}
Authorization: Bearer {token}
Content-Type: application/json
```

### 4. Delete Promotion (Admin)
```http
DELETE /api/admin/promotions/{id}
Authorization: Bearer {token}
```

## Error Responses

### 404 - Promotion Not Found
```json
{
    "success": false,
    "message": "Mã giảm giá không hợp lệ hoặc đã hết hạn"
}
```

### 422 - Validation Error
```json
{
    "success": false,
    "message": "Cần nhập phần trăm hoặc số tiền giảm"
}
```

## Usage Examples

### JavaScript/Fetch
```javascript
// Get all promotions
fetch('http://127.0.0.1:8000/api/promotions')
    .then(response => response.json())
    .then(data => console.log(data));

// Validate promotion code
fetch('http://127.0.0.1:8000/api/promotions/validate', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
    },
    body: JSON.stringify({
        code: 'WELCOME10',
        amount: 1000000
    })
})
.then(response => response.json())
.then(data => console.log(data));
```

### cURL
```bash
# Get all promotions
curl -X GET http://127.0.0.1:8000/api/promotions

# Validate promotion code
curl -X POST http://127.0.0.1:8000/api/promotions/validate \
  -H "Content-Type: application/json" \
  -d '{"code":"WELCOME10","amount":1000000}'

# Get promotion by code
curl -X GET http://127.0.0.1:8000/api/promotions/WELCOME10
```

### PHP
```php
// Get all promotions
$response = file_get_contents('http://127.0.0.1:8000/api/promotions');
$data = json_decode($response, true);

// Validate promotion code
$postData = json_encode([
    'code' => 'WELCOME10',
    'amount' => 1000000
]);

$context = stream_context_create([
    'http' => [
        'method' => 'POST',
        'header' => 'Content-Type: application/json',
        'content' => $postData
    ]
]);

$response = file_get_contents('http://127.0.0.1:8000/api/promotions/validate', false, $context);
$data = json_decode($response, true);
```

## Notes
- Tất cả mã giảm giá được tự động chuyển thành chữ hoa
- API chỉ trả về các mã giảm giá đang hoạt động và chưa hết hạn
- Admin endpoints cần authentication token và quyền admin
- Validation endpoint tính toán số tiền giảm giá tự động

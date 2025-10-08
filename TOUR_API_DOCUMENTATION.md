# Tour API Documentation

## Overview
API endpoints for tour management and retrieval. All endpoints are public and do not require authentication.

## Base URL
```
http://127.0.0.1:8000/api
```

## Endpoints

### 1. Get All Tours
**GET** `/api/tours`

Get a paginated list of all tours with filtering and sorting options.

#### Query Parameters
- `location` (string): Filter by location
- `min_price` (number): Minimum price filter
- `max_price` (number): Maximum price filter
- `available` (boolean): Filter by availability (true/false)
- `search` (string): Search by tour title
- `sort_by` (string): Sort field (default: created_at)
- `sort_order` (string): Sort order - asc/desc (default: desc)
- `per_page` (number): Items per page (default: 10)

#### Example Request
```
GET /api/tours?location=hanoi&min_price=1000000&max_price=5000000&available=true&search=singapore&sort_by=price&sort_order=asc&per_page=15
```

#### Response
```json
{
    "success": true,
    "message": "Tours retrieved successfully",
    "data": {
        "current_page": 1,
        "data": [
            {
                "id": 1,
                "title": "Tour Singapore 2N1Đ",
                "short_description": "Khám phá Singapore",
                "description": "Tour du lịch Singapore 2 ngày 1 đêm...",
                "location": "Singapore",
                "duration": 2,
                "available_seats": 20,
                "price": 5000000,
                "status": "active",
                "created_at": "2024-01-01T00:00:00.000000Z",
                "updated_at": "2024-01-01T00:00:00.000000Z"
            }
        ],
        "first_page_url": "http://127.0.0.1:8000/api/tours?page=1",
        "from": 1,
        "last_page": 3,
        "last_page_url": "http://127.0.0.1:8000/api/tours?page=3",
        "links": [...],
        "next_page_url": "http://127.0.0.1:8000/api/tours?page=2",
        "path": "http://127.0.0.1:8000/api/tours",
        "per_page": 10,
        "prev_page_url": null,
        "to": 10,
        "total": 24
    },
    "filters_applied": {
        "location": "hanoi",
        "min_price": "1000000",
        "max_price": "5000000",
        "available": "true",
        "search": "singapore"
    }
}
```

### 2. Get Featured Tours
**GET** `/api/tours/featured`

Get a list of featured/popular tours (limited to 6 tours).

#### Response
```json
{
    "success": true,
    "message": "Featured tours retrieved successfully",
    "data": [
        {
            "id": 1,
            "title": "Tour Singapore 2N1Đ",
            "short_description": "Khám phá Singapore",
            "location": "Singapore",
            "duration": 2,
            "price": 5000000,
            "status": "active"
        }
    ]
}
```

### 3. Get Tours by Location
**GET** `/api/tours/location/{location}`

Get tours filtered by specific location.

#### Path Parameters
- `location` (string): Location name (e.g., hanoi, singapore, bangkok)

#### Example Request
```
GET /api/tours/location/hanoi
```

#### Response
```json
{
    "success": true,
    "message": "Tours in hanoi retrieved successfully",
    "data": [
        {
            "id": 2,
            "title": "Tour Hà Nội 3N2Đ",
            "location": "Hà Nội",
            "duration": 3,
            "price": 3000000,
            "status": "active"
        }
    ],
    "count": 1
}
```

### 4. Get Single Tour
**GET** `/api/tours/{id}`

Get detailed information about a specific tour.

#### Path Parameters
- `id` (integer): Tour ID

#### Example Request
```
GET /api/tours/1
```

#### Response
```json
{
    "success": true,
    "message": "Tour details retrieved successfully",
    "data": {
        "id": 1,
        "title": "Tour Singapore 2N1Đ",
        "short_description": "Khám phá Singapore",
        "description": "Tour du lịch Singapore 2 ngày 1 đêm...",
        "location": "Singapore",
        "duration": 2,
        "available_seats": 20,
        "price": 5000000,
        "formatted_price": "5,000,000 VND",
        "image": "tour-singapore.jpg",
        "image_url": "/storage/tour-singapore.jpg",
        "status": "active",
        "category": {
            "id": 1,
            "name": "Du lịch nước ngoài"
        },
        "created_at": "2024-01-01T00:00:00.000000Z",
        "updated_at": "2024-01-01T00:00:00.000000Z",
        "is_available": true,
        "days_until_departure": 15,
        "price_per_day": 2500000,
        "services": [
            "Khách sạn",
            "Ăn sáng",
            "Xe đưa đón",
            "Hướng dẫn viên"
        ],
        "itinerary": [
            {
                "day": 1,
                "activities": "Khởi hành từ Hà Nội, tham quan Marina Bay Sands"
            },
            {
                "day": 2,
                "activities": "Tham quan Gardens by the Bay, mua sắm tại Orchard Road"
            }
        ]
    }
```

## Error Responses

All endpoints may return error responses in the following format:

```json
{
    "success": false,
    "message": "Error message",
    "error": "Detailed error information"
}
```

Common HTTP status codes:
- `200`: Success
- `404`: Tour not found
- `500`: Server error

## Usage Examples

### JavaScript/Fetch
```javascript
// Get all tours
fetch('/api/tours')
    .then(response => response.json())
    .then(data => console.log(data));

// Get tours with filters
fetch('/api/tours?location=hanoi&min_price=1000000&available=true')
    .then(response => response.json())
    .then(data => console.log(data));

// Get single tour
fetch('/api/tours/1')
    .then(response => response.json())
    .then(data => console.log(data));
```

### PHP/cURL
```php
// Get all tours
$response = file_get_contents('http://127.0.0.1:8000/api/tours');
$data = json_decode($response, true);

// Get tours with filters
$url = 'http://127.0.0.1:8000/api/tours?' . http_build_query([
    'location' => 'hanoi',
    'min_price' => 1000000,
    'available' => 'true'
]);
$response = file_get_contents($url);
$data = json_decode($response, true);
```

## Notes
- All endpoints are public and do not require authentication
- All responses are in JSON format
- Pagination is available for the main tours endpoint
- Filtering and sorting options are available
- Error handling is implemented for all endpoints

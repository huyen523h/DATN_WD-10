```bash
# Lấy tất cả tours
curl "http://127.0.0.1:8000/api/v1/tours"

# Tìm kiếm tours
curl "http://127.0.0.1:8000/api/v1/tours?search=Đà+Nẵng"

# Lọc theo danh mục
curl "http://127.0.0.1:8000/api/v1/tours?category_id=1"

# Lọc theo khoảng giá
curl "http://127.0.0.1:8000/api/v1/tours?min_price=1000000&max_price=3000000"

# Lọc theo địa điểm
curl "http://127.0.0.1:8000/api/v1/tours?location=Đà+Nẵng"

# Sắp xếp theo giá tăng dần
curl "http://127.0.0.1:8000/api/v1/tours?sort_by=price&sort_direction=asc"

# Kết hợp nhiều filter
curl "http://127.0.0.1:8000/api/v1/tours?search=du+lịch&category_id=1&min_price=1000000&sort_by=price&sort_direction=desc&per_page=10"

# Phân trang
curl "http://127.0.0.1:8000/api/v1/tours?page=2&per_page=5"
```

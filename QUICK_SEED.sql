-- Script SQL đơn giản để thêm 4 bản ghi check-in/check-out
-- Copy toàn bộ và chạy trong phpMyAdmin hoặc MySQL Client

-- Lưu ý: Thay đổi user_id và booking_id theo dữ liệu thực tế trong database của bạn
-- Chạy query này trước để lấy các ID:
-- SELECT id FROM users LIMIT 4;
-- SELECT id FROM bookings LIMIT 2;

INSERT INTO check_in_outs 
    (user_id, booking_id, type, check_time, location, latitude, longitude, notes, status, verified_by, verified_at, metadata, created_at, updated_at) 
VALUES
    -- Check-in 1: Đã xác nhận
    (3, 1, 'check_in', DATE_SUB(NOW(), INTERVAL 2 DAY), 'Bến xe Miền Tây, Q. Bình Tân, TP.HCM', 10.7530, 106.6274, 'Khách hàng đến đúng giờ, ổn định', 'confirmed', 'Nhân viên Nguyễn Văn A', DATE_SUB(NOW(), INTERVAL 2 DAY), '{"device":"Mobile","ip_address":"192.168.1.100"}', NOW(), NOW()),
    
    -- Check-out 1: Đã xác nhận
    (4, 1, 'check_out', DATE_SUB(NOW(), INTERVAL 1 DAY), 'Khách sạn Pullman Saigon, Q. 1, TP.HCM', 10.7837, 106.7018, 'Tour kết thúc tốt đẹp, khách hàng hài lòng', 'confirmed', 'Hướng dẫn viên Trần Thị B', DATE_SUB(NOW(), INTERVAL 1 DAY), '{"device":"Tablet","ip_address":"192.168.1.101"}', NOW(), NOW()),
    
    -- Check-in 2: Chờ xác nhận
    (3, 2, 'check_in', DATE_SUB(NOW(), INTERVAL 12 HOUR), 'Sân bay Nội Bài, Hà Nội', 21.2210, 105.8066, 'Check-in tại sân bay', 'pending', NULL, NULL, '{"device":"Mobile","ip_address":"10.0.0.50"}', NOW(), NOW()),
    
    -- Check-out 2: Chờ xác nhận
    (4, 2, 'check_out', DATE_SUB(NOW(), INTERVAL 3 HOUR), 'Bãi biển Mỹ Khê, Đà Nẵng', 16.0597, 108.2456, 'Check-out tại điểm cuối tour', 'pending', NULL, NULL, '{"device":"Mobile","ip_address":"172.16.0.10"}', NOW(), NOW());

-- Kiểm tra kết quả
SELECT * FROM check_in_outs ORDER BY check_time DESC LIMIT 5;

